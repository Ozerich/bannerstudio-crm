<ul class="row">

    <li class="col-name">
        <a href="/projects/<?=$data->id?>" target="_blank" class="project-name"><?=$data->name?> (ID <?=$data->id?>)</a>
        <span class="datetime"><?=$data->created_time?></span>
    </li>

    <li class="col-price">

        <? foreach ($data->workers as $worker): ?>

        <div class="user worker">
            <span class="price"><?=$data->worker_price?> руб.</span>
            <span class="username"><?=$worker->display_name?></span>
        </div>
        <? endforeach; ?>

        <? foreach ($data->customers as $customer): ?>

        <div class="user customer">
            <span class="price"><?=$data->customer_price?> руб.</span>
            <span class="username"><?=$customer->display_name?></span>
        </div>
        <? endforeach; ?>

    </li>

    <li class="col-status">
        <div class="buttons">
            <a href="/projects/edit/<?=$data->id?>" class="btn btn-mini"><i class="icon icon-pencil"></i></a>
            <a href="/projects/delete/<?=$data->id?>"
               onclick="return confirm('Вы уверены, что хотите удалить проект?');" class="btn btn-mini btn-danger"><i
                    class="icon icon-trash"></i></a>
        </div>
        <span class="status"><?=$data->closed ? 'ЗАКРЫТ' : Project::$statuses[$data->status]?></span>
    </li>

    <li class="clearfix"></li>
</ul>