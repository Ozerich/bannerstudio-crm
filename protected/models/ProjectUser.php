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
            array('datetime', 'safe'),
        );
    }


    public static function add($project_id, $user_id, $send_worker_email = false, $send_customer_email = false)
    {
        if ($send_customer_email || $send_worker_email) {

            if (ProjectUser::model()->countByAttributes(array('project_id' => $project_id, 'user_id' => $user_id)) == 0) {

                $user = User::model()->findByPk($user_id);
                if (!$user) {
                    return FALSE;
                }

                $project = Project::model()->findByPk($project_id);
                if (!$project) {
                    return FALSE;
                }

                if (($user->role === 'worker' && $send_worker_email) || ($user->role === 'customer' && $send_customer_email)) {

                    $message = new YiiMailMessage;

                    $message->subject = 'Уважаемый, ' . $user->login . '! Вас добавили в проект на сайте BannerStudio.ru';
                    $message->view = 'add_to_project';
                    $message->from = Yii::app()->params['email_admin'];
                    $message->to = $user->email;

                    $message->setBody(array(
                        'user' => $user,
                        'project' => $project
                    ), 'text/html');

                    Yii::app()->mail->send($message);

                }
            }
        }

        if (is_numeric($project_id) && is_numeric($user_id)) {

            $attrs = array(
                'project_id' => $project_id,
                'user_id' => $user_id
            );

            $project_user = self::model()->findByAttributes($attrs);
            $last_time = $project_user ? $project_user->datetime : null;
            self::model()->deleteAllByAttributes($attrs);

            $model = new self;
            $model->project_id = $project_id;
            $model->user_id = $user_id;
            $model->datetime = $last_time ? $last_time : new CDbExpression('NOW()');


            $comments = ProjectComment::model()->findAllByAttributes(array('project_id' => $project_id));
            foreach($comments as $comment){
                $read = new ProjectCommentRead();
                $read->user_id = Yii::app()->user->id;
                $read->comment_id = $comment->id;
                $read->save();
            }

            return $model->save();
        }

        return FALSE;
    }
}