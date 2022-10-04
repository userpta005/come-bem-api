<template>
  <el-table :data="tableData" :border="false" size="small" style="width: 100%;">
    <el-table-column type="expand" :width="25">
      <template #default="props">
        <Phases :phases="props.row.phases" :rowStyle="rowStyle" v-if="props.row.phases.length"></Phases>
      </template>
    </el-table-column>
    <el-table-column label="#" :width="30">
      <template #default="props">
        <Status :status="props.row.status"></Status>
      </template>
    </el-table-column>
    <el-table-column label="ANO" prop="year" sortable />
    <el-table-column label="NOME" prop="name" sortable />
    <el-table-column label="CATG." prop="category.name" sortable />
    <el-table-column label="DT. CRIAC." prop="dt_created" sortable :width="110" />
    <el-table-column label="AÇÃO" align="right" :width="70">
      <template #default="props">
        <DropDown competition="true" :show="'/competitions/' + props.row.id"
          :competition-phases="'/competitions/' + props.row.id + /phases/"
          :competition-classifications="'/competitions/' + props.row.id + /classifications/"
          :edit="'/competitions/' + props.row.id + /edit/" :destroy="'/api/competitions/' + props.row.id">
        </DropDown>
      </template>
    </el-table-column>
  </el-table>
</template>

<script setup>
import Phases from './phases.vue'
import DropDown from './dropdown.vue'
import Status from './status.vue'
const tableData = window.competitions

function rowStyle() {
  return {
    background: "#E5EAF3",
  };
}
</script>
  