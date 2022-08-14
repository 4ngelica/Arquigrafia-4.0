import moment from 'moment';
import 'moment/locale/pt';

/**
 * This is a date formatter. It returns a function that formats dates, when you pass a format
 * @param {String} date The date that we wanna format
 */
export const dateFormatter = date => (format) => {
  moment.locale('pt');
  return date && moment(date).isValid() ? moment(date).format(format) : '';
};

export const day = date => dateFormatter(date)('DD');
export const month = date => dateFormatter(date)('MM');
export const year = date => dateFormatter(date)('YYYY');
export const hours = date => dateFormatter(date)('HH:mm');
export const fullDate = date => `${hours(date)} - ${day(date)}/${month(date)}/${year(date)}`;
export const nameDay = date => dateFormatter(date)('ddd');
