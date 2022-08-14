/**
 * This file is used as a interface between the page and the ES6 classes
 * This files requires some global variables:
 * user - The current user logged in
 * photo - The photo that the user is seeing
 * missingFields - Fields that are missing and need suggestions
 * gamed - If it's gamified or not
 */

/* global user photo missingFields gamed Handlebars */

import $ from 'jquery';
import SuggestionModal from './SuggestionModal';
import { logOpenModal } from '../../services/SuggestionService';

$(document).ready(() => {
  // Don't show modal when it's a institution
  if (photo.institution) $('#OpenModal').hide();
  else $('#OpenModal').show();

  // Showing progress bar if it's gamed and it's not institution and not a video
  if (gamed && !photo.institution && photo.type !== 'video') {
    $('#progress-bar').removeClass('hidden');
  }

  // On DOM ready, add the click event to the open modal button
  $('.OpenModal').click((element) => {
    // Don't show the modal when we don't have a user logged in
    if (!user || !user.id) {
      // Go to login page
      window.location = '/users/login';
      return;
    }

    // If the user is the owner, go to edit page
    if (user.id === photo.user_id) {
      window.location = `/photos/${photo.id}/edit`;
      return;
    }

    // Filtering the missing fields (Remove the already reviewed ones)
    const filteredMissingFields = missingFields.filter(field => field.status !== 'reviewed');

    // Only shows the modal if we have missing fields
    if (filteredMissingFields && filteredMissingFields.length > 0) {
      // Sending to API the element origin (for logging purposes)
      const elementOrigin = element.target.getAttribute('data-origin');
      logOpenModal(photo.id, elementOrigin);

      // Opening modal
      const suggestionModal = new SuggestionModal(filteredMissingFields, user, photo, gamed);
      suggestionModal.showModal(
        filteredMissingFields[0].type,
        filteredMissingFields[0].field_name,
        filteredMissingFields[0].field_content,
        filteredMissingFields[0].question,
        filteredMissingFields[0].attribute_type,
        0,
      );
    }
  });

  // Registering Handlebars helpers
  // A helper include some functionalities to Handlebars
  Handlebars.registerHelper('times', (n, block) => {
    let accum = '';
    for (let i = 0; i < n; i += 1) {
      accum += block.fn(i);
    }
    return accum;
  });
  Handlebars.registerHelper('ifCond', (v1, v2, options) => {
    if (v1 === v2) {
      return options.fn(this);
    }
    return options.inverse(this);
  });
});

