import { defineStore } from 'pinia';
import { router, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { usePage } from '@inertiajs/vue3';
import {useNotificationStore} from './Notification';

interface FormState {
    email: string;
    password: string;
}
interface User {
    id: number;
    name: string;
    email: string;
}
interface AuthProps {
    user?: User | null; // Optional if user can be null/undefined
}
export const useAuthStore = defineStore('auth', () => {
    const page = usePage();
    const loading = ref(false);
    const form = ref<FormState>({
        email: '',
        password: '',
    });

    const authProps = computed(() => page.props.auth as AuthProps);

    // Reactive user computed property
    const user = computed(() => authProps.value.user);

    async function login() {
        loading.value = true;

        const formData = useForm({
            email: form.value.email,
            password: form.value.password,
        });

        formData.post('post-login', {
            preserveScroll: true,
            onFinish: (response) => {
                console.log(response);
                useNotificationStore().handleNotification(page.props.errors);
                loading.value = false;
                formData.reset();
            },
        });
    }

    function logout() {
        router.delete('logout');
    }

    return {
        loading,
        form,
        user,
        login,
        logout
    };
});
