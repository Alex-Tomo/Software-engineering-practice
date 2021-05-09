$(document).ready(() => {
    let enquireBtn = document.getElementById('enquireBtn');
    let referBtn = document.getElementById('referBtn');


    //Calls displayEnquireForm() function when button clicked
    enquireBtn.addEventListener('click', () => {
        displayEnquireForm();
    });

    //Calls displayReferForm() function when button clicked
    referBtn.addEventListener('click', () => {
        displayReferForm();
    });

    //Closes enquire form
    document.getElementById('closeEnquireBtn').addEventListener('click', (e) => {
        e.preventDefault();
        document.getElementById('popup-1').style.display = 'none';
    })

    //Closes enquire form
    document.getElementById('overlay-1').addEventListener('click', (e) => {
        e.preventDefault();
        document.getElementById('popup-1').style.display = 'none';
    })

    //Submit enquire form
    document.getElementById('submitEnquireBtn').addEventListener('click', (e) => {
        e.preventDefault();
        document.getElementById('popup-1').style.display = 'none';
        let jobId = document.getElementById('jobId').getAttribute('name');
        let desc = document.getElementById('desc').value;
        let email = document.getElementById('userEmail').getAttribute('name');

        // On form submit the data is sent to sendEnquiryMessage.php
        // which is updated in the database and sends a message to the user
        $.ajax({
            url: "./handlers/sendEnquiryMessage.php",
            method: "POST",
            data: {
                jobId: jobId,
                desc: desc,
                email: email
            },
            success: (data) => {
                if(data) {
                    socket.send(data);
                }
            }
        });

        document.getElementById('desc').value = '';
    });
});

//Displays enquire form
const displayEnquireForm = () => {
    document.getElementById('popup-1').style.display = 'block';
}

//Refer form
const displayReferForm = () => {

    document.getElementById('popup-2').style.display = 'block';

    //Closes refer form
    document.getElementById('overlay-2').addEventListener('click', (e) => {
        e.preventDefault();
        document.getElementById('popup-2').style.display = 'none';
    })

    //Closes refer form
    document.getElementById('closeReferBtn').addEventListener('click', (e) => {
        e.preventDefault();
        document.getElementById('popup-2').style.display = 'none';
    })

    //Submits refer form, alert is displayed
    document.getElementById('submitReferBtn').addEventListener('click', (e) => {
        e.preventDefault();
        document.getElementById('popup-2').style.display = 'none';
        alert(document.getElementById('email').value);
    });
}