<?php

// Requires
require('../db_connector.php');
require('../pageTemplate.php');
require('../database_functions.php');

// Initial variables
// Get the page template class
$page = new pageTemplate('Home');
$conn = getConnection();

// Checks if the user is loggedin, if not redirects the user to the signin page
if(!$_SESSION['loggedin']) { header('location: signin.php'); }

// Add CSS
$page->addCSS("<link rel=\"stylesheet\" href=\"./css/styling.css\">");
$page->addCSS("<link rel=\"stylesheet\" href=\"./css/footerStyling.css\">");
$page->addCSS("<link rel=\"stylesheet\" href=\"./css/headerStyling.css\">");

// Add JS
$page->addJavaScript("<script src=\"./js/navBar.js\"></script>");
$page->addJavaScript("<script src=\"./js/sendMessages.js\"></script>");
$page->addJavaScript("<script src=\"./js/openMessage.js\"></script>");
$page->addJavaScript("<script src=\"./js/jobPayment.js\"></script>");

// Main content

$page->addPageBodyItem("
            <input type='text' id='email' value='{$_SESSION['email']}' style='display: none'>
            <div class='pageContainer' style='padding: 0;'>
                <div id='messagesList'>");

// use the try catch block in case the user has no messages, stop
// the page from displaying a white screen
try {

    // Get the users id
    $userId = messagesGetUserId($conn, $_SESSION['email']);

    // get the id of the other users to chat to
    $chatIds = messagesGetChatUsers($conn, $userId);

    // get the other users details
    list($name, $fullName, $online, $userImages) = messagesGetUserDetails($conn, $chatIds);

    $i = 0;
    // for each user to chat to
    foreach ($chatIds as $chatId) { // start of outer for each loop

        // get latest messages
        $messageDetails = messagesGetMessageDetails($conn, $chatId, $userId);

        foreach($messageDetails as $messageDetail) { // start of inner foreach loop

            // get the job title name
            $jobTitle = messagesGetJobTitle($conn, $messageDetail->job_id);

            // check if the user is online, if so display a green
            // dot if not display a red dot
            if ($online[$i] == true) {
                $onlineColour = '#03AC13';
            } else {
                $onlineColour = '#FF0000';
            }

            // Display all the users information
            $page->addPageBodyItem("
                    <div class='clickable message_users' id='{$messageDetail->job_id}' name='$chatId'>
                        <img class='clickable personIcon' src='assets/{$userImages[$i]}'>
                        <div id='online' style='background-color: {$onlineColour};'></div>
                        <h1 class='clickable' id='name'>{$fullName[$i]}<i> - '{$jobTitle->job_title}' position</i></h1><br>
                        <div id='onlineStatusListPage'>
                    </div>");


            // if you sent the last message prepend 'You: ' to the message
            // otherwise prepend the users name to the message
            if($userId == $messageDetail->user_id) { // start of inner if statement
                $page->addPageBodyItem("<p style='display: inline;'>You: {$messageDetail->message}</p>");
            } else {
                $page->addPageBodyItem("<p style='display: inline;'>{$name[$i]}: {$messageDetail->message}</p>");
            } // end of inner if statement



            $page->addPageBodyItem("<p id='messageDetails'><i>{$messageDetail->created_on}</i></p>
                    </div><hr>");

        } // end of inner foreach loop
        $i++;

    } // end of outer foreach loop


$page->addPageBodyItem("
                </div>
                <div id='messagesTitle' style='display: none;'>
                    <a class='clickable back' id='backToMessageList'>< Back to messages</a> 
                    <img class='personIcon' id='messagingImage' src='' alt='User icon'>
                    <div id='userOnline' style='background-color: {$onlineColour};'></div>
                    <h1 id='messagingName'></h1>                           
                    <button id='showPaymentBtn' class='clickable nextLink'>Job Finished</button></div>");



// Gets all the users jobs
$jobsArray = getUsersJobs($conn, $_SESSION['email']);
foreach($jobsArray as $job) { // start of outer foreach loop


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
                                <img alt='PayPal payment button' border='0' src='https://www.paypalobjects.com/en_US/i/scr/pixel.gif' width='1' height='1'>
                            </div>
                        </form>
                    </div>");


} // end of foreach loop

} catch(Exception $e) {
    $page->addPageBodyItem("<h1>No messages</h1>");
    logError($e);
} // end of try catch block

// Displays all the users messages
$page->addPageBodyItem("
                </div>
                <div id='messages' style='display: none;'></div>
                <div id='sendMessages' style='display: none; background-color: white;'>
                    <input id='msg' type='text' placeholder='Type Here...' style='display: inline; width: 70%; margin: 0;;'>
                    <button class='clickable nextLink' id='send' name='' style='margin: 5px 0 5px 0; width: 25%;'>Send!</button>
                    <input id='jobId' style='display: none;'>
                    <input id='otherUserId' style='display: none;'>
                    </div>
                </div>");
// last div is the closing div of the page container

// Display the page
$page->displayPage();

?>