<div class="row-fluid">
<div class="widget" id="form_login">
    <div class="widget-header">
        <i class="icon-user"></i>

        <h3>Вход в систему:</h3>
    </div>

    <div class="widget-content">

        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'login-form',
        )); ?>

        <?=$form->label($model, 'email'); ?>
        <?=$form->textField($model, 'email', array('class' => 'span3')); ?>
        <?=$form->error($model, 'email'); ?>

        <?=$form->label($model, 'password'); ?>
        <?=$form->passwordField($model, 'password', array('class' => 'span3')); ?>
        <?=$form->error($model, 'password'); ?>

        <?=$form->checkbox($model, 'rememberMe');?>
        <?=$form->labelEx($model, 'rememberMe'); ?>
        <div class="form-actions">
            <?php $this->widget('ext.bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'type' => 'primary', 'label' => 'Войти')); ?>
        </div>

        <?php $this->endWidget(); ?>
    </div>

</div>
</div>
