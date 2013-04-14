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

            array('avatar', 'file', 'types' => 'jpg, png, jpeg, bmp', 'maxSize' => 1024 * 1024 * 10, 'tooLarge' => 'Файл имеет большой размер', 'allowEmpty' => true),

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
        return crypt($password, $this->salt) === $this->password;
    }

    public function hashPassword($password)
    {
        return crypt($password, $this->salt);
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


    public static function GetWorkers()
    {
        return self::model()->findAllByAttributes(array('role' => 'worker'));
    }

    public static function GetCustomers()
    {
        return self::model()->findAllByAttributes(array('role' => 'customer'));
    }


    // Возвращает проекты где задействован пользователь
    public function getProjects()
    {
        $result = array();

        foreach (ProjectUser::model()->findAllByAttributes(array('user_id' => $this->id)) as $_project_user) {
            $project = Project::model()->findByPk($_project_user->project_id);
            if ($project) {
                $result[] = $project;
            }
        }

        return $result;
    }


    public function afterDelete()
    {
        foreach ($this->comments as $comment) {
            $comment->delete();
        }
    }

}