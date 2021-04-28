const displayEditProfile = () => {

    const closeInfoForm = (e) => {
        e.stopImmediatePropagation();
        document.getElementById('popup-2').style.display = 'none';
    }

    let popup = document.getElementById('popup-2');
    popup.style.display = 'block';

    let tab1 = document.getElementById('tab1');
    let nextButtonTab1 = document.getElementById('nextProfileBtn1');

    // tab2 tab and button(s)
    let tab2 = document.getElementById('tab2');
    let nextButtonTab2 = document.getElementById('nextProfileBtn2');
    let prevButtonTab2 = document.getElementById('prevProfileBtn2');

    // only display tab 1
    tab1.style.display = 'block';
    tab2.style.display = 'none';

    let fname, lname, gender, lang, region, jobType, category;

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
            popup.style.display = 'none';

            // Split the chosen jobs into a number array
            let email = document.getElementById('email').value;
            $.ajax({
                url: "./handlers/update_myprofile.php",
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
                    openPage('userProfile.php');
                }
            });
        }
    });

    prevButtonTab2.addEventListener('click', () => {
        tab2.style.display = 'none';
        tab1.style.display = 'inherit';
    });
}