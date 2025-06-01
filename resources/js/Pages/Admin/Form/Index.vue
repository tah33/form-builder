<script setup lang="ts">

import FlashMessage from '../../../components/FlashMessage.vue';

import {
    BButton,
    BCol,
    BContainer,
    BDropdown,
    BDropdownItem,
    BPagination,
    BRow,
} from 'bootstrap-vue-next';
import { useNotificationStore } from '../../../store/Notification';
import { useFormStore } from '../../../store/Form';

const notificationStore = useNotificationStore();
const formStore = useFormStore();

import CustomForm from "../../../components/Form/CustomForm.vue";
import { onMounted,ref } from "vue";
import { Head, Link, router } from '@inertiajs/vue3';

onMounted(() => {
    formStore.fetchForms(1)
})

const modal = ref(false);

function addNew() {
    modal.value = !modal.value;
    formStore.form.id = null;
}
async function editForm(form) {
    modal.value = !modal.value
    await formStore.editForm(form)
}

</script>

<template>
    <Head title="Forms" />
    <div class="container mt-5">
        <div class="row">
            <div class="col-lg-12">
                <FlashMessage v-if="notificationStore.message.type === 'success'"
                    :show="notificationStore.message.show"
                    :type="notificationStore.message.type"
                    :message="notificationStore.message.text"
                    @close="notificationStore.clearNotification"
                />
            </div>
            <div class="col-lg-12 d-flex justify-content-between">
                <h5>Form List</h5>
                <BButton variant="primary" size="sm" @click="addNew()">+ Add New</BButton>
            </div>
        </div>
        <table class="table table-hover">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Title</th>
                <th scope="col">Action</th>
                <th scope="col">Method</th>
                <th scope="col">Total Input Fields</th>
                <th scope="col">Action</th>
            </tr>
            </thead>
            <tbody>
            <tr v-if="formStore.index_loading">
                <td colspan="6" class="text-center text-muted py-3">
                    Loading.....
                </td>
            </tr>

            <tr v-else-if="formStore.forms.length === 0">
                <td colspan="6" class="text-center text-muted py-3">
                    No Forms Found
                </td>
            </tr>
            <tr v-else v-for="(form,index) in formStore.forms.data" :key="index">
                <td>{{ formStore.paginated_data.first_item + index }}</td>
                <td>{{ form.title }}</td>
                <td>{{ form.action }}</td>
                <td>{{ form.method }}</td>
                <td>{{ form.configs_count }}</td>
                <td>
                    <BDropdown size="sm" text="Action" variant="primary" class="me-2">
                        <li>
                            <Link class="dropdown-item" :href="`forms/${form.id}`" role="menuitem">Form Builder</Link>
                        </li>

                        <BDropdownItem href="javascript:void(0)" @click="editForm(form)">Edit</BDropdownItem>
                        <BDropdownItem class="delete-item" href="javascript:void(0)" @click="formStore.deleteForm(form.id)">Delete</BDropdownItem>
                    </BDropdown>
                </td>
            </tr>
            </tbody>
        </table>
        <BContainer v-if="formStore.forms.data && formStore.forms.data.length > 0">
            <BRow>
                <BCol>
                    <div class="overflow-auto">
                        <b-pagination
                            v-model="formStore.paginated_data.currentPage"
                            :total-rows="formStore.paginated_data.rows"
                            :per-page="formStore.paginated_data.perPage"
                            aria-controls="my-table"
                            @page-click="router.visit('forms?page='+$event.target.innerText)"
                        />
                    </div>
                </BCol>
            </BRow>
        </BContainer>
        <CustomForm :show="modal" @close="modal = false"/>
    </div>
</template>

<style scoped>

</style>
