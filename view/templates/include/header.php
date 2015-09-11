<div class="global-actions">
    <ul class="left">
        <li><a href="<?=BASE_URL ?>/"><?=$this->trans('Home')?></a></li>
    </ul>
    <? if ($this->getUser()) {?>
        <p><?=$this->trans('Hello %Name%!', array('%Name%' => htmlspecialchars($this->getUser()->FirstName)))?></p>
    <? } ?>
    <ul class="right">
        <? foreach ($this->availableLocales() as $choice) { ?>
            <li><a href="<?=BASE_URL . '/lang/' . $choice['value'] ?>"><?=$this->trans($choice['title'])?></a></li>
        <? } ?>
        <? if ($this->getUser()) {?>
            <li><a href="<?=BASE_URL . '/profile'?>"><?=$this->trans('My profile')?></a></li>
            <li><a href="<?=BASE_URL . '/logout'?>"><?=$this->trans('Logout')?></a></li>
        <?} else {?>
            <li><a href="<?=BASE_URL . '/login'?>"><?=$this->trans('Login')?></a></li>
            <li><a href="<?=BASE_URL . '/register'?>"><?=$this->trans('Register')?></a></li>
        <? } ?>
    </ul>
</div>
