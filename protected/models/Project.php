<?php

/*
 * @property string $id
 * @property string $name
 * @property string $worker_price
 * @property string $customer_price
 * @property string $worker_text
 * @property string $customer_text
 * @property string $created_time
 * @property string $closed
 * @property string $status
 */
class Project extends CActiveRecord
{

    public $workers_list = '';
    public $customers_list = '';

    public $workers = array();
    public $customers = array();

    public static $statuses = array(
        'status_1' => 'Статус 1',
        'status_2' => 'Статус 2',
        'status_3' => 'Статус 3',
        'status_4' => 'Статус 4',
    );

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'projects';
    }

    public function rules()
    {
        return array(
            array('name, closed, status', 'required'),
            array('worker_price, customer_price', 'numerical'),

            //     array('worker_text, customer_text', 'filter', 'filter' => 'strip_tags'),

            array('worker_price, customer_price, worker_text, customer_text', 'safe'),

            array('id, name, worker_price, customer_price, worker_text, customer_text, created_time', 'safe', 'on' => 'search'),
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
            'worker_price' => 'Стоимость для сотрудника',
            'customer_price' => 'Стоимость для заказчика',
            'worker_text' => 'Описание для сотрудника',
            'customer_text' => 'Описание для заказчика',
            'name' => 'Название проекта',
            'created_time' => 'Дата создания',
            'status' => 'Статус',
            'closed' => 'Закрыт',
        );
    }


    public function beforeSave()
    {
        if ($this->isNewRecord) {
            $this->created_time = new CDbExpression('NOW()');
        }

        return parent::beforeSave();
    }


    public function afterFind()
    {
        $users = ProjectUser::model()->findAllByAttributes(array('project_id' => $this->id));

        foreach ($users as $_user) {
            $user = User::model()->findByPk($_user->user_id);
            if (!$user || $user->role === 'admin') {
                $_user->delete();
                continue;
            }

            if ($user->role == 'customer') {
                $this->customers[] = $user;
                $this->customers_list .= ($this->customers_list ? ',' : '') . $user->id;
            } elseif ($user->role == 'worker') {
                $this->workers[] = $user;
                $this->workers_list .= ($this->workers_list ? ',' : '') . $user->id;
            }
        }

    }

    public function afterDelete()
    {
        ProjectUser::model()->deleteAllByAttributes(array(
            'project_id' => $this->id
        ));
    }


}