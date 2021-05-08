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
$page->addJavaScript("<script src=\"./js/jobPayment.js\"></script>");


// Main content
$page->addPageBodyItem("
        <input type='text' id='email' value='{$_SESSION['email']}' style='display: none'>
        <div class='pageContainer' style='padding: 0;'>
            <div id='messagesList' style='overflow-y: scroll; height:500px;'>");

try {
    // Find the other users name using the job id
    // find your name using the user id
    $statement = $conn->prepare("
            SELECT sep_users.user_id
            FROM sep_users
            WHERE sep_users.user_email = '{$_SESSION['email']}'
        ");
    $statement->execute();
    $result = $statement->fetchObject();
    $userId = $result->user_id;

    $statement = $conn->prepare("
            SELECT sep_messages.user_id, sep_messages.other_user_id
            FROM sep_messages
            WHERE sep_messages.user_id = {$userId} OR sep_messages.other_user_id = {$userId}
            ORDER BY sep_messages.created_on DESC
        ");
    $statement->execute();
    $chatIds = array();
    while($result = $statement->fetchObject()) {
        if(($result->user_id != $userId) && (!in_array($result->user_id, $chatIds))) {
            array_push($chatIds, $result->user_id);
        } else if(($result->other_user_id != $userId) && (!in_array($result->other_user_id, $chatIds))) {
            array_push($chatIds, $result->other_user_id);
        }
    }

    $name = array();
    $fullName = array();
    $online = array();
    $userImages = array();
    foreach($chatIds as $chatId) {
        $statement = $conn->prepare("
                SELECT sep_user_info.user_fname, sep_user_info.user_lname, sep_users.user_online, sep_user_info.user_image
                FROM sep_user_info JOIN sep_users ON sep_user_info.user_id = sep_users.user_id
                WHERE (sep_user_info.user_id = {$chatId})
            ");
        $statement->execute();
        $result = $statement->fetchObject();
        array_push($name, "{$result->user_fname}");
        array_push($fullName, "{$result->user_fname} {$result->user_lname}");
        array_push($online, $result->user_online);
        if($result->user_image != null) {
            array_push($userImages, 'user_images/' . $result->user_image);
        } else {
            array_push($userImages, 'person.svg');
        }
    }

    $i = 0;
    // userid is who sent the message
    foreach ($chatIds as $chatId) {
        $statement = $conn->prepare("
                SELECT sep_messages.message, sep_messages.created_on, sep_messages.job_id, sep_messages.user_id
                FROM sep_messages 
                WHERE (sep_messages.user_id = {$chatId} AND sep_messages.other_user_id = {$userId})
                    OR (sep_messages.other_user_id = {$chatId} AND sep_messages.user_id = {$userId})
                GROUP BY sep_messages.message_id
                ORDER BY sep_messages.created_on DESC
            ");
        $statement->execute();
        while ($row = $statement->fetchObject()) {
            $statement = $conn->prepare("
                    SELECT job_title FROM sep_available_jobs WHERE job_id = {$row->job_id}
                ");
            $statement->execute();
            $r = $statement->fetchObject();

            if ($online[$i] == true) {
                $onlineColour = '#03AC13';
            } else {
                $onlineColour = '#FF0000';
            }

            $page->addPageBodyItem("<div class='message_users' id='{$row->job_id}' name='$chatId' style='margin: 5px;'>
                    <img class='personIcon' src='assets/{$userImages[$i]}' style='border-radius: 25px; float: left;'>
                    <div style='position: absolute; background-color: {$onlineColour}; height: 10px; width: 10px; margin-top: 30px; margin-left: 30px; border-radius: 25px;'></div>
                    <h1 style='padding-top: 8px; margin: 0;'>{$fullName[$i]}<i> - '{$r->job_title}' position</i></h1><br>
                    <div id='onlineStatusListPage' style='margin-bottom: 5px;'>");
            $page->addPageBodyItem("</div>");

            if($userId == $row->user_id) {
                $page->addPageBodyItem("<p style='display: inline;'>You: {$row->message}</p>");
            } else {
                $page->addPageBodyItem("<p style='display: inline;'>{$name[$i]}: {$row->message}</p>");
            }
            $page->addPageBodyItem("<p style='display: inline; float: right; margin: 0; font-size: smaller;'><i>{$row->created_on}</i></p>
                </div><hr>");
        }
        $i++;
    }
} catch(Exception $e) { logError($e); }
$page->addPageBodyItem("</div>
            <div id='messagesTitle' style='background-color: #EEEEEE; position: absolute; width: 100%; display: none; padding-bottom: 10px'>
                <a id='backToMessageList' style='margin: 5px; display: block'>-- back to chat</a> 
                <img class='personIcon' id='messagingImage' src='' style='border-radius: 25px; float: left; margin-left: 10px;'>
                <div id='userOnline' style='position: absolute; background-color: {$onlineColour}; height: 10px; width: 10px; margin-top: 30px; margin-left: 38px; border-radius: 25px;'></div>
                <h1 id='messagingName' style='margin: 5px; display: inline-block; width: fit-content; margin-top: 8px;'></h1>                           
                <button id='showPaymentBtn' class='clickable nextLink'>Job Finished</button>              
");
//try{
//     $statement = $conn->prepare("
//            SELECT sep_available_jobs.job_price
//            FROM sep_available_jobs
//            JOIN sep_users ON sep_users.user_id = sep_available_jobs.job_id
//            WHERE sep_available_jobs.job_id = sep_users.user_id;
//        ");
//    $statement->execute();
//    $result = $statement->fetchObject();
//    $jobId = $result->jobId;
//} catch(Exception $e) { logError($e); }

// Gets all the users jobs
$jobsArray = getUsersJobs($conn, $_SESSION['email']);
foreach($jobsArray as $job)
{
    $page->addPageBodyItem("          
                <div class='popup' id='popup-5' style='display: none'>
                    <div id='overlay' class='clickable overlay'></div>    
                        <form class='popupForm' id='paymentForm' action='https://www.paypal.com/cgi-bin/webscr' method='post' target='_top'>          
                            <div id='tab1'>
                                <button class='clickable backLink' id='closePaymentBtn'>Cancel</button>
                                <h1>Payment Form</h1>
                                <label for='hours'>Enter how many hours</label><br>
                                <input type='number' id='hours' name='hours' placeholder='Hours'><br>
                                <label id='priceText' for='jobPrice'>Price per hour: </label><br><br>
                                <input type='hidden' id='jobPrice' name='{$job['jobPrice']}'><p id='jobPrice'>£{$job['jobPrice']}/h</p></input>
                                <button class='clickable nextLink' type='button' id='calcTotal'>Calculate Total</button>                           
                                <span id='total'></span>
                                <input type='hidden' name='cmd' value='_xclick'>
                                <input type='hidden' name='business' value='sb-qes1476153889@personal.example.com'>
                                <input type='hidden' name='lc' value='US'>
                                <input type='hidden' name='item_name' value='Job'>
                                <input type='hidden' name='button_subtype' value='services'>
                                <input type='hidden' name='no_note' value='0'>
                                <input type='hidden' name='currency_code' value='GBP'>
                                
                                <input type='hidden' id='amount' name='amount' value='0'>
                                <input type='image' id='payPalBtn' src='https://www.paypalobjects.com/en_US/i/btn/btn_paynowCC_LG.gif' border='0' name='submit' alt='PayPal - The safer, easier way to pay online!'>
                                <img alt='' border='0' src='https://www.paypalobjects.com/en_US/i/scr/pixel.gif' width='1' height='1'>
                            </div>
                        </div>
                    </form>
                </div>
");
} // end of foreach loop
$page->addPageBodyItem("
<div id='messages' style='overflow-y: scroll; height:500px; display: none;'>
            </div>
            <div id='sendMessages' style='display: none;'>
                <input id='msg' type='text' placeholder='Type Here...' style='padding: 10px; width: 80%;'>
                <button id='send' name='' style='width: 20%;'>Send!</button>
                <input id='jobId' style='display: none;'>
                <input id='otherUserId' style='display: none;'>
            </div>
        </div>
        ");

$page->addPageBodyItem("
            <script>
                $(document).ready(() => {
                    window.onresize = () => {
                        document.getElementById('messages').style.marginTop = document.getElementById('messagesTitle').offsetHeight + 'px';
                    }
                    
                    // Create a new WebSocket.
                    let socket  = new WebSocket('ws://127.0.0.1:3001');
                    
                    document.getElementById('send').addEventListener('click', () => {
                        if(document.getElementById('msg').value.trim() !== '') {
                        
                            let email = '{$_SESSION['email']}';
                            let msg = document.getElementById('msg').value;       
                            let jobId = document.getElementById('jobId').getAttribute('name');
                            let otherUserId = document.getElementById('otherUserId').getAttribute('name');
                            
                            console.log(jobId);
                            console.log(otherUserId);
                            
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
                        }
                        document.getElementById('messages').scrollTop = document.getElementById('messages').scrollHeight;        
                    }
                });
                
    
            </script>
            ");

// Display the page
$page->displayPage();

?>