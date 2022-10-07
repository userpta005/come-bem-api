<template>
  <el-table :data="tableData" :border="false" size="small" style="width: 100%;" :show-header="false"
    :row-style="rowStyle">
    <el-table-column :width="24"></el-table-column>
    <el-table-column type="expand" :width="25">

      <template #default="props">
        <Cards :table-data="props.row.cards" v-if="props.row.cards.length"></Cards>
      </template>

    </el-table-column>
    <el-table-column label="Saldo" prop="attr_balance" sortable>
      <template #default="props">
        <span><b>Saldo: </b>{{ props.row.attr_balance }}</span>
      </template>
    </el-table-column>
    <el-table-column label="Limite Diário" prop="attr_daily_limit" sortable>
      <template #default="props">
        <span><b>Limite/Dia: </b>{{ props.row.attr_daily_limit }}</span>
      </template>
    </el-table-column>
    <el-table-column label="Turma" prop="class" sortable>
      <template #default="props">
        <span><b>Turma: </b>{{ props.row.class }}</span>
      </template>
    </el-table-column>
    <el-table-column label="Série" prop="school_year" sortable>
      <template #default="props">
        <span><b>Série: </b>{{ props.row.school_year }}</span>
      </template>
    </el-table-column>
    <el-table-column label="Status" prop="attr_status" sortable :width="110" />
    <el-table-column label="DT. CRIAC." prop="attr_created_at" sortable :width="110" />
    <el-table-column label="DT. ATUAL." prop="attr_updated_at" sortable :width="110" />
    <el-table-column align="right" :width="70">
      <template #default="props">
        <DropDown :show="'/dependents/' + props.row.dependent_id + '/accounts/' + props.row.id"
          :edit="'/dependents/' + props.row.dependent_id + '/accounts/' + props.row.id + '/edit'"
          :destroy="'/api/v1/accounts/' + props.row.id">
          <el-dropdown-item>
            <el-link type="info" :href="url + '/accounts/' + props.row.id + '/cards'" :underline="false">
              Cartões
            </el-link>
          </el-dropdown-item>
        </DropDown>
      </template>
    </el-table-column>
  </el-table>
</template>

<script setup>
import Cards from './cards.vue'
import DropDown from './dropdown.vue'
const props = defineProps(['tableData'])
const url = getUrl();

function rowStyle() {
  return {
    background: "#E5EAF3",
  };
}
</script>