window.onload = () => {

    document.getElementById('postJobBtn').addEventListener('click', () => {
        postJob();
    });

}
const postJob = () => {
    let title = document.getElementById('title').value;
    let desc = document.getElementById('desc').value;
    let price = document.getElementById('price').value;
    let image = document.getElementById('image').files[0];
    let paraCategories = document.getElementById('suggestion').getElementsByTagName('p');
    let categories = [];
    for(let i = 0; i < paraCategories.length; i++) {
        categories[i] = paraCategories[i].getAttribute('id');
    }

    $.ajax({
        url: "./handlers/post_job_handler.php",
        method: "POST",
        data: {
            title: title,
            desc: desc,
            price: price,
            image: image,
            categories: categories
        },
        success: (data) => {
            alert(data);
        }
    });
}
