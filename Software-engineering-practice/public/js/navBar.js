function signUpPage(){
    let path = window.location.href.split('/');
    let signinPath = '';
    for(let i = 0; i < path.length; i++) {
        signinPath += path[i]+'/';
        if(path[i] === 'Software-engineering-practice') {
            break;
        }
    }
    window.location.href = signinPath + "/public/signin.php";
}

function registerPage(){
    let path = window.location.href.split('/');
    let registerPath = '';
    for(let i = 0; i < path.length; i++) {
        registerPath += path[i]+'/';
        if(path[i] === 'Software-engineering-practice') {
            break;
        }
    }
    window.location.href = registerPath + "/public/register.php";
}