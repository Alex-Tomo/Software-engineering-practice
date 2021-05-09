// start the websocket for notifications
let socket = new WebSocket('ws://127.0.0.1:3002');

// when the server sends a message
socket.onmessage = (e) => {
    let data = JSON.parse(e.data);
    let email = document.getElementById('usersEmailAddress').getAttribute('name');

    //trim the message so it doesnt overflow (max of 50 characters)
    let shortDesc = '';
    for(let i = 0; i < data.desc.length; i++) {
        if(i <= 50) {
            shortDesc += data.desc[i];
        } else if(i > 50) {
            shortDesc += '...';
            break;
        }
    }

    //  check if the user exists
    $.ajax({
        url: "./handlers/checkIfUser.php",
        method: "POST",
        data: {
            id: data.userId,
            email: email,
            jobId: data.jobId
        },
        success: (d) => {
            if(d) {

                //if mobile view
                if(window.innerWidth <= 768) {

                    // load the notifications into the div
                    let existingPs = document.getElementById('floatingNotificationsDiv').getElementsByTagName('p');
                    if (existingPs.length >= 5) {
                        document.getElementById('floatingNotificationsDiv').removeChild(document.getElementById('floatingNotificationsDiv').lastElementChild);
                    }

                    let p = document.createElement('p');
                    let hr = document.createElement('hr');
                    p.innerText = data.name + " sent you a message regarding '" + data.jobTitle + "'\n" + shortDesc + "\n" + data.dt;
                    p.style.padding = '10px';

                    document.getElementById('floatingNotificationsDiv').insertBefore(hr, existingPs[0]);
                    document.getElementById('floatingNotificationsDiv').insertBefore(p, hr);

                    if (document.body.contains(document.getElementById('noNotifications'))) {
                        document.getElementById('floatingNotificationsDiv').removeChild(document.getElementById('floatingNotificationsDiv').lastElementChild);
                    }

                    document.getElementById('floatingNotifications').src = 'assets/bell-red.svg';
                    document.getElementById('floatingNumberOfNotifications').style.display = 'inline';
                    document.getElementById('floatingNumberOfNotifications').innerText = '' + d;

                    let notifications = document.getElementById('floatingNotificationsDiv').getElementsByTagName('p');
                    for (let i = 0; i < notifications.length; i++) {
                        notifications[i].addEventListener('click', () => {
                            openPage('messages.php');
                        });
                    }

                    // desktop
                } else {

                    //load the notifications into the div
                    let existingPs = document.getElementById('notificationsDiv').getElementsByTagName('p');
                    if (existingPs.length >= 5) {
                        document.getElementById('notificationsDiv').removeChild(document.getElementById('notificationsDiv').lastElementChild);
                    }

                    let p = document.createElement('p');
                    let hr = document.createElement('hr');
                    p.innerText = data.name + " sent you a message regarding '" + data.jobTitle + "'\n" + shortDesc + "\n" + data.dt;
                    p.style.padding = '10px';

                    document.getElementById('notificationsDiv').insertBefore(hr, existingPs[0]);
                    document.getElementById('notificationsDiv').insertBefore(p, hr);

                    if (document.body.contains(document.getElementById('noNotifications'))) {
                        document.getElementById('notificationsDiv').removeChild(document.getElementById('notificationsDiv').lastElementChild);
                    }

                    document.getElementById('notifications').src = 'assets/bell-red.svg';
                    document.getElementById('numberOfNotifications').style.display = 'inline';
                    document.getElementById('numberOfNotifications').innerText = '' + d;
                    let notifications = document.getElementById('notificationsDiv').getElementsByTagName('p');
                    for (let i = 0; i < notifications.length; i++) {
                        notifications[i].addEventListener('click', () => {
                            openPage('messages.php');
                        });
                    }
                }
            }
        }
    })
}