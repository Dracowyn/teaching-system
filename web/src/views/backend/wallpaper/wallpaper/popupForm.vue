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
                    <FormItem :label="t('wallpaper.wallpaper.image')" type="image" v-model="baTable.form.items!.image" prop="image" />
                    <FormItem :label="t('wallpaper.wallpaper.description')" type="string" v-model="baTable.form.items!.description" prop="description" :placeholder="t('Please input field', { field: t('wallpaper.wallpaper.description') })" />
                    <FormItem :label="t('wallpaper.wallpaper.nickname')" type="string" v-model="baTable.form.items!.nickname" prop="nickname" :placeholder="t('Please input field', { field: t('wallpaper.wallpaper.nickname') })" />
                    <FormItem :label="t('wallpaper.wallpaper.tabs')" type="string" v-model="baTable.form.items!.tabs" prop="tabs" :placeholder="t('Please input field', { field: t('wallpaper.wallpaper.tabs') })" />
                    <FormItem :label="t('wallpaper.wallpaper.score')" type="number" v-model="baTable.form.items!.score" prop="score" :input-attr="{ step: 1 }" :placeholder="t('Please input field', { field: t('wallpaper.wallpaper.score') })" />
                    <FormItem :label="t('wallpaper.wallpaper.wallpaper_classify_ids')" type="remoteSelects" v-model="baTable.form.items!.wallpaper_classify_ids" prop="wallpaper_classify_ids" :input-attr="{ pk: 'classify.id', field: 'string', remoteUrl: '/admin/wallpaper.Classify/index' }" :placeholder="t('Please select field', { field: t('wallpaper.wallpaper.wallpaper_classify_ids') })" />
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
    image: [buildValidatorData({ name: 'required', title: t('wallpaper.wallpaper.image') })],
    score: [buildValidatorData({ name: 'float', title: t('wallpaper.wallpaper.score') }), buildValidatorData({ name: 'required', title: t('wallpaper.wallpaper.score') })],
    wallpaper_classify_ids: [buildValidatorData({ name: 'required', title: t('wallpaper.wallpaper.wallpaper_classify_ids') })],
    create_time: [buildValidatorData({ name: 'date', title: t('wallpaper.wallpaper.create_time') })],
    update_time: [buildValidatorData({ name: 'date', title: t('wallpaper.wallpaper.update_time') })],
})
</script>

<style scoped lang="scss"></style>
