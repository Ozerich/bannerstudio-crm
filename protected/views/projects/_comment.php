<div class="comment-item comment-<?=$data->user->role?>" data-id="<?=$data->id?>">

    <div class="user-photo-wr">
        <div class="user-photo">
            <img src="<?= $data->user->avatar_url ?>"/>
        </div>
    </div>

    <div class="line"></div>

    <div class="comment-content">
        <span class="user-name"><?=$data->user->display_name?></span>

        <p><?=nl2br($data->text)?></p>

        <ul class="files">
            <? foreach($data->files as $file): ?>
                <li data-id="<?=$file->id?>">
                    <? if(Yii::app()->user->role == 'admin'): ?><input type="checkbox"><? endif; ?>
                    <span class="filename"><?=$file->real_filename?></span>
                    <span class="size">(<?=$file->file_size_str?>)</span>
                    <? if($file->can_view): ?><a href="<?=$file->url?>" target="_blank" class="btn btn-mini">Открыть</a><? endif; ?>
                    <a href="/projects/download/<?=$file->id?>" target="_blank" class="btn btn-mini">Скачать</a>
                    <? if(Yii::app()->user->role == 'admin'): ?><a href="#" class="btn btn-mini btn-delete">Удалить</a><? endif; ?>
                </li>
            <? endforeach; ?>
        </ul>

        <span class="time-label"><?=$data->datetime_str?></span>
    </div>

</div>