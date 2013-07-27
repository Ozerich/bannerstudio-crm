<?php

/*
 * @property string $id
 * @property string $role
 * @property string $email
 * @property string $login
 * @property string $password
 * @property string $contact
 * @property string $time_created
 * @property string $last_visit
 * @property string $hide_information
 * @property string $avatar
 */
class User extends CActiveRecord
{
    // Высылать приглашение на почту
    public $send_invite;

    // ссылка на аватарку
    public $avatar_url;

    // нужно ли генерировать пароль
    public $need_generate_password = false;

    // незашифрованный проль
    public $real_password = '';

    // Отображаемое имя
    public $display_name = '';

    // Отображаемая дата регистрации
    public $display_register_date = '';

    // Отображаемая дата последнего входа
    public $display_last_visit = '';

    public static $roles = array(
        'admin' => 'Администратор',
        'worker' => 'Сотрудник',
        'customer' => 'Клиент'
    );

    public static $roles_multiple = array(
        'admin' => 'Администраторы',
        'worker' => 'Сотрудники',
        'customer' => 'Клиенты'
    );

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'users';
    }

    public function rules()
    {
        return array(
            array('role, email, login, time_created', 'required'),
            array('email, login', 'unique'),
            array('role', 'length', 'max' => 8),
            array('email', 'email'),
            array('email, login', 'length', 'max' => 255),
            array('contact, hide_information, password, send_invite', 'safe'),

            array('password', 'required', 'on' => 'insert'),

            array('avatar', 'file', 'types' => 'jpg, png, jpeg, bmp, gif', 'maxSize' => 1024 * 1024 * 10, 'tooLarge' => 'Файл имеет большой размер', 'allowEmpty' => true),

            array('id, role, email, login, password, contact, time_created, last_visit', 'safe', 'on' => 'search'),
        );
    }

    public function relations()
    {
        return array(
            'comments' => array(self::HAS_MANY, 'ProjectComment', 'user_id')
        );
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'role' => 'Тип пользователя',
            'email' => 'E-mail',
            'login' => 'Логин',
            'password' => $this->scenario == 'insert' ? 'Пароль' : 'Новый пароль',
            'contact' => 'Контактная информация',
            'time_created' => 'Время регистрации',
            'last_visit' => 'Последний визит',
            'hide_information' => 'Скрытая информация',
            'send_invite' => 'Отправить приглашение на почту?',
            'avatar' => (!empty($this->avatar) ? 'Новая ' : '') . 'Фотография (размер: 50 x 50)',
        );
    }

    public static function getRandomPassword()
    {
        return rand() % 1000000 + 1000000;
    }

    public function validatePassword($password)
    {
        return md5($password) === $this->password;
    }

    public function hashPassword($password)
    {
        return md5($password);
    }

    public function beforeValidate()
    {
        if ($this->isNewRecord) {
            $this->time_created = date("Y-m-d H:i:s");
        }

        return parent::beforeValidate();
    }

    public function beforeSave()
    {
        if ($this->isNewRecord || $this->need_generate_password) {
            $this->real_password = $this->password;
            $this->password = $this->hashPassword($this->password);
        }

        if ($this->isNewRecord && $this->send_invite) {

            $message = new YiiMailMessage;

            $message->subject = 'Уважаемый, ' . $this->login . '! Вы были успешно зарегистрированы на сайте BannerStudio.ru';
            $message->view = 'register_invite';
            $message->from = Yii::app()->params['email_admin'];
            $message->to = $this->email;

            $message->setBody(array(
                'model' => $this,
            ), 'text/html');

            Yii::app()->mail->send($message);

        }


        foreach ($this->comments as $comment) {
            $comment->mode = $this->role;
            $comment->save();
        }


        return parent::beforeSave();
    }


    public function afterFind()
    {
        $this->avatar_url = empty($this->avatar) ? "/img/no-avatar.png" : Yii::app()->params['upload_avatar'] . $this->avatar;

        $this->display_name = $this->login;

        $this->display_register_date = date('d.m.Y H:i', strtotime($this->time_created));

        if ($this->last_visit === null) {
            $this->display_last_visit = 'Не заходил';
        } else {
            $this->display_last_visit = date('d.m.Y H:i', strtotime($this->last_visit));
        }
    }

    public function afterSave()
    {
        Yii::app()->authManager->revoke('admin', $this->id);
        Yii::app()->authManager->revoke('customer', $this->id);
        Yii::app()->authManager->revoke('worker', $this->id);
        Yii::app()->authManager->assign($this->role, $this->id);
    }


    public static function GetWorkers()
    {
        return self::model()->findAllByAttributes(array('role' => 'worker'));
    }

    public static function GetCustomers()
    {
        return self::model()->findAllByAttributes(array('role' => 'customer'));
    }

    public static function GetAdmins()
    {
        return self::model()->findAllByAttributes(array('role' => 'admin'));
    }


    // Возвращает проекты где задействован пользователь
    public function getProjects()
    {
        $result = array();

        if ($this->role == 'admin') {
            $result = Project::model()->findAll();
        } else {
            foreach (ProjectUser::model()->findAllByAttributes(array('user_id' => $this->id)) as $_project_user) {
                $project = Project::model()->findByPk($_project_user->project_id);
                if ($project) {
                    $result[] = $project;
                }
            }
        }

        usort($result, 'sort_projects_function');

        return $result;
    }


    public function afterDelete()
    {
        foreach ($this->comments as $comment) {
            $comment->delete();
        }

        foreach (ProjectUser::model()->findAllByAttributes(array('user_id' => $this->id)) as $project_user) {
            $project_user->delete();
        }
    }


    public function getInboxComments($from_time = 0)
    {
        $projects = array();
        if ($this->role == 'admin') {
            $projects = Project::model()->findAll();
        } else {
            foreach (ProjectUser::model()->findAllByAttributes(array('user_id' => $this->id)) as $_project_user) {
                $project = Project::model()->findByPk($_project_user->project_id);
                if ($project) {
                    $projects[] = $project;
                }
            }
        }
        if (empty($projects)) {
            return array();
        }

        $comments = array();
        foreach ($projects as $project) {
            foreach ($project->comments as $comment) {
                if ($this->role == 'admin') {
                    if ($comment->user_id != $this->id) {
                        $comments[] = $comment;
                    }
                } else {
                    if ($comment->mode == $this->role) {
                        $comments[] = $comment;
                    }
                }
            }
        }

        $result = array();

        foreach ($comments as $comment) {
            if (strtotime($comment->datetime) > strtotime($comment->user->time_created) && strtotime($comment->datetime) >= $from_time) {
                $result[] = array(
                    'comment' => $comment,
                    'readed' => ProjectCommentRead::model()->countByAttributes(array('user_id' => $this->id, 'comment_id' => $comment->id)) > 0
                );
            }
        }

        return $result;
    }
}