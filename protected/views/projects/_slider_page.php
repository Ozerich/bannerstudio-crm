<? if (Yii::app()->user->role == 'admin'): ?>
    <div class="page-actions">
        <a href="#" class="btn-add_html">Добавить HTML элемент</a>
        <a href="#" class="btn-delete">Удалить страницу</a>
    </div>
<? endif; ?>

<? foreach ($page->items as $ind => $item): ?>
    <? if ($ind % 2 == 0): ?>
        <div class="slider-row <?= $ind == count($page->items) - 1 ? 'single' : '' ?>">
    <? endif; ?>
    <div class="slider-item" data-id="<?= $item->id ?>">
        <div class="slider-item-block">
            <? if (empty($item->html)): ?>
                <? if ($item->is_image): ?>
                    <img src="<?= Yii::app()->params['upload_dir_comments'] . $item->file ?>">
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
                <? if ($item->is_image): ?> <a href="<?= Yii::app()->params['upload_dir_comments'] . $item->file ?>"
                                               class="fancybox">открыть</a><? endif; ?>
            <? endif; ?>
            <?if (Yii::app()->user->role == 'admin'): ?><a href="#" class="btn-delete">убрать</a><? endif; ?>
        </div>
    </div>
    <? if ($ind == count($page->items) - 1 || $ind % 2 == 1): ?>
        </div>
    <? endif; ?>
<? endforeach; ?>