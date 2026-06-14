//owl carousel slider
jQuery(document).ready(function ($) {
	$(".owl-carousel").owlCarousel({
		loop: true, // Enable infinite loop
		margin: 20, // Space between items
		nav: true, // Show navigation arrows
		dots: false, // Disable dots
		autoplay: true, // Enable autoplay
		autoplayTimeout: 3000, // 3 seconds delay
		autoplayHoverPause: true, // Pause on hover
		slideBy: 1, // Slide one box at a time
		responsive: {
			0: { items: 1 }, // 1 box on small screens
			600: { items: 2 }, // 2 boxes on medium screens
			1000: { items: 3 } // 4 boxes on large screens
		}
	});
});