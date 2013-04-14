<?php

class ProjectsController extends Controller
{
    public function filters()
    {
        return array(
            'Rights',
        );
    }

    public function actionIndex()
    {
        $this->redirect('/');
        $this->breadcrumbs = array('Все проекты');

        $this->render('index', array('dataProvider' => new CActiveDataProvider('Project')));
    }

    public function actionEdit($id)
    {
        $model = Project::model()->findByPk($id);

        if (!$model) {
            throw new CHttpException(404);
        }

        if (Yii::app()->request->isPostRequest) {
            $model->attributes = Yii::app()->request->getPost('Project');

            if ($model->save()) {

                foreach (array_merge(explode(',', $model->workers_list), explode(',', $model->customers_list)) as $id) {
                    ProjectUser::add($model->id, $id, isset($_POST['send_worker_email']), isset($_POST['send_customer_email']));
                }

                $this->redirect('/projects/' . $model->id);
            }
        }

        $this->breadcrumbs = array(
            'Все проекты' => '/',
            'Проект ID ' . $model->id
        );

        $this->render('item', array('model' => $model, 'page_title' => 'Проект ID ' . $model->id));
    }

    public function actionDelete($id = 0)
    {
        $model = Project::model()->findByPk($id);

        if (!$model) {
            throw new CHttpException(404);
        }

        $model->delete();

        $this->redirect(Yii::app()->request->urlReferrer);
    }


    public function actionCreate()
    {
        $model = new Project;

        if (Yii::app()->request->isPostRequest) {
            $model->attributes = Yii::app()->request->getPost('Project');

            if (Yii::app()->user->role == 'customer') {
                $model->closed = 0;
                $model->status = 'customer_created';
            }

            if ($model->save()) {

                if (Yii::app()->user->role == 'customer') {
                    ProjectUser::add($model->id, Yii::app()->user->id);
                }

                foreach (array_merge(explode(',', $model->workers_list), explode(',', $model->customers_list)) as $id) {
                    ProjectUser::add($model->id, $id, isset($_POST['send_worker_email']), isset($_POST['send_customer_email']));
                }

                $this->redirect('/projects/' . $model->id);
            }
        }

        $this->breadcrumbs = array(
            'Все проекты' => '/',
            'Новый проект'
        );

        $this->render('item', array('model' => $model, 'page_title' => 'Новый проект'));
    }


    public function actionView($id)
    {
        $model = Project::model()->findByPk($id);

        if (!$model || !$model->checkAccess()) {
            throw new CHttpException(404);
        }

        $this->breadcrumbs = array(
            'Все проекты' => '/',
            'Проект ID ' . $model->id
        );

        $this->render('view', array('model' => $model, 'page_title' => 'Проект ID ' . $model->id));
    }


    public function actionAdd_Comment()
    {
        if (!Yii::app()->request->isAjaxRequest) {
            throw new CHttpException(404);
        }
        $project_id = Yii::app()->request->getPost('project_id');
        $message = Yii::app()->request->getPost('message');

        $model = new ProjectComment();

        $model->project_id = $project_id;
        $model->text = $message;
        $model->user_id = Yii::app()->user->id;

        $model->save();

        echo json_encode(array('comment_id' => $model->id));

        Yii::app()->end();
    }

    public function actionUpload_Comment_File($comment = 0)
    {
        if (!Yii::app()->request->isPostRequest) {
            throw new CHttpException(404);
        }

        $file = CUploadedFile::getInstanceByName('file');

        if (!$file) {
            echo '0';
        } else {

            $model = new ProjectCommentFile();

            $model->comment_id = $comment;
            $model->file = uniqid() . '.' . $file->getExtensionName();
            $model->real_filename = $file->getName();
            $model->file_size = $file->getSize();

            if ($model->save()) {
                $file->saveAs($_SERVER['DOCUMENT_ROOT'] . Yii::app()->params['upload_dir_comments'] . $model->file);

                echo '1';
            } else {
                echo '0';
            }

        }

        Yii::app()->end();
    }


    public function actionComments($id = 0)
    {
        if (!Yii::app()->request->isAjaxRequest) {
            throw new CHttpException(404);
        }

        echo $this->renderPartial('_comments_block', array('project' => Project::model()->findByPk($id)));

        Yii::app()->end();
    }


    public function actionDelete_Comment_File($id = 0)
    {
        if (!Yii::app()->request->isAjaxRequest) {
            throw new CHttpException(404);
        }

        $file = ProjectCommentFile::model()->findByPk($id);

        if ($file) {
            $file->delete();
        }

        Yii::app()->end();
    }

    public function actionDownload($id = 0){
        $file = ProjectCommentFile::model()->findByPk($id);

        if($file){
            $file->download();
        }

        Yii::app()->end();
    }
}