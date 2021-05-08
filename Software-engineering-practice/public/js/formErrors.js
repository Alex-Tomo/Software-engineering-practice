const error = document.getElementById('error');

document.getElementById('password').addEventListener('keypress', (e) => {
    if (document.getElementById('password').length < 0){
        error.textContent = 'Password must be more characters';
    } else {
        error.textContent = '';
    }
});