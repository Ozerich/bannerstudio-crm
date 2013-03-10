<?php

class Controller extends RController
{

	public $layout='//layouts/main';

	public $menu=array();

	public $breadcrumbs=array();

    public function init(){
        if (Yii::app()->urlManager->showScriptName == false){
            if (strpos(Yii::app()->request->requestUri, '/index.php') !== false){
                $_uri = str_replace("/index.php", "", Yii::app()->request->requestUri);
                $_uri = str_replace("//", "", $_uri);
                $this->redirect($_uri);
            }
        }
    }
}