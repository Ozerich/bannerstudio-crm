<? if(Yii::app()->user->role != 'customer'): ?>
<div mode="worker">
    <?=$this->renderPartial('_comments_block', array('project' => $model, 'type' => 'worker'));?>
</div>
<? endif; ?>

<? if(Yii::app()->user->role != 'worker'): ?>
<div mode="customer">
    <?=$this->renderPartial('_comments_block', array('project' => $model, 'type' => 'customer'));?>
</div>
<? endif; ?>