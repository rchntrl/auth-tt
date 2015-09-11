<?php
include 'include/breadcrumbs.php';
/** @var WorkExperienceForm $form */
$form = $this->form;
include 'include/form-macro.php';
?>
<h1><?=$this->trans($this->title)?></h1>
<div class="form-container">
    <? include 'include/message.php' ?>
    <form class="form" method="post" action="<?= $this->getUrl() ?>" novalidate>
        <div class="column-50">
            <?=$formRow('form-status', 'Status', 'Status', true)?>
            <?=$formRow('form-institution', 'Institution', 'Institution', true)?>
            <?=$formRow('form-department', 'Department', 'Department')?>
            <?=$formRow('form-study-program', 'StudyProgram', 'Study Program')?>
            <?=$formRow('form-graduation-year', 'GraduationYear', 'Graduation year')?>
            <div class="item title right">
                <button type="submit" name="submit"><?=$this->trans('Save')?></button>
                <button type="submit" name="SaveAndExit" value="1"><?=$this->trans('Save and Exit')?></button>
            </div>
        </div>
    </form>
</div>
<script type="text/javascript">
    var inputs = {
        'form-status': {'constraints': ['NotBlank']},
        'form-institution': {'constraints': ['NotBlank']},
        'form-graduation-year': {'constraints': ['Numeric']}
    };

    bindFeedbackValidation(inputs);
</script>
