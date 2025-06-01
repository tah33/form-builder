import { defineStore } from 'pinia';
import { ref } from 'vue';
import { router, useForm, usePage } from '@inertiajs/vue3';
import { useNotificationStore } from './Notification';

interface CustomForm {
    id: number;
    json_data: string;
    file: File | null;
}

interface PaginatedData {
    currentPage: number;
    rows: number;
    perPage: number;
}


export const useFormStore = defineStore('form', () => {
    const forms = ref<any[]>([]);
    const loading = ref(false);
    const index_loading = ref(false);
    const form = ref<CustomForm>({
        id: null,
        json_data: '',
        file: null
    });

    const paginated_data = ref<PaginatedData>({
        currentPage: 1,
        rows: 10,
        perPage: 10
    });

    // Fetch Forms
    async function fetchForms(page: number) {
        forms.value = usePage().props.forms as CustomForm[];
        paginated_data.value = usePage().props.paginated_data as PaginatedData;
    }

    // Add New Form
    async function addForm(): Promise<unknown> {
        loading.value = true;

        useNotificationStore().emptyState();
        const formData = useForm({
            json_data: form.value.json_data,
            file: form.value.file
        });

        return new Promise((resolve, reject) => {
            formData.post('forms', {
                preserveScroll: true,
                onSuccess: (response) => {
                    useNotificationStore().handleNotification({
                        status: 200,
                        message: 'Form Added Successfully'
                    });
                    loading.value = false;
                    formData.reset();
                    fetchForms(1)
                    resolve(true);
                },
                onError: (errors) => {
                    useNotificationStore().handleNotification(usePage().props.errors);
                    loading.value = false;
                    reject();
                },
                onFinish: (errors) => {
                    loading.value = false;
                }
            });
        });
    }

    // Edit Task
    async function editForm(editForm: CustomForm) {
        console.log(editForm);
        form.value.id = editForm.id;
        form.value.json_data = editForm.json_data;
        console.log(form);
    }

    // Update Form
    async function updateForm(id:number): Promise<unknown> {
        loading.value = true;

        useNotificationStore().emptyState();
        const formData = useForm({
            json_data: form.value.json_data,
            file: form.value.file
        });

        return new Promise((resolve, reject) => {
            formData.put('forms/'+id, {
                preserveScroll: true,
                onSuccess: (response) => {
                    useNotificationStore().handleNotification({
                        status: 200,
                        message: 'Form updated Successfully'
                    });
                    loading.value = false;
                    formData.reset();
                    fetchForms(1)
                    resolve(true);
                },
                onError: (errors) => {
                    useNotificationStore().handleNotification(usePage().props.errors);
                    loading.value = false;
                    reject();
                }
            });
        });
    }

    // Delete Task
    async function deleteForm(id: string): Promise<void> {
        if (!confirm('Are you sure?')) return;

        router.delete(`forms/${id}`, {
            onSuccess: () => {
                useNotificationStore().handleNotification({
                    status: 200,
                    message: 'Form deleted Successfully'
                });
                fetchForms(1);
            }
        });
    }

    return {
        // State
        forms,
        loading,
        index_loading,
        form,
        paginated_data,

        // Methods
        fetchForms,
        addForm,
        editForm,
        updateForm,
        deleteForm

    };
});
