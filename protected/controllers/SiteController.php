<?php

class SiteController extends Controller
{
    public function actionIndex()
    {
        if (Yii::app()->user->isGuest) {
            $this->redirect('/login');
        }

        $dataProvider = new CArrayDataProvider(Yii::app()->user->getModel()->getProjects());

        $this->render('index', array('projects_dataProvider' => $dataProvider, 'comments_dataProvider' => $this->getCommentsDataProvider()));
    }


}