import moment from 'moment';

/**
 * Format date year-month-day
 * @param {Date} date
 */
export function formatDate(date) {
  return moment(date).format('YYYY-MM-DD');
}

/**
 * Upper case first char
 * @param {String} string
 */
export function uppercaseFirst(string) {
  return string.charAt(0).toUpperCase() + string.slice(1);
}
