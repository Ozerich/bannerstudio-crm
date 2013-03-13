<?php

/*
 * @property string $id
 * @property string $user_id
 * @property string $project_id
 */
class ProjectUser extends CActiveRecord
{

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'project_users';
    }

    public function rules()
    {
        return array(
            array('user_id, project_id', 'required'),
        );
    }


    public static function add($project_id, $user_id)
    {
        if (is_numeric($project_id) && is_numeric($user_id)) {

            self::model()->deleteAllByAttributes(array(
                'project_id' => $project_id,
                'user_id' => $user_id
            ));

            $model = new self;
            $model->project_id = $project_id;
            $model->user_id = $user_id;

            return $model->save();
        }

        return FALSE;
    }
}