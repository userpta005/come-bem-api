<style>
.cash-movement-dialog>.el-dialog__header {
  display: none;
}

.cash-movement-dialog>.el-dialog__body {
  padding: 20px;
}

.cash-movement-tabs>.el-tabs__content {
  height: 500px;
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
    class="cash-movement-dialog">

    <el-tabs type="border-card"
      class="cash-movement-tabs">

      <el-tab-pane>
        <template #label>
          <span style="font-size: large; color: black;">Recarga</span>
        </template>
        Route

        <div style="text-align: center;">
          <el-button @click="$emit('closeCashMovementDialog')">
            Cancelar
          </el-button>
          <el-button color="#ff7e07"
            style="color: white;"
            @click="handleSubmit(formRef)">
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
const sangriaFormRef = ref(null)
const sangriaForm = reactive({
  cashier_id: store.cashier.id,
  movement_type_id: 3,
  amount: null,
  balance: null
})
const trocoFormRef = ref(null)
const trocoForm = reactive({
  cashier_id: store.cashier.id,
  movement_type_id: 4,
  amount: null,
  balance: null
})

const handleGetCashier = async () => {
  try {
    const { data } = await axios.get(`${url}/api/v1/cashiers/${store.cashier.id}`)
    store.cashier = data.data
    sangriaForm.balance = floatToMoney(store.cashier.balance)
    sangriaForm.amount = null
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
  handleGetCashier()
}

const handleSangriaAmountChange = () => {
  const amount = moneyToFloat(sangriaForm.amount)
  const balance = moneyToFloat(store.cashier.balance)
  sangriaForm.balance = !!amount ? floatToMoney(balance - amount) : floatToMoney(balance)
}

const handleSangriaSubmit = (formEl) => {
  if (!formEl) return
  formEl.validate(async (valid) => {
    if (valid) {
      try {
        const { data } = await axios.post(`${url}/api/v1/cash-movements`, sangriaForm)
        store.cashier = data.data
        emit('closeCashMovementDialog')
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

const handleTrocoAmountChange = () => {
  const amount = moneyToFloat(trocoForm.amount)
  const balance = moneyToFloat(store.cashier.balance)
  trocoForm.balance = !!amount ? floatToMoney(balance + amount) : floatToMoney(balance)
}

const handleTrocoSubmit = (formEl) => {
  if (!formEl) return
  formEl.validate(async (valid) => {
    if (valid) {
      try {
        const { data } = await axios.post(`${url}/api/v1/cash-movements`, trocoForm)
        store.cashier = data.data
        emit('closeCashMovementDialog')
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