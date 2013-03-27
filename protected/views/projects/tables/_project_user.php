<ul class="row">

    <li class="col-name">
        <a href="/projects/<?=$data->id?>" target="_blank" class="project-name"><?=$data->name?></a>
    </li>

    <li class="col-price">
        <span class="price"><?=Yii::app()->user->role == 'worker' ? $data->worker_price : $data->customer_price;?> руб.</span>
    </li>

    <li class="col-status">
        <span class="status"><?=$data->closed ? 'ЗАКРЫТ' : Project::$statuses[$data->status]?></span>
    </li>

    <li class="clearfix"></li>
</ul>