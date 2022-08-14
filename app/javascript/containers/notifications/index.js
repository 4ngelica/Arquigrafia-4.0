/**
 * This file is used as a interface between the page and the ES6 classes
 */

import $ from 'jquery';
import { createLog } from '../../services/LogsService';

$(document).ready(() => {
  // Setting suggestion-analysed-notification-link action on click
  $('.suggestion-analysed-notification-link').click((element) => {
    // Defining log params
    const notificationId = element.currentTarget.getAttribute('data-id');
    const payload = {
      notification_id: notificationId,
    };
    // Creating log
    createLog(window.location.origin, 'pressed-suggestion-analysed-notification', payload);
  });
  // Setting suggestion-received-notification-link action on click
  $('.suggestion-received-notification-link').click((element) => {
    // Defining log params
    const notificationId = element.currentTarget.getAttribute('data-id');
    const payload = {
      notification_id: notificationId,
    };
    // Creating log
    createLog(window.location.origin, 'pressed-suggestion-received-notification', payload);
  });
});
