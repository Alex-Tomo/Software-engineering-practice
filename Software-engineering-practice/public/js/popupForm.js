window.onload = () => {

    // Get the popup window and tabs
    let popup1 = document.getElementById('popup-1');

    // tab1 tab and button(s)
    let tab1 = document.getElementById('tab1');
    let nextButtonTab1 = document.getElementById('nextBtn1');

    // tab2 tab and button(s)
    let tab2 = document.getElementById('tab2');
    let nextButtonTab2 = document.getElementById('nextBtn2');
    let prevButtonTab2 = document.getElementById('prevBtn2');

    // tab3 tab and button(s)
    let tab3 = document.getElementById('tab3');
    let nextButtonTab3 = document.getElementById('nextBtn3');
    let prevButtonTab3 = document.getElementById('prevBtn3');

    // tab4 tab and button(s)
    let tab4 = document.getElementById('tab4');
    let nextButtonTab4 = document.getElementById('nextBtn4');
    let prevButtonTab4 = document.getElementById('prevBtn4');

    // only display tab 1
    tab2.style.display = 'none';
    tab3.style.display = 'none';
    tab4.style.display = 'none';

    // Form info
    let fname, lname, gender, lang, region, jobType, category, hourlyRate;

    nextButtonTab1.addEventListener('click', () => {
        // Get tab 1 info
        fname = document.getElementById('fname').value;
        lname = document.getElementById('lname').value;
        gender = document.getElementById('gender').value;
        lang = document.getElementById('lang').value;
        region = document.getElementById('reg').value;

        if((fname !== '') && (lname !== '') && (gender !== null) &&
            (lang !== null) && (region !== null)) {
            tab1.style.display = 'none';
            tab2.style.display = 'inherit';
        } else {
            console.log("Missing info");
        }
    });

    nextButtonTab2.addEventListener('click', () => {
        // Get tab 2 info

        tab2.style.display = 'none';
        tab3.style.display = 'inherit';
    });

    nextButtonTab3.addEventListener('click', () => {
        // Get tab 3 info

        tab3.style.display = 'none';
        tab4.style.display = 'inherit';
    });

    nextButtonTab4.addEventListener('click', () => {
        // Get tab 4 info

        tab4.style.display = 'none';
        popup1.style.display = 'none';
    });

    prevButtonTab2.addEventListener('click', () => {
        tab2.style.display = 'none';
        tab1.style.display = 'inherit';
    });

    prevButtonTab3.addEventListener('click', () => {
        tab3.style.display = 'none';
        tab2.style.display = 'inherit';
    });

    prevButtonTab4.addEventListener('click', () => {
        tab4.style.display = 'none';
        tab3.style.display = 'inherit';
    });


};
