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
    protected $need_generate_password = false;

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
            array('role', 'length', 'max' => 8),
            array('email', 'email'),
            array('email, login, password, salt', 'length', 'max' => 255),
            array('contact, hide_information', 'safe'),

            array('password', 'required', 'on' => 'insert'),

            array('avatar', 'file', 'types' => 'jpg, png, jpeg, bmp', 'maxSize' => 1024 * 1024 * 10, 'tooLarge' => 'Файл имеет большой размер', 'allowEmpty' => true),

            array('id, role, email, login, password, salt, contact, time_created, last_visit', 'safe', 'on' => 'search'),
        );
    }

    public function relations()
    {
        return array();
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
        if ($this->isNewRecord || $this->need_generate_password) {
            $this->salt = $this->generateSalt();
        }

        if ($this->isNewRecord) {
            $this->time_created = date("Y-m-d H:i:s");
        }

        return parent::beforeValidate();
    }

    public function beforeSave()
    {
        if ($this->isNewRecord || $this->need_generate_password) {
            $this->password = $this->hashPassword($this->password);
        }

        return parent::beforeSave();
    }


    public function afterFind()
    {
        $this->avatar_url = empty($this->avatar) ? "/img/no-avatar.png" : Yii::app()->params['upload_avatar'] . $this->avatar;
    }

}