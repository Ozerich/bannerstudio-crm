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

            $criteria = new CDbCriteria();

            $criteria->order = 'datetime desc';
            $criteria->limit = 50;

            $comments = ProjectComment::model()->findAll($criteria);

        } else {

            $projects = array();

            $project_users = ProjectUser::model()->findAllByAttributes(array('user_id' => Yii::app()->user->id));
            foreach ($project_users as $project_user) {
                $projects[] = Project::model()->findByPk($project_user->project_id);
            }

            $dataProvider = new CArrayDataProvider($projects);


            $criteria = new CDbCriteria();

            $criteria->order = 'datetime desc';
            $criteria->limit = 50;

            $criteria->addNotInCondition('user_id', Yii::app()->user->id);

            foreach($projects as $project){
                $criteria->compare('project_id', $project->id, false, 'OR');
            }

            $comments = ProjectComment::model()->findAll($criteria);

        }

        $this->render('index', array('projects_dataProvider' => $dataProvider, 'comments_dataProvider' => new CArrayDataProvider($comments, array(
            'pagination' => array(
                'pageSize' => 10000,
            ),
        ))));
    }


}