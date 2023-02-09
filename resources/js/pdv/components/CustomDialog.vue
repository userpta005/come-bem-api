<style scoped>
.dialog-footer button:first-child {
  margin-right: 10px;
}
</style>

<template>
  <el-dialog v-model="store.customDialogVisible"
    width="30%"
    destroy-on-close
    center>
    <template #header>
      <div style="display: flex; justify-content: center;">
        <div style="border: 5px solid black; border-radius: 100px; padding: 10px;">
          <el-icon size="80px"
            color="black">
            <Check />
          </el-icon>
        </div>
      </div>
    </template>
    <div v-html="store.customDialogMessage"
      style="color:black; font-size: 20px; text-align: center;">
    </div>
    <template #footer>
      <span class="dialog-footer">
        <el-button @click="store.customDialogVisible = false">Fechar</el-button>
      </span>
    </template>
  </el-dialog>
</template>

<script setup>
import useStorageStore from '../stores/storage'

const store = useStorageStore()
</script>