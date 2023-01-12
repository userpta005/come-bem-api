<template>
    <el-table :data="tableData"
        :border="false"
        size="small"
        style="width: 100%;"
        :show-header="false"
        :row-style="rowStyle">
        <el-table-column :width="24"></el-table-column>
        <el-table-column type="expand"
            :width="25">
            <template #default="scope">
                <Cards :table-data="scope.row.cards"
                    v-if="scope.row.cards.length"></Cards>
            </template>
        </el-table-column>
        <el-table-column label="#"
            :width="30">
            <template #default="props">
                <Status :status="props.row.status"></Status>
            </template>
        </el-table-column>
        <el-table-column sortable>
            <template #default="scope">
                <span><b>Escola: </b>{{ scope.row.store.people.full_name }}</span>
            </template>
        </el-table-column>
        <el-table-column label="Saldo"
            :width="130"
            prop="attr_balance"
            sortable>
            <template #default="scope">
                <span><b>Saldo: </b>{{ scope.row.attr_balance }}</span>
            </template>
        </el-table-column>
        <el-table-column label="Limite Diário"
            :width="130"
            prop="attr_daily_limit"
            sortable>
            <template #default="scope">
                <span><b>Limite/Dia: </b>{{ scope.row.attr_daily_limit }}</span>
            </template>
        </el-table-column>
        <el-table-column label="Turma"
            :width="130"
            prop="class"
            sortable>
            <template #default="scope">
                <span><b>Turma: </b>{{ scope.row.class }}</span>
            </template>
        </el-table-column>
        <el-table-column label="Série"
            :width="130"
            prop="school_year"
            sortable>
            <template #default="scope">
                <span><b>Série: </b>{{ scope.row.school_year }}</span>
            </template>
        </el-table-column>
        <el-table-column align="right"
            :width="70">
            <template #default="scope">
                <DropDown :show="`/dependents/${scope.row.dependent_id}/accounts/${scope.row.id}`"
                    :edit="`/dependents/${scope.row.dependent_id}/accounts/${scope.row.id}/edit`"
                    :destroy="`/api/v1/accounts/${scope.row.id}`">
                    <el-dropdown-item>
                        <el-link type="info"
                            :href="`${url}/accounts/${scope.row.id}/cards/create`"
                            :underline="false">
                            Criar Cartão
                        </el-link>
                    </el-dropdown-item>
                    <el-dropdown-item>
                        <el-link type="info"
                            :href="`${url}/accounts/${scope.row.id}/limited_products`"
                            :underline="false">
                            Restrição de Produtos
                        </el-link>
                    </el-dropdown-item>
                </DropDown>
            </template>
        </el-table-column>
    </el-table>
</template>

<script setup>
import Cards from './cards.vue'
import DropDown from './dropdown.vue'
import Status from './status.vue'
const props = defineProps(['tableData'])
const url = getUrl();

function rowStyle () {
    return {
        background: "#E5EAF3",
    };
}
</script>
