function myFunction() {
    var x = document.getElementById("myLinks");
    if (x.style.display === "block") {
        x.style.display = "none";
    } else {
        x.style.display = "block";
    }
}

function signUpPage(){
    let path = window.location.href.split('/');
    let signinPath = '';
    for(let i = 0; i < path.length; i++) {
        signinPath += path[i]+'/';
        if(path[i] === 'public') {
            break;
        }
    }
    window.location.href = signinPath + "signin.php";
}

function registerPage(){
    let path = window.location.href.split('/');
    let registerPath = '';
    for(let i = 0; i < path.length; i++) {
        registerPath += path[i]+'/';
        if(path[i] === 'public') {
            break;
        }
    }
    window.location.href = registerPath + "register.php";
}