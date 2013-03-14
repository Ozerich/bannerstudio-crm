<h1><?=isset($page_header) ? $page_header : 'Пользователь'?></h1>

<a href="/users/edit/<?=$model->id?>" class="btn btn-primary header-button">Редактировать</a>

<div id="page_profile">

    <div class="user-params">
        <div class="param">
            <label>ID</label>
            <span><?=$model->id?></span>
        </div>
        <div class="param">
            <label>Роль</label>
            <span><?=User::$roles[$model->role]?></span>
        </div>
        <div class="param">
            <label>Имя</label>
            <span><?=$model->login?></span>
        </div>
        <div class="param">
            <label>Email</label>
            <span><?=$model->email?></span>
        </div>
        <div class="param">
            <label>Контактная информация</label>
            <span><?=nl2br($model->contact)?></span>
        </div>
        <div class="param">
            <label>Скрытая информация</label>
            <span><?=nl2br($model->hide_information)?></span>
        </div>
        <div class="param">
            <label>Время регистрации</label>
            <span><?=$model->display_register_date?></span>
        </div>
        <div class="param">
            <label>Последний визит</label>
            <span><?=$model->display_last_visit?></span>
        </div>
    </div>

    <div class="projects-list">
        <?=$this->renderPartial('/projects/tables/_table', array('dataProvider' => $dataProvider));?>
    </div>

</div>