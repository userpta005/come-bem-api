<template>
  <el-dropdown :hide-on-click="false" size="large">
    <span class="el-dropdown-link">
      <el-icon class="el-icon--right" size="large">
        <MoreFilled/>
      </el-icon>
    </span>
    <template #dropdown>
      <el-dropdown-menu>
        <slot></slot>
        <el-dropdown-item>
          <el-link type="info" :href="url + show" :underline="false">Visualizar</el-link>
        </el-dropdown-item>
        <el-dropdown-item>
          <el-link type="info" :href="url + edit" :underline="false">Editar</el-link>
        </el-dropdown-item>
        <el-dropdown-item>
          <Destroy :action="url + destroy"></Destroy>
        </el-dropdown-item>
      </el-dropdown-menu>
    </template>
  </el-dropdown>
</template>

<script setup>
import { MoreFilled } from '@element-plus/icons-vue'
import Destroy from './destroy.vue'
const props = defineProps([
  'show',
  'edit',
  'destroy',
])

const url = getUrl();

</script>