<div class="form user-form" id="form_project">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'project-form',
    )); ?>


    <div class="header-block">
        <div class="input">
            <?=$form->textField($model, 'name', array('placeholder' => 'Название проекта'));?>
            <?=$form->error($model, 'name');?>
        </div>
    </div>

    <div class="description-block">
        <label>Описание проекта:</label>
        <div class="input">
            <?=$form->textarea($model, 'customer_text');?>
            <?=$form->error($model, 'customer_text');?>
        </div>
    </div>

    <p class="additional-info">После создания проекта вы сможете прикрепить к нему необходимые файлы с помощью комментария</p>


    <div class="submit-buttons">
        <?=CHtml::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', array('class' => 'btn btn-primary'));?>
    </div>

    <? $this->endWidget(); ?>
</div>