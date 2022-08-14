import $ from 'jquery';
import { createChat } from '../../services/ChatsService';
import { createLog } from '../../services/LogsService';

// On DOM ready
$(document).ready(() => {
  /**
   * Click event to the open modal button
   */
  $('.create-chat-link').click((e) => {
    // Getting userID
    const userID = $(e.currentTarget).data('val');
    // Setting redirectWindow variable
    const redirectWindow = window.open('', '_blank');

    // Creating chat
    createChat(window.location.origin, userID)
      .then((data) => {
        // Open chat tab
        redirectWindow.location = `/chats/${data}`;
      }).catch((error) => {
        // Showing error
        console.info('ERRO', error);
      });
  });

  /**
   * User pressed thumbs-link
   */
  $('.thumbs-link').click((e) => {
    // First, we will send the log to API
    let status;
    if ($(e.currentTarget).hasClass('thumbs-up')) {
      // If clicked on thumbs up button, status is 'aceita'
      status = 'aceita';
    } else {
      status = 'recusada';
    }
    const suggestionId = $(e.currentTarget).attr('data-id');
    const payload = {
      status,
      suggestion_id: suggestionId,
    };
    // Sending log
    createLog(window.location.origin, 'accepted-rejected-suggestion', payload);

    // When the user clicks on Thumbs Down or
    // Thumbs Up on Suggestions List, submits the children form.
    // Important: The form must be the CHILDREN element.
    $(e.currentTarget).children('form').submit();
  });
});
