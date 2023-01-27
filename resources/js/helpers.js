const floatToMoney = (float) => {
  return parseFloat(float).toLocaleString('pt-BR', { minimumFractionDigits: 2 })
}

function moneyToFloat(str) {
  return parseFloat(!!str ? str.replace(',', '.') : 0);
}

function limitString(str, num) {
  if (str.length > num) {
    return str.substring(0, num) + '...'
  }
  return str
}

export {
  floatToMoney,
  moneyToFloat,
  limitString
}
