<template>
    <!-- 对话框表单 -->
    <!-- 建议使用 Prettier 格式化代码 -->
    <!-- el-form 内可以混用 el-form-item、FormItem、ba-input 等输入组件 -->
    <el-dialog
        class="ba-operate-dialog"
        :close-on-click-modal="false"
        :model-value="['Add', 'Edit'].includes(baTable.form.operate!)"
        @close="baTable.toggleForm"
        width="50%"
    >
        <template #header>
            <div class="title" v-drag="['.ba-operate-dialog', '.el-dialog__header']" v-zoom="'.ba-operate-dialog'">
                {{ baTable.form.operate ? t(baTable.form.operate) : '' }}
            </div>
        </template>
        <el-scrollbar v-loading="baTable.form.loading" class="ba-table-form-scrollbar">
            <div
                class="ba-operate-form"
                :class="'ba-' + baTable.form.operate + '-form'"
                :style="config.layout.shrink ? '':'width: calc(100% - ' + baTable.form.labelWidth! / 2 + 'px)'"
            >
                <el-form
                    v-if="!baTable.form.loading"
                    ref="formRef"
                    @submit.prevent=""
                    @keyup.enter="baTable.onSubmit(formRef)"
                    :model="baTable.form.items"
                    :label-position="config.layout.shrink ? 'top' : 'right'"
                    :label-width="baTable.form.labelWidth + 'px'"
                    :rules="rules"
                >
                    <FormItem :label="t('app.version.name')" type="remoteSelect" v-model="baTable.form.items!.name" prop="name" :input-attr="{ pk: 'ba_app_name.id', field: 'name', remoteUrl: '/admin/app.Name/index' }" :placeholder="t('Please select field', { field: t('app.version.name') })" />
                    <FormItem :label="t('app.version.old_version')" type="string" v-model="baTable.form.items!.old_version" prop="old_version" :placeholder="t('Please input field', { field: t('app.version.old_version') })" />
                    <FormItem :label="t('app.version.new_version')" type="string" v-model="baTable.form.items!.new_version" prop="new_version" :placeholder="t('Please input field', { field: t('app.version.new_version') })" />
                    <FormItem :label="t('app.version.version_code')" type="string" v-model="baTable.form.items!.version_code" prop="version_code" :placeholder="t('Please input field', { field: t('app.version.version_code') })" />
                    <FormItem :label="t('app.version.package_size')" type="string" v-model="baTable.form.items!.package_size" prop="package_size" :placeholder="t('Please input field', { field: t('app.version.package_size') })" />
                    <FormItem :label="t('app.version.platform')" type="checkbox" v-model="baTable.form.items!.platform" prop="platform" :input-attr="{ content: { android: t('app.version.platform android'), ios: t('app.version.platform ios') } }" :placeholder="t('Please select field', { field: t('app.version.platform') })" />
                    <FormItem :label="t('app.version.content')" type="textarea" v-model="baTable.form.items!.content" prop="content" :input-attr="{ rows: 3 }" @keyup.enter.stop="" @keyup.ctrl.enter="baTable.onSubmit(formRef)" :placeholder="t('Please input field', { field: t('app.version.content') })" />
                    <FormItem :label="t('app.version.pkg_url')" type="string" v-model="baTable.form.items!.pkg_url" prop="pkg_url" :placeholder="t('Please input field', { field: t('app.version.pkg_url') })" />
                    <FormItem :label="t('app.version.wgt_url')" type="string" v-model="baTable.form.items!.wgt_url" prop="wgt_url" :placeholder="t('Please input field', { field: t('app.version.wgt_url') })" />
                    <FormItem :label="t('app.version.ios_url')" type="string" v-model="baTable.form.items!.ios_url" prop="ios_url" :placeholder="t('Please input field', { field: t('app.version.ios_url') })" />
                    <FormItem :label="t('app.version.is_force_update')" type="radio" v-model="baTable.form.items!.is_force_update" prop="is_force_update" :input-attr="{ content: { '1': t('app.version.is_force_update 1'), '0': t('app.version.is_force_update 0') } }" :placeholder="t('Please select field', { field: t('app.version.is_force_update') })" />
                    <FormItem :label="t('app.version.is_hot_update')" type="radio" v-model="baTable.form.items!.is_hot_update" prop="is_hot_update" :input-attr="{ content: { '1': t('app.version.is_hot_update 1'), '0': t('app.version.is_hot_update 0') } }" :placeholder="t('Please select field', { field: t('app.version.is_hot_update') })" />
                    <FormItem :label="t('app.version.weigh')" type="number" prop="weigh" :input-attr="{ step: 1 }" v-model.number="baTable.form.items!.weigh" :placeholder="t('Please input field', { field: t('app.version.weigh') })" />
                    <FormItem :label="t('app.version.status')" type="switch" v-model="baTable.form.items!.status" prop="status" :input-attr="{ content: { '0': t('app.version.status 0'), '1': t('app.version.status 1') } }" />
                </el-form>
            </div>
        </el-scrollbar>
        <template #footer>
            <div :style="'width: calc(100% - ' + baTable.form.labelWidth! / 1.8 + 'px)'">
                <el-button @click="baTable.toggleForm()">{{ t('Cancel') }}</el-button>
                <el-button v-blur :loading="baTable.form.submitLoading" @click="baTable.onSubmit(formRef)" type="primary">
                    {{ baTable.form.operateIds && baTable.form.operateIds.length > 1 ? t('Save and edit next item') : t('Save') }}
                </el-button>
            </div>
        </template>
    </el-dialog>
</template>

<script setup lang="ts">
import type { FormInstance, FormItemRule } from 'element-plus'
import { inject, reactive, ref } from 'vue'
import { useI18n } from 'vue-i18n'
import FormItem from '/@/components/formItem/index.vue'
import { useConfig } from '/@/stores/config'
import type baTableClass from '/@/utils/baTable'
import { buildValidatorData } from '/@/utils/validate'

const config = useConfig()
const formRef = ref<FormInstance>()
const baTable = inject('baTable') as baTableClass

const { t } = useI18n()

const rules: Partial<Record<string, FormItemRule[]>> = reactive({
    name: [buildValidatorData({ name: 'required', title: t('app.version.name') })],
    new_version: [buildValidatorData({ name: 'required', title: t('app.version.new_version') })],
    version_code: [buildValidatorData({ name: 'required', title: t('app.version.version_code') })],
    pkg_url: [buildValidatorData({ name: 'url', title: t('app.version.pkg_url') })],
    wgt_url: [buildValidatorData({ name: 'url', title: t('app.version.wgt_url') })],
    ios_url: [buildValidatorData({ name: 'url', title: t('app.version.ios_url') })],
    update_time: [buildValidatorData({ name: 'date', title: t('app.version.update_time') })],
    create_time: [buildValidatorData({ name: 'date', title: t('app.version.create_time') })],
})
</script>

<style scoped lang="scss"></style>
