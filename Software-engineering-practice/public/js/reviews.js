let nextSlideAnchor = document.getElementsByClassName('next');
let prevSlideAnchor = document.getElementsByClassName('prev');
let dotAnchor1 = document.getElementsByClassName('dot1');
let dotAnchor2 = document.getElementsByClassName('dot2');
let dotAnchor3 = document.getElementsByClassName('dot3');

window.onload = function() {
    let slideIndex = 1;
    showSlides(slideIndex);
    carousel();

    nextSlideAnchor[0].addEventListener("click", function() { plusSlides(1); });
    prevSlideAnchor[0].addEventListener("click", function() { plusSlides(-1); });
    dotAnchor1[0].addEventListener("click", function() { currentSlide(1); });
    dotAnchor2[0].addEventListener("click", function() { currentSlide(2); });
    dotAnchor3[0].addEventListener("click", function() { currentSlide(3); });

    //Shows the next slide
    function plusSlides(n) {
        showSlides(slideIndex += n);
    }

    //Gets the current slide
    function currentSlide(n) {
        showSlides(slideIndex = n);
    }

    //Shows the slides, when user clicks on back or forward or the dots the slides move on
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

    //Slides move on their own after a certain amount of time
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