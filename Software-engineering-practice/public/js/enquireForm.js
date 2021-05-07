$(document).ready(() => {
    let enquireBtn = document.getElementById('enquireBtn');
    let referBtn = document.getElementById('referBtn');

    enquireBtn.addEventListener('click', () => {
        displayEnquireForm();
    });

    referBtn.addEventListener('click', () => {
        displayReferForm();
    });

    document.getElementById('closeEnquireBtn').addEventListener('click', (e) => {
        e.preventDefault();
        document.getElementById('popup-1').style.display = 'none';
    })

    document.getElementById('submitEnquireBtn').addEventListener('click', (e) => {
        e.preventDefault();
        document.getElementById('popup-1').style.display = 'none';
        let jobId = document.getElementById('jobId').getAttribute('name');
        let desc = document.getElementById('desc').value;
        let email = document.getElementById('userEmail').getAttribute('name');

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

const displayEnquireForm = () => {
    document.getElementById('popup-1').style.display = 'block';
}

const displayReferForm = () => {

    document.getElementById('popup-2').style.display = 'block';

    document.getElementById('closeReferBtn').addEventListener('click', (e) => {
        e.preventDefault();
        document.getElementById('popup-2').style.display = 'none';
    })

    document.getElementById('submitReferBtn').addEventListener('click', (e) => {
        e.preventDefault();
        document.getElementById('popup-2').style.display = 'none';
        alert(document.getElementById('email').value);
    });
}