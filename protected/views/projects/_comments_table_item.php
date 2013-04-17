<ul class="row role-<?=$data->user->role?> <?=$data->readed ? 'readed' : 'no-read'?>">
    <li class="col-project">
        <a href="/projects/<?= $data->project->id ?>"><?=$data->project->name?></a>
    </li>
    <li class="col-message">

        <div class="user-photo-wr">
            <div class="user-photo">
                <img src="<?= $data->user->avatar_url ?>">
            </div>
        </div>

        <div class="message-content">
            <span class="username"><?=$data->user->display_name?></span>

            <p><?=nl2br($data->text)?></p>
            <span class="time-label"><?=$data->datetime_short_str?></span>
        </div>

    </li>
    <li class="clearfix"></li>
</ul>