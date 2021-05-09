let selectedJobsArray = [];

// filterJobsList() checks if the user input is in the li list
// if not, then the list removes all these results.
const filterJobsList = () => {
    let input = document.getElementById('jobsListInput');
    let filter = input.value.toUpperCase();
    let ul = document.getElementById('searchJobsList');
    let li = ul.getElementsByTagName('li');

    for(let i = 0; i < li.length; i++) {
        let txtValue = li[i].innerText;
        if(txtValue.toUpperCase().indexOf(filter) > -1) {
            li[i].style.display = "";
        } else {
            li[i].style.display = "none";
        }
    }
}

// Set some styling and disable the suggestion from the datalist
const selectJob = () => {
    let jobsList = document.getElementById('searchJobsList').getElementsByTagName('option');

    let job = document.getElementById('jobsListInput').value;
    document.getElementById('jobsListInput').value = '';

    // Show the user what they have selected
    let p = document.createElement('input');
    p.className = 'clickable chosenJob';
    p.value = job + ' x';
    p.style.backgroundColor = '#017EFC';
    p.style.color = '#FFFFFF';
    p.style.fontSize = 'small';
    p.style.fontWeight = 'normal';
    p.style.width = 'fit-content';
    p.style.padding = '10px';
    p.style.margin = '2.5px 5px 2.5px 0';
    p.style.borderRadius = '5px';
    p.style.textAlign = 'center';
    p.id = document.getElementById(job).getAttribute('name');
    p.setAttribute('name', 'categories[]');

    // Hidden value so we can retrieve the id of the job
    let hiddenP = document.createElement('input');
    hiddenP.value = p.id;
    hiddenP.style.display = 'none';
    hiddenP.setAttribute('name', 'categoryIds[]');

    p.addEventListener('click', () => { removeJob(p.id, job, hiddenP); });
    document.getElementById('suggestion').append(p);
    document.getElementById('suggestion').append(hiddenP);


    for(let i = 0; i < jobsList.length; i++) {
        if(jobsList[i].value === job) {
            jobsList[i].disabled = true;
            if(!selectedJobsArray.includes(jobsList[i].getAttribute('name'))) {
                selectedJobsArray.push(jobsList[i].getAttribute('name'));
            }
        }
    }

    localStorage.setItem("jobsArray", selectedJobsArray);
}

// Remove the option from the suggestion div and enable it in the datalist

const getSelectedJob = () => {
    let jobsList = document.getElementById('searchJobsList').getElementsByTagName('option');
    for(let i = 0; i < jobsList.length; i++) {
        if (!selectedJobsArray.includes(jobsList[i].getAttribute('name')) && (jobsList[i].disabled)) {
            selectedJobsArray.push(jobsList[i].getAttribute('name'));
        }
    }

    localStorage.setItem("jobsArray", selectedJobsArray);
}

const removeJobProfile = (id, job, hiddenP) => {
    document.getElementById(id).remove();
    // hiddenP.remove();
    let jobsList = document.getElementById('searchJobsList').getElementsByTagName('option');
    for(let i = 0; i < jobsList.length; i++) {
        if(jobsList[i].value === job) {
            jobsList[i].disabled = false;
        }
    }

    selectedJobsArray = selectedJobsArray.filter(job => job !== id);
    localStorage.setItem("jobsArray", selectedJobsArray);
}

const removeJob = (id, job, hiddenP) => {
    document.getElementById(id).remove();
    hiddenP.remove();
    let jobsList = document.getElementById('searchJobsList').getElementsByTagName('option');
    for(let i = 0; i < jobsList.length; i++) {
        if(jobsList[i].value === job) {
            jobsList[i].disabled = false;
        }
    }

    selectedJobsArray = selectedJobsArray.filter(job => job !== id);
    localStorage.setItem("jobsArray", selectedJobsArray);
}
