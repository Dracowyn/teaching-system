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
                    <FormItem :label="t('card.card.nickname')" type="string" v-model="baTable.form.items!.nickname" prop="nickname" :placeholder="t('Please input field', { field: t('card.card.nickname') })" />
                    <FormItem :label="t('card.card.mobile')" type="string" v-model="baTable.form.items!.mobile" prop="mobile" :placeholder="t('Please input field', { field: t('card.card.mobile') })" />
                    <FormItem :label="t('card.card.gender')" type="radio" v-model="baTable.form.items!.gender" prop="gender" :data="{ content: { '0': t('card.card.gender 0'), '1': t('card.card.gender 1'), '2': t('card.card.gender 2') } }" :placeholder="t('Please select field', { field: t('card.card.gender') })" />
                    <FormItem :label="t('card.card.city')" type="city" v-model="baTable.form.items!.city" prop="city" :placeholder="t('Please select field', { field: t('card.card.city') })" />
                    <FormItem :label="t('card.card.remark')" type="textarea" v-model="baTable.form.items!.remark" prop="remark" :input-attr="{ rows: 3 }" @keyup.enter.stop="" @keyup.ctrl.enter="baTable.onSubmit(formRef)" :placeholder="t('Please input field', { field: t('card.card.remark') })" />
                    <FormItem :label="t('card.card.type_id')" type="remoteSelect" v-model="baTable.form.items!.type_id" prop="type_id" :input-attr="{ pk: 'ba_card_type.id', field: 'name', 'remote-url': '/admin/card.Type/index' }" :placeholder="t('Please select field', { field: t('card.card.type_id') })" />
                    <FormItem :label="t('card.card.user_id')" type="remoteSelect" v-model="baTable.form.items!.user_id" prop="user_id" :input-attr="{ pk: 'ba_user.id', field: 'username', 'remote-url': '/admin/user.User/index' }" :placeholder="t('Please select field', { field: t('card.card.user_id') })" />
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
    nickname: [buildValidatorData({ name: 'required', title: t('card.card.nickname') })],
    mobile: [buildValidatorData({ name: 'required', title: t('card.card.mobile') }), buildValidatorData({ name: 'mobile', title: t('card.card.mobile') })],
    type_id: [buildValidatorData({ name: 'required', title: t('card.card.type_id') })],
    create_time: [buildValidatorData({ name: 'date', title: t('card.card.create_time') })],
    update_time: [buildValidatorData({ name: 'date', title: t('card.card.update_time') })],
})
</script>

<style scoped lang="scss"></style>
