window.onload = () => {
    
    let interval;
    let intervalListPage;

    const checkUserStatus = (index) => {
        let chat_users = document.getElementsByClassName('message_users');

        $.ajax({
            url: './handlers/checkUserOnline.php',
            method: 'POST',
            data: {
                target_id: chat_users[index].getAttribute('name')
            },
            success: (data) => {

                if (data.trim().includes('online')) {
                    document.getElementById('messagesTitle').getElementsByTagName('div')[0].style.backgroundColor = '#03AC13';
                } else if (!data.trim().includes('online')) {
                    document.getElementById('messagesTitle').getElementsByTagName('div')[0].style.backgroundColor = '#FF0000';
                }
            }
        });
    }

    const checkUserStatusListPage = (index) => {
        let users = document.getElementsByClassName('message_users');

        $.ajax({
            url: './handlers/checkUserOnline.php',
            method: 'POST',
            data: {
                target_id: users[index].getAttribute('name')
            },
            success: (data) => {

                let chatUsers = users[index].getElementsByTagName('div')[0];

                if (data.trim().includes('online')) {
                    chatUsers.style.backgroundColor = '#03AC13';
                } else if (!data.trim().includes('online')) {
                    chatUsers.style.backgroundColor = '#FF0000';
                }
            }
        });
    }

    let messageUsers = document.getElementsByClassName('message_users');
    let email = document.getElementById('email').value;

    for(let i = 0; i < messageUsers.length; i++) {

        checkUserStatusListPage(i);
        intervalListPage = setInterval(() => {
            checkUserStatusListPage(i);
        }, 3000);

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



            checkUserStatus(i);

            let interval = setInterval(() => {
                checkUserStatus(i);
            }, 3000);

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

            $.ajax({
                url: "./handlers/updateMessageRead.php",
                method: "POST",
                data: {
                    jobId: jobId,
                    email: email
                },
                success: (d) => {
                    console.log(d);
                }
            });


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
                    document.getElementById('messagingImage').src = `${data[1]}`;

                    for(let i = 0; i < data[2].length; i++) {
                        if(data[2][i].userId !== otherUserId) {

                            let p = document.createElement('div');
                            p.innerHTML = '<b>You</b> - ' + data[2][i].messages + '<br><i style=\'float:right; font-size: xx-small; margin-top: 5px;\'>sent - ' + data[2][i].createdOn + '</i>';
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
                            p.innerHTML = '<b>' + data[0] + '</b> - ' + data[2][i].messages + '<br><i style=\'float: right; font-size: xx-small; margin-top: 5px;\'>recieved - '+ data[2][i].createdOn +'</i>';
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
                }
            });
        });
    }

    let backToMessageList = document.getElementById('backToMessageList');

    backToMessageList.addEventListener('click', (e) => {
        e.preventDefault();
        clearInterval(interval);

        while(document.getElementById('messages').lastElementChild) {
            document.getElementById('messages').removeChild(document.getElementById('messages').lastElementChild);
        }

        document.getElementById('messagesList').style.display = 'block';
        document.getElementById('messages').style.display = 'none';
        document.getElementById('sendMessages').style.display = 'none';
        document.getElementById('messagesTitle').style.display = 'none';

    });
}