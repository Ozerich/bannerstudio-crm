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

    public function actionMark_all_read()
    {
        $inbox_comments = Yii::app()->user->getModel()->getInboxComments();
        foreach ($inbox_comments as $comment) {
            if ($comment['readed'] == false) {
                $read = new ProjectCommentRead;
                $read->user_id = Yii::app()->user->id;
                $read->comment_id = $comment['comment']->id;
                $read->save();
            }
        }
    }

    public function actionGet_Unread_Comments($project_id = 0)
    {
        $time = Yii::app()->request->getPost('time', 0);
        $project = Project::model()->findByPk($project_id);

        $inbox_comments = Yii::app()->user->getModel()->getInboxComments();
        $count = 0;
        $unread_ids = array();

        foreach ($inbox_comments as $comment) {
            if ($comment['readed'] == false) {
                $count++;
                $unread_ids[] = $comment['comment']->id;
            }
        }

        if ($time == 0) {
            $result = array(
                'timestamp' => time(),
                'count' => $count,
                'unread_ids' => $unread_ids,
            );

            echo json_encode($result);
            die;
        }


        $project_new_count = array();
        $html = array();


        foreach ($inbox_comments as $comment) {
            if (strtotime($comment['comment']->datetime) >= $time) {
                if ($comment['readed'] == false) {

                    if (!isset($project_new_count[$comment['comment']->project_id])) {
                        $project_new_count[$comment['comment']->project_id] = 0;
                    }
                    $project_new_count[$comment['comment']->project_id]++;

                    $html[] = array(
                        'header' => $this->renderPartial('//projects/_header_comments_item', array('data' => $comment['comment']), true),
                        'index' => $this->renderPartial('//projects/_comments_table_item', array('data' => $comment['comment']), true)
                    );
                }
            }
        }

        $slider_stats = array();
        if ($project) {
            foreach ($project->slider_pages as $page) {
                $slider_stats[$page->id] = count($page->items);
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