<?php

//Include session stuff here instead of including it on every page
//ini_set("session.save_path", __DIR__."/sessionData");
session_start();

// Checks if the user is logged in, if not sets the loggedin SESSION to false
if(!isset($_SESSION['loggedin'])) {
    $_SESSION['loggedin'] = false;
}

// Page template for all the php files, displays the header
// and footer and allows the user to add javascript, css, body items etc
// use as follows:

// $page = new pageTemplate("Some page title");
// $page->addCSS(<Link/ To/ CSS/ File>);
// $page->addJavaScript(<Link/ To/ JavaScript/ File>);
// $page->addBodyItem("Some body item");
// $page->displayPage();

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

        // Gets the path to the Software-engineering-practice folder
        // dynamically so it doesnt matter where the user is on the website
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
                <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no\">
                <link rel=\"stylesheet\" href=\"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css\">
                <script src=\"https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js\"></script>";

                foreach ($this->css as $style) { $this->head .= "\n".$style; }
                foreach ($this->js as $script) { $this->head .= "\n".$script; }

                $this->head .= "\n"."<title>{$this->title}</title>
            </head>";

        return $this->head;
    }

    public function addCSS($cssLinkTag) {
        return array_push($this->css, $cssLinkTag);
    }

    private function pageHeader() {
        include $this->includePath.'public/include/header.php';
        return getHeader();
    }

    public function addPageBodyItem($item) {
        array_push($this->bodyItem, $item);
    }

    private function pageNotifications() {
        include $this->includePath.'public/include/notifications.php';
        return getNotifications();
    }

    private function pageFooter() {
        include $this->includePath.'public/include/footer.php';
        return getFooter();
    }

    private function pageBody() {
        $this->body .= "<body>";
        $this->body .= $this->pageHeader();

        foreach ($this->bodyItem as $item) { $this->body .= "\n".$item; }

        $this->body .= $this->pageNotifications();
        $this->body .= $this->pageFooter();
        $this->body .= "</body>";
        return $this->body;
    }

    public function addJavaScript($jsScriptTag) {
        return array_push($this->js, $jsScriptTag);
    }

    public function displayPage() {
        echo $this->pageHead().$this->pageBody()."</html>";
    }

    public function setSession($key, $value) {
        $_SESSION["{$key}"] = $value;
    }

}
