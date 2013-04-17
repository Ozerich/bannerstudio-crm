<?php

class ProjectCommentFile extends CActiveRecord
{
    public $file_size_str;
    public $can_view;
    public $url;

    public static $can_view_exts = array(
        'jpg', 'jpeg', 'png', 'bmp', 'swf', 'gif'
    );

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }


    public function tableName()
    {
        return 'project_comment_files';
    }

    public function rules()
    {
        return array(
            array('comment_id, file,real_filename, file_size', 'required'),
            array('comment_id, file_size', 'numerical', 'integerOnly' => true),
            array('file', 'length', 'max' => 255),

            array('id, project_id, comment_id, file,real_filename, file_size', 'safe', 'on' => 'search'),
        );
    }


    public function relations()
    {
        return array(
            'comment' => array(self::BELONGS_TO, 'ProjectComment', 'comment_id'),
        );
    }

    public function afterFind()
    {
        $size = $this->file_size;

        if ($size < 1024) {
            $size = $size . ' б.';
        } else if ($size < 1024 * 1024) {
            $size = round($size / 1024, 2) . ' кб.';
        } else if ($size < 1024 * 1024 * 1024) {
            $size = round($size / 1024 / 1024, 2) . ' Мб.';
        }

        $this->file_size_str = $size;


        $file_ext = strpos($this->file, '.') !== false ? substr($this->file, strrpos($this->file, '.') + 1) : '';
        $this->can_view = in_array($file_ext, self::$can_view_exts);


        $this->url = 'http://'.$_SERVER['HTTP_HOST'].Yii::app()->params['upload_dir_comments'].$this->file;
    }


    public function download(){

        $file = $_SERVER['DOCUMENT_ROOT'].Yii::app()->params['upload_dir_comments'].$this->file;
        $newfile = $_SERVER['DOCUMENT_ROOT'].Yii::app()->params['tmp_dir'].$this->real_filename;

        copy($file, $newfile);

        header("Content-Disposition: attachment;filename=\"".$this->real_filename."\"");
        header("Content-Transfer-Encoding: binary");
        header('Pragma: no-cache');
        header('Expires: 0');

        set_time_limit(0);
        readfile($newfile);
        unlink($newfile);
    }
}