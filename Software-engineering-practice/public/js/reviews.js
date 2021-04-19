// Added by Alex
let nextSlideAnchor = document.getElementsByClassName('next');
let prevSlideAnchor = document.getElementsByClassName('prev');

window.onload = function() {
    let slideIndex = 1;
    showSlides(slideIndex);
    carousel();

    // Added by alex, removed the home.php onclicks, use these instead (more dynamic).
    nextSlideAnchor[0].addEventListener("click", function() { plusSlides(1); });
    prevSlideAnchor[0].addEventListener("click", function() { plusSlides(-1); });

    function plusSlides(n) {
        showSlides(slideIndex += n);
    }

    function showSlides(n) {
        let i;
        let slides = document.getElementsByClassName("mySlides");
        let dots = document.getElementsByClassName("dot");
        if (n > slides.length) {slideIndex = 1}
        if (n < 1) {slideIndex = slides.length}
        for (i = 0; i < slides.length; i++) {
            slides[i].style.display = "none";
        }
        for (i = 0; i < dots.length; i++) {
            dots[i].className = dots[i].className.replace(" active", "");
        }
        slides[slideIndex-1].style.display = "block";
        dots[slideIndex-1].className += " active";
    }

    function carousel() {
        let i;
        let x = document.getElementsByClassName("mySlides");
        let dots = document.getElementsByClassName("dot");
        for (i = 0; i < x.length; i++) {
            x[i].style.display = "none";
        }
        slideIndex++;
        for (i = 0; i < dots.length; i++) {
            dots[i].className = dots[i].className.replace(" active", "");
        }
        if (slideIndex > x.length) {slideIndex = 1}
        x[slideIndex-1].style.display = "block";
        dots[slideIndex-1].className += " active";

        setTimeout(carousel, 7000);
    }
}