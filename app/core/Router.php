<?php

class Router
{
    /**
     * @var Request
     */
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function isMatch($pattern)
    {
        return in_array(preg_replace($pattern, '', $this->request->getUrl()), array('', '/'));
    }

    /**
     * @param String $pattern
     * @param Array $data
     * @param $func
     */
    public function handle($pattern, $data, $func)
    {
        if ($this->isMatch($pattern)) {
            $this->request->parseParams($data);
            $func();
            exit();
        }
    }
}
