import { request, getQueryString, GET } from '../services/Network';

test('Should get snapshot from API', () => {
  expect.assertions(1);
  return request(GET, 'https://jsonplaceholder.typicode.com/posts').then((data) => {
    expect(data).toMatchSnapshot();
  });
});

test('URL params must be test1=test&test2=test', () => {
  expect(getQueryString({ test1: 'test', test2: 'test' })).toBe('test1=test&test2=test');
});
