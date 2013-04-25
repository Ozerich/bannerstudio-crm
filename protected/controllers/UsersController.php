<?php

class UsersController extends Controller
{
    public function filters()
    {
        return array(
            'Rights',
        );
    }

    private function processPost($model, $redirect = '')
    {
        $redirect = empty($redirect) ? Yii::app()->request->urlReferrer : $redirect;
        if (Yii::app()->request->isPostRequest) {

            $old_password = $model->password;
            $model->attributes = Yii::app()->request->getPost('User');

            $uploadedFile = CUploadedFile::getInstance($model, 'avatar');

            if ($uploadedFile) {
                $filename = uniqid() . '.' . $uploadedFile->getExtensionName();
                $model->avatar = $filename;
            }

            $model->need_generate_password = !empty($_POST['User']['password']);
            if (!$model->need_generate_password) {
                $model->password = $old_password;
            }

            if ($model->save()) {

                if ($uploadedFile) {
                    $filename = $_SERVER['DOCUMENT_ROOT'] . Yii::app()->params['upload_avatar'] . $filename;
                    $uploadedFile->saveAs($filename);

                    $thumb = Yii::app()->phpThumb->create($filename);
                    $thumb->resize(48, 48);
                    $thumb->save($filename);

                }

                $this->redirect($redirect);
            }

        }
    }

    public function actionProfile()
    {
        $model = Yii::app()->user->getModel();

        $this->processPost($model);

        $model->password = '';

        $this->breadcrumbs = array('Ваш профиль');
        $this->render('profile', array('model' => $model));
    }

    public function actionDelete($id = 0)
    {
        $model = User::model()->findByPk($id);

        if (!$model) {
            throw new CHttpException(404);
        }

        if ($model->role != 'admin') {
            $model->delete();
        }

        $this->redirect(Yii::app()->request->urlReferrer);
    }

    public function actionIndex($role = '')
    {
        $conditions = !empty($role) ? '`role` = "' . $role . '"' : '';

        $dataProvider = new CActiveDataProvider('User', array(
            'criteria' => array(
                'condition' => $conditions,
            ),
        ));


        if (empty($role)) {
            $this->breadcrumbs = array('Пользователи');
        } else {
            $this->breadcrumbs = array(
                'Пользователи' => '/users/',
                User::$roles_multiple[$role]
            );
        }

        $this->render('index', array(
            'dataProvider' => $dataProvider,
            'page_header' => empty($role) ? 'Пользователи' : User::$roles_multiple[$role]
        ));
    }

    public function actionCreate()
    {
        $model = new User();

        $this->processPost($model, '/users/');

        $this->breadcrumbs = array(
            'Пользователи' => array('/users/'),
            'Новый пользователь',
        );

        $this->render('item', array('model' => $model, 'page_header' => 'Новый пользователь'));
    }

    public function actionEdit($id = 0)
    {
        $model = User::model()->findByPk($id);

        if (!$model) {
            throw new CHttpException(404);
        }

        $this->processPost($model);

        $model->password = '';

        $this->breadcrumbs = array(
            'Пользователи' => array('/users/'),
            'Пользователь ' . $model->login
        );

        $this->render('item', array('model' => $model, 'page_header' => 'Пользователь ' . $model->login));
    }


    public function actionView($id)
    {
        $model = User::model()->findByPk($id);

        if(!$model){
            throw new CHttpException(404);
        }

        $dataProvider = new CArrayDataProvider($model->getProjects());

        $this->breadcrumbs = array(
            'Пользователи' => array('/users'),
            'Пользователь ' . $model->login
        );

        $this->render('view', array('model' => $model, 'page_header' => 'Пользователь ' . $model->login, 'dataProvider' => $dataProvider));
    }

}