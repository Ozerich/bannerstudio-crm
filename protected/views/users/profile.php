<h1><?=isset($page_title) ? $page_title : 'Ваш профиль'?></h1>

<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'profile-form',
        'htmlOptions' => array('class' => 'well', 'enctype' => 'multipart/form-data',),
    )); ?>

    <fieldset>

        <p class="note">Поля со знаком <span class="required">*</span> обязательны к заполнению.</p>

        <div class="param">
            <?=$form->labelEx($model, 'login');?>
            <?=$form->textField($model, 'login', array('maxlength' => 200));?>
            <?=$form->error($model, 'login');?>
        </div>

        <div class="param value-param">
            <label>Тип пользователя:</label>
            <span class="value"><?=User::$roles[$model->role]?></span>
        </div>

        <div class="param">
            <?=$form->labelEx($model, 'email');?>
            <?=$form->textField($model, 'email', array('maxlength' => 200));?>
            <?=$form->error($model, 'email');?>
        </div>

        <div class="param">
            <?=$form->labelEx($model, 'password');?>
            <?=$form->textField($model, 'password');?>
            <?=$form->error($model, 'password');?>
        </div>

        <? if ($model->avatar): ?>
        <div class="param photo-param">
            <label>Текущая фотография:</label>
            <img src="<?=$model->avatar_url?>"/>
        </div>
        <? endif; ?>

        <div class="param">
            <?=$form->labelEx($model, 'avatar');?>
            <?=$form->fileField($model, 'avatar');?>
            <?=$form->error($model, 'avatar');?>
        </div>

        <div class="param">
            <?=$form->labelEx($model, 'contact');?>
            <?=$form->textarea($model, 'contact');?>
            <?=$form->error($model, 'contact');?>
        </div>


    </fieldset>

    <div class="form-actions">
        <?=CHtml::submitButton('Сохранить', array('class' => 'btn btn-primary')); ?>
    </div>

    <?php $this->endWidget(); ?>

</div>