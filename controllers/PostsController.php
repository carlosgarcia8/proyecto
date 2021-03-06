<?php

namespace app\controllers;

use Yii;
use LengthException;
use app\models\Post;
use app\models\Upvote;
use app\models\Downvote;
use app\models\Comentario;
use app\models\PostSearch;
use yii\bootstrap\Alert;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\UploadForm;
use app\models\Usuario;
use yii\filters\AccessControl;
use Imagine\Image\BoxInterface;
use yii\web\UploadedFile;

/**
 * PostsController implements the CRUD actions for Post model.
 */
class PostsController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['login', 'logout', 'signup', 'upload'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['login', 'signup'],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['logout', 'upload'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Post models.
     * @return mixed
     */
    public function actionIndex()
    {
        // $searchModel = new PostSearch();
        // $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        // $posts = Post::find()->orderBy(['fecha_publicacion' => SORT_DESC])->all();
        $posts = Post::find()->pending()->all();

        // var_dump($posts);
        // die();

        return $this->render('index', [
            // 'searchModel' => $searchModel,
            // 'dataProvider' => $dataProvider,
            'posts' => $posts,
        ]);
    }

    /**
     * Displays a single Post model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $comentarios = Comentario::find()->where(['post_id' => $id])->all();

        if (Yii::$app->user->isGuest) {
            $esAutor = false;
        } else {
            $esAutor = $model->usuario_id === Yii::$app->user->identity->id;
        }

        return $this->render('view', [
            'model' => $model,
            'esAutor' => $esAutor,
            'comentarios' => $comentarios,
        ]);
    }

    /**
     * Updates an existing Post model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $esAutor = $model->usuario_id === Yii::$app->user->identity->id;

        if ($esAutor) {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        } else {
            return $this->redirect(['view', 'id' => $model->id]);
        }
    }

    /**
     * Deletes an existing Post model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if ($model->usuario_id === Yii::$app->user->identity->id) {
            if (file_exists(Yii::$app->basePath . '/web/uploads/' . $model->id . '.' . $model->extension)) {
                unlink(Yii::$app->basePath . '/web/uploads/' . $model->id . '.' . $model->extension);
            }
            if (file_exists(Yii::$app->basePath . '/web/uploads/' . $model->id . '-resized.' . $model->extension)) {
                unlink(Yii::$app->basePath . '/web/uploads/' . $model->id . '-resized.' . $model->extension);
            }
            if (file_exists(Yii::$app->basePath . '/web/uploads/' . $model->id . '-longpost.' . $model->extension)) {
                unlink(Yii::$app->basePath . '/web/uploads/' . $model->id . '-longpost.' . $model->extension);
            }
            $model->delete();

            return $this->redirect(['index']);
        }

        return $this->render('view', ['model' => $model]);
    }

    public function actionDownvote()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(Url::toRoute(['site/login']));
        }
        $id = $_POST['id'];
        $post = $this->findModel($id);

        $upvoteUser = Upvote::find()->where(['usuario_id' => Yii::$app->user->id, 'post_id' => $id])->one();
        $downvoteUser = Downvote::find()->where(['usuario_id' => Yii::$app->user->id, 'post_id' => $id])->one();

        if (!empty($upvoteUser)) {
            $upvoteUser->delete();
        }

        if (empty($downvoteUser)) {
            $downvote = new Downvote;
            $downvote->usuario_id = Yii::$app->user->id;
            $downvote->post_id = $id;
            $downvote->save();
        } else {
            $downvoteUser->delete();
        }

        return $post->getVotos();
    }

    public function actionUpvote()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(Url::toRoute(['site/login']));
        }
        $id = $_POST['id'];
        $post = $this->findModel($id);
        $upvoteUser = Upvote::find()->where(['usuario_id' => Yii::$app->user->id, 'post_id' => $id])->one();
        $downvoteUser = Downvote::find()->where(['usuario_id' => Yii::$app->user->id, 'post_id' => $id])->one();

        if (!empty($downvoteUser)) {
            $downvoteUser->delete();
        }

        if (empty($upvoteUser)) {
            $upvote = new Upvote;
            $upvote->usuario_id = Yii::$app->user->id;
            $upvote->post_id = $id;
            $upvote->save();
        } else {
            $upvoteUser->delete();
        }

        return $post->getVotos();
    }

    public function actionUpload()
    {
        $model = new Post();

        if ($model->load(Yii::$app->request->post())) {
            $model->usuario_id = Usuario::findOne(['nick' => Yii::$app->user->identity->nick])->id;
            $imagen = UploadedFile::getInstance($model, 'imageFile');
            $long = $_POST['Post']['long'];

            if ($imagen !== null && $long !== '') {
                $model->longpost = $long > 7000 ? true : false;
                $model->imageFile = $imagen;
                $model->extension = $model->imageFile->extension;
                $model->markPending();

                if ($model->save() && $model->upload()) {

                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        }
        return $this->render('upload', ['model' => $model]);
    }

    /**
     * Finds the Post model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Post the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Post::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
