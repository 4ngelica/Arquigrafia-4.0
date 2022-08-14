import { request, POST } from './Network';

/**
 * Create a chat with a user id
 * @params  {String}  originURL  The origin url for API
 * @params  {Number}  userID  The user ID that you wanna create a chat with
 * @return  {Promise}  Returns the promise of creating a chat
 */
export const createChat = (originURL, userID) => new Promise((resolve, reject) => {
  // Creating URL
  const url = `${originURL}/chats`;
  // Defining data to create chat
  const params = {
    participants: [userID],
  };
  // Making request
  request(POST, url, params)
    .then((response) => {
      console.info('CHAT CRIADO', response);
      if (response !== false) resolve(response);
      else reject(response);
    })
    .catch((error) => {
      console.info('ERRO AO CRIAR CHAT', error);
      reject(error);
    });
});

