<style>
.purchase-order-finish-dialog {
  padding: 10px 20px;
}
</style>

<template>
  <el-dialog class="purchase-order-finish-dialog"
    v-model="dialogVisible"
    width="470"
    :close-on-click-modal="false"
    :close-on-press-escape="false"
    :show-close="false"
    align-center
    destroy-on-close
    @open="openedDialog">
    <template #header>
      <div style="display: flex; flex-direction: column; align-items: flex-start;">
        <h4 style="font-weight: 600; margin: 0;">
          Forma de pagamento
          <hr style="background: #ff7e07; margin: 5px 0 0 0">
        </h4>
      </div>
    </template>
    <template #default>
      <div style="display: flex; justify-content: space-between; align-items: center;">
        <el-form label-position="top"
          style="width: 60%;">
          <el-form-item>
            <template #label>
              <span style="color: black; font-size: 16px">Consumidor<span style="color: red;">*</span></span>
            </template>
            <el-select v-model="form.account_id"
              size="large"
              clearable
              filterable
              remote
              placeholder="Digite o nome ou cpf"
              :remote-method="remoteMethod"
              :loading="loading"
              @change="getDependent">
              <el-option v-for="item in dependents"
                :key="item.value"
                :label="item.people.info"
                :value="item.accounts[0].id" />
            </el-select>
          </el-form-item>
        </el-form>
        <span v-if="!!dependent"
          style="color: black; margin-top: 13px">
          Crédito (R$): {{ floatToMoney(!!dependent? dependent.accounts[0].balance : 0) }}
        </span>
      </div>
      <hr style="margin: 0 0 20px 0;" />
      <div style="display: flex; justify-content: center; flex-wrap: wrap; gap: 10px 25px;">
        <el-button size="large"
          style="border-color: #a6a9ad; width: 180px; height: 50px; margin: 0; cursor: pointer;"
          :style="{
            backgroundColor: item.id == form.payment_method_id ? '#2474fd' : '#f6f6f6',
            color: item.id == form.payment_method_id ? 'white' : 'black'
          }"
          @click="paymentMethodChange(item)"
          v-for="item in paymentMethods"
          :key="item.id"
          v-show="(item.id == 5 && !!dependent && dependent.client.type != 3) || item.id != 5">
          {{ item.name }}
        </el-button>
      </div>
      <hr style="margin: 20px 0;" />
      <div style="display: flex; flex-direction: column; gap: 5px;">
        <div v-if="form.payment_method_id == 1"
          style="display: flex; align-items: center; justify-content: space-between;">
          <label style="font-size: 14px; color: black;">
            Valor (R$)
            <span style="color: red;">*</span>
          </label>
          <input type="text"
            style="height: 40px; border: 0; border-radius: 10px; background-color: #ebebeb; width: 120px; text-align: center;"
            v-maska
            data-maska="9.99#,##"
            data-maska-reversed
            data-maska-tokens="9:[0-9]:repeated"
            v-model="form.amount_entry">
        </div>
        <div style="display: flex; align-items: center; justify-content: space-between;">
          <span style="font-size: 14px; color: black;">
            Total do pedido (R$)
          </span>
          <span type="text"
            style="height: 40px; border: 0; width: 120px; color: black; display: flex; justify-content:center; align-items: center;"
            disabled>{{ floatToMoney(total) }}</span>
        </div>
        <div v-if="form.payment_method_id == 1"
          style="display: flex; align-items: center; justify-content: space-between;">
          <label style="font-size: 14px; color: black;">
            Troco (R$)
          </label>
          <span type="text"
            style="height: 40px; border: 0; width: 120px; color: black; display: flex; justify-content:center; align-items: center;">
            {{ floatToMoney(moneyChange) }}
          </span>
        </div>
        <div v-if="form.payment_method_id == 5"
          style="display: flex; align-items: center; justify-content: space-between;">
          <label style="font-size: 14px; color: black;">
            Saldo (R$)
          </label>
          <span type="text"
            style="height: 40px; border: 0; width: 120px; color: black; display: flex; justify-content:center; align-items: center;">
            {{ floatToMoney(balance) }}
          </span>
        </div>
      </div>
    </template>
    <template #footer>
      <div class="dialog-footer"
        style="text-align: center;">
        <el-button @click="$emit('closeDialog')">
          Cancelar
        </el-button>
        <el-button color="#ff7e07"
          style="color: white;"
          @click="handleSubmit">
          Confirmar
        </el-button>
      </div>
    </template>
  </el-dialog>
</template>

<script setup>
import { ref, computed } from 'vue'
import api from '../../api'
import useStorageStore from '../stores/storage'
import { floatToMoney, moneyToFloat } from '../../helpers'
import { vMaska } from 'maska'

const props = defineProps({
  dialogVisible: {
    type: Boolean,
    required: true
  },
  accountId: {
    type: [Number, String],
    required: false
  }
})

const emit = defineEmits(['closeDialog'])

const store = useStorageStore()
const loading = ref(false)
const dependents = ref([])
const dependent = ref(null)
const form = ref({
  account_id: null,
  payment_method_id: 1,
  amount_entry: null,
  cart: []
})
const paymentMethods = ref([
  { id: 1, name: 'Dinheiro' },
  { id: 4, name: 'Pix' },
  { id: 3, name: 'Cartão de Crédito' },
  { id: 2, name: 'Cartão de Débito' },
  { id: 5, name: 'Crédito' }
])

const total = computed(() => {
  let vlTotal = 0
  form.value.cart.forEach(item => {
    vlTotal += (item.price * item.quantity)
  })
  return vlTotal
})

const moneyChange = computed(() => {
  return !!form.value.amount_entry && moneyToFloat(form.value.amount_entry) > total.value ?
    moneyToFloat(form.value.amount_entry) - total.value : 0
})

const balance = computed(() => {
  return dependent.value.accounts[0].balance - total.value
})

const paymentMethodChange = (paymentMethod) => {
  form.value.payment_method_id = paymentMethod.id
}

const remoteMethod = async (query) => {
  try {
    if (query) {
      loading.value = true
      const { data } = await api({
        url: '/api/v1/dependents',
        params: { store_id: store.store.id, search: query }
      })
      dependents.value = data
      loading.value = false
    } else {
      dependents.value = []
    }
  } catch (error) {
    ElNotification({
      title: 'Erro !',
      message: error,
      type: 'error',
    })
  }
}

const getDependent = async () => {
  try {
    if (!!form.value.account_id) {
      const { data } = await api({
        url: `/api/v1/accounts/${form.value.account_id}`
      })
      data.dependent.accounts = data.dependent.accounts.filter(item => item.id == form.value.account_id)
      dependents.value.push(data.dependent)
      dependent.value = data.dependent
    } else {
      dependent.value = null
    }
  } catch (error) {
    ElNotification({
      title: 'Erro !',
      message: error,
      type: 'error',
    })
  }
}

const openedDialog = () => {
  form.value.account_id = props.accountId
  form.value.cart = store.cart
  if (!!form.value.account_id) {
    getDependent()
  }
}

const handleSubmit = async () => {
  try {
    if ([3, 4].includes(form.value.payment_method_id)) {
      return
    } else if (!form.value.account_id) {
      ElNotification({
        title: 'Aviso !',
        message: 'Consumidor é obrigatório !',
        type: 'warning',
      })
      return
    } else if (form.value.payment_method_id == 1 && !form.value.amount_entry) {
      ElNotification({
        title: 'Aviso !',
        message: 'Valor é obrigatório !',
        type: 'warning',
      })
      return
    }

    const data = await api({
      method: 'post',
      url: `/api/v1/accounts/${form.value.account_id}/pdv-orders`,
      data: {
        cashier_id: store.cashier.id,
        payment_method_id: form.value.payment_method_id,
        products: form.value.cart
      }
    })

    emit('closeDialog')
    ElNotification({
      title: 'Sucesso !',
      message: data.message,
      type: 'success',
    })

  } catch (error) {
    ElNotification({
      title: 'Erro !',
      message: error,
      type: 'error',
    })
  }
}

</script>