<div class="<?=!$data->readed ? 'unreaded' : ''?> comment-item comment-<?= $data->user->role ?>" data-id="<?= $data->id ?>">

    <div class="user-photo-wr">
        <div class="user-photo">
            <img src="<?= $data->user->avatar_url ?>"/>
        </div>
    </div>

    <div class="line"></div>

    <div class="comment-content">

        <? if (Yii::app()->user->role == 'admin'): ?>
            <div class="pull-right">
                <a href="#" class="btn-edit-comment"><i class="icon-pencil"></i></a>
                <a href="#" class="btn-delete-comment"><i class="icon-trash"></i></a>
            </div>
        <? endif; ?>

        <span class="user-name"><?=$data->user->display_name?></span>

        <p class="comment-text"><?=nl2br($data->text)?></p>

        <? if (Yii::app()->user->role == 'admin'): ?>
            <div class="edit-block" style="display: none">
                <textarea></textarea>
                <button class="btn btn-mini btn-success btn-save">Сохранить</button>
                <button class="btn btn-mini btn-danger btn-cancel">Отменить</button>
            </div>
        <? endif; ?>

        <ul class="files">
            <? foreach ($data->files as $file): ?>
                <li data-id="<?= $file->id ?>">
                    <? if (Yii::app()->user->role == 'admin'): ?><input type="checkbox"><? endif; ?>
                    <span class="filename"><?=$file->real_filename?></span>
                    <span class="size">(<?=$file->file_size_str?>)</span>
                    <? if ($file->can_view): ?><a href="<?= $file->url ?>" target="_blank" class="fancybox btn btn-mini">
                            Открыть</a><? endif; ?>
                    <a href="/projects/download/<?= $file->id ?>" target="_blank" class="btn btn-mini">Скачать</a>
                    <? if (Yii::app()->user->role == 'admin'): ?><a href="#"
                                                                    class="btn btn-mini btn-delete">Удалить</a><? endif; ?>
                </li>
            <? endforeach; ?>
        </ul>

        <span class="time-label"><?=$data->datetime_str?></span>
    </div>

</div>

<?
if ($data->readed == false) {
    $project_comment_read = new ProjectCommentRead();
    $project_comment_read->comment_id = $data->id;
    $project_comment_read->user_id = Yii::app()->user->id;
    $project_comment_read->save();
}
?>