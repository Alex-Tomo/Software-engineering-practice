<?php

    require('../db_connector.php');
    require('../pageTemplate.php');
    require('../database_functions.php');


// Initial variables
// Get the page template class
$page = new pageTemplate('Home');

// Add CSS
$page->addCSS("<link rel=\"stylesheet\" href=\"./css/styling.css\">");
$page->addCSS("<link rel=\"stylesheet\" href=\"./css/footerStyling.css\">");
$page->addCSS("<link rel=\"stylesheet\" href=\"./css/headerStyling.css\">");

// Add JS
$page->addJavaScript("<script src=\"./js/navBar.js\"></script>");

// Main content
$page->addPageBodyItem("
    <div class='pageContainer'>
        <div id='messages' style='overflow-y: scroll; height:500px;'></div>
        <div style='display: flex;'>
            <input id='msg' type='text' placeholder='Type Here...' style='padding: 10px; width: 80%;'>
            <button id='send' style='width: 20%;'>Send!</button>
        </div>
    </div>
    ");

$page->addPageBodyItem("
        <script>
            // Create a new WebSocket.
            let socket  = new WebSocket('ws://127.0.0.1:3001');
                    
            document.getElementById('send').addEventListener('click', () => {
                
                let msg = document.getElementById('msg').value;            
                socket.send( msg ); 
                
                let p = document.createElement('p');
                p.innerText = 'you: ' + msg;
                p.style.backgroundColor = '#DADADA';
                p.style.width = '35%';
                p.style.marginLeft = '10px';
                p.style.padding = '10px';
                p.style.borderRadius = '10px';
                document.getElementById('messages').appendChild(p);
                
            }); 
            
            socket.onmessage = (e) => { 
                
                let p = document.createElement('p');
                p.innerText = e.data;
                p.style.backgroundColor = '#017EFC';
                p.style.width = '35%';
                p.style.marginLeft = '10px';
                p.style.padding = '10px';
                p.style.borderRadius = '10px';
                p.style.float = 'right';
                document.getElementById('messages').appendChild(p);
                
            }

        </script>
        ");

// Display the page
$page->displayPage();

?>