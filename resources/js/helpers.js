const floatToMoney = (float) => {
  return parseFloat(float).toLocaleString('pt-BR', { minimumFractionDigits: 2 })
}

function moneyToFloat(str) {
  if (isMoney(str)) {
    return parseFloat(str.replace('.', '').replace(',', '.'))
  }
  return isNumber(str) ? parseFloat(str) : 0
}

function isMoney(value) {
  const pattern = /^[R\$]?[ ]?\d{1,3}(\.\d{3})*,[0-9]{2}$/
  return pattern.test(value)
}

function isNumber(value) {
  if(!value) return false
  return !isNaN(Number(value))
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
