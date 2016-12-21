<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Usuario;

/**
 * RegistroForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class RegistroForm extends Model
{
    public $username;
    public $password;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
        ];
    }

    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function registrar()
    {
        if ($this->validate()) {
            $usuario = new Usuario();
            $usuario->password = Yii::$app->getSecurity()->generatePasswordHash($this->password);
            $usuario->nick = $this->username;
            $usuario->save();
        }
        return false;
    }
}
