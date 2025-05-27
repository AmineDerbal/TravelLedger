export const downloadExcelFile = (response) => {
  const blob = new Blob([response.data], {
    type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
  });
  const url = window.URL.createObjectURL(blob);
  const link = document.createElement('a');
  link.href = url;
  link.setAttribute('download', 'transactions.xlsx');
  document.body.appendChild(link);
  link.click();
  URL.revokeObjectURL(link.href);

  return response;
};
