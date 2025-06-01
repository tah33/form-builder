<script setup lang="ts">

import { BButton, BModal, BSpinner } from 'bootstrap-vue-next';

import { useNotificationStore } from '../../store/Notification';
import { useFormStore } from '../../store/Form';
import FlashMessage from '../FlashMessage.vue';
import InputError from '../../components/InputError.vue';

const formStore = useFormStore();
const notificationStore = useNotificationStore();

defineProps({
    show : {
        type: Boolean,
        required: true
    }
});
const emit = defineEmits(['close']);
async function submitForm() {
    let res;
    if (formStore.form.id) {
        res = await formStore.updateForm(formStore.form.id);
    } else {
        res = await formStore.addForm();
    }
    if (res) {
        emit('close');
    }
}
</script>

<template>
    <BModal :show="show" @hide="$emit('close')" id="modal-center" centered :title="formStore.form.id ? 'Edit Form' : 'Create Form'">
        <FlashMessage v-if="notificationStore.message.type === 'danger'"
                      :show="notificationStore.message.show"
                      :type="notificationStore.message.type"
                      :message="notificationStore.message.text"
                      @close="notificationStore.clearNotification"
        />
        <div class="text-end">
            <a href="json_config.json" download="sample.json">Download Sample file</a>
        </div>
        <form>
            <div class="form-group">
                <label for="file">Upload a Json File</label>
                <input type="file" class="form-control" @input="formStore.form.file = $event.target.files[0]" />
                <InputError v-if="notificationStore.errors" :message="notificationStore.errors.file"/>
            </div>
            <p class="text-center mt-2 mb-0">Or</p>
            <hr class="text-center mt-0 pt-0">
            <div class="form-group">
                <label for="description">Copy your json data here</label>
                <textarea name="description" v-model="formStore.form.json_data" id="description" class="form-control"></textarea>
                <InputError v-if="notificationStore.errors" :message="notificationStore.errors.json_data"/>
            </div>
        </form>
        <template #footer>
            <BButton variant="secondary" size="sm" @click="$emit('close')">Cancel</BButton>
            <BButton variant="primary" size="sm"
                     @click="submitForm"
                     :disabled="formStore.loading">
                <template v-if="formStore.loading">
                    <BSpinner small class="me-1" />
                    Submit...
                </template>
                <template v-else>
                    Submit
                </template>
            </BButton>
        </template>
    </BModal>
</template>

<style scoped>

</style>
