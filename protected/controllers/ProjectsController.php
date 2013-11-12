<?php

class ProjectsController extends Controller
{
    public function filters()
    {
        return array(
            'Rights',
        );
    }

    public function allowedActions()
    {
        return 'files, download, flash, mark_comment';
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

                $users_all = ProjectUser::model()->findAllByAttributes(array('project_id' => $model->id));
                $users = array();
                foreach ($users_all as $user) {
                    $users[$user->user_id] = false;
                }

                foreach (array_merge(explode(',', $model->workers_list), explode(',', $model->customers_list)) as $id) {
                    if (isset($users[$id])) {
                        $users[$id] = true;
                    }
                    ProjectUser::add($model->id, $id, isset($_POST['send_worker_email']), isset($_POST['send_customer_email']));
                }

                foreach ($users as $user_id => $found) {
                    if (!$found) {
                        ProjectUser::model()->deleteAllByAttributes(array(
                            'project_id' => $model->id,
                            'user_id' => $user_id,
                        ));
                    }
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
            }

            $model->created_time = new CDbExpression('NOW()');
            $model->out_hash = md5(microtime());

            if ($model->save()) {

                if (Yii::app()->user->role == 'customer') {
                    ProjectUser::add($model->id, Yii::app()->user->id);
                } else {
                    foreach (array_merge(explode(',', $model->workers_list), explode(',', $model->customers_list)) as $id) {
                        ProjectUser::add($model->id, $id, isset($_POST['send_worker_email']), isset($_POST['send_customer_email']));
                    }
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


    public function actionView($id, $mode = 'customer')
    {
        if (Yii::app()->user->role == 'worker') {
            $mode = 'worker';
        } elseif (Yii::app()->user->role == 'customer') {
            $mode = 'customer';
        } elseif (Yii::app()->user->role == 'administrator') {
            $mode = $mode == 'customer' || $mode == 'worker' ? $mode : 'customer';
        }


        $model = Project::model()->findByPk($id);


        if (!$model || !$model->checkAccess()) {
            throw new CHttpException(404);
        }

        $this->breadcrumbs = array(
            'Все проекты' => '/',
            'Проект ID ' . $model->id
        );

        $this->render('view', array('mode' => $mode, 'model' => $model, 'page_title' => 'Проект ID ' . $model->id));
    }


    public function actionAdd_Comment()
    {
        $project_id = Yii::app()->request->getPost('project_id');
        $message = Yii::app()->request->getPost('message');
        $mode = Yii::app()->request->getPost('mode');

        if (Yii::app()->request->isAjaxRequest) {
            $session = Yii::app()->request->getPost('session');

            $model = new ProjectComment();
            $model->project_id = $project_id;
            $model->text = $message;
            $model->user_id = Yii::app()->user->id;
            $model->mode = $mode;
            $model->save();

            if (isset($_SESSION['files'][$session])) {
                foreach ($_SESSION['files'][$session] as $file_id) {
                    $file = ProjectCommentFile::model()->findByPk($file_id);
                    if ($file) {
                        $file->comment_id = $model->id;
                        $file->save();
                    }
                }
            }


            echo json_encode(array('comment_id' => $model->id));
            Yii::app()->end();
        }

        if (Yii::app()->request->isPostRequest) {

            $model_comment = new ProjectComment();
            $model_comment->project_id = $project_id;
            $model_comment->text = $message;
            $model_comment->user_id = Yii::app()->user->id;
            $model_comment->mode = $mode;
            $model_comment->save();

            $files = CUploadedFile::getInstancesByName('file');
            print_r($files);exit;
            foreach ($files as $pos => $file) {
                $model = new ProjectCommentFile();

                $model->file = uniqid() . '.' . $file->getExtensionName();
                $model->real_filename = $file->getName();
                $model->pos = $pos;
                $model->file_size = $file->getSize();
                $model->comment_id = $model_comment->id;
                $model->save();
            }

            $this->redirect('/projects/' . $project_id);
        }
    }

    public function actionEdit_Comment($id = 0)
    {
        if (!Yii::app()->request->isAjaxRequest) {
            throw new CHttpException(404);
        }

        $message = Yii::app()->request->getPost('message');

        $model = ProjectComment::model()->findByPk($id);
        $model->text = $message;
        $model->save();

        Yii::app()->end();
    }


    public function actionDelete_Comment($id = 0)
    {
        $comment = ProjectComment::model()->findByPk($id);

        if ($comment) {
            $comment->delete();
        }

        if (Yii::app()->request->isAjaxRequest) {
            Yii::app()->end();
        } else {
            $this->redirect(Yii::app()->request->urlReferer);
        }
    }


    public function actionUpload_Comment_File($session = '', $pos = 0)
    {
        if (!Yii::app()->request->isPostRequest) {
            throw new CHttpException(404);
        }

        $file = CUploadedFile::getInstanceByName('file');

        if (!$file) {
            echo '0';
        } else {

            $model = new ProjectCommentFile();

            $model->file = uniqid() . '.' . $file->getExtensionName();
            $model->real_filename = $file->getName();
            $model->pos = $pos;
            $model->file_size = $file->getSize();

            if ($model->save()) {

                if (!isset($_SESSION['files'])) {
                    $_SESSION['files'] = array();
                }

                if (!isset($_SESSION['files'][$session])) {
                    $_SESSION['files'] = array($session => array());
                }

                $_SESSION['files'][$session][] = $model->id;

                $file->saveAs($_SERVER['DOCUMENT_ROOT'] . Yii::app()->params['upload_dir_comments'] . $model->file);


                echo '1';
            } else {
                echo print_r($model->getErrors());;
            }


        }

        Yii::app()->end();
    }


    public function actionComments($id = 0)
    {
        if (!Yii::app()->request->isAjaxRequest) {
            throw new CHttpException(404);
        }

        echo $this->renderPartial('_comments_list', array('model' => Project::model()->findByPk($id)));

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

    public function actionDownload($id = 0)
    {
        $file = ProjectCommentFile::model()->findByPk($id);

        if ($file) {
            $file->download();
        }

        Yii::app()->end();
    }


    public function actionAdmin_Comment($id = 0)
    {
        if (!Yii::app()->request->isAjaxRequest) {
            throw new CHttpException(404);
        }

        $project = Project::model()->findByPk($id);
        if (!$project) die;

        $message = Yii::app()->request->getPost('message');

        $to = Yii::app()->request->getPost('to');
        if ($to != 'customer' && $to != 'worker') die;

        $files = Yii::app()->request->getPost('files', array());

        $model = new ProjectComment();

        $model->user_id = Yii::app()->user->id;
        $model->project_id = $project->id;
        $model->text = $message;
        $model->mode = $to;

        if ($model->save()) {
            $to_slider = array();

            foreach ($files as $ind => $_file) {

                $file = ProjectCommentFile::model()->findByPk($_file['id']);
                if (!$file) continue;

                $new_file = new ProjectCommentFile();

                $new_file->comment_id = $model->id;
                $new_file->file = $file->file;
                $new_file->real_filename = $file->real_filename;
                $new_file->file_size = $file->file_size;
                $new_file->pos = $ind + 1;

                if(!$new_file->save()){
                    print_r($new_file->getErrors());
                }

                if ($_file['to_slider']) {
                    $to_slider[] = $new_file;
                }
            }

            if (!empty($to_slider)) {
                $page = new SliderPage();
                $page->project_id = $project->id;
                $page->save();

                foreach ($to_slider as $file) {
                    $slide_item = new SliderItem();

                    $slide_item->page_id = $page->id;
                    $slide_item->comment_file_id = $file->id;
                    $slide_item->file = $file->file;
                    $slide_item->real_filename = $file->real_filename;

                    $slide_item->save();
                }

            }
        }

        Yii::app()->end();
    }

    public function actionDelete_Files()
    {
        if (!Yii::app()->request->isAjaxRequest) {
            throw new CHttpException(404);
        }

        $files = Yii::app()->request->getPost('files', array());

        foreach ($files as $file_id) {
            ProjectCommentFile::model()->deleteByPk($file_id);
        }

        Yii::app()->end();
    }


    public function actionFiles($project_id = '', $hash = '', $page_id = 0)
    {
        $this->layout = 'none';

        $project = Project::model()->findByPk($project_id);
        if (!$project || $hash != $project->out_hash) {
            throw new CHttpException(404);
        }

        $page = SliderPage::model()->findByPk($page_id);
        if ($page->project_id != $project_id) {
            throw new CHttpException(404);
        }

        foreach ($page->items as $item) {
            if ($item->file_url) {
                $files[] = $item;
            }
        }

        $this->render('files', array('project' => $project, 'items' => $files));
    }

    public function actionFlash($id = 0)
    {
        if (Yii::app()->user->isGuest) {
            throw new CHttpException(404);
        }
        ;

        $this->layout = 'none';

        $file = ProjectCommentFile::model()->findByPk($id);
        if (!$file) {
            throw new CHttpException(404);
        }

        $this->render('flash', array('file' => $file));
    }


    public function actionMark_comment($id)
    {
        $comment = ProjectComment::model()->findByPk($id);

        if (!$comment) {
            throw new CHttpException(404);
        }

        $f = fopen('data.txt', 'w+');
        fwrite($f, print_r($_SERVER, true));
        fclose($f);

        if ($comment->user_id == Yii::app()->user->id) {
            Yii::app()->end();
        }

        $project_comment_read = new ProjectCommentRead();
        $project_comment_read->comment_id = $id;
        $project_comment_read->user_id = Yii::app()->user->id;
        $project_comment_read->save();
    }
}