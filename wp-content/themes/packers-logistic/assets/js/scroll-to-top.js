document.addEventListener('DOMContentLoaded', function () {
    const packers_logistic_button = document.querySelector('.scroll-top-button');
    const packers_logistic_link = document.querySelector('.scroll-top-button a');

    // Show/Hide button on scroll
    window.addEventListener('scroll', function () {
        if (document.documentElement.scrollTop > 100) {
            packers_logistic_button.style.display = "block";
        } else {
            packers_logistic_button.style.display = "none";
        }
    });

    // Scroll to top on click
    if (packers_logistic_link) {
        packers_logistic_link.addEventListener('click', function (packers_logistic_event) {
            packers_logistic_event.preventDefault();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }

});