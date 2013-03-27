<div class="w-table projects-table">

    <div class="w-thead">

        <ul class="row">

            <? if (Yii::app()->user->role == 'admin'): ?>
                <li class="col-name">Название | Дата</li>
            <? else: ?>
                <li class="col-name">Название</li>
            <? endif; ?>

            <li class="col-price">Стоимость</li>
            <li class="col-status">Статус</li>
            <li class="clearfix"></li>
        </ul>

    </div>

    <div class="w-tbody">

        <? $this->widget('bootstrap.widgets.TbListView', array(
            'dataProvider' => $dataProvider,
            'itemView' => '/projects/tables/'.(Yii::app()->user->role == 'admin' ? '_project_admin' : '_project_user'),
            'template' => "{items}\n{pager}",
            'enablePagination' => true,
        ));
        ?>


    </div>

</div>