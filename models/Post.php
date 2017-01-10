<?php

namespace app\models;

use Yii;
use app\models\Usuario;
use Imagine\Image\Point;
use yii\imagine\Image;
use Imagine\Gd;
use Imagine\Image\Box;
use Imagine\Image\BoxInterface;
use yii\web\UploadedFile;

/**
 * This is the model class for table "posts".
 *
 * @property integer $id
 * @property string $titulo
 * @property string $ruta
 * @property string $extension
 * @property string $fecha_publicacion
 * @property bool   $longpost
 * @property integer $usuario_id
 *
 * @property Usuarios $usuario
 */
class Post extends \yii\db\ActiveRecord
{
    const SCENARIO_UPLOAD = 'upload';

    public $long;

    public $imageFile;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'posts';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['imageFile'], 'required', 'on' => self::SCENARIO_UPLOAD],
            [['titulo'], 'required'],
            [['usuario_id'], 'integer'],
            [['titulo'], 'string', 'max' => 100],
            [['fecha_publicacion'], 'safe'],
            [['longpost'], 'boolean'],
            [['extension'], 'string', 'max' => 20],
            ['imageFile', 'image', 'extensions' => 'png, jpg, gif',
                'minWidth' => 500, 'maxWidth' => 2000,
                'minHeight' => 260, 'maxHeight' => 20000,
            ],
            [['usuario_id'], 'exist', 'skipOnError' => true, 'targetClass' => Usuario::className(), 'targetAttribute' => ['usuario_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'titulo' => 'Titulo',
            'extension' => 'Extension',
            'usuario_id' => 'Usuario ID',
            'fecha_publicacion' => 'Fecha de publicación',
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            $this->extension = $this->imageFile->extension;

            $this->imageFile->saveAs(Yii::$app->basePath . '/web/uploads/' . $this->id . '.' . $this->extension);

            if ($this->extension === 'gif') {
                return true;
            }

            $imagen = Image::getImagine()
                ->open(Yii::$app->basePath . '/web/uploads/' . $this->id . '.' . $this->extension);
            $imagen->thumbnail(new Box(500, $imagen->getSize()->getHeight()))
                    ->save(Yii::$app->basePath . '/web/uploads/' . $this->id . '-resized.' . $this->extension, ['quality' => 90]);

            if ($this->longpost) {
                $imagen->crop(new Point(0, 0), new Box(500, 260));
                $imagen->save(Yii::$app->basePath . '/web/uploads/' . $this->id . '-longpost.' . $this->extension, ['quality' => 90]);
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsuario()
    {
        return $this->hasOne(Usuario::className(), ['id' => 'usuario_id'])->inverseOf('posts');
    }

    public function getImageurl()
    {
        if ($this->longpost) {
            return Yii::$app->request->BaseUrl . '/uploads/' . $this->id . '-longpost.' . $this->extension;
        } elseif ($this->extension === 'gif') {
            return Yii::$app->request->BaseUrl . '/uploads/' . $this->id . '.' . $this->extension;
        } else {
            return Yii::$app->request->BaseUrl . '/uploads/' . $this->id . '-resized.' . $this->extension;
        }
    }

    public function getUpvotes()
    {
        return $this->hasMany(Upvote::className(), ['post_id' => 'id'])->count();
    }

    public function getDownvotes()
    {
        return $this->hasMany(Downvote::className(), ['post_id' => 'id'])->count();
    }

    public function getVotos()
    {
        return $this->upvotes - $this->downvotes;
    }
}
