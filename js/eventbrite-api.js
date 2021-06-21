(function ($, _, _) {

  $(".containerSlideshow").each(createSlideshow);

  function createSlideshow(i, elem) {
    var slideshow = $(elem);
    var count = slideshow.find(".isSlide").length;
    var backButton = slideshow.find(".btnPrevSlide");
    var nextButton = slideshow.find(".btnNextSlide");

    if (count > 1) {

      backButton.removeClass("collapse");
      nextButton.removeClass("collapse");

      function gotoSlide(n) {
        slideshow.find(".isSlide").removeClass("active").fadeOut(500);
        slideshow.find(".isSlide").eq(n).addClass("active").fadeIn(500);
      }

      //back & next buttons
      backButton.click(function() {
        var prev = slideshow.find(".isSlide.active").index() - 1;
        gotoSlide(prev);
      });

      nextButton.click(function() {
        var next = slideshow.find(".isSlide.active").index() + 1;
        if (next == count) next = 0;
        gotoSlide(next);
      });

    }
  }
})(jQuery, Drupal, Drupal.debounce);