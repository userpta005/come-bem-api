<template>
  <el-row>
    <el-col :md="18"
      :xs="24">
      <Main />
    </el-col>
    <el-col :md="6"
      :xs="24">
      <Aside />
    </el-col>
  </el-row>
</template>

<script setup>
import Main from './components/Main.vue'
import Aside from './components/Aside.vue'
import useStorageStore from './stores/storage'

const store = useStorageStore()

store.tenant = window.tenant
store.store = window.store
store.user = window.currentUser
store.openedCashier = window.openedCashier
store.cashier = window.cashier

</script>
