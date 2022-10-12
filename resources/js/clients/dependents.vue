<template>
  <el-table :data="tableData" :border="false" size="small" style="width: 100%;" :show-header="false"
    :row-style="rowStyle">
    <el-table-column :width="12"></el-table-column>
    <el-table-column type="expand" :width="25">
      <template #default="scope">
        <Accounts :table-data="scope.row.accounts" v-if="scope.row.accounts.length"></Accounts>
      </template>
    </el-table-column>
    <el-table-column label="Nome Completo/CPF" prop="info" sortable />
    <el-table-column label="Email" prop="email" sortable />
    <el-table-column label="Status" prop="attr_status" sortable :width="90" />
    <el-table-column label="DT. CRIAC." prop="attr_created_at" sortable :width="110" />
    <el-table-column label="DT. ATUAL." prop="attr_updated_at" sortable :width="110" />
    <el-table-column align="right" :width="70">
      <template #default="scope">
        <DropDown :show="`/clients/${scope.row.client_id}/dependents/${scope.row.id}`"
          :edit="`/clients/${scope.row.client_id}/dependents/${scope.row.id}/edit`"
          :destroy="`/api/v1/dependents/${scope.row.id}`">
          <el-dropdown-item>
            <el-link type="info" :href="`${url}/dependents/${scope.row.id}/accounts/create`" :underline="false">
              Criar Conta
            </el-link>
          </el-dropdown-item>
        </DropDown>
      </template>
    </el-table-column>
  </el-table>
</template>

<script setup>
import Accounts from './accounts.vue'
import DropDown from './dropdown.vue'
const props = defineProps(['tableData'])
const url = getUrl();

function rowStyle() {
  return {
    background: "#E5EAF3",
  };
}
</script>