<?php

function getFooter()
{

    //Dynamic redirect
    $path = '';
    $arr = explode("\\", __DIR__);
    foreach ($arr as $a) {
        $path .= $a . '/';
        if ($a == 'Software-engineering-practice') {
            $path .= 'db_connector.php';
            break;
        }
    }

    include_once $path;

    $footer = '';

    $footer .= "<footer>
            <div class='footerLinks'>
                <h3>Platform</h3>
                <ul>";
    if ($_SESSION['loggedin']) {
        $footer .= "<li><a class='clickable' onClick='openPage(`loggedinHome.php`)'>Home</a></li>";
    } else {
        $footer .= "<li><a class='clickable' onClick='openPage(`home.php`)'>Home</a></li>";
    }
    $footer .= "<li><a class='clickable' href='#'>Features</a></li>
                    <li><a class='clickable' href='#'>Testimonials</a></li>
                    <li><a class='clickable' href=''>Contact Us</a></li>
                    <li><a class='clickable' href=''>Follow Us</a></li>
                </ul>
            </div>

    <div class='footerLinks'>
        <h3>Company</h3>
        <ul>
            <li><a class='clickable' href='#'>About Application</a></li>
            <li><a class='clickable' href='#'>Brand Guidelines</a></li>
            <li><a class='clickable' href='#'>Press Kit</a></li>
            <li><a class='clickable' href='#'>Support</a></li>
        </ul>
    </div>

    <div id='logoTop'>";
    if ($_SESSION['loggedin']) {
        $footer .= "<h1 onClick='openPage(`loggedinHome.php`)' class='clickable' id='logoText1'> skip</h1><h1 onClick='openPage(`loggedinHome.php`)' class='clickable'>CV</h1>";
    } else {
        $footer .= "<h1 onClick = 'openPage(`home.php`)' class='clickable' id='logoText1'> skip</h1><h1 onClick = 'openPage(`home.php`)' class='clickable'>CV</h1>";
    }
    $footer .= "</div>
    <p>This application is for people who would like to spend their spare time productively and earn some money.</p>
    <div id='logoBottom'>";
    if ($_SESSION['loggedin']) {
        $footer .= "<h1 onClick='openPage(`loggedinHome.php`)' class='clickable' id='logoText1'> skip</h1><h1 onClick='openPage(`loggedinHome.php`)' class='clickable'>CV</h1>";
    } else {
        $footer .= "<h1 onClick = 'openPage(`home.php`)' class='clickable' id='logoText1'> skip</h1><h1 onClick='openPage(`home.php`)' class='clickable'>CV</h1>";
    }
    $footer .= "</div>
</footer>";

    return $footer;
}
?>