<?

$admins = User::model()->findAllByAttributes(array('role' => 'admin'));
$admin_ids = array();

foreach ($admins as $admin) {
    $admin_ids[] = $admin->id;
}

$admins_cond = implode(' OR user_id = ', $admin_ids);
$admins_cond = empty($admin_ids) ? '' : ' OR user_id = '.$admins_cond;

$dataProvider = new CActiveDataProvider('ProjectComment', array(
    'criteria' => array(
        'condition' => 'project_id = ' . $project->id . ' AND mode = "' . $type . '"' . (Yii::app()->user->role != 'admin' ? ' AND (user_id = ' . Yii::app()->user->id. $admins_cond.')' : ''),
        'order' => 'datetime DESC',
    ),
    'pagination' => array(
        'pageSize' => 80,
    ),
));


$this->widget('bootstrap.widgets.TbListView', array(
    'dataProvider' => $dataProvider,
    'itemView' => '_comment',
    'template' => "{items}\n{pager}",
    'enablePagination' => true,
    'beforeAjaxUpdate' => "function(){
           window.forceScroll = true;
           $('html, body').animate({scrollTop:0}, 500, 'swing', function(){
                window.forceScroll = false;
           });
       }",
));
?>