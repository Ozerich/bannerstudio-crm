<?php

class LoginForm extends CFormModel
{
    public $email;
    public $password;
    public $rememberMe;

    private $_identity;

    public function rules()
    {
        return array(
            array('email, password', 'required'),
            array('rememberMe', 'boolean'),
            array('password', 'authenticate'),
        );
    }


    public function attributeLabels()
    {
        return array(
            'email' => 'E-mail',
            'password' => 'Пароль',
            'rememberMe' => 'Запомнить',
        );
    }


    public function authenticate($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $this->_identity = new UserIdentity($this->email, $this->password);
            if (!$this->_identity->authenticate()) {
                $this->addError('password', 'Неверный пароль');
            }
        }
    }

    public function login()
    {
        if ($this->_identity === null) {
            $this->_identity = new UserIdentity($this->email, $this->password);
            $this->_identity->authenticate();
        }
		
        if ($this->_identity->errorCode === UserIdentity::ERROR_NONE) {
            $duration = $this->rememberMe ? 3600 * 24 * 30 : 0;
            Yii::app()->user->login($this->_identity, $duration);
            return true;
        } else
            return false;
    }
}
