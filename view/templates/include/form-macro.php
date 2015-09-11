<?php

$formRow = function($id, $name, $label, $required = false, $description = null) use($form) { ?>
    <div class="item<?=$form->hasErrors($name) ? ' has-errors' : '' ?>">
        <label class="label" for="<?=$id?>"><?=$this->trans($label) . ($required ? '<sup class="required-field">*</sup>' : '') ?></label>
        <input type="text" maxlength="255" <?=$required ? 'required' : ''?> id="<?=$id?>" name="<?=$name?>" value="<?= $form->getValue($name) ?>" />
        <span class="form-control-feedback"></span>
        <? foreach ($form->getErrors($name) as $message) { ?>
            <div class="message"><?=$message?></div>
        <? } ?>
        <? if ($description) { ?>
            <div class="description"><?=$this->trans($description)?></div>
        <? } ?>
    </div>
<?};

$formPasswordRow = function($id, $name, $label, $required = false, $description = null) use($form) { ?>
    <div class="item<?=$form->hasErrors($name) ? ' has-errors' : '' ?>">
        <label class="label" for="<?=$id?>"><?=$this->trans($label) . ($required ? '<sup class="required-field">*</sup>' : '') ?></label>
        <input type="password" maxlength="255" <?=$required ? 'required' : ''?> id="<?=$id?>" name="<?=$name?>" value="<?= $form->getValue($name) ?>" />
        <span class="form-control-feedback"></span>
        <? foreach ($form->getErrors($name) as $message) { ?>
            <div class="message"><?=$message?></div>
        <? } ?>
        <? if ($description) { ?>
            <div class="description"><?=$this->trans($description)?></div>
        <? } ?>
    </div>
<?};

$formTextAreaRow = function($id, $name, $label, $required = false, $description = null) use($form) { ?>
    <div class="item<?=$form->hasErrors($name) ? ' has-errors' : '' ?>">
        <label class="label" for="<?=$id?>"><?=$this->trans($label) . ($required ? '<sup class="required-field">*</sup>' : '') ?></label>
        <textarea id="<?=$id?>" name="<?=$name?>" rows="5" ><?=$form->getValue($name)?></textarea>
        <span class="form-control-feedback"></span>
        <? foreach ($form->getErrors($name) as $message) { ?>
            <div class="message"><?=$message?></div>
        <? } ?>
        <? if ($description) { ?>
            <div class="description"><?=$this->trans($description)?></div>
        <? } ?>
    </div>
<?};

$formWidget  = function($id, $name, $required = false) use($form) { ?>
    <input type="text" maxlength="255" id="<?=$id?>" <?=$required ? 'required' : ''?> name="<?=$name?>" value="<?= $form->getValue($name) ?>" />
    <span class="form-control-feedback"></span>
    <? foreach ($form->getErrors($name) as $message) { ?>
        <div class="message"><?=$message?></div>
    <? } ?>
<?};

$formSelectWidget = function($id, $name, $choices) use($form) { ?>
    <select id="<?=$id?>" name="<?=$name?>">
        <option value=""><?=$this->trans('Not selected')?></option>
        <? foreach($choices as $key => $val) { ?>
            <option <?=$form->getValue($name) == $key ? 'selected' : '' ?> value="<?=$key?>"><?=$this->trans($val)?></option>
        <? } ?>
    </select>
    <? foreach ($form->getErrors($name) as $message) { ?>
        <div class="message"><?=$message?></div>
    <? } ?>
<?};

$formSelectRow = function($id, $name, $label, $choices = array(), $description = null) use($form, $formSelectWidget) { ?>
    <div class="item<?=$form->hasErrors($name) ? ' has-errors' : '' ?>">
        <label class="label" for="<?=$id?>-month"><?=$this->trans($label) ?></label>
        <?=$formSelectWidget($id, $name, $choices)?>
        <? if ($description) { ?>
            <div class="description"><?=$this->trans($description)?></div>
        <? } ?>
    </div>
<?};

$formDateRow = function($id, $name, $label, $required = false, $description = null) use($form) {?>
    <div class="item<?=$form->hasErrors($name) ? ' has-errors' : '' ?>">
        <label class="label" for="<?=$id?>"><?=$this->trans($label) . ($required ? '<sup class="required-field">*</sup>' : '') ?></label>
        <input type="text" placeholder="dd.mm.yyyy | dd-mm-yyyy" <?=$required ? 'required' : ''?> id="<?=$id?>" name="<?=$name?>" value="<?= $form->getValue($name) ?>" />
        <span class="form-control-feedback"></span>
        <? foreach ($form->getErrors($name) as $message) { ?>
            <div class="message"><?=$message?></div>
        <? } ?>
        <? if ($description) { ?>
            <div class="description"><?=$this->trans($description)?></div>
        <? } ?>
    </div>
<?};

$formMonthRow = function($id, $name) use($form, $formSelectWidget) { ?>
    <?=$formSelectWidget($id, $name, array(
        '01' => 'January',
        '02' => 'February',
        '03' => 'March',
        '04' => 'April',
        '05' => 'May',
        '06' => 'june',
        '07' => 'July',
        '08' => 'August',
        '09' => 'September',
        '10' => 'October',
        '11' => 'November',
        '12' => 'December',
    ))?>
<?};
