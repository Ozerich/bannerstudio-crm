<?php

class SliderController extends Controller
{
    public $layout = 'none';

    public function actionAdd()
    {
        if (!Yii::app()->request->isAjaxRequest) {
            throw new CHttpException(404);
        }

        $project_id = Yii::app()->request->getPost('project_id');
        $project = Project::model()->findByPk($project_id);
        if (!$project) {
            throw new CHttpException(404);
        }

        $files = Yii::app()->request->getPost('files');

        $page = Yii::app()->request->getPost('page', 0);
        if ($page == 0) {
            $page = new SliderPage();
            $page->project_id = $project->id;
            $page->save();
        } else {
            $page = SliderPage::model()->findByPk($page);
        }

        if (!$page) {
            Yii::app()->end();
        }

        if ($files) {
            foreach ($files as $file_id) {
                $file = ProjectCommentFile::model()->findByPk($file_id);
                if (!$file) continue;

                $model = new SliderItem();

                $model->page_id = $page->id;
                $model->file = $file->file;
                $model->real_filename = $file->real_filename;

                $model->save();
            }
        } else {
            $html = Yii::app()->request->getPost('html');
            if (!empty($html)) {
                $model = new SliderItem();
                $model->page_id = $page->id;
                $model->html = $html;
                $model->save();
            }
        }
    }

    public function actionView($id = 0)
    {
        $project = Project::model()->findByPk($id);
        if (!$project) {
            throw new CHttpException(404);
        }

        if (isset($_POST['page_id'])) {
            $page_id = Yii::app()->request->getPost('page_id');
            $page = SliderPage::model()->findByPk($page_id);

            $this->renderPartial('//projects/_slider_page', array('page' => $page));
            die;
        } else {
            $pages = array();

            foreach ($project->slider_pages as $page) {
                $pages[] = (int)$page->id;
            }

            $result = array(
                'html' => $this->renderPartial('//projects/_slider', array('model' => $project), true),
                'pages' => $pages
            );
        }

        echo json_encode($result);
    }

    public function actionDelete_Item($id)
    {
        $item = SliderItem::model()->findByPk($id);
        if (!$item) {
            throw new CHttpException(404);
        }

        $page = $item->page;
        $page_count = count($page->items);

        $item->delete();
        if ($page_count == 1) {
            $page->delete();
        }
    }

    public function actionDownload($id = 0)
    {
        $file = SliderItem::model()->findByPk($id);

        if ($file) {
            $file->download();
        }

        Yii::app()->end();
    }

    public function actionDelete_Page($id = 0)
    {
        $page = SliderPage::model()->findByPk($id);

        if (!$page) {
            throw new CHttpException(404);
        }

        $page->delete();
    }
}