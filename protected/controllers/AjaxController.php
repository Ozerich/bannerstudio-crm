<?php

class AjaxController extends Controller
{
    public function beforeAction($action)
    {
        $this->layout = 'none';
        if (!Yii::app()->request->isAjaxRequest) {
            //  throw new CHttpException(404);
        }
        return true;
    }

    public function actionGet_Unread_Comments($project_id = 0)
    {
        $project = Project::model()->findByPk($project_id);

        $inbox_comments = Yii::app()->user->getModel()->getInboxComments();
        $count = 0;
        foreach ($inbox_comments as $comment) {
            if (!$comment['readed'] && $comment['comment']->user_id != Yii::app()->user->id) {
                $count++;
            }
        }

        $unread_ids = array();
        foreach ($inbox_comments as $comment) {
            if (!$comment['readed']) {
                $unread_ids[] = $comment['comment']->id;
            }
        }

        $time = Yii::app()->request->getPost('time', 0);
        if ($time == 0) {

            $result = array(
                'timestamp' => time(),
                'count' => $count,
                'unread_ids' => $unread_ids,
            );

            echo json_encode($result);
            die;
        }


        $inbox_comments = Yii::app()->user->getModel()->getInboxComments($time);

        $comments_unread = array();
        foreach ($inbox_comments as $comment) {
            if ($comment['readed'] == false) {
                $comments_unread[] = $comment['comment'];
            }
        }

        $comments = array();
        $project_new_count = array();
        foreach ($comments_unread as $comment) {
            $comments[] = array(
                'project_id' => $comment->project_id,
                'text' => $comment->text,
            );

            if (!isset($project_new_count[$comment->project_id])) {
                $project_new_count[$comment->project_id] = 0;
            }

            if ($comment->user_id != Yii::app()->user->id)
                $project_new_count[$comment->project_id]++;
        }


        $slider_stats = array();
        if ($project) {
            foreach ($project->slider_pages as $page) {
                $slider_stats[$page->id] = count($page->items);
            }
        }


        $html = array();
        foreach ($inbox_comments as $comment) {
            if ($comment['comment']->user_id != Yii::app()->user->id) {
                $html[] = array(
                    'header' => $this->renderPartial('//projects/_header_comments_item', array('data' => $comment['comment']), true),
                    'index' => $this->renderPartial('//projects/_comments_table_item', array('data' => $comment['comment']), true)
                );
            }
        }

        $result = array(
            'timestamp' => time(),
            'count' => $count,
            'project_new_count' => $project_new_count,
            'slider_stats' => $slider_stats,
            'html' => $html,
            'unread_ids' => $unread_ids,
        );

        echo json_encode($result);
    }

}