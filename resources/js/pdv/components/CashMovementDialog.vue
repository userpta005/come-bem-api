<style>
.cash-movement-dialog>.el-dialog__header {
  display: none;
}

.cash-movement-dialog>.el-dialog__body {
  padding: 20px;
}

.cash-movement-tabs>.el-tabs__content {
  height: 450px;
}

.cash-movement-tabs>.el-tabs__content>.el-tab-pane {
  height: 100%;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}
</style>

<template>
  <el-dialog v-model="dialogVisible"
    :close-on-click-modal="false"
    :close-on-press-escape="false"
    :show-close="false"
    @open="openedDialog"
    align-center
    width="580"
    class="cash-movement-dialog"
    destroy-on-close>

    <div v-if="pixShow"
      style="min-height: 491px; display:flex; flex-direction:column; justify-content:space-between">
      <div>
        <div style="display: flex;
        flex-direction: column;
        align-items: flex-start;
        padding: 10px 30px 0 30px; margin-bottom: 30px;">
          <h3 style="font-weight: 600;
          margin: 0;">
            Pix
            <hr style="background: #ff7e07;
            margin: 5px 0 0 0">
          </h3>
        </div>
        <div style="padding: 0 30px;">
          <p style="font-weight: 500;">
            Abra o aplicativo que você tenha o PIX habilitado e utilize o QR Code abaixo para realizar o
            pagamento.
          </p>

          <div style="text-align: center; margin: 25px 0;">
            <img :href="checkout.payment_response[0].links[0].href"
              style="height: 200px; width: 200px;" />
          </div>

          <p style="font-weight: 500;">
            {{ checkout.value.payment_response[0].text }}
          </p>
          <div style="text-align: center; margin: 15px 0 25px 0;">
            <el-button type="primary"
              style="text-decoration:underline;"
              link
              @click="copyCode">
              Copiar código
            </el-button>
          </div>
        </div>
      </div>
      <div style="text-align: center;">
        <el-button @click="handlePixShow">
          Cancelar
        </el-button>
        <el-button color="#ff7e07"
          style="color: white;"
          @click="handlePixConfirm">
          Confirmar
        </el-button>
      </div>
    </div>

    <el-tabs v-if="!pixShow"
      type="border-card"
      class="cash-movement-tabs">

      <el-tab-pane>
        <template #label>
          <span style="font-size: large; color: black;">Recarga</span>
        </template>
        <el-form label-position="top"
          style="padding: 0 30px;"
          require-asterisk-position="right"
          ref="recargaFormRef"
          :model="recargaForm">
          <el-row>
            <el-col :span="24">
              <el-form-item label="Consumidor"
                style="margin: 0 18px 18px 0;"
                prop="account_id"
                :rules="[{ required: true, message: 'Consumidor é obrigatório !' }]">
                <el-select v-model="recargaForm.account_id"
                  style="width: 100%;"
                  size="large"
                  fit-input-width
                  filterable
                  remote
                  placeholder="Digite o nome ou cpf do consumidor"
                  :remote-method="remoteMethod"
                  :loading="loading">
                  <el-option v-for="item in dependents"
                    :key="item.value"
                    :label="item.people.info"
                    :value="item.accounts[0].id" />
                </el-select>
              </el-form-item>
            </el-col>
            <el-col :span="24">
              <el-form-item label="Valor da recarga (R$)"
                style="margin: 0 18px 18px 0;"
                prop="amount"
                :rules="[
                { required: true, message: 'Valor da recarga é obrigatória !' }]">
                <el-input v-model="recargaForm.amount"
                  size="large"
                  autocomplete="off"
                  v-maska
                  data-maska="9.99#,##"
                  data-maska-reversed
                  data-maska-tokens="9:[0-9]:repeated"
                  @change="handleChangeRadio" />
              </el-form-item>
            </el-col>
            <el-col :span="14">
              <el-form-item label="Forma de pagamento"
                style="margin: 0 18px 18px 0;"
                prop="payment_method_id"
                :rules="[{ required: true, message: 'Forma de pagamento é obrigatória !' }]">
                <el-radio-group v-model="recargaForm.payment_method_id"
                  style="display: flex; flex-direction: column; align-items: start;"
                  @change="handleChangeRadio">
                  <el-radio :label="item.id"
                    size="large"
                    v-for="item in paymentMethods"
                    :key="item.id">
                    {{ item.name }}
                  </el-radio>
                </el-radio-group>
              </el-form-item>
            </el-col>
            <el-col :span="10"
              v-if="moneyChangeShow">
              <el-form-item label="Valor (R$)"
                style="margin: 0 18px 18px 0;"
                prop="amount_entry"
                :rules="[{ required: true, message: 'Valor é obrigatório !' }]">
                <el-input v-model="recargaForm.amount_entry"
                  style="width: 120px;"
                  size="large"
                  autocomplete="off"
                  v-maska
                  data-maska="9.99#,##"
                  data-maska-reversed
                  data-maska-tokens="9:[0-9]:repeated"
                  @change="moneyChangeEvent" />
              </el-form-item>
              <el-form-item label="Troco (R$)"
                style="margin: 0 18px 18px 0;">
                <el-input v-model="recargaForm.money_change"
                  style="width: 120px;"
                  size="large"
                  disabled
                  autocomplete="off"
                  v-maska
                  data-maska="9.99#,##"
                  data-maska-reversed
                  data-maska-tokens="9:[0-9]:repeated" />
              </el-form-item>
            </el-col>
          </el-row>
        </el-form>
        <div style="text-align: center;">
          <el-button @click="$emit('closeCashMovementDialog')">
            Cancelar
          </el-button>
          <el-button color="#ff7e07"
            style="color: white;"
            @click="handleRecargaSubmit(recargaFormRef)">
            Confirmar
          </el-button>
        </div>
      </el-tab-pane>

      <el-tab-pane>
        <template #label>
          <span style="font-size: large; color: black;">Sangria</span>
        </template>
        <el-form label-position="top"
          style="padding: 0 30px;"
          require-asterisk-position="right"
          ref="sangriaFormRef"
          :model="sangriaForm">
          <el-row>
            <el-col :span="24">
              <el-form-item label="Descrição"
                style="margin: 0 18px 18px 0;"
                prop="description"
                :rules="[{ required: true, message: 'Descrição é obrigatória !' }]">
                <el-input v-model="sangriaForm.description"
                  size="large"
                  autocomplete="off" />
              </el-form-item>
            </el-col>
            <el-col :span="24">
              <el-form-item label="Valor (R$)"
                style="margin: 0 18px 18px 0;"
                prop="amount"
                :rules="[{ required: true, message: 'Valor é obrigatória !' }]">
                <el-input v-model="sangriaForm.amount"
                  size="large"
                  autocomplete="off"
                  v-maska
                  data-maska="9.99#,##"
                  data-maska-reversed
                  data-maska-tokens="9:[0-9]:repeated"
                  @change="handleSangriaAmountChange()" />
              </el-form-item>
            </el-col>
            <el-col :span="24">
              <el-form-item label="Saldo (R$)"
                style="margin: 0 18px 18px 0;">
                <el-input v-model="sangriaForm.balance"
                  size="large"
                  disabled
                  v-maska
                  data-maska="9.99#,##"
                  data-maska-reversed
                  data-maska-tokens="9:[0-9]:repeated" />
              </el-form-item>
            </el-col>
          </el-row>
        </el-form>
        <div style="text-align: center;">
          <el-button @click="$emit('closeCashMovementDialog')">
            Cancelar
          </el-button>
          <el-button color="#ff7e07"
            style="color: white;"
            @click="handleSangriaSubmit(sangriaFormRef)">
            Confirmar
          </el-button>
        </div>
      </el-tab-pane>

      <el-tab-pane>
        <template #label>
          <span style="font-size: large; color: black;">Troco</span>
        </template>
        <el-form label-position="top"
          style="padding: 0 30px;"
          require-asterisk-position="right"
          ref="trocoFormRef"
          :model="trocoForm">
          <el-row>
            <el-col :span="24">
              <el-form-item label="Troco (R$)"
                style="margin: 0 18px 18px 0;"
                prop="amount"
                :rules="[{ required: true, message: 'Troco é obrigatório !' }]">
                <el-input v-model="trocoForm.amount"
                  size="large"
                  autocomplete="off"
                  v-maska
                  data-maska="9.99#,##"
                  data-maska-reversed
                  data-maska-tokens="9:[0-9]:repeated"
                  @change="handleTrocoAmountChange()" />
              </el-form-item>
            </el-col>
            <el-col :span="24">
              <el-form-item label="Saldo (R$)"
                style="margin: 0 18px 18px 0;">
                <el-input v-model="trocoForm.balance"
                  size="large"
                  disabled
                  v-maska
                  data-maska="9.99#,##"
                  data-maska-reversed
                  data-maska-tokens="9:[0-9]:repeated" />
              </el-form-item>
            </el-col>
          </el-row>
        </el-form>
        <div style="text-align: center;">
          <el-button @click="$emit('closeCashMovementDialog')">
            Cancelar
          </el-button>
          <el-button color="#ff7e07"
            style="color: white;"
            @click="handleTrocoSubmit(trocoFormRef)">
            Confirmar
          </el-button>
        </div>
      </el-tab-pane>

    </el-tabs>
  </el-dialog>
</template>

<script setup>
import axios from 'axios'
import { ref, reactive } from 'vue'
import useStorageStore from '../stores/storage'
import { vMaska } from 'maska'
import { moneyToFloat, floatToMoney } from '../../helpers'

const props = defineProps({
  dialogVisible: {
    type: Boolean,
    defualt: false
  }
})

const emit = defineEmits(['closeCashMovementDialog'])

const url = getUrl()
const store = useStorageStore()
const paymentMethods = [
  { id: 3, name: 'Cartão de crédito' },
  { id: 4, name: 'PIX' },
  { id: 1, name: 'Dinheiro' }
]
const recargaFormRef = ref(null)
const loading = ref(false)
const dependents = ref([])
const recargaForm = reactive({
  account_id: null,
  amount: null,
  payment_method_id: null,
  amount_entry: null,
  money_change: '0,00'
})
const moneyChangeShow = ref(false)
const sangriaFormRef = ref(null)
const sangriaForm = reactive({
  cashier_id: null,
  movement_type_id: 3,
  description: null,
  amount: null,
  balance: null
})
const trocoFormRef = ref(null)
const trocoForm = reactive({
  cashier_id: null,
  movement_type_id: 4,
  amount: null,
  balance: null
})
const pixShow = ref(false)
const checkout = ref(null)

const handlePixShow = () => {
  recargaForm.payment_method_id = null
  pixShow.value = false
}

const copyCode = async () => {
  try {
    await navigator.clipboard.writeText(checkout.value.payment_response[0].text)
    ElNotification({
      title: 'Sucesso !',
      message: 'Código copiado com sucesso !',
      type: 'success',
    })
  } catch (error) {
    ElNotification({
      title: 'Erro !',
      message: 'Ocorreu algum erro ao copiar o código !',
      type: 'error',
    })
  }
}

const handleGetCashier = async () => {
  try {
    const { data } = await axios.get(`${url}/api/v1/cashiers/${store.cashier.id}`)
    store.cashier = data.data
    sangriaForm.cashier_id = store.cashier.id
    sangriaForm.balance = floatToMoney(store.cashier.balance)
    sangriaForm.amount = null
    trocoForm.cashier_id = store.cashier.id
    trocoForm.balance = floatToMoney(store.cashier.balance)
    trocoForm.amount = null
  } catch ({ response }) {
    ElNotification({
      title: 'Erro !',
      message: response.data.message,
      type: 'error',
    })
  }
}

const openedDialog = () => {
  recargaForm.account_id = null
  recargaForm.amount = null
  recargaForm.payment_method_id = null
  recargaForm.amount_entry = null
  recargaForm.money_change = '0,00'
  pixShow.value = false
  handleGetCashier()
}

const remoteMethod = async (query) => {
  try {
    if (query) {
      loading.value = true
      const { data } = await axios.get(`${url}/api/v1/dependents`, { params: { store_id: store.store.id, search: query } })
      dependents.value = data.data
      loading.value = false
    }
  } catch ({ message }) {
    ElNotification({
      title: 'Erro !',
      message: response.data.message,
      type: 'error',
    })
  }
}

const handleChangeRadio = async () => {
  if (recargaForm.payment_method_id == 1) {
    recargaForm.amount_entry = recargaForm.amount
    recargaForm.money_change = '0,00'
    moneyChangeShow.value = true
  } else if (recargaForm.payment_method_id == 4) {
    if (!recargaForm.account_id || !recargaForm.amount) {
      ElNotification({
        title: 'Erro !',
        message: 'Preencha todos os campos obrigatórios !',
        type: 'error',
      })
      recargaForm.payment_method_id = null
      return
    }
    try {
      const form = {
        cashier_id: store.cashier.id,
        amount: recargaForm.amount,
        payment_method_id: recargaForm.payment_method_id
      }
      const { data } = await axios.post(`${url}/api/v1/accounts/${recargaForm.account_id}/credit-purchases`, form)
      checkout.value = data.data
      pixShow.value = true
    } catch (error) {
      let msg = null
      if (error.response) {
        const response = error.response
        if (response.status === 422) {
          const data = response.data.data
          const property = Object.keys(data)[0]
          msg = data[property][0]
        } else {
          msg = response.data.message
        }
      } else {
        msg = error.message
      }
      ElNotification({
        title: 'Erro !',
        message: msg,
        type: 'error',
      })
    }
  }
  else {
    recargaForm.amount_entry = null
    recargaForm.money_change = '0,00'
    moneyChangeShow.value = false
  }
}

const moneyChangeEvent = () => {
  const amount_entry = moneyToFloat(recargaForm.amount_entry)
  const amount = moneyToFloat(recargaForm.amount)
  if (amount_entry > amount) {
    recargaForm.money_change = floatToMoney(amount_entry - amount)
  } else {
    recargaForm.money_change = '0,00'
  }
}

const handleRecargaSubmit = (formEl) => {
  if (!formEl) return
  formEl.validate(async (valid) => {
    if (valid) {
      try {
        if (moneyToFloat(recargaForm.amount) < 1) {
          ElNotification({
            title: 'Erro !',
            message: 'Valor da recarga não pode ser menor que 1 !',
            type: 'error',
          })
          return
        } else if (recargaForm.payment_method_id == 1 && moneyToFloat(recargaForm.amount_entry) < moneyToFloat(recargaForm.amount)) {
          ElNotification({
            title: 'Erro !',
            message: 'Valor não pode ser menor que valor da recarga !',
            type: 'error',
          })
          return
        }
        const form = {
          cashier_id: store.cashier.id,
          amount: recargaForm.amount,
          payment_method_id: recargaForm.payment_method_id
        }
        const { data } = await axios.post(`${url}/api/v1/accounts/${recargaForm.account_id}/credit-purchases`, form)
        emit('closeCashMovementDialog')

        store.customDialogVisible = true
        store.customDialogMessage = `Recarga de <b>R$ ${floatToMoney(recargaForm.amount)}</b> inserido com sucesso.`
      } catch (error) {
        let msg = null
        if (error.response) {
          const response = error.response
          if (response.status === 422) {
            const data = response.data.data
            const property = Object.keys(data)[0]
            msg = data[property][0]
          } else {
            msg = response.data.message
          }
        } else {
          msg = error.message
        }
        ElNotification({
          title: 'Erro !',
          message: msg,
          type: 'error',
        })
      }
    } else {
      return false
    }
  })
}

const handleSangriaAmountChange = () => {
  let amount = moneyToFloat(sangriaForm.amount)
  const balance = moneyToFloat(store.cashier.balance)

  if (amount > balance) {
    sangriaForm.amount = floatToMoney(balance)
    sangriaForm.balance = 0
    return
  }

  sangriaForm.balance = !!amount ? floatToMoney(balance - amount) : floatToMoney(balance)
}

const handleSangriaSubmit = (formEl) => {
  if (!formEl) return
  formEl.validate(async (valid) => {
    if (moneyToFloat(sangriaForm.amount) < 1) {
      ElNotification({
        title: 'Aviso !',
        message: 'Valor de sangria minímo é R$ 1 !',
        type: 'warning',
      })
      return
    }
    if (valid) {
      try {
        const { data } = await axios.post(`${url}/api/v1/cash-movements`, sangriaForm)
        store.cashier = data.data
        emit('closeCashMovementDialog')
        store.customDialogVisible = true
        store.customDialogMessage = `Sangria de <b>R$ ${floatToMoney(sangriaForm.amount)}</b> inserido com sucesso.`
      } catch (error) {
        let msg = null
        if (error.response) {
          const response = error.response
          if (response.status === 422) {
            const data = response.data.data
            const property = Object.keys(data)[0]
            msg = data[property][0]
          } else {
            msg = response.data.message
          }
        } else {
          msg = error.message
        }
        ElNotification({
          title: 'Erro !',
          message: msg,
          type: 'error',
        })
      }
    } else {
      return false
    }
  })
}

const handleTrocoAmountChange = () => {
  const amount = moneyToFloat(trocoForm.amount)
  const balance = moneyToFloat(store.cashier.balance)
  trocoForm.balance = !!amount ? floatToMoney(balance + amount) : floatToMoney(balance)
}

const handleTrocoSubmit = (formEl) => {
  if (!formEl) return
  formEl.validate(async (valid) => {
    if (moneyToFloat(trocoForm.amount) < 1) {
      ElNotification({
        title: 'Aviso !',
        message: 'Valor de troco minímo é R$ 1 !',
        type: 'warning',
      })
      return
    }
    if (valid) {
      try {
        const { data } = await axios.post(`${url}/api/v1/cash-movements`, trocoForm)
        store.cashier = data.data
        emit('closeCashMovementDialog')
        store.customDialogVisible = true
        store.customDialogMessage = `Troco de <b>R$ ${floatToMoney(trocoForm.amount)}</b> inserido com sucesso.`
      } catch (error) {
        let msg = null
        if (error.response) {
          const response = error.response
          if (response.status === 422) {
            const data = response.data.data
            const property = Object.keys(data)[0]
            msg = data[property][0]
          } else {
            msg = response.data.message
          }
        } else {
          msg = error.message
        }
        ElNotification({
          title: 'Erro !',
          message: msg,
          type: 'error',
        })
      }
    } else {
      return false
    }
  })
}

const handlePixConfirm = () => {
  emit('closeCashMovementDialog')
  ElNotification({
    title: 'Sucesso !',
    message: 'Recarga realizada com sucesso !',
    type: 'success',
  })
}

</script>