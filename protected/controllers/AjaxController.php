<?php

class AjaxController extends Controller
{
    public function beforeAction($action)
    {
        $this->layout = 'none';

        if (!Yii::app()->request->isAjaxRequest) {
            throw new CHttpException(404);
        }
        return true;
    }


    public function actionGet_Unread_Comments()
    {
        $project_ids = array();

        if (Yii::app()->user->role == 'admin') {
            foreach (Project::model()->findAll() as $project) {
                $project_ids[] = $project->id;
            }
        } else {
            foreach (ProjectUser::model()->findAllByAttributes(array('user_id' => Yii::app()->user->id)) as $project) {
                $project_ids[] = $project->project_id;
            }
        }
        $comments_all = array();

        foreach ($project_ids as $project_id) {

            if (Yii::app()->user->role == 'admin') {
                $project_comments = ProjectComment::model()->findAllByAttributes(array('project_id' => $project_id));
            } else {
                $project_comments = ProjectComment::model()->findAllByAttributes(array('project_id' => $project_id,
                    'mode' => Yii::app()->user->role
                ));
            }

            $comments_all = array_merge($comments_all, $project_comments);
        }

        $comments_unread = array();

        foreach ($comments_all as $comment) {
            if (!$comment->readed && $comment->datetime >= $comment->user->time_created) {
                $comments_unread[] = $comment;
            }
        }

        $comments = array();
        $projects_count = array();
        foreach ($comments_unread as $comment) {
            $comments[] = array(
                'project_id' => $comment->project_id,
                'text' => $comment->text,
            );

            if (!isset($projects_count[$comment->project_id])) {
                $projects_count[$comment->project_id] = 0;
            }

            $projects_count[$comment->project_id]++;
        }


        $result = array(
            'count' => count($comments),
            'projects_count' => $projects_count,
            'comments' => $comments
        );

        echo json_encode($result);
    }

}