<?php
/** @var UserForm $form */
$form = $this->form;
include 'include/form-macro.php';
?>
<h1><?= $this->trans($this->title) ?></h1>
<? if ($this->getUser()) { ?>
    <div class="message">
        <p><?= $this->trans('You already signed in as %Name%', array('%Name%' => $this->getUser()->getName())) ?></p>
    </div>
<? } else { ?>
    <div class="form-container">
        <form class="form" method="post" action="<?= BASE_URL . '/register' ?>" novalidate>
            <div class="column-50">
                <?=$formRow('form-first-name', 'FirstName', 'First Name', true)?>
                <?=$formRow('form-last-name', 'LastName', 'Last Name', true)?>
                <?=$formRow('form-middle-name', 'MiddleName', 'Middle Name', true)?>
                <?=$formDateRow('form-birth-date', 'BirthDate', 'Birth Date')?>
                <?=$formRow('form-email', 'Email', 'Email', true)?>
                <?=$formPasswordRow('form-password', 'PlainPassword', 'Password', true, '')?>
                <div class="item">
                    <label class="label"><?=$this->trans('Image')?></label>
                    <input id="form-image-path" type="hidden" name="ImagePath" value="<?= htmlspecialchars($form->getValue('ImagePath')) ?>" />
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
                <div class="item title">
                    <button type="submit" class="right" name="submit"><?=$this->trans('Create Account')?></button>
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
            'form-birth-date': {'constraints': ['NotBlank', 'DateFormat']},
            'form-password': {'constraints': ['NotBlank', 'Password']}
        };
        bindFeedbackValidation(inputs);
    </script>
<? }?>
