import { getTabById } from '../services/ContributionsTabsService';

it('expect to get the curatorship tab', () => {
  expect(getTabById('curatorship').id).toBe('curatorship');
});

it('expect to return null when theres no tab', () => {
  expect(getTabById('dkasjdkasjdjka')).toBe(null);
});

