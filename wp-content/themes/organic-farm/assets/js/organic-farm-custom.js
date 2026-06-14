function organic_farm_gb_Menu_open() {
	jQuery(".side_gb_nav").addClass('show');
}
function organic_farm_gb_Menu_close() {
	jQuery(".side_gb_nav").removeClass('show');
}

jQuery(function($){
	$('.gb_toggle').click(function () {
        organic_farm_Keyboard_loop($('.side_gb_nav'));
    });
});

jQuery(window).scroll(function(){
	if (jQuery(this).scrollTop() > 120) {
		jQuery('.menu_header').addClass('fixed');
	} else {
  		jQuery('.menu_header').removeClass('fixed');
	}
});


jQuery(window).scroll(function(){
	if (jQuery(this).scrollTop() > 50) {
		jQuery('.scrollup').addClass('is-active');
	} else {
  		jQuery('.scrollup').removeClass('is-active');
	}
});

jQuery( document ).ready(function() {
	jQuery('#organic-farm-scroll-to-top').click(function (argument) {
		jQuery("html, body").animate({
		       scrollTop: 0
		   }, 600);
	})
})

/* Custom Cursor
 **-----------------------------------------------------*/
// Add this in custom-cursor.js
jQuery(document).ready(function($) {
  var cursor = $(".custom-cursor");
  var follower = $(".custom-cursor-follower");
  var offsetX = 15; // Set your desired horizontal offset
  var offsetY = 15; // Set your desired vertical offset

  $(document).mousemove(function(e) {
    cursor.css({
      top: e.clientY - offsetY + "px",
      left: e.clientX - offsetX + "px"
    });
    follower.css({
      top: e.clientY + "px",
      left: e.clientX + "px"
    });
  });

  $("a, button").hover(
    function() {
      cursor.addClass("active");
      follower.addClass("active");
    },
    function() {
      cursor.removeClass("active");
      follower.removeClass("active");
    }
  );
});

/*preloader*/
jQuery(document).ready(function($) {

  // Function to hide preloader
  function hidePreloader() {
    $("#preloader ").delay(2000).fadeOut("slow");
  }

  // Check if all resources have been loaded
  if (document.readyState === "complete") {
    hidePreloader();
  } else {
    window.onload = hidePreloader;
  }
});


jQuery('document').ready(function() {
    // Target the button and the dropdown menu
    var catButton = jQuery('.cat-dropdown-toggle');
    var catDropdown = jQuery('.cat-dropdown-menu');

    // Toggle the dropdown menu on button click
    catButton.on('click', function(e) {
      e.preventDefault();
      catDropdown.toggleClass('show');
    });

    // Close the dropdown menu when clicking outside of it
    jQuery('document').on('click', function(e) {
      if (!catButton.is(e.target) && catButton.has(e.target).length === 0 &&
          !catDropdown.is(e.target) && catDropdown.has(e.target).length === 0) {
        catDropdown.removeClass('show');
      }
    });
  });