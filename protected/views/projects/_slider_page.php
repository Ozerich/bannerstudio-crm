<? if (Yii::app()->user->role == 'admin'): ?>
    <div class="page-actions">
        <a href="#" class="btn-add_html">Добавить HTML элемент</a>
        <a href="#" class="btn-delete">Удалить страницу</a>
    </div>
<? endif; ?>

<? foreach ($page->items as $ind => $item): ?>
    <div class="slider-item <?=$item->html ? 'html' : ''?>" data-id="<?= $item->id ?>">
        <div class="slider-item-block">
            <? if (empty($item->html)): ?>
                <? if ($item->is_image): ?>
                    <img src="<?= Yii::app()->params['upload_dir_comments'] . $item->file ?>">
                <? elseif ($item->is_swf): ?>
                    <object  width="<?=$item->image_width?>" height="<?=$item->image_height?>" id="fancybox-swf" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000">
                        <param name="movie" value="<?= Yii::app()->params['upload_dir_comments'] . $item->file ?>">
                        <param name="wmode" value="transparent">
                        <param name="allowfullscreen" value="true">
                        <param name="allowscriptaccess" value="always">
                        <embed src="<?= Yii::app()->params['upload_dir_comments'] . $item->file ?>"
                               type="application/x-shockwave-flash" width="<?=$item->image_width?>" height="<?=$item->image_height?>" wmode="transparent"
                               allowfullscreen="true" allowscriptaccess="always">
                    </object>
                <? else: ?>
                    <img src="/img/zip.png">
                <? endif; ?>
            <? else: ?>
                <?= $item->html ?>
            <? endif; ?>
        </div>
        <div class="slider-item-info">
            <? if (empty($item->html)): ?>
                <span><?=($item->image_width && $item->image_height ? $item->image_width . 'x' . $item->image_height : $item->real_filename)?>
                    (<?=$item->file_size?>)</span>
                <a target="_blank" href="/slider/download/<?= $item->id ?>">скачать</a>
                <? if ($item->is_image ): ?> <a href="<?= Yii::app()->params['upload_dir_comments'] . $item->file ?>"
                                               class="fancybox" data-width="<?=$item->file->width?>" data-height="<?=$item->file->height?>">открыть</a><? endif; ?>
            <? endif; ?>
            <?if (Yii::app()->user->role == 'admin'): ?><a href="#" class="btn-delete">убрать</a><? endif; ?>
        </div>
    </div>
<? endforeach; ?>

<p class="out-link">
    <label>Внешняя ссылка на эти файлы: </label>
    <a target="_blank" href="<?=$page->getOutUrl();?>"><?=$page->getOutUrl();?></a>
</p>