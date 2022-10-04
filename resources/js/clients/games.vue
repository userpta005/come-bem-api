<template>
  <el-table :data="games" :border="false" size="small" style="width: 100%;" :show-header="false" :row-style="rowStyle">
    <el-table-column :width="50"></el-table-column>
    <el-table-column :width="30">
      <template #default="props">
        <Status :status="props.row.status"></Status>
      </template>
    </el-table-column>
    <el-table-column prop="dt_match" :width="130" />
    <el-table-column prop="place_name" />
    <el-table-column prop="principal" />
    <el-table-column prop="visitor" />
    <el-table-column align="right" :width="70">
      <template #default="props">
        <DropDown game="true" :show="'/games/' + props.row.id" :edit="'/games/' + props.row.id + /edit/"
          :gameRefereesCreate="'/games/' + props.row.id + '/referees/create'"
          :gameDocumentsCreate="'/games/' + props.row.id + '/documents/create'"
          :gameResultsCreate="'/games/' + props.row.id + '/results/create'" :destroy="'/api/games/' + props.row.id">
        </DropDown>
      </template>
    </el-table-column>
  </el-table>
</template>

<script setup>
import Status from './status.vue'
import DropDown from './dropdown.vue'
const props = defineProps(['games', 'rowStyle'])
</script>