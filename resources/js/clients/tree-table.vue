<template>
  <el-table :data="tableData" :border="false" size="small" style="width: 100%;">
    <el-table-column type="expand" :width="25">
      <template #default="scope">
        <Dependents :table-data="scope.row.dependents" v-if="scope.row.dependents.length"></Dependents>
      </template>
    </el-table-column>
    <el-table-column label="Nome C./Razão S./CPF/CNPJ" prop="info" sortable />
    <el-table-column label="Email" prop="email" sortable />
    <el-table-column label="Status" prop="attr_status" sortable :width="90" />
    <el-table-column label="DT. CRIAC." prop="attr_created_at" sortable :width="110" />
    <el-table-column label="DT. ATUAL." prop="attr_updated_at" sortable :width="110" />
    <el-table-column label="AÇÃO" align="right" :width="70">
      <template #default="scope">
        <DropDown :show="`/clients/${scope.row.id}`" :edit="`/clients/${scope.row.id}/edit/`"
          :destroy="`/api/v1/clients/${scope.row.id}`">
          <el-dropdown-item>
            <el-link type="info" :href="`${url}/clients/${scope.row.id}/dependents/create`" :underline="false">
              Criar Consumidor
            </el-link>
          </el-dropdown-item>
        </DropDown>
      </template>
    </el-table-column>
  </el-table>
</template>

<script setup>
import Dependents from './dependents.vue'
import DropDown from './dropdown.vue'
const tableData = window.clients
const url = getUrl();
</script>
