<h1><?=isset($page_title) ? $page_title : 'Новый пользователь'?></h1>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>