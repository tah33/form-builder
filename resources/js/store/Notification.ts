import { defineStore } from 'pinia';

interface MessageState {
    type: string;
    text: string;
    show: boolean;
    dismissible : boolean
}
export const useNotificationStore = defineStore('notification', {
    state: () => ({
        errors: {} as Record<string, string[]>,
        message: {
            type: '', // 'success' | 'error'
            text: '',
            show: false,
            dismissible: true
        } as MessageState,
    }),

    actions: {
        clearNotification() {
            this.message.show = false;
        },
        emptyState() {
            this.message = {
                type: '',
                text: '',
                show: false,
                dismissible: true
            };
            useNotificationStore().errors = {};
        },
        handleNotification(object) {
            if (! object.status) {
                object.status = 422;
            }

            if (object.status === 200 || object.status === 201) {
                this.message = {
                    type: 'success',
                    text: object.message,
                    show: true,
                    dismissible: true
                };
            } else if (object.status === 422) {
                this.errors = object;
            } else {
                this.message = {
                    type: 'danger',
                    text: object.message,
                    show: true,
                    dismissible: true
                };
                this.errors = {}
            }
        }
    }
});
