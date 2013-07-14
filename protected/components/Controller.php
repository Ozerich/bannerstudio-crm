<?php

class Controller extends RController
{

    public $layout = '//layouts/main';

    public $menu = array();

    public $breadcrumbs = array();

    public function init()
    {
        if (Yii::app()->urlManager->showScriptName == false) {
            if (strpos(Yii::app()->request->requestUri, '/index.php') !== false) {
                $_uri = str_replace("/index.php", "", Yii::app()->request->requestUri);
                $_uri = str_replace("//", "", $_uri);
                $this->redirect($_uri);
            }
        }
    }

    public function getCommentsDataProvider()
    {
        $comments = array();

        if (Yii::app()->user->role == 'admin') {
            $criteria = new CDbCriteria();
            $criteria->order = 'datetime desc';
            $criteria->condition = 'user_id <> ' . Yii::app()->user->id;
            $criteria->limit = 50;

            $comments = ProjectComment::model()->findAll($criteria);

        } else {

            $projects = array();

            $project_users = ProjectUser::model()->findAllByAttributes(array('user_id' => Yii::app()->user->id));
            foreach ($project_users as $project_user) {
                $project = Project::model()->findByPk($project_user->project_id);
                if ($project) {
                    $projects[] = $project;
                }
            }

            if (!empty($projects)) {
                $criteria = new CDbCriteria();
                $criteria->order = 'datetime desc';
                $criteria->limit = 50;
                $criteria->addNotInCondition('user_id', array(Yii::app()->user->id));

                $project_ids= array();
                foreach ($projects as $project) {
                    $project_ids[] = $project->id;
                }

                $criteria->compare('project_id', $project_ids, false, 'AND');

                $comments_all = ProjectComment::model()->findAll($criteria);
                foreach ($comments_all as $comment) {
                    if ($comment->user_id != Yii::app()->user->id && $comment->mode == Yii::app()->user->role) {
                        $comments[] = $comment;
                    }
                }
            }
        }

        $result = array();

        foreach ($comments as $comment) {
            if ($comment->project) {
                $result[] = $comment;
            }
        }

        return new CArrayDataProvider($result, array(
            'pagination' => array(
                'pageSize' => 10000,
            ),
        ));
    }
}