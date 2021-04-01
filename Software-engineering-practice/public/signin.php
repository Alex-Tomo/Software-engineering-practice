<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="./css/formStyling.css">
    <link rel="stylesheet" href="./css/footerStyling.css">
    <link rel="stylesheet" href="./css/headerStyling.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <title>Sign In</title>
</head>

<body>

<?php
    include ("./include/header.html");
?>

<div id="registerForm">
    <form action='./handlers/signin_handler.php' method='POST'>
        <h2>Login</h2>
        <label for="email">Email</label><br>
        <input type="email" id="email" name="email" placeholder="Enter your email"><br>
        <label for="password">Password</label><br>
        <input type="password" id="password" name="password" placeholder="Enter your password"><br>
        <input type="submit" value="Login">
        <p class="para">Don't have an account?</p>
        <a id="login" href="./register.php">Create one!</a>
    </form>
</div>

<?php
    include("./include/footer.html");
?>
<!--<div id="registerForm2">-->
<!--    <h2>General information</h2>-->
<!--        <label for="fname">First name</label><br>-->
<!--        <input type="text" id="fname" name="fname" placeholder="Your name"><br>-->
<!--        <label for="lname">Last name</label><br>-->
<!--        <input type="text"  id="lname" name="lname" placeholder="Last name"><br>-->
<!--        <label for="gender">Sex</label>-->
<!--        <select id="gender" name="gender">-->
<!--            <option value="0"> Select </option>-->
<!--            <option value="male">Male</option>-->
<!--            <option value="female">Female</option>-->
<!--            <option value="pnts">Prefer not to say</option>-->
<!--        </select>-->
<!--        <label for="birthday">Birth date</label><br>-->
<!--        <input type="date" id="birthday" name="birthday"><br>-->
<!--        <label for="lang">Preferred language</label><br>-->
<!--        <select id="lang" name="lang">-->
<!--            <option value="0">Choose your language</option>-->
<!--        </select>-->
<!--        <label for="reg">Region</label><br>-->
<!--        <select id="reg" name="reg">-->
<!--            <option value="0">Choose your region</option>-->
<!--        </select>-->
<!--&lt;!&ndash;        <div id="tandc">&ndash;&gt;-->
<!--&lt;!&ndash;            <p>Creating the account you agree with our <a id="tandcLink" href="">Terms & Conditions</a>.</p>&ndash;&gt;-->
<!--&lt;!&ndash;        </div>&ndash;&gt;-->
<!--        <input id="submit" type="submit" value="Next">-->
<!--    </form>-->
<!--</div>-->
<!--<a id="back" href="">Back</a>-->
</body>
</html>