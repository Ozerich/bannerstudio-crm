<?php

class SliderPage extends CActiveRecord
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'slider_pages';
    }


    public function rules()
    {
        return array(
            array('project_id', 'required')
        );
    }


    public function relations()
    {
        return array(
            'project' => array(self::BELONGS_TO, 'Project', 'project_id'),
            'items' => array(self::HAS_MANY, 'SliderItem', 'page_id'),
        );
    }

    public function afterDelete()
    {
        foreach ($this->items as $item) {
            $item->delete();
        }
    }
}