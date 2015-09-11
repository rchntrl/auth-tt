<?php
include 'include/breadcrumbs.php';
/** @var WorkExperienceForm $form */
$form = $this->form;
include 'include/form-macro.php';
?>
<h1><?=$this->trans($this->title)?></h1>
<div class="form-container">
    <? include 'include/message.php' ?>
    <form class="form" method="post" action="<?= BASE_URL . '/profile/location' ?>" novalidate>
        <div class="column-50">
            <?=$formRow('form-country', 'Country', 'Country', true)?>
            <?=$formRow('form-region', 'Region', 'Region', true)?>
            <?=$formRow('form-city', 'City', 'City', true)?>
            <div class="item title right">
                <button type="submit" name="submit"><?=$this->trans('Save')?></button>
                <button type="submit" name="SaveAndExit" value="1"><?=$this->trans('Save and Exit')?></button>
            </div>
        </div>
    </form>
</div>
<script type="text/javascript">
    var inputs = {
        'form-country': {'constraints': ['NotBlank']},
        'form-region': {'constraints': ['NotBlank']},
        'form-city': {'constraints': ['NotBlank']}
    };

    bindFeedbackValidation(inputs);
</script>