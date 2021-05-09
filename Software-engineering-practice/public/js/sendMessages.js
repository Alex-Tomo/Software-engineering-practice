// Create a new WebSocket.
try {
    let socket = new WebSocket('ws://127.0.0.1:3001');

    $(document).ready(() => {
        window.onresize = () => {
            // document.getElementById('messages').style.marginTop = document.getElementById('messagesTitle').offsetHeight + 'px';
        }

        document.getElementById('send').addEventListener('click', () => {

            if (document.getElementById('msg').value.trim() !== '') {

                let email = document.getElementById('usersEmailAddress').getAttribute('name');
                let msg = document.getElementById('msg').value;
                let jobId = document.getElementById('jobId').getAttribute('name');
                let otherUserId = document.getElementById('otherUserId').getAttribute('name');

                let data = {
                    email: email, // for the users id
                    msg: msg, // the actual message
                    jobId: jobId,
                    otherUserId: otherUserId
                }

                document.getElementById('msg').value = '';

                console.log(data);

                socket.send(JSON.stringify(data));
            }
        });

        socket.onmessage = (e) => {
            let data = JSON.parse(e.data);

            if (data.from == 'You') {
                let p = document.createElement('div');
                p.innerHTML = '<b>' + data.from + '</b> - ' + data.msg + '<br><i style=\'float: right; font-size: xx-small;  margin-top: 5px;\'>sent - ' + data.datetime + '</i>';
                p.style.backgroundColor = '#DADADA';
                p.style.width = '60%';
                p.style.marginLeft = '10px';
                p.style.marginTop = '10px';
                p.style.marginBottom = '10px';
                p.style.padding = '10px 10px 25px 10px';
                p.style.borderRadius = '10px';
                p.style.display = 'block';
                p.style.wordWrap = 'break-word';
                p.style.boxShadow = '4px 4px 6px 6px rgba(0, 0, 0, 0.05)';
                document.getElementById('messages').appendChild(p);

                let message = data.msg;
                let otherUserId = data.otherUserId;
                let userId = data.userId;
                let jobId = data.jobId;
                let datetime = data.datetime;

                $.ajax({
                    url: './handlers/updateMessages.php',
                    method: 'POST',
                    data: {
                        message: message,
                        otherUserId: otherUserId,
                        userId: userId,
                        datetime: datetime,
                        jobId: jobId
                    },
                    success: (data) => {
//                                    console.log(data);
                        if (data.trim().includes('true')) {
                            console.log('database updated!');
                        }
                    }
                });

            } else {
                let p = document.createElement('div');
                p.innerHTML = '<b>' + data.from + '</b> - ' + data.msg + '<br><i style=\'float:right; font-size: xx-small; margin-top: 5px;\'>recieved - ' + data.datetime + '</i>';
                p.style.backgroundColor = '#4BA4FE';
                p.style.width = '60%';
                p.style.marginLeft = 'auto';
                p.style.marginTop = '15px';
                p.style.marginRight = '10px';
                p.style.marginBottom = '10px';
                p.style.padding = '10px 10px 25px 10px';
                p.style.borderRadius = '10px';
                p.style.display = 'block';
                p.style.wordWrap = 'break-word';
                p.style.boxShadow = '4px 4px 6px 6px rgba(0, 0, 0, 0.05)';
                document.getElementById('messages').appendChild(p);
            }
            document.getElementById('messages').scrollTop = document.getElementById('messages').scrollHeight;
        }
    });
}catch (e) {
    console.log('e');
    }