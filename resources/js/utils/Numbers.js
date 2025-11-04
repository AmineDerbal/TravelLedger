export const formatNumber = (number, locale = 'fr-FR', options = {}) => {
  return Number(number).toLocaleString(locale, options);
};
