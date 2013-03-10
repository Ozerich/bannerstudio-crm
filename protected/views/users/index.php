<h1><?=isset($page_header) ? $page_header : 'Пользователи'?></h1>

<div class="btn-group">
    <a class="btn" href="/users">Все типы</a>
    <? foreach (User::$roles_multiple as $id => $label): ?>
    <a class="btn" href="/users/?role=<?=$id?>"><?=$label?></a>
    <? endforeach; ?>
</div>

<?

$buttons = array();

if (Yii::app()->user->checkAccess('Users.Edit')) {
    $buttons['update'] = array(
        'label' => 'Редактировать',
        'url' => "CHtml::normalizeUrl(array('users/edit', 'id'=>\$data->id))",
    );
}

if (Yii::app()->user->checkAccess('Users.Delete')) {
    $buttons['delete'] = array(
        'label' => 'Удалить',
        'url' => "CHtml::normalizeUrl(array('users/delete', 'id'=>\$data->id))",
    );
}

$this->widget('bootstrap.widgets.TbGridView', array(
    'dataProvider' => $dataProvider,
    'type' => 'striped bordered condensed',
    'template' => "{pager}{items}{pager}",
    'columns' => array(
        array('name' => 'id', 'htmlOptions' => array('class' => 'span1')),
        array('name' => 'login', 'htmlOptions' => array('class' => 'span2'), 'type' => 'raw', 'value' => 'CHtml::link($data->login, array("users/edit", "id"=>$data->id))'),
        array('name' => 'email', 'htmlOptions' => array('class' => 'span2')),
        array('name' => 'role', 'value' => 'User::$roles[$data->role]', 'htmlOptions' => array('class' => 'span3')),
        array('name' => 'contact'),
        array('name' => 'hide_information'),
        array('name' => 'last_visit', 'value' => 'Yii::app()->prettydate->relativeTime($data->last_visit)'),
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{update} | {delete}',
            'buttons' => $buttons,
        ),

    )));
?>