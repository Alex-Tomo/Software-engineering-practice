window.onresize = () => {
    x = document.getElementById('notifications').getBoundingClientRect().x;
    y = document.getElementById('notifications').getBoundingClientRect().y;
    document.getElementById('notificationsDiv').style.left = (x-160)+'px';
    document.getElementById('notificationsDiv').style.top = (y+y+25)+'px';
}

$(document).ready(() => {

    let x = document.getElementById('notifications').getBoundingClientRect().x;
    let y = document.getElementById('notifications').getBoundingClientRect().y;
    document.getElementById('notificationsDiv').style.left = (x-160)+'px';
    document.getElementById('notificationsDiv').style.top = (y+y+25)+'px';

    document.getElementById('notifications').addEventListener('click', (e) => {
        e.preventDefault();

        if(document.getElementById('notificationsDiv').style.display == 'none') {
            document.getElementById('notificationsDiv').style.display = 'block';
            document.getElementById('numberOfNotifications').style.display = 'none';
            document.getElementById('notifications').src = 'assets/bell.svg';

            let email = document.getElementById('usersEmailAddress').getAttribute('name');
            let notificationsParas = document.getElementById('notificationsDiv').getElementsByTagName('p');
            if(notificationsParas.length > 1) {
                if(document.body.contains(document.getElementById('noNotifications'))) {
                    document.getElementById('notificationsDiv').removeChild(document.getElementById('notificationsDiv').lastElementChild);
                }
            }
            let notifications = [];
            let notificationsMessages = [];
            for(let i = 0; i < notificationsParas.length; i++) {
                let jobTitle = notificationsParas[i].innerText.split('\'');
                notifications[i] = jobTitle[1];
                let jobMessage = notificationsParas[i].innerText.split('\n');
                notificationsMessages[i] = jobMessage[1];
            }

            for(let i = 0; i < notificationsParas.length; i++) {
                notificationsParas[i].addEventListener('click', () => {
                    openPage('messages.php');
                });
            }

            $.ajax({
                url: "./handlers/readNotifications.php",
                method: "POST",
                data: {
                    email: email,
                    notifications: notifications,
                    notificationsMessages: notificationsMessages
                },
                success: (data) => {
                    if(data) {

                    }
                }
            });
        } else {
            document.getElementById('notificationsDiv').style.display = 'none';
        }
    });

    document.getElementById('dropdown').addEventListener('mouseenter', () => {
        document.getElementById('notificationsDiv').style.display = 'none';
    });
});

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
