jQuery(document).ready(function () {
  var packers_logistic_swiper_testimonials = new Swiper(".testimonial-swiper-slider.mySwiper", {
    slidesPerView: 3,
      spaceBetween: 50,
      speed: 1000,
      autoplay: {
        delay: 3000,
        disableOnPoppinsaction: false,
      },
      navigation: {
        nextEl: ".testimonial-swiper-button-next",
        prevEl: ".testimonial-swiper-button-prev",
      },
      breakpoints: {
        0: {
          slidesPerView: 1,
        },
        767: {
          slidesPerView: 2,
        },
        1023: {
          slidesPerView: 3,
        }
    },
  });
});

jQuery(document).ready(function ($) {
  var packers_logistic_owl = $(".collection-in-box.owl-carousel");
  packers_logistic_owl.owlCarousel({
    loop: true,
    items: 1,
    margin: 20,
    autoplayTimeout: 3000,
    speed: 300,
    nav: true,
    dots: false,
    rtl: false,
    autoplay: true,
  });
});