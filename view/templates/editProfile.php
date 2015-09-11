<?php
include 'include/breadcrumbs.php';
/** @var UserForm $form */
$form = $this->form;
include 'include/form-macro.php';
/** @var User $user */
$user = $this->form->getData();
?>
<h1><?=$this->trans($this->title)?></h1>
<div class="form-container">
    <? include 'include/message.php' ?>
    <form class="form" method="post" action="<?= BASE_URL . '/profile/edit' ?>" novalidate>
        <div class="column-50">
            <?=$formRow('form-first-name', 'FirstName', 'First Name', true)?>
            <?=$formRow('form-last-name', 'LastName', 'Last Name', true)?>
            <?=$formRow('form-middle-name', 'MiddleName', 'Middle Name', true)?>
            <?=$formDateRow('form-birth-date', 'BirthDate', 'Birth Date', true)?>
            <?=$formSelectRow('form-relationship-status', 'RelationshipStatus', 'Relationship Status', User::getRelationshipChoices())?>
            <?=$formRow('form-skype', 'Skype', 'Skype')?>
            <?=$formRow('form-phone', 'Phone', 'Phone')?>
            <?=$formRow('form-email', 'Email', 'Email', true)?>
            <?=$formPasswordRow('form-password', 'PlainPassword', 'Password', false, 'Leave blank if you do not have to change your password')?>
            <?=$formTextAreaRow('form-more-about-me', 'MoreAboutMe', 'More About Me')?>
            <div class="item">
                <label class="label"><?=$this->trans('Image')?></label>
                <input id="form-image-path" type="hidden" name="ImagePath" value="<?= $form->getValue('ImagePath') ?>" />
                <div id="form-image-path-container" class="form-image-container <?=$form->getValue('ImagePath')? '' : 'empty-image'?>">
                    <div class="form-image">
                        <label title="<?=$this->trans('Upload Image')?>" class="image-upload" for="form-image-path-file-control">
                            <input class="hidden" type="file" id="form-image-path-file-control" name="file" onchange="uploadImage('form-image-path');" />
                        </label>
                        <div title="<?= $this->trans('Remove image')?>" class="image-remove" onclick="removeImage('form-image-path')"></div>
                        <img id="form-image-path-image" src="<?=$form->getValue('ImagePath')?>">
                    </div>
                    <div class="form-image-info">
                        <div id="form-image-path-error-message" class="message"></div>
                    </div>
                </div>
                <div class="description"><?=$this->trans('Maximum file size - %Size%', array('%Size%' => '700 KB'))?></div>
            </div>
            <div class="item title right">
                <button type="submit" name="submit"><?=$this->trans('Save')?></button>
                <button type="submit" name="SaveAndExit" value="1"><?=$this->trans('Save and Exit')?></button>
            </div>
        </div>
    </form>
</div>
<script type="text/javascript">
    var inputs = {
        'form-first-name': {'constraints': ['NotBlank']},
        'form-last-name': {'constraints': ['NotBlank']},
        'form-middle-name': {'constraints': ['NotBlank']},
        'form-email': {'constraints': ['NotBlank', 'Email']},
        'form-birth-date': {'constraints': ['NotBlank', 'Date']}
    };

    bindFeedbackValidation(inputs);
</script>
