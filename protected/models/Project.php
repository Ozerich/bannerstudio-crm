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

    public $workers_list;
    public $customers_list;

    public $workers = array();
    public $customers = array();

    public static $statuses = array(
        'customer_created' => 'Создан заказчиком',
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

            array('worker_price, customer_price, worker_text, customer_text, workers_list, customers_list, status', 'safe'),

            array('id, name, worker_price, customer_price, worker_text, customer_text, created_time, status,workers_list,customers_list', 'safe', 'on' => 'search'),
        );
    }

    public function relations()
    {
        return array(
            'comments' => array(self::HAS_MANY, 'ProjectComment', 'project_id'),
            'slider_items' => array(self::HAS_MANY, 'SliderItem', 'project_id'),
        );
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

    public function afterSave()
    {
        if ($this->isNewRecord && Yii::app()->user->role == 'customer') {

            $message = new YiiMailMessage;

            $message->subject = 'Новый проект от заказчика';
            $message->view = 'new_project_from_customer';
            $message->from = Yii::app()->params['email_admin'];
            $message->to = Yii::app()->params['email_admin'];

            $message->setBody(array(
                'project' => $this
            ), 'text/html');

            Yii::app()->mail->send($message);

        }
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

        foreach ($this->comments as $comment) {
            $comment->delete();
        }

        foreach ($this->slider_items as $item) {
            $item->delete();
        }
    }


    public function checkAccess()
    {
        if (Yii::app()->user->role == 'admin') {
            return true;
        }

        return ProjectUser::model()->countByAttributes(array(
            'project_id' => $this->id,
            'user_id' => Yii::app()->user->id
        )) > 0;
    }

    public static function FindLastProjects($count = 10)
    {
        $criteria = new CDbCriteria();
        $criteria->compare('user_id', Yii::app()->user->id);
        $criteria->limit = 10;

        $projects_for_user = ProjectUser::model()->findAll($criteria);

        $projects = array();
        foreach ($projects_for_user as $_project) {
            $projects[] = Project::model()->findByPk($_project->project_id);
        }

        return $projects;
    }


    public function getSlidesCount()
    {
        $max = 0;
        $items = SliderItem::model()->findAllByAttributes(array('project_id' => $this->id));
        foreach ($items as $item) {
            $max = max($max, $item->page);
        }
        return $max;
    }
}