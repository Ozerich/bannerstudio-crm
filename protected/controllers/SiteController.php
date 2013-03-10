<?php

class SiteController extends Controller
{
    public function actionIndex()
    {
        if(Yii::app()->user->isGuest){
            $this->redirect('/login');
        }

        $this->render('index');
    }


}