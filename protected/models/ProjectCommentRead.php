<?php

class ProjectCommentRead extends CActiveRecord
{

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'project_comment_reads';
    }


    public function rules()
    {
        return array(
            array('user_id, comment_id', 'required'),

            array('id, user_id, comment_id', 'safe', 'on' => 'search'),
        );
    }
}