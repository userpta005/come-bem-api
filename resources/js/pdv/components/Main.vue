<style scoped>
.el-card-body {
  display: flex;
  flex-direction: column;
  justify-content: space-around;
  align-items: center;
  cursor: pointer;
}

.current-section {
  border-color: #00251a;
}
</style>

<template>
  <el-row>

    <Divider />
    <div style="font-size: 18px; color: black; font-weight: lighter; margin-bottom: 15px;">Seções</div>
    <el-col :span="24">
      <el-space wrap>

        <el-card :class="{ 'current-section': !currentSection }"
          :body-style="{ padding: '10px' }">
          <div class="el-card-body"
            style="width: 72.7px; height: 60px;"
            @click="handleShowAllSections">
            <el-icon :size="40">
              <Grid />
            </el-icon>
            <span style="text-align: center; font-size:x-small; word-break: break-all;">
              Todos
            </span>
          </div>
        </el-card>

        <el-card :class="{ 'current-section': currentSection === section.id }"
          :body-style="{ padding: '10px' }"
          v-for="(section, index) in sections"
          :key="index">
          <div class="el-card-body"
            style="width: 72.7px; height: 60px;"
            @click="handleFilterProducts(section)">
            <img :src="section.image_url"
              style="height: 40px; width: 40px;">
            <span style="text-align: center; font-size:x-small; word-break: break-all;">
              {{ limitString(section.name, 12) }}
            </span>
          </div>

        </el-card>

      </el-space>
    </el-col>

    <Divider />

    <div style="color: black; font-weight: 300; margin-bottom:5px;">Pesquisar produto</div>
    <el-input v-model="productSearch"
      placeholder="Digite o nome do produto"
      size="large"
      clearable />

    <div style="margin: 12px 0; font-size:large; color: red;">
      Para adicionar o produto no pedido, clique na imagem abaixo.
    </div>

    <el-col :span="24">
      <el-space wrap>

        <el-card :body-style="{ padding: '10px' }"
          v-for="(product, index) in filteredProducts"
          :key="index"
          @click="handleAddProduct(product)">
          <div class="el-card-body"
            style="width: 120px; height: 125px;">
            <img :src="product.image_url"
              style="height: 50px; width: 50px;">
            <span style="text-align: center; word-break: break-all;">{{ limitString(product.name, 20) }}</span>
            <span style="text-align: center; font-weight: 600;">R$ {{ floatToMoney(product.price) }}</span>
          </div>

        </el-card>

      </el-space>
    </el-col>

  </el-row>
</template>

<script setup>
import Divider from './Divider.vue'
import axios from 'axios'
import { ref, watch } from 'vue'
import { limitString, floatToMoney } from '../../helpers'
import useStorageStore from '../stores/storage';

const store = useStorageStore()
const url = getUrl()
const sections = ref([])
const currentSection = ref(0)
const products = ref([])
const filteredProducts = ref([])
const productSearch = ref(null)

const like = (str, pattern) => {
  return str.toLowerCase().includes(pattern.toLowerCase());
}

watch(productSearch, (newValue) => {
  filteredProducts.value = !!currentSection.value ? products.value.filter(item => item.section_id === currentSection.value) : products.value
  if (newValue) {
    filteredProducts.value = filteredProducts.value.filter(item => like(item.name, newValue))
  }
})

const handleFilterProducts = (section) => {
  currentSection.value = section.id
  filteredProducts.value = products.value.filter(item => item.section_id === section.id)
}

const handleShowAllSections = () => {
  filteredProducts.value = products.value
  currentSection.value = 0
}

const handleGetSections = async () => {
  try {
    const { data } = await axios.get(`${url}/api/v1/sections`)
    sections.value = data.data
  } catch ({ response }) {
    ElNotification({
      title: 'Erro !',
      message: response.data.message,
      type: 'error',
    })
  }
}

const handleGetProducts = async () => {
  try {
    const { data } = await axios.get(`${url}/api/v1/products`)
    products.value = filteredProducts.value = data.data
  } catch ({ response }) {
    ElNotification({
      title: 'Erro !',
      message: response.data.message,
      type: 'error',
    })
  }
}

handleGetSections()
handleGetProducts()

const handleAddProduct = (product) => {
  const exists = store.cart.find(item => item.id == product.id)
  if (!!exists) {
    ElNotification({
      title: 'Aviso !',
      message: 'Produto já adicionado ao carrinho !',
      type: 'warning',
    })
  } else {
    product.quantity = 1
    store.cart.push(product)
  }
}

</script>
