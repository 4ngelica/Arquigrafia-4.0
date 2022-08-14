import { day, month, year, hours, fullDate, nameDay } from '../services/DateFormatter';

// new Date(year, month, day, hours, minutes, seconds, milliseconds)
// The date below is a wednesdey
const date = '2017-09-13T10:00';

test('should return the day 13', () => {
  expect(day(date)).toBe('13');
});


test('should return the month 9', () => {
  expect(month(date)).toBe('09');
});


test('should return the year 2017', () => {
  expect(year(date)).toBe('2017');
});


test('should return the hours 10:00', () => {
  expect(hours(date)).toBe('10:00');
});


test('should return the full date 10:00 - 13/09/2017', () => {
  expect(fullDate(date)).toBe('10:00 - 13/09/2017');
});


test('should return the name of the day QUA', () => {
  expect(nameDay(date).toUpperCase()).toBe('QUA');
});


test('should return empty string if send null', () => {
  expect(day(null)).toBe('');
});


test('should return empty string if send invalid date', () => {
  expect(day('sadasldkjaklsd')).toBe('');
});
