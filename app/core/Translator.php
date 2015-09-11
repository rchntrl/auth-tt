<?php

class Translator {

    private $default_replacements = array(
        '/&amp;/u' => '-and-',
        '/&/u' => '-and-',
        '/\s|\+/u' => '-', // remove whitespace/plus
        '/[_.]+/u' => '-', // underscores and dots to dashes
        '/[^A-Za-z0-9\-]+/u' => '', // remove non-ASCII chars, only allow alphanumeric and dashes
        '/[\-]{2,}/u' => '-', // remove duplicate dashes
        '/^[\-]+/u' => '', // Remove all leading dashes
        '/[\-]+$/u' => '' // Remove all trailing dashes
    );

    private $filePath;

    private $lang;

    public function __construct($lang = 'en') {
        $this->filePath = __DIR__ . '/../../translations/messages.xml';
        if (empty($lang)) {
            $lang = 'en';
        }
        $this->lang = $lang;
    }

    /**
     * @return SimpleXMLElement
     */
    public function getDocument() {
        return new SimpleXMLElement($this->filePath, 0, true);
    }

    public function getReplacements() {
        return $this->default_replacements;
    }

    public function filter($text) {
        $text = mb_strtolower($text);
        $replacements = $this->getReplacements();

        foreach($replacements as $regex => $replace) {
            $text = preg_replace($regex, $replace, $text);
        }

        return $text;
    }

    public function trans($text, $data = null) {
        $xml = $this->getDocument();
        $id = $this->filter($text);
        $node = $xml->xpath("//document//trans[@id='" . $id . "']//target[@lang='" . $this->lang . "']");
        if (!isset($node[0]) && $this->lang != 'en') {
            $this->addTranslation($id, $text);
        }
        $phrase = isset($node[0]) ? (string)$node[0] : $text;
        if ($data) {
            foreach($data as $key => $val) {
                $phrase = preg_replace('/' . $key . '/', $val, $phrase);
            }
        }
        return $phrase;
    }

    protected function addPhrase($text, $lang = null) {
        if (!$lang) {
            $lang = $this->lang;
        }
        $xml = $this->getDocument();
        $trans = $xml->addChild("trans");
        $id = $this->filter($text);
        $trans->addAttribute('id', $id);
        $trans->source = $text;
        $trans->target = $text;
        $trans->target->addAttribute('lang', $lang);
        $xml->asXML($this->filePath);
    }

    protected function addTranslation($id, $text, $lang = null) {
        if (!$lang) {
            $lang = $this->lang;
        }
        $xml = $this->getDocument();
        $trans = $xml->xpath("//document//trans[@id='" . $id . "']");
        if (isset($trans[0])) {
            //$trans->addAttribute('lang', $lang);
            $trans[0]->addChild('target', '')->addAttribute('lang', $lang);
            $target = $xml->xpath("//document//trans[@id='" . $id . "']//target[@lang='" . $this->lang . "']");
            $target[0] = $text;
            $xml->asXML($this->filePath);
        } else {
            $this->addPhrase($text);
        }
    }
}
