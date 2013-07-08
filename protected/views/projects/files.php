<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"/>
    <meta name="description" content="">
    <meta name="author" content="Vital Ozierski, ozicoder@gmail.com">

    <title>Файлы</title>

    <link rel="stylesheet" href="/css/normalize.css">
    <link rel="stylesheet/less" href="/css/out.less"/>

    <script src="/js/less-1.3.3.min.js"></script>
</head>

<body>
<div id="outfiles_page">

    <h1><?=$project->name?></h1>

    <? if (empty($items)): ?>
        <p class="no-files">Нет файлов</p>
    <? else: ?>
        <? foreach ($items as $item): ?>
<div class="item-container">

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
                                   type="application/x-shockwave-flash" wmode="transparent"
                                   allowfullscreen="true" allowscriptaccess="always"  width="<?=$item->image_width?>" height="<?=$item->image_height?>">
                        </object>
                    <? else: ?>
                        <img src="/img/zip.png">
                    <? endif; ?>
                <? else: ?>
                    <?= $item->html ?>
                <? endif; ?>


            <div class="slider-item-info">
                <? if (empty($item->html)): ?>
                    <span><?=($item->image_width && $item->image_height ? $item->image_width . 'x' . $item->image_height : $item->real_filename)?>
                        (<?=$item->file_size?>)</span>

                    <a href="/slider/download/<?= $item->id ?>">скачать</a>
                <? endif; ?>

            </div>
</div>
        <? endforeach; ?>
    <? endif; ?>
</div>

</body>

</html>