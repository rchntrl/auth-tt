<html>
<head>
    <meta charset="utf-8">
    <base href="<?=BASE_URL?>/"/>
    <title><?= ($this->title) ?> - <?= $this->siteTitle ?></title>
    <? if ($this->metaDescription) { ?>
        <meta name="description" content="<?= $this->metaDescription ?>" />
    <? } ?>
    <? if ($this->metaKeywords) { ?>
        <meta name="keywords" content="<?= $this->metaKeywords ?>" />
    <? } ?>
    <link rel="stylesheet" href="<?=BASE_URL?>/view/css/common.css">
    <script type="text/javascript" src="<?=BASE_URL?>/view/js/common.js"></script>
    <script type="text/javascript" src="<?=BASE_URL?>/view/js/app.js"></script>
</head>
<body>
<div class="row">
    <div class="container">
        <div class="header">
            <? include $this->header ?>
        </div>
        <div class="layout">
            <?php include $this->layout ?>
        </div>
    </div>
</div>
</body>
</html>
