import { defineStore } from 'pinia'
import { ref } from 'vue'

const useStorageStore = defineStore(
  'storage',
  () => {
    const tenant = ref(null)
    const store = ref(null)
    const user = ref(null)
    const cashier = ref(null)
    const openedCashier = ref(false)

    return {
      tenant,
      store,
      user,
      cashier,
      openedCashier
    }
  },
  {
    persist: true
  }
);

export default useStorageStore
