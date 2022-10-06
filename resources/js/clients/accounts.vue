<template>
  <el-table :data="tableData" :border="false" size="small" style="width: 100%;" :show-header="false"
    :row-style="rowStyle">
    <el-table-column :width="24"></el-table-column>
    <el-table-column type="expand" :width="25">

      <template #default="props">
        <Cards :table-data="props.row.cards" v-if="props.row.cards.length"></Cards>
      </template>

    </el-table-column>
    <el-table-column label="Saldo" prop="attr_balance" sortable />
    <el-table-column label="Limite Diário" prop="attr_daily_limit" sortable />
    <el-table-column label="Turma" prop="class" sortable />
    <el-table-column label="Série" prop="school_year" sortable />
    <el-table-column label="Status" prop="attr_status" sortable :width="110" />
    <el-table-column label="DT. CRIAC." prop="attr_created_at" sortable :width="110" />
    <el-table-column label="DT. ATUAL." prop="attr_updated_at" sortable :width="110" />
    <el-table-column align="right" :width="70">
      <template #default="props">
        <DropDown account="true" :accountCards="'/accounts/' + props.row.id + '/cards'"
          :show="'/dependents/' + props.row.dependent_id + '/accounts/' + props.row.id"
          :edit="'/dependents/' + props.row.dependent_id + '/accounts/' + props.row.id + '/edit'"
          :destroy="'/api/v1/accounts/' + props.row.id">
        </DropDown>
      </template>
    </el-table-column>
  </el-table>
</template>

<script setup>
import Cards from './cards.vue'
import DropDown from './dropdown.vue'
const props = defineProps(['tableData'])

function rowStyle() {
  return {
    background: "#E5EAF3",
  };
}
</script>