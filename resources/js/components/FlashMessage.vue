<script setup>
import {BAlert} from "bootstrap-vue-next";
import { useNotificationStore } from '../store/Notification.js';

const notificationStore = useNotificationStore();
</script>

<template>
    <BAlert
        v-model="notificationStore.message.show"
        :variant="notificationStore.message.type"
        :dismissible="notificationStore.message.dismissible"
        @dismissed="notificationStore.clearNotification()"
        style="z-index: 11">
        {{ notificationStore.message.text }}
    </BAlert>
</template>

<style scoped>

</style>
