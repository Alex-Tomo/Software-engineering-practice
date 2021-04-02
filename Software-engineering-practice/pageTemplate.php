<?php


class pageTemplate {

    private $head = '';
    private $title = '';
    private $bodyItem = array();
    private $body = '';
    private $css = array();
    private $js = array();
    private $includePath = '';

    function __construct($title) {
        $this->title = $title;

        $arr = explode("\\", __DIR__);
        foreach ($arr as $a) {
            $this->includePath .= $a.'/';
            if ($a == 'Software-engineering-practice') {
                break;
            }
        }
    }

    private function pageHead() {
        $this->head .= "<!DOCTYPE html>
            <html lang=\"en\">
            <head>
                <meta charset=\"utf-8\">
                <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
                <script src=\"https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js\"></script>";

                foreach ($this->css as $style) { $this->head .= "\n".$style; }
                foreach ($this->js as $script) { $this->head .= "\n".$script; }

                $this->head .= "\n"."<title>{$this->title}</title>
            </head>";
        return $this->head;
    }

    public function addCSS($cssPath) {
        return array_push($this->css, "<link rel=\"stylesheet\" href=\"{$cssPath}\">");
    }

    private function pageHeader() {
        return file_get_contents ($this->includePath.'public/include/header.html');
    }

    public function addPageBodyItem($item) {
        array_push($this->bodyItem, $item);
    }

    private function pageBody() {
        $this->body = $this->pageHeader();
        $this->body .= "<body>";

        foreach ($this->bodyItem as $item) { $this->body .= "\n".$item; }

        $this->body .= "</body>";
        $this->body .= $this->pageFooter();
        return $this->body;
    }

    private function pageFooter() {
        return file_get_contents ($this->includePath.'public/include/footer.html');
    }

    public function addJavaScript($jsPath) {
        return array_push($this->js, $jsPath);
    }

    public function displayPage() {
        echo $this->pageHead().$this->pageBody()."</html>";
    }

}
