import { createLog } from '../services/LogsService';

test('expect createLog return logged true', () => {
  expect.assertions(1);

  return createLog('http://localhost:8000', 'open-modal', { origin: 'jest-test' })
    .then((res) => {
      expect(res.logged).toBeTruthy();
    });
});
