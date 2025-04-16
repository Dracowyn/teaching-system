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
                    <FormItem :label="t('wallpaper.notice.title')" type="string" v-model="baTable.form.items!.title" prop="title" :placeholder="t('Please input field', { field: t('wallpaper.notice.title') })" />
                    <FormItem :label="t('wallpaper.notice.author')" type="string" v-model="baTable.form.items!.author" prop="author" :placeholder="t('Please input field', { field: t('wallpaper.notice.author') })" />
                    <FormItem :label="t('wallpaper.notice.content')" type="editor" v-model="baTable.form.items!.content" prop="content" @keyup.enter.stop="" @keyup.ctrl.enter="baTable.onSubmit(formRef)" :placeholder="t('Please input field', { field: t('wallpaper.notice.content') })" />
                    <FormItem :label="t('wallpaper.notice.recommend')" type="switch" v-model="baTable.form.items!.recommend" prop="recommend" :input-attr="{ content: { '0': t('wallpaper.notice.recommend 0'), '1': t('wallpaper.notice.recommend 1') } }" />
                    <FormItem :label="t('wallpaper.notice.view')" type="number" v-model="baTable.form.items!.view" prop="view" :input-attr="{ step: 1 }" :placeholder="t('Please input field', { field: t('wallpaper.notice.view') })" />
                    <FormItem :label="t('wallpaper.notice.status')" type="switch" v-model="baTable.form.items!.status" prop="status" :input-attr="{ content: { '0': t('wallpaper.notice.status 0'), '1': t('wallpaper.notice.status 1') } }" />
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
    title: [buildValidatorData({ name: 'required', title: t('wallpaper.notice.title') })],
    content: [buildValidatorData({ name: 'editorRequired', title: t('wallpaper.notice.content') })],
    view: [buildValidatorData({ name: 'number', title: t('wallpaper.notice.view') })],
    create_time: [buildValidatorData({ name: 'date', title: t('wallpaper.notice.create_time') })],
    update_time: [buildValidatorData({ name: 'date', title: t('wallpaper.notice.update_time') })],
})
</script>

<style scoped lang="scss"></style>
