<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"/>
    <meta name="description" content="">
    <meta name="author" content="Vital Ozierski, ozicoder@gmail.com">

    <title></title>


    <link rel="stylesheet" href="/css/normalize.css">
    <link rel="stylesheet" href="/css/smoothness/jquery-ui-1.10.1.custom.min.css">

    <link rel="stylesheet/less" href="/css/base.less"/>

    <? if (Yii::app()->user->isGuest): ?>
    <link rel="stylesheet/less" href="/css/login.less"/>
    <? else: ?>
    <link rel="stylesheet/less" href="/css/styles.less"/>
    <link rel="stylesheet/less" href="/css/responsive/media.less"/>
    <? endif; ?>


    <script src="/js/modernizr.2.6.2.min.js"></script>
    <script src="/js/less-1.3.3.min.js"></script>
    <script src="/js/jquery-ui-1.10.1.custom.min.js"></script>

    <? if (!Yii::app()->user->isGuest): ?>
    <script src="/js/scripts.js"></script>
    <? endif; ?>

</head>

<body>

<div class="navbar navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">

            <a href="/" class="brand"><img src="/img/logo.png"></a>

            <div class="nav-collapse" id="collapse_0">
                <? if (!Yii::app()->user->isGuest): ?>

                <ul class="nav">

                    <li><a href="/">Главная</a></li>

                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">Проекты <span
                                class="caret"></span></a>
                        <ul id="yw1" class="dropdown-menu">
                            <li><a tabindex="-1" href="/projects">Все проекты</a></li>
                            <li><a tabindex="-1" href="/projects/create">Добавить проект</a></li>
                        </ul>
                    </li>

                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">Администрирование<span
                                class="caret"></span></a>
                        <ul id="yw3" class="dropdown-menu">

                            <? if (Yii::app()->user->checkAccess('Users.Index')): ?>
                            <li><a tabindex="-1" href="/users">Список пользователей</a></li>
                            <? endif; ?>

                            <? if (Yii::app()->user->checkAccess('Users.Create')): ?>
                            <li><a tabindex="-1" href="/users/create">Добавить пользователя</a></li>
                            <? endif; ?>


                            <li><a tabindex="-1" href="/logs">Просмотр Логов</a></li>
                        </ul>
                    </li>

                </ul>
                <? endif; ?>
            </div>

            <? if (!Yii::app()->user->isGuest): ?>
            <div class="profile-block dropdown">

                <a href="#" class="dropdown-toggle">
                    <div class="photo">
                        <img src="<?=Yii::app()->user->getModel()->avatar_url?>"/>
                    </div>
                    <span class="username"><?=Yii::app()->user->getModel()->login?></span>
                    <div style="clear: both"></div>
                </a>

                <ul id="yw4" class="dropdown-menu">
                    <li><a href="/profile">Личные данные</a></li>
                    <li><a href="/logout">Выйти</a></li>
                </ul>


            </div>

            <? endif; ?>
        </div>
    </div>
</div>


<div class="container">
    <div class="row-fluid">

        <?php $this->widget('bootstrap.widgets.TbBreadcrumbs', array(
        'homeLink' => CHtml::link('Главная', '/'),
        'links' => $this->breadcrumbs,
    )); ?>
    </div>


    <div class="page-container">
        <? if (isset($page_header)): ?>
        <h1><?=$page_header?></h1>
        <? endif; ?>
        <?=$content?>
    </div>
</div>

<?php

/*$this->widget('bootstrap.widgets.TbNavbar', array(
    'type' => null,
    'brand' => '<img src="/img/logo.png"/>',
    'brandUrl' => '/',
    'collapse' => true,
    'items' => array(
        array(
            'class' => 'bootstrap.widgets.TbMenu',
            'items' => array(
                array('label' => 'Главная', 'url' => array('/index'), 'visible' => !Yii::app()->user->isGuest),
                array('label' => 'Проекты', 'visible' => !Yii::app()->user->isGuest, "items" => array(
                    array('label' => 'Все проекты', 'url' => array('project/index')),
                    array('label' => 'Добавить проект', 'url' => array('project/create'), 'visible' => Yii::app()->user->checkAccess("createProject")),
                )),
                array('label' => "Пользователь " . $username, 'visible' => !Yii::app()->user->isGuest, "items" => array(
                    array('label' => 'Личные данные', 'url' => array('user/update', 'id' => Yii::app()->user->id)),
                )),
                array('label' => 'Администрирование', 'url' => "#", 'visible' => Yii::app()->user->role == 1, "items" => array(
                    array('label' => 'Список пользователей', 'url' => array('user/index'), 'visible' => Yii::app()->user->checkAccess("viewUser")),
                    array('label' => 'Добавить пользователя', 'url' => array('user/create'), 'visible' => Yii::app()->user->checkAccess("createUser")),
                    array('label' => 'Просмотр Логов', 'url' => array('site/log'), 'visible' => Yii::app()->user->checkAccess("showLogs")),
                )),
                array('label' => 'Выйти', 'url' => array('site/logout'), 'visible' => !Yii::app()->user->isGuest),
            ),
        ),

    ),
)); */ ?>

</body>