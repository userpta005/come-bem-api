<template>
  <el-table :data="phases" :border="false" size="small" style="width: 100%;" :show-header="false" :row-style="rowStyle">
    <el-table-column :width="12"></el-table-column>
    <el-table-column type="expand" :width="25">
      <template #default="props">
        <Rounds :rounds="props.row.rounds" :rowStyle="rowStyle" v-if="props.row.rounds.length"></Rounds>
      </template>
    </el-table-column>
    <el-table-column :width="30">
      <template #default="props">
        <Status :status="props.row.status"></Status>
      </template>
    </el-table-column>
    <el-table-column prop="name" />
    <el-table-column prop="type_name" />
    <el-table-column prop="dt_created" :width="110" />
    <el-table-column align="right" :width="70">
      <template #default="props">
        <DropDown phase="true" :show="'/phases/' + props.row.id" :edit="'/phases/' + props.row.id + /edit/"
          :phaseRounds="'/phases/' + props.row.id + /rounds/" :destroy="'/api/phases/' + props.row.id">
        </DropDown>
      </template>
    </el-table-column>
  </el-table>
</template>

<script setup>
import Rounds from './rounds.vue'
import Status from './status.vue'
import DropDown from './dropdown.vue'
const props = defineProps(['phases', 'rowStyle'])
</script>