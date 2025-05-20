const getTodayDate = () => {
  return new Date().toISOString().split('T')[0];
};

const getYesterdayDate = () => {
  const today = new Date();
  today.setDate(today.getDate() - 1);
  return today.toISOString().split('T')[0];
};

export { getTodayDate, getYesterdayDate };
