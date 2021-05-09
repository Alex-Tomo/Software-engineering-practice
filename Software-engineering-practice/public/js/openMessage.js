window.onload = () => {

    let interval;
    let intervalListPage;

    // check if the user is online on the 1 to 1 chat
    const checkUserStatus = (index) => {
        let chat_users = document.getElementsByClassName('message_users');

        $.ajax({
            url: './handlers/checkUserOnline.php',
            method: 'POST',
            data: {
                target_id: chat_users[index].getAttribute('name')
            },
            success: (data) => {

                // change the colour of the chat icon depending on online status
                if (data.trim().includes('online')) {
                    document.getElementById('messagesTitle').getElementsByTagName('div')[0].style.backgroundColor = '#03AC13';
                } else if (!data.trim().includes('online')) {
                    document.getElementById('messagesTitle').getElementsByTagName('div')[0].style.backgroundColor = '#FF0000';
                }
            }
        });
    }

    // check if the user is online on the 1 to many page
    const checkUserStatusListPage = (index) => {
        let users = document.getElementsByClassName('message_users');

        $.ajax({
            url: './handlers/checkUserOnline.php',
            method: 'POST',
            data: {
                target_id: users[index].getAttribute('name')
            },
            success: (data) => {

                // if the user is online set the chat icon colour
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

        //check every user every 3 seconds
        checkUserStatusListPage(i);
        intervalListPage = setInterval(() => {
            checkUserStatusListPage(i);
        }, 3000);

        // get the latest messages for the chat list page
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
                   //load the chat message and time it was sent
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

        // when clicking on a user stop checking the list pages online statuses
        messageUsers[i].addEventListener('click', () => {
            clearInterval(intervalListPage);

            // check the status of the individual user every 3 seconds
            checkUserStatus(i);

            let interval = setInterval(() => {
                checkUserStatus(i);
            }, 3000);

            // display the correct messaging section
            document.getElementById('messagesTitle').setAttribute('name', messageUsers[i].getAttribute('name'));
            document.getElementById('messagesList').style.display = 'none';
            document.getElementById('messagesTitle').style.display = 'inline-block';
            while(document.getElementById('messages').lastElementChild) {
                document.getElementById('messages').removeChild(document.getElementById('messages').lastElementChild);
            }
            document.getElementById('messages').style.display = 'block';
            document.getElementById('sendMessages').style.display = 'block';
            document.getElementById('messages').style.marginTop = '0';

            document.getElementById('jobId').setAttribute('name', messageUsers[i].id);
            document.getElementById('otherUserId').setAttribute('name', messageUsers[i].getAttribute('name'));

            let jobId = document.getElementById('jobId').getAttribute('name');
            let otherUserId = document.getElementById('otherUserId').getAttribute('name');

            // set the latest messages to read in the databse
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


            // get the users latest messages
            //working
            $.ajax({
                url: "./handlers/userMessagingDetails.php",
                method: "POST",
                data: {
                    id: otherUserId,
                    email: email
                },
                success: (d) => {
                    // load in the latest messages
                    let data = JSON.parse(d);
                    document.getElementById('messagingName').innerText = `${data[0]}`;
                    document.getElementById('messagingImage').src = `${data[1]}`;

                    // if you sent the message prepend you
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
                            p.style.boxShadow = '4px 4px 6px 6px rgba(0, 0, 0, 0.05)';
                            document.getElementById('messages').appendChild(p);

                        } else {

                            // otherwise prepend their name
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
                            p.style.boxShadow = '4px 4px 6px 6px rgba(0, 0, 0, 0.05)';
                            document.getElementById('messages').appendChild(p);
                        }
                    }
                    // scroll to the latest message
                    document.getElementById('messages').scrollTop = document.getElementById('messages').scrollHeight;
                }
            });
        });
    }

    //on back to message list
    let backToMessageList = document.getElementById('backToMessageList');

    backToMessageList.addEventListener('click', (e) => {
        e.preventDefault();
        clearInterval(interval);

        //remove all the messages
        while(document.getElementById('messages').lastElementChild) {
            document.getElementById('messages').removeChild(document.getElementById('messages').lastElementChild);
        }

        // display the corrent screens
        document.getElementById('messagesList').style.display = 'block';
        document.getElementById('messages').style.display = 'none';
        document.getElementById('sendMessages').style.display = 'none';
        document.getElementById('messagesTitle').style.display = 'none';

    });
}