let selectedJobsArray = [];

// Added by Alex
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

// Added by Alex
// selectJob(jobId) takes the elements id value as an input, when the
// list item is tapped then the items colour will be flipped and the item
// will be added to/removed from the array. The array is then added to
// localStorage for later use in popupForm.js
const selectJob = (jobId) => {
    if (document.getElementById(jobId).style.backgroundColor === "rgb(1, 126, 252)") {
        document.getElementById(jobId).style.backgroundColor = "#FFFFFF";
        document.getElementById(jobId).style.color = "#000000";
        selectedJobsArray = selectedJobsArray.filter(job => job !== jobId);
    } else {
        document.getElementById(jobId).style.backgroundColor = "#017EFC";
        document.getElementById(jobId).style.color = "#FFFFFF";
        selectedJobsArray.push(jobId);
    }
    localStorage.setItem("jobsArray", selectedJobsArray);
}
