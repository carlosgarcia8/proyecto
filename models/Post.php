<?php

namespace app\models;

use Yii;
use app\models\Usuario;
use yii\imagine\Image;
use Imagine\Gd;
use Imagine\Image\Box;
use Imagine\Image\BoxInterface;

/**
 * This is the model class for table "posts".
 *
 * @property integer $id
 * @property string $titulo
 * @property integer $votos
 * @property string $ruta
 * @property string $extension
 * @property integer $usuario_id
 *
 * @property Usuarios $usuario
 */
class Post extends \yii\db\ActiveRecord
{
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
            [['titulo'], 'required'],
            [['votos', 'usuario_id'], 'integer'],
            [['titulo'], 'string', 'max' => 100],
            [['ruta'], 'string', 'max' => 50],
            [['extension'], 'string', 'max' => 20],
            ['imageFile', 'image', 'extensions' => 'png, jpg',
                'minWidth' => 500, 'maxWidth' => 2000,
                'minHeight' => 500, 'maxHeight' => 2000,
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
            'votos' => 'Votos',
            'ruta' => 'Ruta',
            'extension' => 'Extension',
            'usuario_id' => 'Usuario ID',
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            $this->imageFile->saveAs(Yii::$app->basePath . '/web/uploads/' . $this->imageFile->baseName . '.' . $this->imageFile->extension);
            $imagen = Image::getImagine()
                ->open(Yii::$app->basePath . '/web/uploads/' . $this->imageFile->baseName . '.' . $this->imageFile->extension);
            $imagen->thumbnail(new Box(500, $imagen->getSize()->getHeight()))
                ->save(Yii::$app->basePath . '/web/uploads/' . $this->imageFile->baseName . '-resized.' . $this->imageFile->extension, ['quality' => 90]);
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
        return Yii::$app->request->BaseUrl . '/uploads/' . $this->ruta . '-resized.' . $this->extension;
    }
}
