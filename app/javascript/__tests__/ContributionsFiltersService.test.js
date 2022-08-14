import { getFilterById } from '../services/ContributionsFiltersService';

it('expect get accepted filter', () => {
  expect(getFilterById('accepted').id).toBe('accepted');
});

it('expected unexistent filter to return null', () => {
  expect(getFilterById('dkajsdkljasd')).toBe(null);
});
