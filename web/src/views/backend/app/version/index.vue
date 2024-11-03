<template>
    <div class="default-main ba-table-box">
        <el-alert class="ba-table-alert" v-if="baTable.table.remark" :title="baTable.table.remark" type="info" show-icon />

        <!-- 表格顶部菜单 -->
        <!-- 自定义按钮请使用插槽，甚至公共搜索也可以使用具名插槽渲染，参见文档 -->
        <TableHeader
            :buttons="['refresh', 'add', 'edit', 'delete', 'comSearch', 'quickSearch', 'columnDisplay']"
            :quick-search-placeholder="t('Quick search placeholder', { fields: t('app.version.quick Search Fields') })"
        ></TableHeader>

        <!-- 表格 -->
        <!-- 表格列有多种自定义渲染方式，比如自定义组件、具名插槽等，参见文档 -->
        <!-- 要使用 el-table 组件原有的属性，直接加在 Table 标签上即可 -->
        <Table ref="tableRef"></Table>

        <!-- 表单 -->
        <PopupForm />
    </div>
</template>

<script setup lang="ts">
import { onMounted, provide, ref } from 'vue'
import { useI18n } from 'vue-i18n'
import PopupForm from './popupForm.vue'
import { baTableApi } from '/@/api/common'
import { defaultOptButtons } from '/@/components/table'
import TableHeader from '/@/components/table/header/index.vue'
import Table from '/@/components/table/index.vue'
import baTableClass from '/@/utils/baTable'

defineOptions({
    name: 'app/version',
})

const { t } = useI18n()
const tableRef = ref()
const optButtons: OptButton[] = defaultOptButtons(['weigh-sort', 'edit', 'delete'])

/**
 * baTable 内包含了表格的所有数据且数据具备响应性，然后通过 provide 注入给了后代组件
 */
const baTable = new baTableClass(
    new baTableApi('/admin/app.Version/'),
    {
        pk: 'id',
        column: [
            { type: 'selection', align: 'center', operator: false },
            { label: t('app.version.id'), prop: 'id', align: 'center', width: 70, operator: 'RANGE', sortable: 'custom' },
            { label: t('app.version.nametable__name'), prop: 'nameTable.name', align: 'center', operatorPlaceholder: t('Fuzzy query'), render: 'tags', operator: 'LIKE' },
            { label: t('app.version.old_version'), prop: 'old_version', align: 'center', operatorPlaceholder: t('Fuzzy query'), operator: 'LIKE', sortable: false },
            { label: t('app.version.new_version'), prop: 'new_version', align: 'center', operatorPlaceholder: t('Fuzzy query'), operator: 'LIKE', sortable: false },
            { label: t('app.version.version_code'), prop: 'version_code', align: 'center', operatorPlaceholder: t('Fuzzy query'), operator: 'LIKE', sortable: false },
            { label: t('app.version.package_size'), prop: 'package_size', align: 'center', operatorPlaceholder: t('Fuzzy query'), operator: 'LIKE', sortable: false },
            { label: t('app.version.platform'), prop: 'platform', align: 'center', render: 'tags', operator: 'FIND_IN_SET', sortable: false, replaceValue: { android: t('app.version.platform android'), ios: t('app.version.platform ios') } },
            { label: t('app.version.pkg_url'), prop: 'pkg_url', align: 'center', operatorPlaceholder: t('Fuzzy query'), operator: 'LIKE', sortable: false },
            { label: t('app.version.wgt_url'), prop: 'wgt_url', align: 'center', operatorPlaceholder: t('Fuzzy query'), operator: 'LIKE', sortable: false },
            { label: t('app.version.ios_url'), prop: 'ios_url', align: 'center', operatorPlaceholder: t('Fuzzy query'), operator: 'LIKE', sortable: false },
            { label: t('app.version.is_force_update'), prop: 'is_force_update', align: 'center', render: 'switch', operator: 'eq', sortable: false, replaceValue: { '1': t('app.version.is_force_update 1'), '0': t('app.version.is_force_update 0') } },
            { label: t('app.version.is_hot_update'), prop: 'is_hot_update', align: 'center', render: 'switch', operator: 'eq', sortable: false, replaceValue: { '1': t('app.version.is_hot_update 1'), '0': t('app.version.is_hot_update 0') } },
            { label: t('app.version.weigh'), prop: 'weigh', align: 'center', operator: 'RANGE', sortable: 'custom' },
            { label: t('app.version.status'), prop: 'status', align: 'center', render: 'switch', operator: 'eq', sortable: false, replaceValue: { '0': t('app.version.status 0'), '1': t('app.version.status 1') } },
            { label: t('app.version.update_time'), prop: 'update_time', align: 'center', render: 'datetime', operator: 'RANGE', sortable: 'custom', width: 160, timeFormat: 'yyyy-mm-dd hh:MM:ss' },
            { label: t('app.version.create_time'), prop: 'create_time', align: 'center', render: 'datetime', operator: 'RANGE', sortable: 'custom', width: 160, timeFormat: 'yyyy-mm-dd hh:MM:ss' },
            { label: t('Operate'), align: 'center', width: 140, render: 'buttons', buttons: optButtons, operator: false },
        ],
        dblClickNotEditColumn: [undefined, 'status'],
        defaultOrder: { prop: 'weigh', order: 'desc' },
    },
    {
        defaultItems: { platform: '', is_force_update: '0', is_hot_update: '0', status: '1' },
    }
)

provide('baTable', baTable)

onMounted(() => {
    baTable.table.ref = tableRef.value
    baTable.mount()
    baTable.getIndex()?.then(() => {
        baTable.initSort()
        baTable.dragSort()
    })
})
</script>

<style scoped lang="scss"></style>
