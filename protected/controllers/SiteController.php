<?php

class SiteController extends Controller
{
    public function actionIndex()
    {
        if (Yii::app()->user->isGuest) {
            $this->redirect('/login');
        }

        if (Yii::app()->user->role == 'admin') {
            $dataProvider = new CActiveDataProvider('Project');
        } else {

            $projects = array();

            $project_users = ProjectUser::model()->findAllByAttributes(array('user_id' => Yii::app()->user->id));
            foreach ($project_users as $project_user) {
                $projects[] = Project::model()->findByPk($project_user->project_id);
            }

            $dataProvider = new CArrayDataProvider($projects);
        }


        $this->render('index', array('projects_dataProvider' => $dataProvider));
    }


}