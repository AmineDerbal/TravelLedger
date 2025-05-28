const getTodayDate = () => {
  return new Date().toISOString().split('T')[0];
};

const getYesterdayDate = () => {
  const today = new Date();
  today.setDate(today.getDate() - 1);
  return today.toISOString().split('T')[0];
};

export const formatDateToDdMmYyyy = (date) => {
  return new Date(date).toLocaleDateString('fr-FR', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric',
  });
};

export { getTodayDate, getYesterdayDate };
