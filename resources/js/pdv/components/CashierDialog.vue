<template>
  <el-dialog v-model="dialogVisible"
    width="580"
    :close-on-click-modal="false"
    :close-on-press-escape="false"
    :show-close="false"
    align-center
    @open="openedDialog"
    destroy-on-close>
    <template #header>
      <div style="display: flex;
      flex-direction: column;
      align-items: flex-start;
      padding: 10px 30px 0 30px;">
        <h4 style="font-weight: 600;
        margin: 0;">
          {{ form.operation == 1 ? 'Abertura' : 'Fechamento' }}
          <hr style="background: #ff7e07;
          margin: 5px 0 0 0">
        </h4>
      </div>
    </template>

    <el-form label-position="top"
      style="padding: 0 30px;"
      :inline="true"
      ref="formRef"
      :model="form"
      require-asterisk-position="right">
      <el-row>
        <el-col :span="8">
          <el-form-item label="Caixa"
            style="margin: 0 18px 18px 0;">
            <el-select v-model="form.cashier_id"
              size="large"
              :disabled="!!store.openedCashier"
              @change="handleCashierChange">
              <el-option v-for="item in cashiers"
                :key="item.id"
                :label="item.description"
                :value="item.id" />
            </el-select>
          </el-form-item>
        </el-col>
        <el-col :span="16">
          <el-form-item label="Usuário"
            style="margin: 0 18px 18px 0;">
            <el-input v-model="form.name"
              size="large"
              disabled />
          </el-form-item>
        </el-col>
        <el-col :span="8">
          <el-form-item label="Operação"
            style="margin: 0 18px 18px 0;">
            <el-select v-model="form.operation"
              size="large"
              disabled>
              <el-option v-for="item in operations"
                :key="item.id"
                :label="item.name"
                :value="item.id" />
            </el-select>
          </el-form-item>
        </el-col>
        <el-col :span="8">
          <el-form-item :label="form.operation == 1 ? 'Troco (R$)' : 'Sangria (R$)'"
            style="margin: 0 18px 18px 0;"
            prop="money_change"
            :rules="[{ required: true, message: form.operation == 1 ? 'Troco é obrigatório !' : 'Sangria é obrigatória !' }]">
            <el-input v-model="form.money_change"
              size="large"
              autocomplete="off"
              v-maska
              data-maska="9.99#,##"
              data-maska-reversed
              data-maska-tokens="9:[0-9]:repeated"
              @change="handleCashierChange" />
          </el-form-item>
        </el-col>
        <el-col :span="8">
          <el-form-item label="Saldo (R$)"
            style="margin: 0 18px 18px 0;">
            <el-input v-model="form.balance"
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

    <template #footer>
      <div class="dialog-footer"
        style="text-align: center;">
        <el-button @click="$emit('closeCashierDialog')">
          Cancelar
        </el-button>
        <el-button color="#ff7e07"
          style="color: white;"
          @click="handleSubmit(formRef)">
          Confirmar
        </el-button>
      </div>
    </template>
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

const emit = defineEmits(['closeCashierDialog'])

const url = getUrl()
const store = useStorageStore()
const cashiers = ref([])
const operations = [
  { id: 1, name: 'Abertura' },
  { id: 2, name: 'Fechamento' }
]
const formRef = ref(null)
const form = reactive({
  cashier_id: null,
  user_id: null,
  name: null,
  operation: null,
  money_change: null,
  balance: null
})

const handleCashierChange = () => {
  const cashier = cashiers.value.find(item => item.id === form.cashier_id)
  const moneyChange = moneyToFloat(form.money_change)
  const balance = moneyToFloat(cashier.balance)
  form.operation = cashier.status == 1 ? 2 : 1
  form.balance = floatToMoney(cashier.status == 1 ? balance - moneyChange : balance + moneyChange)
}

const handleGetCashiers = async () => {
  try {
    const { data } = await axios.get(`${url}/api/v1/cashiers`)
    cashiers.value = data.data
    form.user_id = store.user.id
    form.name = store.user.name
    form.money_change = null
    if (!!store.openedCashier) {
      store.cashier = cashiers.value.find(item => item.id == store.cashier.id)
      form.cashier_id = store.cashier.id
      form.operation = store.cashier.status == 1 ? 2 : 1
      form.balance = floatToMoney(store.cashier.balance)
    } else {
      form.cashier_id = cashiers.value[0].id
      form.operation = cashiers.value[0].status == 1 ? 2 : 1
      form.balance = floatToMoney(cashiers.value[0].balance)
    }
  } catch ({ response }) {
    ElNotification({
      title: 'Erro !',
      message: response.data.message,
      type: 'error',
    })
  }
}

const openedDialog = () => {
  handleGetCashiers()
}

const handleSubmit = (formEl) => {
  if (!formEl) return
  formEl.validate(async (valid) => {
    if (valid) {
      try {
        const { data } = await axios.post(`${url}/api/v1/open-cashiers`, form)
        store.cashier = data.data
        store.openedCashier = store.cashier.status == 1 ? true : false

        const elText = document.querySelector('.opened-cashier-span')
        const elBadge = document.querySelector('.opened-cashier-badge')

        elText.innerText = !!store.openedCashier ? store.cashier.description + ' aberto' : 'Caixa fechado'
        elBadge.style.backgroundColor = !!store.openedCashier ? '#92e18b' : '#e18b8b'

        emit('closeCashierDialog')
        ElNotification({
          title: 'Sucesso !',
          message: data.message,
          type: 'success',
        })
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

</script>