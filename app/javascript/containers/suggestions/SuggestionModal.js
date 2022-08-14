import jBox from 'jbox';
import $ from 'jquery';
import { sendFinalSuggestions, sendSuggestion } from '../../services/SuggestionService';
import { createChat } from '../../services/ChatsService';
import { createLog } from '../../services/LogsService';

/* global Handlebars location */

class SuggestionModal {
  /**
   * Constructor
   * @param  {Object} missingFields   Objects with all fields and the questions to ask to the user
   * @param  {Object} user            The current user logged in
   * @param  {Object} photo           The current photo
   * @param  {Boolean} gamed          Will render the gamed or not gamed modal
   */
  constructor(missingFields, user, photo, gamed) {
    /**
     * This variable stores the array of modals.
     * This variable is needed because when we animate one modal to another
     * we need to hide this modal and close it later (see onClose on showModal function)
     * @type {Array}
     */
    this.suggestionModals = [];

    // Setting Missing fields
    this.missingFields = missingFields;
    this.user = user;
    this.photo = photo;
    this.gamed = gamed;
    this.points = 0;
    this.currentIndex = 0;
    this.numberSuggestions = 0;
  }

  /**
   * Generates the jBox TITLE html using Handlebars
   * @param  {String} attributeType  The icon that will be rendered on the title area of jBox
   * @return {String} The HTML string that represents the jBox title
   */
  getTitleHTML(attributeType) {
    // Getting icon base on field type
    let icon;
    switch (attributeType) {
      case 'workDate':
        icon = 'date-icon';
        break;
      case 'street':
      case 'district':
      case 'city':
      case 'state':
      case 'country':
        icon = 'location-icon';
        break;
      case 'name':
      case 'description':
      case 'imageAuthor':
      case 'lastPage':
        icon = 'feedback-icon';
        break;
      case 'authors':
        icon = 'author-icon';
        break;
      default:
        break;
    }

    let sourceTitle;
    let templateTitle;
    let titleHTML;
    if (this.gamed) {
      sourceTitle = $('#suggestion-modal-title-gamefied').html();
      templateTitle = Handlebars.compile(sourceTitle);
      titleHTML = templateTitle({ icon, points: this.points });
    } else {
      sourceTitle = $('#suggestion-modal-title').html();
      templateTitle = Handlebars.compile(sourceTitle);
      titleHTML = templateTitle({ icon });
    }

    return titleHTML;
  }

  /**
   * Generates the jBox CONTENT html using Handlebars
   * @param  {String} type     Can be 'confirm' or 'text'. Represents the type of content
   * @param  {String} name     The top name that will show on modal
   * @param  {String} question The question that you wanna ask to the user
   * @return {String}          The HTML that represents the jBox content
   */
  getContentHTML(type, name, question) {
    let sourceContent;
    let templateContent;
    let contentHTML;

    // Rendering the right content by type
    if (type === 'confirm') {
      sourceContent = $('#suggestion-modal-confirm-content').html();
      templateContent = Handlebars.compile(sourceContent);
      contentHTML = templateContent({ name, question });
    } else if (type === 'suggestion') {
      sourceContent = $('#suggestion-modal-text-content').html();
      templateContent = Handlebars.compile(sourceContent);

      // Setting jumpLabel
      let jumpLabel;
      if (this.gamed) jumpLabel = 'Pular';
      else jumpLabel = 'Não sei';

      // Setting image if it's description field
      let imageID;
      if (name === 'Descrição') {
        imageID = this.photo.id;
      }

      contentHTML = templateContent({ name, question, jumpLabel, imageID });
    } else if (type === 'lastPage') {
      sourceContent = $('#suggestion-modal-last-page-content').html();
      templateContent = Handlebars.compile(sourceContent);
      contentHTML = templateContent({ name, question, userID: this.photo.user_id });
    } else if (type === 'lastPageGamed') {
      sourceContent = $('#suggestion-modal-last-page-gamed-content').html();
      templateContent = Handlebars.compile(sourceContent);
      contentHTML = templateContent({ name, question, userID: this.photo.user_id });
    } else {
      return '';
    }

    return contentHTML;
  }

  /**
   * Generates the jBox FOOTER html using Handlebars
   * @param  {String} type Can be 'confirm' or 'jump'. Represents the type of footer.
   * @return {String}      The HTML that represents the jBox footer
   */
  getFooterHTML(type, currentIndex) {
    // Defining the percentage that will show on footer
    const numItems = this.missingFields.length + 1; // + 1 for the last page
    let sourceFooter;
    let templateTitle;
    let footerHTML;

    if (type === 'confirm') {
      sourceFooter = $('#suggestion-modal-confirm-footer').html();
      templateTitle = Handlebars.compile(sourceFooter);

      let jumpLabel;
      if (this.gamed) jumpLabel = 'Pular';
      else jumpLabel = 'Não sei';

      footerHTML = templateTitle({ numItems, currentIndex, jumpLabel });
    } else if (type === 'jump') {
      sourceFooter = $('#suggestion-modal-jump-footer').html();
      templateTitle = Handlebars.compile(sourceFooter);

      let label;
      if (this.gamed) label = 'Pular';
      else label = 'Não sei';

      footerHTML = templateTitle({ numItems, currentIndex, label });
    } else if (type === 'lastPage') {
      sourceFooter = $('#suggestion-modal-close-footer').html();
      templateTitle = Handlebars.compile(sourceFooter);
      footerHTML = templateTitle({ numItems, currentIndex });
    }

    return footerHTML;
  }

  /**
   * This functions renders the Photos at the final modal on the Gamed version
   * @param  {Array} photos   The photos that will be rendered
   */
  renderGamedNextPhotos(photos) {
    const sourceNextPhotos = $('#suggestion-modal-last-page-gamed-photos').html();
    const templateNextPhotos = Handlebars.compile(sourceNextPhotos);
    const nextPhotosHTML = templateNextPhotos({ photos });

    $('#next-photos-container').html(nextPhotosHTML);

    // Adding callback when clicked on image
    $('.suggestion-last-modal-image').on('click', ((element) => {
      // Defining params to log
      const originURL = window.location.origin;
      const logClass = 'pressed-final-modal-photo';
      const redirectPhotoId = element.target.parentElement.parentElement.getAttribute('data-id');
      const payload = {
        current_photo_id: this.photo.id,
        redirect_photo_id: redirectPhotoId,
        num_suggestions: this.numberSuggestions,
      };
      // Here, we're going to log the click action
      createLog(originURL, logClass, payload);
    }));
  }

  /**
   * Shows to user the modal asking for a suggestion.
   * @param  {Number} currentIndex The current page (index) that we're at the moment
   */
  askSuggestion(currentIndex) {
    // Getting content
    const currentModal = this.suggestionModals[currentIndex];
    const fieldName = this.missingFields[currentIndex].field_name;
    const fieldContent = this.missingFields[currentIndex].field_content;
    const attributeType = this.missingFields[currentIndex].attribute_type;
    const fieldType = this.missingFields[currentIndex].field_type;
    // Mounting modal modal content
    let modalContentText;
    if (fieldType === 'array_strings') {
      // If the field contains an array of strings, show all the content
      modalContentText = `O ${fieldName} atual é: ${fieldContent.reduce((prevVal, currentVal) => `${prevVal}; ${currentVal}`)}`;
    } else {
      // If it's just a value, show just the value
      modalContentText = `O ${fieldName} atual é ${fieldContent}`;
    }
    // Setting the modal question
    let modalQuestionText = 'Você sabe a informação correta? Nos ajude sugerindo uma modificação.';
    if (fieldType === 'array_strings') {
      // If the content is an array of strings, we need to inform to
      // the user that it need to separate content with ;
      modalQuestionText += ' (Para mais de um valor, separe com ";")';
    }
    // Checking if there's a next page to change
    this.showModal(
      'suggestion',
      modalContentText,
      null,
      modalQuestionText,
      attributeType,
      currentIndex,
    );

    // Slide the jBox to the left
    this.suggestionModals[currentIndex].animate('pulse', {
      // Once the jBox animated, hide it
      complete: () => {
        // Hiding the current modal
        if (currentModal) currentModal.wrapper.css('display', 'none');
      },
    });
  }

  /**
   * Shows the last modal
   * @param  {Number} currentIndex The current page (index) that we're at the moment
   * @param  {Boolean} forceOverlay  Force to show black transparent overlay
   */

  showLastModal(currentIndex, forceOverlay = false) {
    let message;

    if (this.points === 0) {
      message = 'Colabore com informações sobre outras imagens';
    } else {
      message = `Obrigado por responder as questões! Você pode ganhar até ${this.points} pontos!`;
    }

    // Here we're at the last modal page
    this.showModal(
      'lastPage',
      null,
      null,
      message,
      'lastPage',
      currentIndex + 1,
      forceOverlay,
    );
  }

  /**
   * Changes the 'page' of the modal.
   * Basically here we're rendering the next modal and showing the next question.
   * @param  {Number} currentIndex The current page (index) that we're at the moment
   */
  changePage(currentIndex) {
    const currentModal = this.suggestionModals[currentIndex];
    // Checking if there's a next page to change
    if (this.missingFields.length > currentIndex + 1) {
      // Defining field content
      const fieldType = this.missingFields[currentIndex + 1].field_type;
      const fieldContent = this.missingFields[currentIndex + 1].field_content;
      let content;
      if (fieldContent && fieldType === 'array_strings') {
        content = fieldContent.reduce((prevVal, currentVal) => `${prevVal}; ${currentVal}`);
      } else {
        content = fieldContent;
      }
      // Showing the next modal (with index + 1)
      this.showModal(
        this.missingFields[currentIndex + 1].type,
        this.missingFields[currentIndex + 1].field_name,
        content,
        this.missingFields[currentIndex + 1].question,
        this.missingFields[currentIndex + 1].attribute_type,
        currentIndex + 1,
      );
    } else if (currentIndex + 1 === this.missingFields.length) {
      this.showLastModal(currentIndex);
    } else {
      // Close the current modal and return
      if (currentModal) currentModal.close();
      return;
    }

    // Slide the jBox to the left
    this.suggestionModals[currentIndex].animate('slideLeft', {
      // Once the jBox animated, hide it
      complete: () => {
        // Hiding the current modal
        if (currentModal) currentModal.wrapper.css('display', 'none');
      },
    });
  }

  /**
   * Mark that we had won points
   */
  wonPoints() {
    this.points += 5;
    this.numberSuggestions += 1;
  }

  /**
   * This function shows a Suggestion Modal
   * @param  {String} type          The type of the modal. Can be 'confirm' or 'text'
   * @param  {String} name          The item name
   * @param  {String} content       The item content (sometimes is null, if the field is not filled)
   * @param  {String} question      The question that the modal will show to user
   * @param  {String} currentIndex  The currentIndex showing (represents the current question)
   * @param  {Boolean} forceOverlay  Force to show black transparent overlay
   */
  showModal(type, name, content, question, attributeType, currentIndex, forceOverlay = false) {
    // Setting this.currentIndex
    this.currentIndex = currentIndex;

    // Getting the HTML content that we're gonna show on the modal
    const titleHTML = this.getTitleHTML(attributeType);
    let contentHTML;
    let footerHTML;
    // Getting content and footer based on type
    if (type === 'confirm') {
      contentHTML = this.getContentHTML(type, content, question);
      footerHTML = this.getFooterHTML('confirm', currentIndex);
    } else if (type === 'lastPage') {
      // Defining content
      if (this.gamed) contentHTML = this.getContentHTML('lastPageGamed', null, question);
      else contentHTML = this.getContentHTML('lastPage', null, null);
      // Defining footer
      footerHTML = this.getFooterHTML(type, currentIndex);
      // Sending that we're at the final page
      const numItems = this.missingFields.length;
      let abandonStatus = 'complete';
      if (this.suggestionModals.length < numItems) abandonStatus = 'incomplete';

      sendFinalSuggestions(this.photo.id, this.points, this.numberSuggestions, abandonStatus)
        .then((data) => {
          // Rendering photos if we're at the gamed version
          if (this.gamed) this.renderGamedNextPhotos(data);
        }).catch((error) => {
          console.info(error);
        });
    } else {
      contentHTML = this.getContentHTML(type, name, question);
      footerHTML = this.getFooterHTML('jump', currentIndex);
    }
    // Is this the initial modal?
    const initial = currentIndex == 0;

    // Showing jBox modal
    this.suggestionModals[currentIndex.toString()] = new jBox('Modal', {
      animation: 'zoomIn',
      overlay: initial || forceOverlay ? true : false, // Only shows the overlay for the first modal
      blockScroll: false,
      closeButton: false,
      closeOnEsc: false,
      closeOnClick: false,
      footer: footerHTML,
      title: titleHTML,
      content: contentHTML,
      zIndex: 10000,

      // Once this jBox is created, the onCreated function is called
      onCreated: (() => {
        // We gonna tell the close button to close the currentIndex modal
        $('.close-button').on('click', (() => {
          // Closing current modal
          this.suggestionModals[currentIndex].close();
        }));

        $('.enviar-button').on('click', (() => {
          // Getting the text from suggestion-text textarea
          const suggestionText = this.suggestionModals[currentIndex].content.find('#sugestion-text').val();
          if (!suggestionText || suggestionText === '') {
            // Showing error message
            $('.error-message.sugestion').removeClass('hidden');
            return;
          }
          // Marking that we won points
          this.wonPoints();
          // Sending suggestion
          sendSuggestion(
            this.user.id,
            this.photo.id,
            this.missingFields[currentIndex].attribute_type,
            suggestionText,
          );
          // Change page (go to the next modal)
          this.changePage(currentIndex);
        }));

        // When click on jump button, changes page doing nothing else
        $('.pular-etapa-button').on('click', (() => {
          // Change page (go to the next modal)
          this.changePage(currentIndex);
        }));

        // When click on close button, changes page doing nothing else
        $('.fechar-button').on('click', (() => {
          // Change page (go to the next modal)
          this.changePage(currentIndex);
        }));

        // Event when clicks on sim-button
        $('.sim-button').on('click', (() => {
          // Marking that we won points
          this.wonPoints();
          // Sending suggestion
          sendSuggestion(
            this.user.id,
            this.photo.id,
            this.missingFields[currentIndex].attribute_type,
            this.missingFields[currentIndex].field_content,
          );
          // Change page (go to the next modal)
          this.changePage(currentIndex);
        }));

        // Event when clicks on nao-button
        $('.nao-button').on('click', (() => {
          // Asking user for suggestion
          this.askSuggestion(currentIndex);
        }));

        // Event when clicks on nao-sei-button
        $('.nao-sei-button').on('click', (() => {
          this.changePage(currentIndex);
        }));

        // Checking when click on send_message
        $('#send_message').on('click', (() => {
          // Setting redirectWindow variable
          const redirectWindow = window.open('', '_blank');

          // Creating chat
          createChat(window.location.origin, this.photo.user_id)
            .then((data) => {
              // Open chat tab
              redirectWindow.location = `/chats/${data}`;
            }).catch((error) => {
              console.info('ERRO', error);
            });
        }));

        $('#last-modal-user-profile').on('click', (() => {
          // Defining params to log
          const originURL = window.location.origin;
          const logClass = 'pressed-final-modal-profile-link';
          const payload = {
            photo_id: this.photo.id,
            num_suggestions: this.numberSuggestions,
          };
          // Here, we're going to log the click action
          createLog(originURL, logClass, payload);
        }));


        if (initial || forceOverlay) {
          setTimeout((() => {
            $('.jBox-overlay').on('click', (() => {
              this.suggestionModals[this.currentIndex].close();
            }));
          }), 100);
        }
      }),
      // When any of the modals is closed, this function is called
      onClose: (() => {
        const numItems = this.missingFields.length;

        if (
          this.currentIndex + 1 === this.suggestionModals.length &&
          this.currentIndex < numItems
          && this.points > 0
        ) {
          // Showing last modal, because we've sent suggestions
          this.showLastModal(numItems - 1, currentIndex === 0);
          return;
        } else if (
          this.currentIndex + 1 === this.suggestionModals.length &&
          this.currentIndex < numItems && this.points === 0
        ) {
          // Sending final suggestions without going to last page
          sendFinalSuggestions(this.photo.id, this.points, this.numberSuggestions, 'none');
        }

        // When closing one of the modals, close all modals
        this.suggestionModals.forEach((modal) => {
          modal.close();
        });
        // Cleaning suggestionModals array
        this.suggestionModals = [];
        // Reloading page
        location.reload();
      }),
    }).open();
  }
}

export default SuggestionModal;
