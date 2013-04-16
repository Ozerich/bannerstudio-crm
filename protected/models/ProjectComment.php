<?php

class ProjectComment extends CActiveRecord
{
    public $datetime_str;
    public $datetime_short_str;

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'project_comments';
    }


    public function rules()
    {
        return array(
            array('user_id, project_id, text, mode', 'required'),

            array('text', 'filter', 'filter' => 'strip_tags'),

            array('datetime', 'default', 'value' => new CDbExpression('NOW()'), 'setOnEmpty' => false, 'on' => 'insert'),

            array('id, user_id, project_id, text, date, mode', 'safe', 'on' => 'search'),
        );
    }


    public function relations()
    {
        return array(
            'project' => array(self::BELONGS_TO, 'Project', 'project_id'),
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
            'files' => array(self::HAS_MANY, 'ProjectCommentFile', 'comment_id'),
        );
    }

    public function afterDelete()
    {
        foreach ($this->files as $file) {
            $file->delete();
        }
    }

    public function afterFind()
    {
        $timestamp = strtotime($this->datetime);
        $date_info = getdate($timestamp);

        $monthes = array('января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря');
        $this->datetime_str = $date_info['mday'] . ' ' . $monthes[$date_info['mon'] - 1] . ' ' . ($date_info['hours'] < 10 ? '0' : '').$date_info['hours'] . ':' . ($date_info['minutes'] < 10 ? '0' : '').$date_info['minutes'] . ' (';

        $diff = time() - $timestamp;

        $diff_years   = floor($diff / (365*60*60*24));
        $diff_monthes  = floor(($diff - $diff_years * 365*60*60*24) / (30*60*60*24));
        $diff_days    = floor(($diff - $diff_years * 365*60*60*24 - $diff_monthes*30*60*60*24)/ (60*60*24));

        $diff_hours   = floor(($diff - $diff_years * 365*60*60*24 - $diff_monthes*30*60*60*24 - $diff_days*60*60*24)/ (60*60));
        $diff_minutes  = floor(($diff - $diff_years * 365*60*60*24 - $diff_monthes*30*60*60*24 - $diff_days*60*60*24 - $diff_hours*60*60)/ 60);


        if ($diff_years > 0) {
            $this->datetime_str .= $diff_years . ' ' . ($diff_years == 1 ? 'год' : ($diff_years >= 2 && $diff_years <= 4 ? 'года' : 'лет')) . ', ';
        }

        if ($diff_monthes > 0) {
            $this->datetime_str .= $diff_monthes . ' ' . ($diff_monthes == 1 ? 'месяц' : ($diff_monthes >= 2 && $diff_monthes <= 4 ? 'месяца' : 'месяцев')) . ', ';
        }

        if ($diff_days > 0) {
            $this->datetime_str .= $diff_days . ' ' . ($diff_days % 10 == 1 && $diff_days != 11 ? 'день' : ($diff_days % 10 >= 2 && $diff_days % 10 <= 4 && $diff_days != 12 ? 'дня' : 'дней')) . ', ';
        }

        if ($diff_hours) {
            $this->datetime_str .= $diff_hours . ' ' . ($diff_hours % 10 == 1 && $diff_hours != 11 ? 'час' : ($diff_hours % 10 >= 2 && $diff_hours % 10 <= 4 && $diff_hours != 12 ? 'часа' : 'часов')) . ', ';
        }

        if ($diff_minutes) {
            $this->datetime_str .= $diff_minutes . ' ' . ($diff_minutes % 10 == 1 && $diff_minutes != 11 ? 'минута' : ($diff_minutes % 10 >= 2 && $diff_minutes % 10 <= 4 && $diff_minutes != 12 ? 'минуты' : 'минут'));
        }

        $this->datetime_str = trim($this->datetime_str);
        if ($this->datetime_str[strlen($this->datetime_str) - 1] == ',') {
            $this->datetime_str = substr($this->datetime_str, 0, strlen($this->datetime_str) - 1);
        }

        $this->datetime_str .= $diff_years + $diff_monthes + $diff_hours + $diff_days + $diff_minutes == 0 ? 'меньше минуты назад)' : ' назад)';
        $this->datetime_short_str = substr($this->datetime_str, 0, strpos($this->datetime_str, '('));
    }
}