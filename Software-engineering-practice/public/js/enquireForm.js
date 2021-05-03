const displayEnquireForm = () => {

    const closeEnquireForm = (e) => {
        e.stopImmediatePropagation();
        document.getElementById('popup-1').style.display = 'none';
    }

    let popup = document.getElementById('popup-1');
    popup.style.display = 'block';

    let tab1 = document.getElementById('tab1');
    let submit = document.getElementById('submitBtn');
    let prevButtonTab2 = document.getElementById('prevProfileBtn2');

    submit.addEventListener('click', () => {
            tab1.style.display = 'none';
            popup.style.display = 'none';
    });
}