<div class="page" id="page_project">

    <div class="header">
        <h1><?=$model->name?> (ID <?=$model->id?>)</h1>
        <span class="price"><?=$model->worker_price?> руб.</span>
        <span class="status"><?=Project::$statuses[$model->status]?></span>
    </div>

</div>