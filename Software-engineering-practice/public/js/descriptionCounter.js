//Counts the number of characters on job description form
const checkWordCount = () => {

    let description = document.getElementById('description');
    let remaining = document.getElementById('remaining');

    description.addEventListener('keyup', (e) => {
        if (description.value.length > 200) {
            return false;
        }
        remaining.style.display = 'block';
        remaining.innerHTML = "Remaining characters : " + (200 - description.value.length);
    });
}

