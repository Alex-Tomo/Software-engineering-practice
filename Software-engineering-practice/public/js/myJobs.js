// TODO: use an object for the styling
// TODO: get a selectPostJobs method to do the AJAX part

selectedJobsArray = [];

window.onload = () => {
    let removeButtons = document.getElementsByClassName('removeJob');
    for (let i = 0; i < removeButtons.length; i++) {
        let id = removeButtons[i].id;
        removeButtons[i].addEventListener('click', (event) => {
            deleteJob(id);
            event.stopPropagation();
        });
    }

    let editButtons = document.getElementsByClassName('editJob');
    for (let i = 0; i < editButtons.length; i++) {
        let id = editButtons[i].id;
        editButtons[i].addEventListener('click', (event) => {
            event.stopPropagation();
            showEditJobDetails(id);
        });
    }

    let closePopup = document.getElementById('closePopup');
    closePopup.addEventListener('click', (event) => {
        document.getElementById('popup-3').style.display = 'none';
        event.preventDefault();
    });

}

const showEditJobDetails = (jobId) => {
    document.getElementById('popup-3').style.display = 'block';
    document.getElementById('jobId').value = jobId;

    // Updates the job by updating the database from editJobDetails.php and
    // displays the new results in the form
    $.ajax({
        url: './handlers/editJobDetails.php',
        method: "POST",
        data: {
            jobId: jobId
        },
        success: (data) => {
            let result = $.parseJSON(data);
            document.getElementById('title').value = result[0];
            document.getElementById('desc').innerText = result[1];
            document.getElementById('price').value = result[2];
            document.getElementById('jobImage').src = './assets/job_images/' + result[3];

            let len = result[4].length;

            for(let i = 0; i < len; i++) {
                let jobsList = document.getElementById('searchJobsList').getElementsByTagName('option');
                for(let j = 0; j < jobsList.length; j++) {
                    if (result[4][i] == jobsList[j].getAttribute('name')) {
                        jobsList[j].disabled = true;

                        // this is the same as selectPostJobs.js
                        let p = document.createElement('input');
                        p.className = 'clickable chosenJob';
                        p.value = jobsList[j].value + ' x';
                        p.style.backgroundColor = '#017EFC';
                        p.style.color = '#FFFFFF';
                        p.style.fontSize = 'small';
                        p.style.fontWeight = 'normal';
                        p.style.width = 'fit-content';
                        p.style.padding = '10px';
                        p.style.margin = '2.5px 5px 2.5px 0';
                        p.style.borderRadius = '5px';
                        p.style.textAlign = 'center';
                        p.id = result[4][i];
                        p.setAttribute('name', 'categories[]');
                        document.getElementById('suggestion').appendChild(p);

                        let hiddenP = document.createElement('input');
                        hiddenP.value = result[4][i];
                        hiddenP.style.display = 'none';
                        hiddenP.setAttribute('name', 'categoryIds[]');
                        document.getElementById('suggestion').appendChild(hiddenP);

                        p.addEventListener('click', () => { removeJob(p.id, jobsList[j].value, hiddenP); });
                    }
                }
            }
        }
    });
}

// If the user chooses yes to removing the job, AJAX sends this response
// to removeJob.php and removes the job from the database (turns the
// availability to false)
const deleteJob = (jobId) => {
    let choice = confirm("Are you sure you want to remove this job?");
    if(choice) {
        $.ajax({
            url: './handlers/removeJob.php',
            method: "POST",
            data: {
                jobId: jobId
            },
            success: () => {
                openPage('userJobs.php');
            }
        });
    }
}