<?

if (Yii::app()->user->checkAccess('Projects.Edit')) {
    $buttons['update'] = array(
        'label' => 'Редактировать',
        'url' => "CHtml::normalizeUrl(array('projects/edit', 'id'=>\$data->id))",
    );
}

if (Yii::app()->user->checkAccess('Projects.Delete')) {
    $buttons['delete'] = array(
        'label' => 'Удалить',
        'url' => "CHtml::normalizeUrl(array('projects/delete', 'id'=>\$data->id))",
    );
}

$this->widget('bootstrap.widgets.TbGridView', array(
    'dataProvider' => $dataProvider,
    'type' => 'striped bordered condensed',
    'template' => "{items}{pager}",
    'htmlOptions' => array('class' => 'projects-table'),
    'columns' => array(

        array('header' => 'Название | Дата', 'htmlOptions' => array('class' => 'column-name_date'), 'type' => 'html', 'value' => function ($data, $row) {
            return '<a href="/projects/' . $data->id . '" class="name">' . $data->name . " (ID " . $data->id . ')</a><span class="date">' . $data->created_time . '</span>';
        }),

        array('header' => 'Стоимость', 'htmlOptions' => array('class' => 'column-price'), 'type' => 'html', 'value' => function ($data, $row) {
            $result = '';

            foreach ($data->workers as $user) {
                $result .= '<div class="user"><span class="price worker">' . $data->worker_price . '</span><span class="username worker">' . $user->login . '</span></div>';
            }

            foreach ($data->customers as $user) {
                $result .= '<div class="user"><span class="price customer">' . $data->customer_price . '</span><span class="username customer">' . $user->login . '</span></div>';
            }

            return $result;
        }),

        array('header' => 'Статус', 'htmlOptions' => array('class' => 'column-status'), 'type' => 'html', 'value' => function ($data, $row) {
            return $data->closed ? '<span class="status status-closed">ЗАКРЫТ</span>' : '<span class="status">' . Project::$statuses[$data->status] . '</span>';
        }),

        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{update} | {delete}',
            'buttons' => $buttons,
        ),

    )));

?>
