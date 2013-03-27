<h1><?=isset($page_title) ? $page_title : 'Новый проект'?></h1>

<?php echo $this->renderPartial(Yii::app()->user->role == 'admin' ? '_form' : '_user_form', array('model' => $model)); ?>