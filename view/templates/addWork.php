<?php
include 'include/breadcrumbs.php';
/** @var WorkExperienceForm $form */
$form = $this->form;
include 'include/form-macro.php';
?>
<h1><?=$this->trans($this->title)?></h1>
<div class="form-container">
    <? include 'include/message.php' ?>
    <form class="form" method="post" action="<?= BASE_URL . '/profile/add-work' ?>" novalidate>
        <div class="column-50">
            <?=$formRow('form-title', 'Title', 'Title', true)?>
            <?=$formRow('form-organization', 'Organization', 'Organization', true)?>
            <div class="item">
                <label class="label"><?=$this->trans('From') ?></label>
                <div class="column-50 item">
                    <?=$formMonthRow('form-start-month', 'StartDateMonth')?>
                </div>
                <div class="column-50 item">
                    <?=$formWidget('form-start-year', 'StartDateYear')?>
                </div>
            </div>
            <div class="item">
                <label class="label"><?=$this->trans('To') ?></label>
                <div class="column-50 item">
                    <?=$formMonthRow('form-end-month', 'EndDateMonth')?>
                </div>
                <div class="column-50 item">
                    <?=$formWidget('form-end-year', 'EndDateYear')?>
                </div>
            </div>
            <?=$formTextAreaRow('form-description', 'Description', 'Description', true)?>
            <div class="item title right">
                <button type="submit" name="submit"><?=$this->trans('Save')?></button>
                <button type="submit" name="SaveAndExit" value="1"><?=$this->trans('Save and Exit')?></button>
            </div>
        </div>
    </form>
</div>
<script type="text/javascript">
    var inputs = {
        'form-title': {'constraints': ['NotBlank']},
        'form-organization': {'constraints': ['NotBlank']},
        'form-start-year': {'constraints': ['Numeric']},
        'form-end-year': {'constraints': ['Numeric']}
    };

    bindFeedbackValidation(inputs);
</script>
