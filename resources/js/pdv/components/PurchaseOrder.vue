<style>
.purchase-order {
  width: 300px;
  min-height: 500px;
  border: 1px solid orange;
  border-radius: 10px;
  padding: 15px;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}

.purchase-order-title {
  color: black;
  font-size: 20px;
  text-align: center;
  font-weight: bold;
}
</style>

<template>
  <div class="purchase-order">
    <div>
      <div class="purchase-order-title">
        Pedido
        <hr>
      </div>
      <el-form label-position="top">
        <el-form-item>
          <template #label>
            <span style="color: black; font-size: 16px">Consumidor</span>
          </template>
          <el-select v-model="accountId"
            size="large"
            filterable
            clearable
            remote
            placeholder="Digite o nome ou cpf"
            :remote-method="remoteMethod"
            :loading="loading">
            <el-option v-for="item in dependents"
              :key="item.value"
              :label="item.people.info"
              :value="item.accounts[0].id" />
          </el-select>
        </el-form-item>
      </el-form>
      <div>
        <span style="color: black; font-size: 16px;">itens do pedido</span>
        <hr style="margin: 5px 0 10px 0;">
      </div>
      <div style="color: black; font-size: 14px;">
        <div v-for="product in store.cart">
          <div>{{ product.name }}</div>
          <div style="display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center;">
              <el-icon color="green"
                size="1.2rem"
                style="border: 1px solid green; border-radius: 10px; cursor: pointer;"
                @click="() => { if (product.quantity > 1) product.quantity-- }">
                <Minus />
              </el-icon>
              <span style="margin: 0 15px;">{{ product.quantity }}</span>
              <el-icon color="green"
                size="1.2rem"
                style="border: 1px solid green; border-radius: 10px; cursor: pointer;"
                @click="product.quantity++">
                <Plus />
              </el-icon>
            </div>
            <div style="display: flex; align-items: center;">
              <span style="margin-right: 8px;">R$ {{ floatToMoney(product.price * product.quantity) }}</span>
              <el-icon color="grey"
                size="1.2rem"
                style="cursor: pointer;"
                @click="deleteProduct(product)">
                <Delete />
              </el-icon>
            </div>
          </div>
          <hr style="margin: 10px 0;">
        </div>
      </div>
    </div>
    <div>
      <hr>
      <div style="display: flex; justify-content: space-between; color: black; margin-bottom:10px;">
        <span>Total(R$):</span>
        <span>{{ floatToMoney(total) }}</span>
      </div>
      <el-button size="large"
        style="float: left;">
        Cancelar
      </el-button>
      <el-button color="#2474fd"
        style="color: white; float: right;"
        size="large"
        @click="purchaseOrderFinish">
        Confirmar
      </el-button>
    </div>
  </div>

  <purchaseOrderFinishDialog :account-id="accountId"
    :dialog-visible="purchaseOrderFinishDialogVisible"
    @close-dialog="purchaseOrderFinishDialogVisible = false" />
</template>

<script setup>
import { ref, computed } from 'vue'
import useStorageStore from '../stores/storage'
import api from '../../api'
import { floatToMoney } from '../../helpers'
import purchaseOrderFinishDialog from './purchaseOrderFinishDialog.vue'

const emit = defineEmits(['cashierDialogOpen'])

const store = useStorageStore()
const loading = ref(false)
const dependents = ref([])
const accountId = ref(null)
const purchaseOrderFinishDialogVisible = ref(false)

const total = computed(() => {
  let vlTotal = 0
  store.cart.forEach(item => {
    vlTotal += (item.price * item.quantity)
  })
  return vlTotal
})

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

const deleteProduct = (product) => {
  store.cart = store.cart.filter(item => item.id != product.id)
}

const purchaseOrderFinish = () => {
  if (!store.cart.length) {
    ElNotification({
      title: 'Aviso !',
      message: 'Carrinho vazio !',
      type: 'warning',
    })
    return
  } else if (!store.openedCashier) {
    emit('cashierDialogOpen')
    return
  }
  purchaseOrderFinishDialogVisible.value = true
}

</script>