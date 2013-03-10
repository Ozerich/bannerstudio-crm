<?php

class WebUser extends RWebUser {
    private $_model = null;

    function getRole() {
        if($user = $this->getModel()){
            return $user->role;
        }
        return null;
    }

    public function getModel(){
        if (!$this->isGuest && $this->_model === null){
            $this->_model = User::model()->findByPk($this->id);
        }
        return $this->_model;
    }

    protected function beforeLogin(){
        $this->returnUrl=array('/');
        return true;
    }
}