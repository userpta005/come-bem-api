<template>
  <el-dropdown :hide-on-click="false" size="large">
    <span class="el-dropdown-link">
      <el-icon class="el-icon--right" size="large">
        <MoreFilled/>
      </el-icon>
    </span>
    <template #dropdown>
      <el-dropdown-menu>
        <el-dropdown-item>
          <el-link type="info" :href="url + show" :underline="false">Visualizar</el-link>
        </el-dropdown-item>
        <el-dropdown-item v-if="competition">
          <el-link type="info" :href="url + competitionPhases" :underline="false">Fases</el-link>
        </el-dropdown-item>
        <el-dropdown-item v-if="competition">
          <el-link type="info" :href="url + competitionClassifications" :underline="false">Classificações</el-link>
        </el-dropdown-item>
        <el-dropdown-item v-if="phase">
          <el-link type="info" :href="url + phaseRounds" :underline="false">Rodadas</el-link>
        </el-dropdown-item>
        <el-dropdown-item v-if="round">
          <el-link type="info" :href="url + roundGames" :underline="false">Jogos</el-link>
        </el-dropdown-item>
        <el-dropdown-item v-if="game">
          <el-link type="info" :href="url + gameRefereesCreate" :underline="false">Árbitros/Apoio</el-link>
        </el-dropdown-item>
        <el-dropdown-item v-if="game">
          <el-link type="info" :href="url + gameDocumentsCreate" :underline="false">Documentos</el-link>
        </el-dropdown-item>
        <el-dropdown-item v-if="game">
          <el-link type="info" :href="url + gameResultsCreate" :underline="false">Resultados</el-link>
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
  'competition',
  'competitionPhases',
  'competitionClassifications',
  'phase',
  'phaseRounds',
  'round',
  'roundGames',
  'game',
  'gameRefereesCreate',
  'gameDocumentsCreate',
  'gameResultsCreate',
])

const url = getUrl();

</script>