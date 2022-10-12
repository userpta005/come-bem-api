<template>
  <el-table :data="tableData" :border="false" size="small" style="width: 100%;" :show-header="false" :row-style="rowStyle">
    <el-table-column :width="50"></el-table-column>
    <el-table-column label="UUID" prop="uuid" sortable >
      <template #default="scope">
        <span><b>UUID: </b>{{ scope.row.uuid }}</span>
      </template>
    </el-table-column>
    <el-table-column label="Status" prop="attr_status" sortable :width="90" />
    <el-table-column label="DT. CRIAC." prop="attr_created_at" sortable :width="110" />
    <el-table-column label="DT. ATUAL." prop="attr_updated_at" sortable :width="110" />
    <el-table-column align="right" :width="70">
      <template #default="scope">
        <DropDown :show="`/accounts/${scope.row.account_id}/cards/${scope.row.id}`"
          :edit="`/accounts/${scope.row.account_id}/cards/${scope.row.id}/edit`"
          :destroy="`/api/v1/cards/${scope.row.id}`">
        </DropDown>
      </template>
    </el-table-column>
  </el-table>
</template>

<script setup>
import DropDown from './dropdown.vue'
const props = defineProps(['tableData'])

function rowStyle() {
  return {
    background: "#E5EAF3",
  };
}
</script>