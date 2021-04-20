window.onload = () => {

    let email = document.getElementById('email').value;
    $.ajax({
        url: "./handlers/check_user_details.php",
        method: "POST",
        data: {
            email: email
        },
        success: (data) => {
            if(data) {
                // if the user has already entered data before then do not show the
                // popup form
                let popup1 = document.getElementById('popup-1');
                popup1.style.display = 'none';
            } else {
                // If the user has never entered data before then show the
                // popup form

                let popup1 = document.getElementById('popup-1');

                // tab1 tab and button(s)
                let tab1 = document.getElementById('tab1');
                let nextButtonTab1 = document.getElementById('nextBtn1');

                // tab2 tab and button(s)
                let tab2 = document.getElementById('tab2');
                let nextButtonTab2 = document.getElementById('nextBtn2');
                let prevButtonTab2 = document.getElementById('prevBtn2');

                // only display tab 1
                tab2.style.display = 'none';

                // Form info
                let fname, lname, gender, lang, region, jobType, category;

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
                        alert("All fields are requied");
                    }
                });

                nextButtonTab2.addEventListener('click', () => {
                    // Get tab 2 info

                    tab2.style.display = 'none';
                    popup1.style.display = 'none';

                    // Split the chosen jobs into a number array
                    let jobsArray = localStorage.getItem("jobsArray").split(",");
                    let email = document.getElementById('email').value;
                    $.ajax({
                        url: "./handlers/userinfo_handler.php",
                        method: "POST",
                        data: {
                            email: email,
                            firstname: fname,
                            lastname: lname,
                            gender: gender,
                            language: lang,
                            region: region,
                            jobsArray: jobsArray
                        },
                        success: (data) => { console.log("error: " + data); }
                    });
                });

                prevButtonTab2.addEventListener('click', () => {
                    tab2.style.display = 'none';
                    tab1.style.display = 'inherit';
                });
            }
        }
    });

};
