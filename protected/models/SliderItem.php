<?php

class SliderItem extends CActiveRecord
{
    public $image_width = 0;
    public $image_height = 0;
    public $file_size = 0;
    public $is_image = false;
    public $is_swf = false;
    public $file_url = '';


    public static $image_exts = array(
        'jpg', 'jpeg', 'png', 'bmp', 'gif'
    );

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'slider_items';
    }


    public function rules()
    {
        return array(
            array('page_id', 'required'),
            array('file, real_filename, html, comment_file_id', 'safe'),
            array('page_id', 'numerical', 'integerOnly' => true),
        );
    }


    public function relations()
    {
        return array(
            'page' => array(self::BELONGS_TO, 'SliderPage', 'page_id'),
            'file' => array(self::BELONGS_TO, 'ProjectCommentFile', 'comment_file_id')
        );
    }

    public function download()
    {
        $file = $_SERVER['DOCUMENT_ROOT'] . Yii::app()->params['upload_dir_comments'] . $this->file;
        $newfile = $_SERVER['DOCUMENT_ROOT'] . Yii::app()->params['tmp_dir'] . $this->real_filename;

        copy($file, $newfile);

        header("Content-Disposition: attachment;filename=\"" . $this->real_filename . "\"");
        header("Content-Transfer-Encoding: binary");
        header("Content-Type: application/x-download" );
        header('Pragma: no-cache');
        header('Expires: 0');

        set_time_limit(0);
        readfile($newfile);
        unlink($newfile);
    }


    public function afterFind()
    {
        if (!empty($this->file)) {
            $file = $_SERVER['DOCUMENT_ROOT'] . Yii::app()->params['upload_dir_comments'] . $this->file;

            if (file_exists($file)) {

                $this->file_url = Yii::app()->getBaseUrl(true) . Yii::app()->params['upload_dir_comments'] . $this->file;

                $file_size = filesize($file);

                if ($file_size < 1024) {
                    $file_size = $file_size . ' б.';
                } else if ($file_size < 1024 * 1024) {
                    $file_size = round($file_size / 1024, 2) . ' кб.';
                } else if ($file_size < 1024 * 1024 * 1024) {
                    $file_size = round($file_size / 1024 / 1024, 2) . ' Мб.';
                }

                $this->file_size = $file_size;

                if ($size = getimagesize($file)) {
                    $this->image_width = $size[0];
                    $this->image_height = $size[1];
                }


                $file_ext = strpos($this->file, '.') !== false ? substr($this->file, strrpos($this->file, '.') + 1) : '';
                $this->is_image = in_array($file_ext, self::$image_exts);
                $this->is_swf = $file_ext == 'swf';
            }
        }
    }


    public function afterDelete()
    {
        if ($this->page) {
            if (count($this->page->items) == 0) {
                $this->page->delete();
            }
        }
    }
}