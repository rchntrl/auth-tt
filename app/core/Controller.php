<?php

/**
 *
 * Class Controller
 */
class Controller
{

    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var
     */
    protected $translator;

    /**
     * @var View
     */
    protected $view;

    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
        $this->request = $registry->request;
        $this->view = new View($registry);
        $this->translator = new Translator(@$_COOKIE['lang']);
    }

    /**
     * @return false|User
     */
    public function getUser()
    {
        return $this->registry->auth->getUser();
    }

    public function customize($data)
    {
        $this->view->customize($data);
    }

    public function render($page, $layout)
    {
        $this->view->render($page, $layout);
    }

    public function trans($text, $data = null) {
        return $this->translator->trans($text, $data);
    }


    public static function handleNotFound($message = '404 Not found')
    {
        header('HTTP/1.0 404 Not Found');
        exit($message);
    }

    public function redirectUrl($url) {
        header("Location: " . $url);
        exit();
    }
}
