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

}