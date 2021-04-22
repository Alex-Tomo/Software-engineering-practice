// Added by Alex

const getImage = (job_id) => {

    let path = window.location.href.split('/');
    let newPagePath = '';
    for(let i = 0; i < path.length; i++) {
        newPagePath += path[i]+'/';
        if(path[i] === 'public') {
            break;
        }
    }

    document.getElementById('image_' + job_id).src = 'assets/job_images/image_' + job_id + '.jpg';
}

const getRecommendedImage = (job_id) => {

    let path = window.location.href.split('/');
    let newPagePath = '';
    for(let i = 0; i < path.length; i++) {
        newPagePath += path[i]+'/';
        if(path[i] === 'public') {
            break;
        }
    }

    document.getElementById('recommendedImage_' + job_id).src = 'assets/job_images/image_' + job_id + '.jpg';
}

const getRecentlyViewedImage = (job_id) => {

    let path = window.location.href.split('/');
    let newPagePath = '';
    for(let i = 0; i < path.length; i++) {
        newPagePath += path[i]+'/';
        if(path[i] === 'public') {
            break;
        }
    }

    document.getElementById('recentlyViewedImage_' + job_id).src = 'assets/job_images/image_' + job_id + '.jpg';
}

