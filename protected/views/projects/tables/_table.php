<div class="w-table projects-table">

    <div class="w-thead">

        <ul class="row">
            <li class="col-name">Название | Дата</li>
            <li class="col-price">Стоимость</li>
            <li class="col-status">Статус</li>

            <li class="clearfix"></li>
        </ul>

    </div>

    <div class="w-tbody">

        <? $this->widget('bootstrap.widgets.TbListView', array(
        'dataProvider' => $dataProvider,
        'itemView' => '/projects/tables/_project',
        'template' => "{items}\n{pager}",
        'enablePagination' => true,
    ));
        ?>


    </div>

</div>