// TODO: do better error handling

window.onload = () => {

    // Stops the window from closing on enter
    $(window).keydown(function(event){
        if(event.keyCode == 13) {
            event.preventDefault();
            return false;
        }
    });

    document.getElementById('showPasswordForm').addEventListener('click', () => { displayChangePassword(); });
    document.getElementById('closePasswordWindow').addEventListener('click', () => { closeChangePassword(); });
    document.getElementById('submitPasswordForm').addEventListener('click', () => { changePassword(); })
    document.getElementById('overlay-2').addEventListener('click', () => { closeChangePassword(); });
}

const closeChangePassword = () => {
    document.getElementById('passwordForm').style.display = 'none';
}

const displayChangePassword = () => {
    document.getElementById('passwordForm').style.display = 'block';
}

const changePassword = () => {
    let oldPassword = document.getElementById('oldPassword').value;
    let newPassword = document.getElementById('newPassword').value;
    let repeatNewPassword = document.getElementById('repeatNewPassword').value;
    let email = document.getElementById('email').value;

    if((oldPassword.trim() === '' || oldPassword.trim() === null) ||
        (newPassword.trim() === '' || newPassword.trim() === null || newPassword.length < 8) ||
        (repeatNewPassword.trim() === '' || repeatNewPassword.trim() === null || repeatNewPassword.length < 8) ||
        (email.trim() === '' || email.trim() === null)) {

        alert('Fields cannot be empty');
    } else {

        // Sends the data to changeUserPassword.php and if the response is 'true'
        // remove the form and alert the user of the password change
        // Otherwise keep the form open and alert the user that the password
        // could not be changed
        $.ajax({
            url: "./handlers/changeUserPassword.php",
            method: "POST",
            data: {
                email: email,
                oldPassword: oldPassword,
                newPassword: newPassword,
                repeatNewPassword: repeatNewPassword
            },
            success: (data) => {
                if (data.toString().includes('true')) {
                    document.getElementById('passwordForm').style.display = 'none';
                    alert('Password Changed');
                } else {
                    alert('Could not change password');
                }
            }
        });
    }
}