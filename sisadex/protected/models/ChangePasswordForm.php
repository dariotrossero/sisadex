<?php

class ChangePasswordForm extends CFormModel
{
    public $currentPassword;
    public $newPassword;
    public $newPassword_repeat;
    private $_user;

    public function rules()
    {
        return array(
            array(
                'currentPassword',
                'compareCurrentPassword'
                ),
            array(
                'currentPassword, newPassword, newPassword_repeat',
                'required',
                'message' => 'Introduzca su {attribute}.'
                ),
            array(
                'newPassword_repeat',
                'compare',
                'compareAttribute' => 'newPassword',
                'message' => 'Las contraseñas no coinciden.'
                )
            );
    }

    public function compareCurrentPassword($attribute, $params)
    {
        if (md5($this->currentPassword) !== $this->_user->password) {
            $this->addError($attribute, 'La contraseña actual es incorrecta');
        }
    }

    public function isPasswordCorrect($params)
    {
        if (md5($params) !== $this->_user->password)
            return false;
        else
            return true;
    }

    public function init()
    {
        $this->_user = Users::model()->findByAttributes(array(
            'id' => Yii::app()->User->name
            ));
    }

    public function attributeLabels()
    {
        return array(
            'currentPassword' => 'Contraseña actual',
            'newPassword' => 'Nueva contraseña',
            'newPassword_repeat' => 'Nueva contraseña (Repetir)'
            );
    }

    public function changePassword()
    {
        $this->_user->password = $this->newPassword;
        if ($this->_user->save())
            return true;
        return false;
    }
}