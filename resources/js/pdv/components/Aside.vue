<style scoped>
.aside-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  margin-top: 20px;
}
</style>

<template>
  <div class="aside-container">
    <div style="display: flex; flex-direction:column; gap: 10px;">
      <el-button color="#2474fd"
        size="large"
        style="width: 300px;
        height: 50px;
        margin: 0;
        font-size: large;
        font-weight: 600;
        align-self: center;"
        @click="cashierDialogVisible = true">
        <el-icon size="25"
          style="margin-right: 10px;">
          <Box />
        </el-icon>
        Abrir/Fechar Caixa
      </el-button>
      <el-button color="#2474fd"
        size="large"
        style="width: 300px;
        height: 50px;
        margin: 0;
        font-size: large;
        font-weight: 600;
        align-self: center;"
        @click="handleCashMovement">
        <el-icon size="25"
          style="margin-right: 10px;">
          <Money />
        </el-icon>
        Movimento do Caixa
      </el-button>
      <PurchaseOrder @cashier-dialog-open="cashierDialogVisible = true" />
    </div>

    <CashierDialog :dialogVisible="cashierDialogVisible"
      @close-cashier-dialog="cashierDialogVisible = false" />

    <CashMovementDialog :dialogVisible="cashMovementDialogVisible"
      @close-cash-movement-dialog="cashMovementDialogVisible = false" />

    <CustomDialog />
  </div>
</template>


<script setup>
import { ref } from 'vue'
import useStorageStore from '../stores/storage'
import CashierDialog from './CashierDialog.vue'
import CashMovementDialog from './CashMovementDialog.vue'
import PurchaseOrder from './PurchaseOrder.vue'
import CustomDialog from './CustomDialog.vue'

const store = useStorageStore()
const cashierDialogVisible = ref(false)
const cashMovementDialogVisible = ref(false)

const handleCashMovement = () => {
  if (!store.openedCashier) {
    cashierDialogVisible.value = true
  } else {
    cashMovementDialogVisible.value = true
  }
}

</script>
