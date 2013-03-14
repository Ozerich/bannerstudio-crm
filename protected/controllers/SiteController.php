<?php

class SiteController extends Controller
{
    public function actionIndex()
    {
        if(Yii::app()->user->isGuest){
            $this->redirect('/login');
        }

        $dataProvider = new CActiveDataProvider('Project');


        $this->render('index', array('projects_dataProvider' => $dataProvider));
    }


}