import fetch from 'node-fetch';

export const GET = 'GET';
export const POST = 'POST';
export const PUT = 'PUT';
export const DELETE = 'DELETE';
export const PATCH = 'PATCH';

export const getQueryString = (params) => {
  const esc = encodeURIComponent;
  return Object.keys(params)
    .map(k => `${esc(k)}=${esc(params[k])}`)
    .join('&');
};

export const request = (type = GET, url, params = null, headers = null) => (
  new Promise((resolve, reject) => {
    // Checking the request type
    if (
      type !== GET &&
      type !== POST &&
      type !== PUT &&
      type !== DELETE &&
      type !== PATCH
    ) {
      reject('Wrong type');
    }
    // Setting final endpoint
    let ENDPOINT = url;

    const data = {
      method: type,
      headers: {
        'Content-Type': 'application/json',
      },
    };
    // Adding params
    if (params !== null && type !== GET) {
      data.body = JSON.stringify(params);
    } else if (params !== null && type === GET) {
      ENDPOINT = `${ENDPOINT}?${getQueryString(params)}`;
    }
    // Adding headers
    if (headers !== null) {
      data.headers = Object.assign(data.headers, headers);
    }

    // console.info(`FETCHING: ${ENDPOINT}`);
    // console.info('SEND DATA', data);
    fetch(ENDPOINT, data)
      .then(response => response.json())
      .then((responseJson) => {
        // console.info('RESPONSE:', responseJson);
        resolve(responseJson);
      })
      .catch((error) => {
        // console.info('ERROR ON FETCH:', error);
        reject(error);
      });
  })
);
