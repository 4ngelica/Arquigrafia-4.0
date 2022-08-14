import { isEven, ceil10, round10, floor10 } from '../services/MathService';

it('should number 1002 must be even', () => {
  expect(isEven(1002)).toBe(true);
});

it('should number 1003 must not be even', () => {
  expect(isEven(1003)).toBe(false);
});

it('should ceil10 of 10.18 must be 10.2', () => {
  expect(ceil10(10.18, -1)).toBe(10.2);
});

it('should round10 of 10.16 must be 10.2', () => {
  expect(round10(10.16, -1)).toBe(10.2);
});

it('should floor10 of 10.19 must be 10.1', () => {
  expect(floor10(10.19, -1)).toBe(10.1);
});
