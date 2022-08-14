/**
 * This file is used as a interface between the page and the ES6 classes
 * This files requires some global variables:
 * profileUser - The current user that you are seeing the profile
 */

/* eslint no-undef: "profileUser" */

import $ from 'jquery';
import { createChat } from '../../services/ChatsService';
import { createLog } from '../../services/LogsService';

$(document).ready(() => {
  // Setting send_message action on click
  $('#send_message a').click(() => {
    // Creating a chat
    createChat(window.location.origin, profileUser.id)
      .then((res) => {
        window.location.replace(`/chats/${res}`);
      });
    // Registering on log
    const payload = {
      chat_user_id: profileUser.id,
    };
    createLog(window.location.origin, 'create-chat-user', payload);
  });
  // Setting num-suggestions-profile action on click
  $('.num-suggestions-profile').click(() => {
    // Registering on log
    createLog(window.location.origin, 'redirect-users-contributions', {});
  });
});
