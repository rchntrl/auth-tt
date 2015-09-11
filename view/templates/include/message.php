<?php
$classes = array(
    'error',
    'success',
    'info',
);
if (FormMessage::hasMessage()) {?>
    <div id="form-message" class="form-message <?= $classes[FormMessage::messageType()] ?>">
        <?=$this->trans(FormMessage::showMessage()) ?>
    </div>
<? } ?>