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
    const purchaseOrder = ref({
      account_id: null,
      cart: []
    })

    return {
      tenant,
      store,
      user,
      cashier,
      openedCashier,
      purchaseOrder
    }
  },
  {
    persist: true
  }
);

export default useStorageStore
