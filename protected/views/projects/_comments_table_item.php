<ul class="row role-<?=$data->user->role?> <?=$data->readed ? 'readed' : 'no-read'?>" data-id="<?=$data->id?>">
    <li class="col-project">
        <a href="/projects/<?= $data->project->id ?><?=Yii::app()->user->role == 'admin' ? ($data->user->role == 'worker' ? '?mode=worker' : ($data->user->role == 'customer' ? '?mode=customer' : '')) : ''?>"><?=$data->project->name?></a>
    </li>
    <li class="col-message">

        <div class="user-photo-wr">
            <div class="user-photo">
                <img src="<?= $data->user->avatar_url ?>">
            </div>
        </div>

        <div class="message-content">
            <? if(Yii::app()->user->role == 'admin'): ?>
            <a href="/users/<?=$data->user->id?>" class="username"><?=$data->user->display_name?></a>
            <? else: ?>
                <span class="username"><?=$data->user->display_name?></span>
            <? endif; ?>
            <p><?=$data->getPlainText(500)?></p>
            <span class="time-label"><?=$data->datetime_short_str?></span>
        </div>

    </li>
    <li class="clearfix"></li>
</ul>