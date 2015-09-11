<h1><?=$this->trans($this->title)?></h1>
<? if ($this->getUser()) { ?>
    <div class="message">
        <p><?= $this->trans('You already signed in as %Name%', array('%Name%' => $this->getUser()->FirstName)) ?></p>
    </div>
<? } else { ?>
    <div class="form-container">
        <form class="form" method="post" action="<?= BASE_URL . '/login' ?>">
            <div class="column-50">
                <div class="item title required">
                    <label for="register-form-email"><?=$this->trans('Email')?></label>
                    <input type="text" id="register-form-email" name="Email" />
                </div>
                <div class="item title required">
                    <label for="register-form-password"><?=$this->trans('Password')?></label>
                    <input type="password" id="register-form-email" name="Password" />
                </div>
                <div class="item title right">
                    <button type="submit" name="submit"><?=$this->trans('Sign in')?></button>
                </div>
            </div>
        </form>
    </div>
<? } ?>
