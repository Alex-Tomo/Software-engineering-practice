function hambgrMenu() {
    var x = document.getElementById("myLinks");
    if (x.style.display === "block") {
        x.style.display = "none";
    } else {
        x.style.display = "block";
    }
}

// Added by Alex - 1 generic method instead of a lot of methods
// To use it:
// onclick="openPage('signin.php')" etc.
// Use this way because it allows us to redirect into the public folder
// from anywhere on the site

function openPage(pageToOpen) {
    let path = window.location.href.split('/');
    let newPagePath = '';
    for(let i = 0; i < path.length; i++) {
        newPagePath += path[i]+'/';
        if(path[i] === 'public') {
            break;
        }
    }
    window.location.href = newPagePath + pageToOpen;
}

// function signUpPage(){
//     let path = window.location.href.split('/');
//     let signinPath = '';
//     for(let i = 0; i < path.length; i++) {
//         signinPath += path[i]+'/';
//         if(path[i] === 'public') {
//             break;
//         }
//     }
//     window.location.href = signinPath + "signin.php";
// }
//
// function registerPage(){
//     let path = window.location.href.split('/');
//     let registerPath = '';
//     for(let i = 0; i < path.length; i++) {
//         registerPath += path[i]+'/';
//         if(path[i] === 'public') {
//             break;
//         }
//     }
//     window.location.href = registerPath + "register.php";
// }