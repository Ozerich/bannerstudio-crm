<?php

/*
 * @property string $id
 * @property string $role
 * @property string $email
 * @property string $login
 * @property string $password
 * @property string $salt
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
            array('role, email, login, salt, time_created', 'required'),
            array('email, login', 'unique'),
            array('role', 'length', 'max' => 8),
            array('email', 'email'),
            array('email, login, salt', 'length', 'max' => 255),
            array('contact, hide_information, password, send_invite', 'safe'),

            array('password', 'required', 'on' => 'insert'),

            array('avatar', 'file', 'types' => 'jpg, png, jpeg, bmp, gif', 'maxSize' => 1024 * 1024 * 10, 'tooLarge' => 'Файл имеет большой размер', 'allowEmpty' => true),

            array('id, role, email, login, password, salt, contact, time_created, last_visit', 'safe', 'on' => 'search'),
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

    public function generateSalt($cost = 10)
    {
        $rand = '';
        for ($i = 0; $i < 8; ++$i)
            $rand .= pack('S', mt_rand(0, 0xffff));
        $rand .= microtime();
        $rand = sha1($rand, true);
        $salt = '$2a$' . str_pad((int)$cost, 2, '0', STR_PAD_RIGHT) . '$';
        $salt .= strtr(substr(base64_encode($rand), 0, 22), array('+' => '.'));
        return $salt;
    }

    public function beforeValidate()
    {
        if ($this->isNewRecord) {
            $this->salt = $this->generateSalt();
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

        $project_ids = array();

        if (Yii::app()->user->role == 'admin') {
            foreach (Project::model()->findAll() as $project) {
                $project_ids[] = array('time' => Yii::app()->user->getModel()->time_created, 'id' => $project->id);
            }
        } else {
            foreach (ProjectUser::model()->findAllByAttributes(array('user_id' => Yii::app()->user->id)) as $project) {
                $project_ids[] = array('time' => $project->datetime, 'id' => $project->project_id);
            }
        }

        $comments_all = array();
        foreach ($project_ids as $project_info) {

            $project_id = $project_info['id'];
            $project_time = strtotime($project_info['time']);

            if (Yii::app()->user->role == 'admin') {
                $project_comments = ProjectComment::model()->findAllByAttributes(array('project_id' => $project_id));
            } else {

                $project_comments = ProjectComment::model()->findAllByAttributes(array('project_id' => $project_id,
                    'mode' => Yii::app()->user->role,
                    'user_id' => $this->id,
                ));

                $admins = User::model()->findAllByAttributes(array('role' => 'admin'));
                foreach($admins as $admin){
                    $project_comments = array_merge($project_comments, ProjectComment::model()->findAllByAttributes(array('project_id' => $project_id,
                        'mode' => Yii::app()->user->role,
                        'user_id' => $admin->id,
                    )));
                }
            }

            $project_comments_filtered = array();

            foreach ($project_comments as $comment) {
                if(strtotime($comment->datetime) >= $project_time){
                    $project_comments_filtered[] = $comment;
                }
            }

            $comments_all = array_merge($comments_all, $project_comments_filtered);
        }


        $result = array();
        foreach ($comments_all as $comment) {
            if ($comment->user_id != Yii::app()->user->id && strtotime($comment->datetime) > $from_time) {
                $result[] = $comment;
            }
        }

        return $result;
    }
}