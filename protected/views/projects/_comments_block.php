<?

$dataProvider = new CActiveDataProvider('ProjectComment', array(
    'criteria' => array(
        'condition' => 'project_id = ' . $project->id . ' AND `mode` = "' . $type . '"',
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