$(document).ready(() => {
    let enquireBtn = document.getElementById('enquireBtn');
    let referBtn = document.getElementById('referBtn');

    enquireBtn.addEventListener('click', () => {
        displayEnquireForm();
    });

    referBtn.addEventListener('click', () => {
        displayReferForm();
    });
});

const displayEnquireForm = () => {

    document.getElementById('popup-1').style.display = 'block';

    document.getElementById('closeEnquireBtn').addEventListener('click', (e) => {
        e.preventDefault();
        document.getElementById('popup-1').style.display = 'none';
    })

    document.getElementById('submitEnquireBtn').addEventListener('click', (e) => {
        e.preventDefault();
        document.getElementById('popup-1').style.display = 'none';
        alert(document.getElementById('desc').value);
    });
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