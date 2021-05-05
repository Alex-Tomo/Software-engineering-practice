window.onload = () => {
    let messageUsers = document.getElementsByClassName('message_users');
    let email = document.getElementById('email').value;

    for(let i = 0; i < messageUsers.length; i++) {

        $.ajax({
           url: "./handlers/getLastestMessages.php",
           method: "POST",
           data: {
               email: email,
               id: messageUsers[i].id
           },
           success: (data) => {
               if(data) {
                   let d = JSON.parse(data);
                   if(d[0] !== null) {
                       let msg = document.getElementById(messageUsers[i].id);
                       let p = msg.getElementsByTagName('p');
                       p[0].innerText = d[i].message;
                       p[1].innerText = d[i].created_on;
                   }
               }
           }
        });

        messageUsers[i].addEventListener('click', () => {
            document.getElementById('messagesList').style.display = 'none';
            document.getElementById('messagesTitle').style.display = 'block';
            while(document.getElementById('messages').lastElementChild) {
                document.getElementById('messages').removeChild(document.getElementById('messages').lastElementChild);
            }
            document.getElementById('messages').style.display = 'block';
            document.getElementById('sendMessages').style.display = 'flex';

            let id = messageUsers[i].id;
            document.getElementById('send').setAttribute('name', id);
            $.ajax({
                url: "./handlers/userMessagingDetails.php",
                method: "POST",
                data: {
                    id: id,
                    email: email
                },
                success: (d) => {
                    let data = JSON.parse(d);
                    // console.log(data);
                    document.getElementById('messagingName').innerText = `${data[0]}`;

                    for(let i = 0; i < data[1].length; i++) {
                        if(data[1][i].userId !== id) {

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
                            document.getElementById('messages').scrollTo(p.getBoundingClientRect().x, p.getBoundingClientRect().y);


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
                            document.getElementById('messages').scrollTo(p.getBoundingClientRect().b, p.getBoundingClientRect().y);

                        }
                    }
                }
            });
        });
    }

    let backToMessageList = document.getElementById('backToMessageList');

    backToMessageList.addEventListener('click', (e) => {
        e.preventDefault();
        document.getElementById('messagesList').style.display = 'block';
        document.getElementById('messages').style.display = 'none';
        document.getElementById('sendMessages').style.display = 'none';
        document.getElementById('messagesTitle').style.display = 'none';
    });
}