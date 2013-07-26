<div class="message role-<?=$data->user->role?> <?=$data->readed ? 'readed' : 'no-read'?>" data-id="<?=$data->id?>">
    <a href="/projects/<?= $data->project->id ?><?=Yii::app()->user->role == 'admin' ? ($data->user->role == 'worker' ? '?mode=worker' : ($data->user->role == 'customer' ? '?mode=customer' : '')) : ''?>">
        <p class="header"><?=$data->project->name?></p>
        <p><?=$data->getPlainText(200)?></p>
    </a>
</div>