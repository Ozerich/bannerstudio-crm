<div id="page_index">

    <div class="widget" id="widget_projects">

        <div class="w-header">
            <h2>Проекты</h2>
            <a href="/projects/create" class="btn btn-info"><i class="icon-plus icon-white"></i> Создать</a>
        </div>

        <div class="w-content">
            <?=$this->renderPartial('/projects/tables/_table', array('dataProvider' => $projects_dataProvider));?>
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