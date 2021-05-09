window.onload = () => {

    // Using the users email address, check the database using AJAX
    // if the user has details in the database, do not display the popup form
    // otherwise display the popup form

    let email = document.getElementById('email').value;

    $.ajax({
        url: "./handlers/checkUserDetails.php",
        method: "POST",
        data: {
            email: email
        },
        success: (data) => {
            if(!data.trim().includes('true')) {
                console.log(data);
                // If the user has never entered data before then show the
                // popup form

                let popup1 = document.getElementById('popup-1');
                popup1.style.display = 'block';

                // tab1 tab and button(s)
                let tab1 = document.getElementById('tab1');
                let nextButtonTab1 = document.getElementById('nextBtn1');

                // tab2 tab and button(s)
                let tab2 = document.getElementById('tab2');
                let nextButtonTab2 = document.getElementById('nextBtn2');
                let prevButtonTab2 = document.getElementById('prevBtn2');

                // only display tab 1
                tab1.style.display = 'block';
                tab2.style.display = 'none';

                // Form info
                let fname, lname, gender, lang, region;

                nextButtonTab1.addEventListener('click', () => {
                //     // Get tab 1 info

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
                    let jobsArray = localStorage.getItem("jobsArray").split(",");
                    if(jobsArray.length < 3) {
                        alert('Must choose at least 3!');
                    } else {

                        tab2.style.display = 'none';
                        popup1.style.display = 'none';

                        // Split the chosen jobs into a number array
                        let email = document.getElementById('email').value;

                        // Insert the users data into the database using the userInfoHandler.php file
                        $.ajax({
                            url: "./handlers/userInfoHandler.php",
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
                            success: (data) => {
                                openPage('loggedinHome.php');
                            }
                        });
                    }
                });

                //Hide tab2 and show tab1
                prevButtonTab2.addEventListener('click', () => {
                    tab2.style.display = 'none';
                    tab1.style.display = 'inherit';
                });
            } else {
                document.getElementById('searchForm').style.display = 'block';
            }
        }
    });

};
