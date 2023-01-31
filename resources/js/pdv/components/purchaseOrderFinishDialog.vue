<template>
  <el-dialog v-model="dialogVisible"
    width="580"
    :close-on-click-modal="false"
    :close-on-press-escape="false"
    :show-close="false"
    align-center
    destroy-on-close
    @open="openedDialog">
    <template #header>
      <div style="display: flex;
      flex-direction: column;
      align-items: flex-start;
      padding: 10px 30px 0 30px;">
        <h4 style="font-weight: 600; margin: 0;">
          Forma de pagamento
          <hr style="background: #ff7e07; margin: 5px 0 0 0">
        </h4>
      </div>
    </template>

    <template #footer>
      <div class="dialog-footer"
        style="text-align: center;">
        <el-button @click="$emit('close-dialog')">
          Cancelar
        </el-button>
        <el-button color="#ff7e07"
          style="color: white;">
          Confirmar
        </el-button>
      </div>
    </template>
  </el-dialog>
</template>

<script setup>
const props = defineProps({
  dialogVisible: {
    type: Boolean,
    required: true
  }
})

const emit = defineEmits(['closeDialog'])

const openedDialog = () => {

}

</script>