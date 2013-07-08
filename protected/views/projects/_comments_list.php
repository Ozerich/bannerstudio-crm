<? if(Yii::app()->user->role != 'customer'): ?>
<div mode="worker" style="display: <?=$mode == 'worker' ? 'block' : 'none'?>">
    <?=$this->renderPartial('_comments_block', array('project' => $model, 'type' => 'worker'));?>
</div>
<? endif; ?>

<? if(Yii::app()->user->role != 'worker'): ?>
<div mode="customer" style="display: <?=$mode == 'customer' ? 'block' : 'none'?>">
    <?=$this->renderPartial('_comments_block', array('project' => $model, 'type' => 'customer'));?>
</div>
<? endif; ?>