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
    document.getElementById('submitPasswordForm').addEventListener('click', () => { changePassword(); });

}

const closeChangePassword = () => {
    document.getElementById('passwordForm').style.display = 'hidden';
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
                if (data === 'true') {
                    document.getElementById('passwordForm').style.display = 'none';
                    alert('Password Changed');
                } else {
                    alert('Could not change password');
                }
            }
        });
    }
}