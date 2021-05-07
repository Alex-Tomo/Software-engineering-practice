window.onload = () => {

    // TODO change the intervals to use websockets (on new user update other users)

    let interval;
    let intervalListPage;

    const checkUserStatus = () => {
        let target_id = document.getElementById('otherUserId').getAttribute('name');

        $.ajax({
            url: './handlers/checkUserOnline.php',
            method: 'POST',
            data: {
                target_id: target_id
            },
            success: (data) => {
                if(data.trim().includes('online') && (document.getElementById('onlineStatus').innerText !== 'Online')) {
                    document.getElementById('onlineStatus').innerText = 'Online';
                } else if(!data.trim().includes('online') && (document.getElementById('onlineStatus').innerText !== 'Offline')) {
                    document.getElementById('onlineStatus').innerText = 'Offline';
                }
            }
        });
    };

    const checkUserStatusListPage = () => {
        let users = document.getElementsByClassName('message_users');
        for(let i = 0; i < users.length; i++) {
            $.ajax({
                url: './handlers/checkUserOnline.php',
                method: 'POST',
                data: {
                    target_id: users[i].getAttribute('name')
                },
                success: (data) => {
                    if (data.trim().includes('online') && (document.getElementById('onlineStatusListPage').innerText !== 'Online')) {
                        document.getElementById('onlineStatusListPage').innerText = 'Online';
                    } else if (!data.trim().includes('online') && (document.getElementById('onlineStatusListPage').innerText !== 'Offline')) {
                        document.getElementById('onlineStatusListPage').innerText = 'Offline';
                    }
                }
            });
        }
    }

    checkUserStatusListPage();
    intervalListPage = setInterval(() => {
        checkUserStatusListPage();
    }, 1000);

    let messageUsers = document.getElementsByClassName('message_users');
    let email = document.getElementById('email').value;

    for(let i = 0; i < messageUsers.length; i++) {

        //working
        $.ajax({
           url: "./handlers/getLatestMessages.php",
           method: "POST",
           data: {
               email: email,
               id: messageUsers[i].getAttribute('name')
           },
           success: (data) => {
               if(data) {
                   let d = JSON.parse(data);
                   if(d[0] !== null) {
                       let msg = document.getElementById(messageUsers[i].id);
                       let p = msg.getElementsByTagName('p');
                       p[0].innerText = d[0].message;
                       p[1].innerText = d[0].created_on;
                   }
               }
           }
        });

        messageUsers[i].addEventListener('click', () => {
            clearInterval(intervalListPage);
            document.getElementById('messagesTitle').setAttribute('name', messageUsers[i].getAttribute('name'));
            document.getElementById('messagesList').style.display = 'none';
            document.getElementById('messagesTitle').style.display = 'block';
            while(document.getElementById('messages').lastElementChild) {
                document.getElementById('messages').removeChild(document.getElementById('messages').lastElementChild);
            }
            document.getElementById('messages').style.display = 'block';
            document.getElementById('sendMessages').style.display = 'flex';
            document.getElementById('messages').style.marginTop = document.getElementById('messagesTitle').offsetHeight + 'px';

            document.getElementById('jobId').setAttribute('name', messageUsers[i].id);
            document.getElementById('otherUserId').setAttribute('name', messageUsers[i].getAttribute('name'));

            let jobId = document.getElementById('jobId').getAttribute('name');
            let otherUserId = document.getElementById('otherUserId').getAttribute('name');

            //working
            $.ajax({
                url: "./handlers/userMessagingDetails.php",
                method: "POST",
                data: {
                    id: otherUserId,
                    email: email
                },
                success: (d) => {
                    let data = JSON.parse(d);
                    document.getElementById('messagingName').innerText = `${data[0]}`;

                    for(let i = 0; i < data[1].length; i++) {
                        if(data[1][i].userId !== otherUserId) {

                            let p = document.createElement('div');
                            p.innerHTML = '<b>You</b> - ' + data[1][i].messages + '<br><i style=\'float:right; font-size: xx-small; margin-top: 5px;\'>sent - ' + data[1][i].createdOn + '</i>';
                            p.style.backgroundColor = '#DADADA';
                            p.style.width = '60%';
                            p.style.marginLeft = '10px';
                            p.style.marginTop = '10px';
                            p.style.marginBottom = '10px';
                            p.style.padding = '10px 10px 35px 10px';
                            p.style.borderRadius = '10px';
                            p.style.display = 'block';
                            p.style.wordWrap = 'break-word';
                            document.getElementById('messages').appendChild(p);

                        } else {

                            let p = document.createElement('div');
                            p.innerHTML = '<b>' + data[0] + '</b> - ' + data[1][i].messages + '<br><i style=\'float: right; font-size: xx-small; margin-top: 5px;\'>recieved - '+ data[1][i].createdOn +'</i>';
                            p.style.backgroundColor = '#4BA4FE';
                            p.style.width = '60%';
                            p.style.marginLeft = 'auto';
                            p.style.marginTop = '15px';
                            p.style.marginRight = '10px';
                            p.style.marginBottom = '10px';
                            p.style.padding = '10px 10px 35px 10px';
                            p.style.borderRadius = '10px';
                            p.style.display = 'block';
                            p.style.wordWrap = 'break-word';
                            document.getElementById('messages').appendChild(p);
                        }
                    }
                    document.getElementById('messages').scrollTop = document.getElementById('messages').scrollHeight;
                    checkUserStatus();

                    let interval = setInterval(() => {
                        checkUserStatus();
                    }, 1000);
                }
            });
        });
    }

    let backToMessageList = document.getElementById('backToMessageList');

    backToMessageList.addEventListener('click', (e) => {
        e.preventDefault();
        clearInterval(interval);
        intervalListPage;

        while(document.getElementById('messages').lastElementChild) {
            document.getElementById('messages').removeChild(document.getElementById('messages').lastElementChild);
        }

        document.getElementById('messagesList').style.display = 'block';
        document.getElementById('messages').style.display = 'none';
        document.getElementById('sendMessages').style.display = 'none';
        document.getElementById('messagesTitle').style.display = 'none';

    });
}