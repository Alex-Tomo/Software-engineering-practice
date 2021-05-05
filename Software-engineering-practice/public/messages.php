<?php

    require('../db_connector.php');
    require('../pageTemplate.php');
    require('../database_functions.php');


    // Initial variables
    // Get the page template class
    $page = new pageTemplate('Home');
    $conn = getConnection();

    if(!$_SESSION['loggedin']) { header('location: signin.php'); }

    // Add CSS
    $page->addCSS("<link rel=\"stylesheet\" href=\"./css/styling.css\">");
    $page->addCSS("<link rel=\"stylesheet\" href=\"./css/footerStyling.css\">");
    $page->addCSS("<link rel=\"stylesheet\" href=\"./css/headerStyling.css\">");

    // Add JS
    $page->addJavaScript("<script src=\"./js/navBar.js\"></script>");
    $page->addJavaScript("<script src=\"./js/openMessage.js\"></script>");

    // Main content
    $page->addPageBodyItem("
        <input type='text' id='email' value='{$_SESSION['email']}' style='display: none'>
        <div class='pageContainer' style='padding: 0;'>
            <div id='messagesList' style='overflow-y: scroll; height:500px;'>");

    try {
        $statement = $conn->prepare("
                                SELECT user_id, user_fname, user_lname
                                FROM sep_user_info");
        $statement->execute();
        $i = 1;
        while($row = $statement->fetchObject()) {
            $page->addPageBodyItem("<div class='message_users' id='{$row->user_id}' style='margin: 5px;'>
                    <h1 style='margin-top: 5px;'>{$row->user_fname} {$row->user_lname}</h1>
                    <p>Last Message</p>
                    <p>Time</p>
                    <div style='margin-bottom: 5px;'>Colour</div>
                </div><hr>");
        }
    } catch(Exception $e) { logError($e); }
            $page->addPageBodyItem("</div>
            
            <div id='messagesTitle' style='position: absolute; width: 100%; display: none;'><a id='backToMessageList' style='margin: 5px;'>--- back to chat</a><h1 id='messagingName' style='margin: 5px;'></h1><hr></div>
            <div id='messages' style='overflow-y: scroll; height:500px; display: none; margin-top: 58px;'>
            </div>
            <div id='sendMessages' style='display: none;'>
                <input id='msg' type='text' placeholder='Type Here...' style='padding: 10px; width: 80%;'>
                <button id='send' name='' style='width: 20%;'>Send!</button>
            </div>
        </div>
        ");

    $page->addPageBodyItem("
            <script>
                $(document).ready(() => {
                    // Create a new WebSocket.
                    let socket  = new WebSocket('ws://127.0.0.1:3001');
                    
                    document.getElementById('send').addEventListener('click', () => {
                        if(document.getElementById('msg').value.trim() !== '') {
                        
                            let email = '{$_SESSION['email']}';
                            let msg = document.getElementById('msg').value;       
                            let target_id = document.getElementById('send').getAttribute('name');
                            
                            let data = {
                                email: email, // for the users id
                                target_id: target_id, // for the other users id
                                msg: msg // the actual message
                                
                            }
                            
                            document.getElementById('msg').value = '';
                            
                            socket.send(JSON.stringify(data));                   
                        }
                    }); 
                    
                    socket.onmessage = (e) => {
                        let data = JSON.parse(e.data);
                        
                        if(data.from == 'You') {
                            let p = document.createElement('div');
                            p.innerHTML = '<b>' + data.from + '</b> - ' + data.msg + '<br><i style=\'float: right; font-size: xx-small;  margin-top: 5px;\'>sent - '+ data.datetime +'</i>';
                            p.style.backgroundColor = '#DADADA';
                            p.style.width = '60%';
                            p.style.marginLeft = '10px';
                            p.style.marginTop = '10px';
                            p.style.marginBottom = '10px';
                            p.style.padding = '10px 10px 25px 10px';
                            p.style.borderRadius = '10px';
                            p.style.display = 'block';                            
                            p.style.wordWrap = 'break-word';                            
                            document.getElementById('messages').appendChild(p);
                            document.getElementById('messages').scrollTo(p.getBoundingClientRect().x, p.getBoundingClientRect().y);
                            
                            let message = data.msg;
                            let targetId = data.target_id;
                            let userId = data.user_id;
                            let datetime = data.datetime;
                            
                            $.ajax({
                                url: './handlers/updateMessages.php',
                                method: 'POST',
                                data: {
                                    message: message,
                                    targetId: targetId,
                                    userId: userId,
                                    datetime: datetime
                                },
                                success: (data) => {
                                    if(data.trim().includes('true')) {
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
                            document.getElementById('messages').appendChild(p);
                            document.getElementById('messages').scrollTo(p.getBoundingClientRect().x, p.getBoundingClientRect().y);
                        }
                                  
                    }
                });
                
    
            </script>
            ");

    // Display the page
    $page->displayPage();

?>