<template>
  <el-button link type="info" @click="dialogVisible = true" style="padding: 0;">
    Deletar
  </el-button>

  <el-dialog v-model="dialogVisible" title="Atenção !" width="30%" :show-close="false">
    <span>Tem certeza que deseja deletar este item?</span>
    <template #footer>
      <span class="dialog-footer">
        <el-button type="primary" @click="dialogVisible = false">Cancelar</el-button>
        <el-button type="danger" @click="onSubmit">Confirmar</el-button>
      </span>
    </template>
  </el-dialog>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'

const props = defineProps(['action'])
const dialogVisible = ref(false)

function onSubmit() {
  axios.delete(props.action)
    .then(res => {
      dialogVisible.value = false
      ElMessageBox({
        title: 'Sucesso',
        message: 'Registro deletado com sucesso.',
        confirmButtonText: 'OK',
        type: 'success',
        autofocos: true,
        showClose: false,
        closeOnClickModal: false,
        closeOnPressEscape: false,
      })
        .then(() => document.location.reload(true))
    })
    .catch(error => {
      dialogVisible.value = false
      ElMessageBox({
        title: 'Erro',
        message: 'Registro vinculado á outra tabela, somente poderá ser excluído se retirar o vinculo.',
        confirmButtonText: 'OK',
        type: 'error',
        autofocos: true,
        showClose: false,
        closeOnClickModal: false,
        closeOnPressEscape: false,
      })
    })
}
</script>