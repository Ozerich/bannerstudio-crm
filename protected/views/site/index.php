<div id="page_index">

    <div class="widget" id="widget_projects">

        <div class="w-header">
            <h2>Проекты</h2>
            <a href="/projects/create" class="btn btn-info"><i class="icon-plus icon-white"></i> Создать</a>
        </div>

        <div class="w-content">
            <div class="w-table">

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
                    'dataProvider' => $projects_dataProvider,
                    'itemView' => '_project',
                    'template' => "{items}\n{pager}",
                    'enablePagination' => true,
                ));
                    ?>


                </div>

            </div>
        </div>
    </div>


    <div class="widget" id="widget_comments">

        <div class="w-header">
            <h2>Последние комментарии</h2>
        </div>

        <div class="w-content">
            <div class="w-table">

                <div class="w-thead">

                </div>

                <div class="w-tbody">

                </div>

            </div>
        </div>
    </div>

</div>