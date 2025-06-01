<script setup lang="ts">
import {BButton, BSpinner} from "bootstrap-vue-next";
import {useAuthStore} from '../../store/Auth'
import FlashMessage from '../../components/FlashMessage.vue';
const authStore = useAuthStore()
import { useNotificationStore } from '../../store/Notification';
import InputError from '../../components/InputError.vue';
import { Head } from '@inertiajs/vue3';

const notificationStore = useNotificationStore();

</script>

<template>
    <Head title="Login" />
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">Log In</div>
                    <div class="card-body">
                        <FlashMessage
                            :show="notificationStore.message.show"
                            :type="notificationStore.message.type"
                            :message="notificationStore.message.text"
                            @close="notificationStore.clearNotification"
                        />
                        <h5 class="mb-3">Login to See Arrange Your Forms</h5>
                        <form @submit.prevent="authStore.login()">
                            <div class="form-group mb-3">
                                <label for="title">Email</label>
                                <input type="email" name="email" id="email"
                                       class="form-control" v-model="authStore.form.email">
                                <InputError v-if="notificationStore.errors" :message="notificationStore.errors.email"/>
                            </div>
                            <div class="form-group mb-3">
                                <label for="password">Password</label>
                                <input type="password" name="password"  id="password"
                                       class="form-control" v-model="authStore.form.password">
                                <p class="text-danger"></p>
                            </div>
                            <div class="form-group text-end">
                                <BButton variant="primary" size="sm"
                                         type="submit"
                                         >
                                    <template v-if="authStore.loading">
                                        <BSpinner small class="me-1" />
                                        Login...
                                    </template>
                                    <template v-else>
                                        Login
                                    </template>
                                </BButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
