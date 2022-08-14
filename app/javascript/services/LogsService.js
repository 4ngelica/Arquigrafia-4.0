import { request, POST } from './Network';

export const createLog = (originURL, logClass, payload) => new Promise((resolve, reject) => {
  // Creating URL
  const url = `${originURL}/logs`;
  // Creating params
  const params = {
    payload,
    class: logClass,
  };
  // Making request
  request(POST, url, params)
    .then((res) => {
      console.info('CREATED LOG', res);
      resolve(res);
    })
    .catch((err) => {
      console.info('ERROR ON CREATE LOG', err);
      reject(err);
    });
});

