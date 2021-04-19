<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<header>
    <div id="mobile_container">
        <div id="topnav">
            <h1 class="clickable" onclick="openPage('home.php')">skip</h1><h1 class="clickable" id="logoText2" onclick="openPage('home.php')">CV</h1>
<?php
if(!$_SESSION['loggedin']){
    echo $_SESSION['loggedin'];
    ?>
    <div id="myLinks">
        <a id="topLink" class="mobLinks clickable" onclick="openPage('home.php')">Home</a>
        <button class="clickable" onclick="openPage('register.php')" id="signUpMob">Sign Up</button><br>
        <button class="clickable" onclick="openPage('signin.php')" id="loginMob">Login</button>
    </div>
            <a href="javascript:void(0);" class="icon" onclick="hambgrMenu()">
                <i class="fa fa-bars"></i>
            </a>
    <?php
} else {
    ?>
    <div id="myLinks">
        <a id="topLink" class="mobLinks clickable" onclick="openPage('home.php')">Home</a>
        <button class="clickable" onclick="openPage('signin.php')" id="loginMob">Logout</button>
    </div>
            <a href="javascript:void(0);" class="icon" onclick="hambgrMenu()">
                <i class="fa fa-bars"></i>
            </a>
<?php
}
?>
        </div>
    </div>
</header>


<!---->
<!--    <div id="desktop_container">-->
<!--        <h1 class="clickable" onclick="openPage('home.php')">skip</h1><h1 class="clickable" id="logoText2" onclick="openPage('home.php')">CV</h1>-->
<!--        <nav>-->
<!--            <button class="clickable" onclick="openPage('register.php')" id="signUp">Sign Up</button>-->
<!--            <button class="clickable" onclick="openPage('signin.php')" id="login"><a class="links">Login</a></button>-->
<!--            <ul>-->
<!--                <li><a class="links clickable" onclick="openPage('home.php')">Home</a></li>-->
<!--            </ul>-->
<!--        </nav>-->
<!--    </div>-->
<!--</header>-->