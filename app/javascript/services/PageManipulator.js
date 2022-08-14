import $ from 'jquery';

/**
 * Scroll page to a specific element given it's ID
 * @params  {String}  elementId  The ID of the element that you wanna scroll to
 */
export const scrollToElement = (elementId) => {
  $('html, body').animate({
    scrollTop: $(elementId).offset().top,
  }, 400);
};

