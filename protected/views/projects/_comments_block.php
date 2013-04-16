<?
    $dataProvider=new CActiveDataProvider('ProjectComment', array(
        'criteria'=>array(
            'condition'=>'project_id = '.$project->id.' AND mode = "'.$type.'"',
            'order'=>'datetime DESC',
        ),
        'pagination'=>array(
            'pageSize'=>3,
        ),
    ));

   $this->widget('bootstrap.widgets.TbListView', array(
       'dataProvider'=>$dataProvider,
       'itemView'=>'_comment',
       'template' => "{items}\n{pager}",
       'enablePagination' => true,
   ));
?>