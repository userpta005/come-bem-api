<style scoped>
.aside-container {
  display: flex;
  flex-direction: column;
  align-items: center;
}
</style>

<template>
  <div class="aside-container">
    <el-space direction="vertical"
      size="large">
      <el-button color="#2474fd"
        size="large"
        style="width: 260px;
        height: 50px;
        margin: 0;
        font-size: large;
        font-weight: 600;"
        @click="cashierDialogVisible = true">
        <el-icon size="25"
          style="margin-right: 10px;">
          <Box />
        </el-icon>
        Abrir/Fechar Caixa
      </el-button>
      <el-button color="#2474fd"
        size="large"
        style="width: 260px;
        height: 50px;
        margin: 0;
        font-size: large;
        font-weight: 600;"
        @click="handleCashMovement">
        <el-icon size="25"
          style="margin-right: 10px;">
          <Money />
        </el-icon>
        Movimento do Caixa
      </el-button>
    </el-space>

    <CashierDialog :dialogVisible="cashierDialogVisible"
      @close-cashier-dialog="cashierDialogVisible = false" />
  </div>
</template>


<script setup>
import { ref } from 'vue'
import useStorageStore from '../stores/storage'
import CashierDialog from './CashierDialog.vue'

const store = useStorageStore()

const cashierDialogVisible = ref(false)
const cashMovementDialogVisible = ref(false)

const handleCashMovement = () => {
  if (!store.openedCashier) {
    cashierDialogVisible.value = true
  }
}

</script>
