<link rel="stylesheet" href="<?= BASE_URL ?>/view/css/profile.css">
<ul class="breadcrumb">
    <li><a href="<?= BASE_URL ?>"><?= $this->trans('Home') ?></a></li>
    <li><span><?= $this->trans($this->title) ?></span></li>
</ul>
<?php
/** @var User $user */
$user = $this->user;
?>
<div class="user-profile">
    <h1><?= $this->trans($this->title) ?></h1>
    <? include 'include/message.php' ?>
    <div id="main-details" class="main-details-section">
        <div class="main-info">
            <h2><?= $user->getFullName() ?> <a title="<?= $this->trans('Edit') ?>" href="<?= BASE_URL . '/profile/edit' ?>"><span class="icon edit"></span></a></h2>
            <? if ($user->ImagePath) { ?>
                <div class="photo">
                    <img src="<?= $user->ImagePath ?>"/>
                </div>
            <? } ?>
        </div>
        <dl>
            <dt><?= $this->trans('Birth Date') ?></dt>
            <dd><?= $user->getBirthDate() ?></dd>
        </dl>
        <dl>
            <dt><?= $this->trans('Email') ?></dt>
            <dd><?= $user->Email ?></dd>
        </dl>
        <dl>
            <dt><?= $this->trans('Phone') ?></dt>
            <dd><?= $user->Phone ?></dd>
        </dl>
        <dl>
            <dt><?= $this->trans('Skype') ?></dt>
            <dd><?= $user->Skype ?></dd>
        </dl>
        <dl>
            <dt><?= $this->trans('Relationship Status') ?></dt>
            <dd><?= $this->trans($user->getRelationshipStatus()) ?></dd>
        </dl>
        <dl>
            <dt><?= $this->trans('More About Me') ?></dt>
            <dd><?= $user->MoreAboutMe ?></dd>
        </dl>
        <dl id="location">
            <dt><?= $this->trans('Location') ?></dt>
            <dd title="<?=$this->trans('Click here to edit')?>">
                <? if ($user->Location) { ?>
                    <a href="<?= BASE_URL . '/profile/location' ?>"><?= $user->Location->getString() ?></a>
                <? } else { ?>
                    <a href="<?= BASE_URL . '/profile/location' ?>"><?= $this->trans('Specify') ?></a>
                <? } ?>
            </dd>
        </dl>
    </div>
    <div id="work-experience" class="work-experience-section">
        <h2><?= $this->trans('Work Experience') ?></h2>
        <div class="work-position-list">
            <? foreach ($user->WorkPositions as $position) { ?>
                <div class="work-position">
                    <ul class="action-control right">
                        <li>
                            <a href="<?= BASE_URL . '/profile/edit-work/' . $position->getId() ?>"
                               title="<?= $this->trans('Edit') ?>"><span class="icon edit"></span></a>
                        </li>
                        <li>
                            <a onclick="removeObject(this)"
                               data-message="<?=$this->trans('Are you sure you want to remove this work position?')?>"
                               data-href="<?= BASE_URL . '/profile/remove-work/' . $position->getId() ?>#work-experience"
                               title="<?= $this->trans('Remove') ?>"><span class="icon remove"></span>
                            </a>
                        </li>
                    </ul>
                    <h3 class="title"><?= $position->Title ?></h3>
                    <div class="Organization"><?= $position->Organization ?></div>
                </div>
            <? } ?>
        </div>
        <a class="button right" href="<?= BASE_URL . '/profile/add-work' ?>"><?= $this->trans('Add Position') ?></a>
    </div>
    <div id="education-section" class="education-section">
        <h2><?= $this->trans('Education') ?></h2>
        <div class="institution-list">
            <? foreach ($user->EducationList as $position) { ?>
                <div class="institution">
                    <ul class="action-control right">
                        <li>
                            <a href="<?= BASE_URL . '/profile/edit-edu/' . $position->getId() ?>"
                               title="<?= $this->trans('Edit') ?>">
                                <span class="icon edit"></span>
                            </a>
                        </li>
                        <li>
                            <a onclick="removeObject(this)"
                               data-message="<?=$this->trans('Are you sure you want to remove this institution?')?>"
                               data-href="<?= BASE_URL . '/profile/remove-edu/' . $position->getId() ?>#education-section"
                               title="<?= $this->trans('Remove') ?>">
                                <span class="icon remove"></span>
                            </a>
                        </li>
                    </ul>
                    <h3 class="title"><?= $position->Status ?></h3>
                    <div class="Organization"><?= $position->Institution ?></div>
                </div>
            <? } ?>
        </div>
        <a class="button right" href="<?= BASE_URL . '/profile/add-edu' ?>"><?= $this->trans('Add Institution') ?></a>
    </div>
</div>
<script type="text/javascript">
    function removeObject(obj) {
        if (confirm(obj.getAttribute('data-message'))) {
            window.location.href = obj.getAttribute('data-href');
        }
    }
</script>