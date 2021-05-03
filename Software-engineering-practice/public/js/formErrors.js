const error = document.getElementById('error');

document.getElementById('password').addEventListener('input', (e) => {
    if ('password' < 0){
        error.textContent = 'Password must be more characters';
    } else {
        error.textContent = '';
    }
});