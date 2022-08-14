/**
 * This file is responsable for Math operations
 */

/**
 * Decimal adjustment of a number.
 *
 * @param {String}  type  The type of adjustment.
 * @param {Number}  value The number.
 * @param {Integer} exp   The exponent (the 10 logarithm of the adjustment base).
 * @returns {Number}      The adjusted value.
 */
export const decimalAdjust = (type, value, exp) => {
  // If the exp is undefined or zero...
  if (typeof exp === 'undefined' || +exp === 0) {
    return Math[type](value);
  }
  let newValue = value;
  let newExp = exp;

  newValue = +newValue;
  newExp = +newExp;

  // If the value is not a number or the newExp is not an integer...
  if (isNaN(newValue) || !(typeof newExp === 'number' && newExp % 1 === 0)) {
    return NaN;
  }
  // Shift
  newValue = newValue.toString().split('e');
  newValue = Math[type](+(`${newValue[0]}e${(newValue[1] ? (+newValue[1] - newExp) : -newExp)}`));
  // Shift back
  newValue = newValue.toString().split('e');
  return +(`${newValue[0]}e${(newValue[1] ? (+newValue[1] + newExp) : newExp)}`);
};

/**
 * Round up a number at a defined place
 * @param {Number} value The number you wanna ceil
 * @param {Number} exp The place that you wanna adjust
 * @return  The number rounded 
 */
export const ceil10 = (value, exp) => decimalAdjust('ceil', value, exp);

/**
 * Round a number at a defined place
 * @param {Number} value The number you wanna round
 * @param {Number} exp The place that you wanna adjust
 * @return  The number rounded
 */
export const round10 = (value, exp) => decimalAdjust('round', value, exp);

/**
 * Round down a number at a defined place
 * @param {Number} value The number you wanna floor
 * @param {Number} exp The place that you wanna adjust
 * @return  The number rounded down
 */
export const floor10 = (value, exp) => decimalAdjust('floor', value, exp);

/**
 * Check if number is even
 * @param {Number} number The number that you wanna check if it's even
 * @return  True if it's even, false if isn't
 */
export const isEven = (number) => {
  if (number % 2 === 0) {
    return true;
  }
  return false;
};
