<?php
class WebPageScraper {
    private $url;
    private $dom;

    public function __construct($url) {
        $this->url = $url;
        $this->dom = new DOMDocument;
    }

    public function getDOM() {
        return $this->dom;
    }

    public function loadPage() {
        if ($this->url) {
            $html = file_get_contents($this->url);
            if ($html) {
                @$this->dom->loadHTML($html);
            } else {
                return null;
            }
            return $this->dom;
        } else {
            return null;
        }
    }

    public function findElementsByClass($className, $tagName = "*") {
        $xpath = new DOMXPath($this->dom);
        $query = "//" . $tagName . "[contains(@class, '$className')]";
        return $xpath->query($query);
    }
}
?>