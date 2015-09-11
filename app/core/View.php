<?php

class View extends Object
{
    private $registry;

    private $template;

    private $layout;

    /**
     * @var Auth
     */
    public $auth;

    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
        /** @var Config $config */
        $config = $this->registry->get('config');
        $this->auth = $this->registry->get('auth');
        $this->fields['siteTitle'] = $config->siteTitle;
        $this->fields['title'] = '';
        $this->fields['metaDescription'] = '';
        $this->fields['metaKeywords'] = '';
        $this->fields['header'] = 'include/header.php';
        $this->setTemplate('page');
        $this->setLayout('index');
    }

    public function availableLocales()
    {
        return $this->registry->config->availableLocales;
    }

    public function assign($variable = '', $value)
    {
        if ($variable == '') {
            $this->fields = $value;
        } else {
            $this->fields[$variable] = $value;
        }
    }

    public function customize($data)
    {
        foreach ($data as $key => $val) {
            $this->assign($key, $val);
        }
    }

    public function setTemplate($page)
    {
        $file = TEMPLATE_DIR . $page . '.php';
        if (file_exists($file)) {
            $this->template = $file;
        } else {
            exit($file . " view file doesn't exist.");
        }
    }

    public function setLayout($layout)
    {
        $file = TEMPLATE_DIR . $layout . '.php';
        if (file_exists($file)) {
            $this->layout = $file;
        } else {
            exit($file . " view file doesn't exist.");
        }
    }

    public function render($page = null, $layout = null)
    {
        $this->translator = new Translator(@$_COOKIE['lang']);
        if ($page) {
            $this->setTemplate($page);
        }
        if ($layout) {
            $this->setLayout($layout);
        }
        include($this->template);
        exit();
    }

    public function trans($text, $data = array())
    {

        return $this->translator->trans($text, $data);
    }

    public function getUrl() {
        return $this->registry->request->getCurrentUrl();
    }

    public function getUser()
    {
        return $this->auth->getUser();
    }
}
