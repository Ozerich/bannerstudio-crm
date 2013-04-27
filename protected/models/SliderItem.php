<?php

class SliderItem extends CActiveRecord
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'slider_items';
    }


    public function rules()
    {
        return array(
            array('project_id, page, file', 'required'),
            array('project_id, page', 'numerical', 'integerOnly' => true),
        );
    }


    public function relations()
    {
        return array(
            'project' => array(self::BELONGS_TO, 'Project', 'project_id')
        );
    }
}