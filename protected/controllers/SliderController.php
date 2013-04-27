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
            $page = $project->getSlidesCount() + 1;
        }

        foreach ($files as $file_id) {
            $file = ProjectCommentFile::model()->findByPk($file_id);
            if (!$file) continue;

            $model = new SliderItem();
            $model->project_id = $project_id;
            $model->file = $file->file;
            $model->page = $page;
            $model->save();
        }
    }

    public function actionView($id = 0)
    {
        $project = Project::model()->findByPk($id);
        if (!$project) {
            throw new CHttpException(404);
        }

        $this->renderPartial('//projects/_slider', array('model' => $project));
    }
}