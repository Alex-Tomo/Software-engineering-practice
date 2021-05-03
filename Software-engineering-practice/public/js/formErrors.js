const error = document.getElementById('error');

document.getElementById('password').addEventListener('input', (e) => {
    if ('password' < 0){
        error.textContent = 'A filename cannot contain any of the following characters: \/:*?"<>|';
    } else {
        error.textContent = '';
    }
});