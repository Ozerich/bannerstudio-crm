<object style="display: block; width:<?=$file->width?>px; height:<?=$file->height?>px" id="fancybox-swf" width="<?=$file->width?>" height="<?=$file->height?>" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000">
    <param name="movie" value="<?=$file->file_url?>">
    <param name="wmode" value="transparent">
    <param name="allowfullscreen" value="true">
    <param name="allowscriptaccess" value="always">
    <embed src="<?=$file->file_url?>"
           type="application/x-shockwave-flash" wmode="transparent"
           allowfullscreen="true" allowscriptaccess="always"  width="<?=$file->width?>" height="<?=$file->height?>">
</object>