<template>
  <el-table :data="rounds" :border="false" size="small" style="width: 100%;"
    :show-header="false" :row-style="rowStyle">
    <el-table-column :width="24"></el-table-column>
    <el-table-column type="expand" :width="25">

      <template #default="props">
        <Games :games="props.row.games" :rowStyle="rowStyle" v-if="props.row.games.length"></Games>
      </template>

    </el-table-column>
    <el-table-column :width="30">
      <template #default="props">
        <Status :status="props.row.status"></Status>
      </template>
    </el-table-column>
    <el-table-column prop="name" />
    <el-table-column prop="dt_created" :width="110" />
    <el-table-column align="right" :width="70">
      <template #default="props">
        <DropDown round="true" :show="'/rounds/' + props.row.id" :edit="'/rounds/' + props.row.id + /edit/"
          :roundGames="'/rounds/' + props.row.id + /games/" :destroy="'/api/rounds/' + props.row.id">
        </DropDown>
      </template>
    </el-table-column>
  </el-table>
</template>

<script setup>
import Games from './games.vue'
import Status from './status.vue'
import DropDown from './dropdown.vue'
const props = defineProps(['rounds', 'rowStyle'])
</script>