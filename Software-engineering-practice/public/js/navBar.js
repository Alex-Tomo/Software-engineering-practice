function myFunction() {
    var x = document.getElementById("myLinks");
    if (x.style.display === "block") {
        x.style.display = "none";
    } else {
        x.style.display = "block";
    }
}

function signUpPage(){
    window.location.href = "signin.php";
}

function registerPage(){
    window.location.href = "register.php";
}