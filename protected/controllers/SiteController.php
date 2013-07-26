<?php

class SiteController extends Controller
{
    public function actionIndex()
    {
        if (Yii::app()->user->isGuest) {
            $this->redirect('/login');
        }
		
		if(!Yii::app()->user->getModel()){
			$this->redirect('/logout');
		}
        $dataProvider = new CArrayDataProvider(Yii::app()->user->getModel()->getProjects());

        $this->render('index', array('projects_dataProvider' => $dataProvider, 'comments_dataProvider' => $this->getCommentsDataProvider()));
    }


    public function actionError(){
        $this->redirect('/');
    }


}