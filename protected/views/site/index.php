<div id="page_index">

    <div class="widget" id="widget_projects">

        <div class="w-header">
            <h2>Проекты</h2>

            <? if (Yii::app()->user->checkAccess('Projects.Create')): ?>
                <a href="/projects/create" class="btn btn-info"><i class="icon-plus icon-white"></i> Создать</a>
            <? endif; ?>
        </div>

        <div class="w-content">
            <?=$this->renderPartial('/projects/tables/_table', array('dataProvider' => $projects_dataProvider));?>
        </div>
    </div>


    <div class="widget" id="widget_comments">

        <? $dataProvider = $this->getCommentsDataProvider();
        $noread_exist = false;
        foreach ($dataProvider->data as $comment) {
            if ($comment->readed == false) {
                $noread_exist = true;
                break;
            }
        }
        ?>

        <div class="w-header">
            <h2>Последние сообщения</h2>
            <button class="btn btn-info btn-mini mark_all_read-btn" style="display: <?= $noread_exist ? 'inline' : 'none' ?>">Все сообщения
                прочитаны
            </button>
        </div>

        <div class="w-content">
            <div class="w-table comments-table">

                <div class="w-thead">

                    <ul class="row">
                        <li class="col-project">В проекте</li>
                        <li class="col-message">Сообщение</li>
                        <li class="clearfix"></li>
                    </ul>
                </div>

                <div class="w-tbody">
                    <? $this->widget('bootstrap.widgets.TbListView', array(
                        'dataProvider' => $dataProvider,
                        'itemView' => '/projects/_comments_table_item',
                        'template' => "{items}",
                        'enablePagination' => false,
                    ));
                    ?>
                </div>

            </div>
        </div>
    </div>

    <br clear="all"/>

</div>