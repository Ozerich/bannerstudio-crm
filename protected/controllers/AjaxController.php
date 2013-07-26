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


    // возвращает непрочитанные последние комментарии
    public function actionGetLastComments()
    {
        $result = array();
        $time = Yii::app()->request->getPost('time', 0);
        if ($time == 0) {
            $result = array('timestamp' => time(), 'items' => array());
        } else {
            $comments_all = Yii::app()->user->getModel()->getInboxComments($time);

            foreach ($comments_all as $comment) {
                $result[] = $this->renderPartial('//projects/_comments_table_item', array('data' => $comment), true);
            }

            $result = array('timestamp' => time(), 'items' => $result);
        }

        echo json_encode($result);
        die;
    }


    public function actionGet_Unread_Comments($project_id = 0)
    {
        $request_project = Project::model()->findByPk($project_id);

        $time = Yii::app()->request->getPost('time', 0);

        $inbox_comments = Yii::app()->user->getModel()->getInboxComments();

        $comments_unread = array();
        foreach ($inbox_comments as $comment) {
            if (!$comment->readed && $comment->datetime >= $comment->user->time_created) {
                $comments_unread[] = $comment;
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

            if (strtotime($comment->datetime) > ($time - 1))
                $project_new_count[$comment->project_id]++;
        }


        $slider_stats = array();
        if ($request_project) {
            foreach ($request_project->slider_pages as $page) {
                $slider_stats[$page->id] = count($page->items);
            }
        }


        $html = array();
        $comments_all = $time ? Yii::app()->user->getModel()->getInboxComments($time) : array();
        foreach ($comments_all as $comment) {
            $html[] = array(
                'header' => $this->renderPartial('//projects/_header_comments_item', array('data' => $comment), true),
                'index' => $this->renderPartial('//projects/_comments_table_item', array('data' => $comment), true)
            );
        }


        $unread_ids = array();
        foreach ($inbox_comments as $comment) {
            if (!$comment->readed) {
                $unread_ids[] = $comment->id;
            }
        }

        $result = array(
            'timestamp' => time(),
            'count' => count($comments),
            'project_new_count' => $project_new_count,
            'slider_stats' => $slider_stats,
            'html' => $html,
            'unread_ids' => $unread_ids,
        );

        echo json_encode($result);
    }

}