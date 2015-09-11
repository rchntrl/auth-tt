<?php

class Request
{
    private $url;

    private $search;

    private $request;

    public $routeParams;

    public function __construct()
    {
        global $url;
        $this->url = $url;
        $this->search = $_GET;
        $this->request = array_merge($_REQUEST, $this->search);
        unset($this->request['url']);
    }

    public function parseParams($data)
    {
        $routeParams = array();
        foreach (array_filter(explode('/', $this->getUrl())) as $val) {
            $routeParams[] = $val;
        }
        foreach ($routeParams as $key => $val) {
            if (isset($data[$key])) {
                $this->routeParams[$data[$key]] = $val;
            }
        }
    }

    public function isPostMethod()
    {
        return $_SERVER['REQUEST_METHOD'] == 'POST';
    }

    public function isAjax() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function getCurrentUrl() {
        return BASE_URL . $this->url;
    }

    public function getBackUrl() {
        if (isset($_SERVER['HTTP_REFERER'])) {
            return $_SERVER['HTTP_REFERER'];
        }
        return BASE_URL;
    }

    public function getSearch()
    {
        return $this->search;
    }

    public function getRequestData() {
        return $this->request;
    }

    public function getValue($key) {
        return @$this->request[$key];
    }

    public function getRouteValue($key)
    {
        return @$this->routeParams[$key];
    }

    public function getFile($key) {
        return @$_FILES[$key];
    }
}
