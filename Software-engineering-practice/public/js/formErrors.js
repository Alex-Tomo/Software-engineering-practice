window.onload = () => {
    let password = document.getElementById('password');
    let password2 = document.getElementById('password2');
    let passwordLength = document.getElementById('passwordLength');
    let passwordMatch = document.getElementById('passwordMatch');

    //Checks to see if password is smaller than 8 characters
    password.addEventListener('keydown', (e) => {
        if (password.value.length <= 7) {
            passwordLength.style.display = 'block';
            passwordLength.innerHTML = 'Password must be 8 or more characters.';
        } else {
            passwordLength.style.display = 'none';
        }
    });

    //Checks to see if the passwords match
    password2.addEventListener('keyup', (e) => {
        if (password.value != password2.value) {
            passwordMatch.style.display = 'block';
            passwordMatch.innerHTML = 'Passwords do not match.';
        } else
            passwordMatch.style.display = 'none';
    });
}