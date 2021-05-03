// $(document).ready(() => {
//
//     let dropdown = document.getElementById("dropbtn");
//     let dropdownContent = document.getElementById('dropdown_content');
//     let mouseOver = false;
//
//     if (dropdown !== null) {
//         dropdown.addEventListener('mouseover', () => {
//             mouseOver = true;
//             document.getElementById('dropdown_content').style.display = 'block';
//         });
//
//         dropdownContent.addEventListener('mouseover', () => {
//             mouseOver = true;
//         });
//
//         dropdownContent.addEventListener('mouseleave', () => {
//             mouseOver = false;
//             document.getElementById('dropdown_content').style.display = 'none';
//         });
//
//         dropdown.addEventListener('mouseleave', () => {
//             if(!mouseOver) {
//                 document.getElementById('dropdown_content').style.display = 'none';
//             }
//         });
//     }
//
//     document.getElementById('dropdown_content').onmouseenter = () => {
//         return false;
//     }
// });
//

function hambgrMenu() {
    let hambgr = document.getElementById("myLinks");
    if (hambgr.style.display === "block") {
        hambgr.style.display = "none";
    } else {
        hambgr.style.display = "block";
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
