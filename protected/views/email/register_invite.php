<p>Вы успешно зарегистрировались на сайте <strong>BannerStudio.ru</strong></p>
<p>Ваш логин: <strong><?=$model->email?></strong><br/>
    Пароль: <strong><?=$model->real_password?></strong>
</p>
<p>Вы можете войти на сайт по ссылке:<br/><a href="<?=Yii::app()->getBaseUrl(true)?>/"><?=Yii::app()->getBaseUrl(true)?>/</a></p>;