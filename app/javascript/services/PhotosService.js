import { request, GET } from './Network';

/**
 * Getting photos to complete
 * @return  {Promise}  Returns a Promise of getting the photos
 */
export const getCompletenessPhotos = () =>
  new Promise((resolve, reject) => {
    // Creating URL
    const url = `${window.location.origin}/photos/to_complete`;
    // Get user suggestions (async)
    request(GET, url)
      .then((res) => {
        console.info('Completeness Photos Response', res);
        resolve(res);
      })
      .catch((err) => {
        console.info(err);
        reject(new Error('Erro ao pegar as fotos para completar'));
      });
  });

