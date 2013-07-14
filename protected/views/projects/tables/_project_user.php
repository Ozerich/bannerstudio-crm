<ul class="row <?=$data->closed ? 'closed' : ''?>"">

    <li class="col-name">
        <a href="/projects/<?=$data->id?>" class="project-name"><?=$data->name?></a>
    </li>

    <li class="col-price">
        <span class="price"><?=Yii::app()->user->role == 'worker' ? $data->worker_price : $data->customer_price;?></span>
    </li>

    <li class="col-status">
        <span class="status"><?=$data->closed ? 'ЗАКРЫТ' : Project::$statuses[$data->status]?></span>
    </li>

    <li class="clearfix"></li>
</ul>