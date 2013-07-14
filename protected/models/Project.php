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
        'status_1' => 'Статус 1',
        'status_2' => 'Статус 2',
        'status_3' => 'Статус 3',
        'status_4' => 'Статус 4',
    );

    public static $default_status = 'status_1';

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
            array('name, closed, status, out_hash', 'required'),

            //     array('worker_text, customer_text', 'filter', 'filter' => 'strip_tags'),

            array('worker_price, customer_price, worker_text, customer_text, workers_list, customers_list, status, worker_price, customer_price', 'safe'),

            array('id, name, worker_price, customer_price, worker_text, customer_text, created_time, status,workers_list,customers_list', 'safe', 'on' => 'search'),
        );
    }

    public function relations()
    {
        return array(
            'comments' => array(self::HAS_MANY, 'ProjectComment', 'project_id'),
            'slider_pages' => array(self::HAS_MANY, 'SliderPage', 'project_id'),
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

    public function beforeValidate()
    {
        if($this->status == '' || !in_array($this->status, self::$statuses)){
            $this->status = self::$default_status;
        }

        return true;
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

        if (!in_array($this->status, self::$statuses)) {
            $this->status = self::$default_status;
            $this->save();
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

        foreach ($this->slider_pages as $page) {
            $page->delete();
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
        if (Yii::app()->user->role == 'admin') {
            $criteria = new CDbCriteria();
            $criteria->compare('closed', 0);
            $criteria->order = 'created_time DESC';
            $criteria->limit = $count;

            return Project::model()->findAll($criteria);
        } else {

            $projects = array();

            foreach (Yii::app()->user->getModel()->getProjects() as $project) {
                if ($project->closed == 0) {
                    $projects[] = $project;
                }
                if (count($projects) == $count) {
                    break;
                }
            }

            return $projects;

        }

    }


    // Подготавливает текст для отображения в HTML
    public function prepareText($text)
    {
        $text = preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@', '<a href="$1" target="_blank">$1</a>', $text);

        return nl2br($text);
    }

    // Возвращает всех сотрудников и заказчиков в данном проекте.
    public function getUsers($mode = 'all')
    {
        $result = array();

        foreach (ProjectUser::model()->findAllByAttributes(array('project_id' => $this->id)) as $_project_user) {
            $user = User::model()->findByPk($_project_user->user_id);
            if ($user && ($mode == 'all' || $user->role == $mode)) {
                $result[] = $user;
            }
        }

        return $result;
    }


    public function getViewUrl($mode = '')
    {
        return Yii::app()->getBaseUrl(true) . '/projects/' . $this->id . ($mode ? '?mode=' . $mode : '');
    }

    public function getOutUrl($mode = '')
    {
        return Yii::app()->getBaseUrl(true) . '/projects/files/' . $this->id . '/' . $this->out_hash;
    }

}