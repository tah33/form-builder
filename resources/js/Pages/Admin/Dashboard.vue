<script setup lang="ts">
import {useAuthStore} from '../../store/Auth'
import { Head, Link } from '@inertiajs/vue3';
const auth = useAuthStore();
</script>

<template>
    <Head title="Dashboard" />
    <div class="container mt-5">
        <h2 class="text-xl font-bold mb-4">Welcome {{ auth.user.name }}</h2>
        <Link href="/forms">Click here to manage your forms</Link>
    </div>
</template>

<style scoped>

</style>
