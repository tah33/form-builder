<script setup lang="ts">
import {
    BNavbar,
    BNavbarBrand,
    BNavbarNav,
    BNavItemDropdown,
    BDropdownItem,
    BCollapse,
    BNavbarToggle,
    BNavItem
} from 'bootstrap-vue-next';

import {useAuthStore} from '../store/Auth'
import { Link, router } from '@inertiajs/vue3';

const auth = useAuthStore();
</script>

<template>
    <div>
        <BNavbar v-if="auth.user" toggleable="lg" variant="primary">
            <BNavbarBrand href="#">Form Builder</BNavbarBrand>
            <Link class="nav-link" href="/forms" style="color: white">Forms</Link>
            <BNavbarToggle target="nav-collapse" />
            <BCollapse id="nav-collapse" is-nav>
                <BNavbarNav class="ms-auto mb-2 mb-lg-0">
                    <BNavItemDropdown right>
                        <!-- Using 'button-content' slot -->
                        <template #button-content>
                            <em class="text-white">{{ auth.user.name }}</em>
                        </template>
                        <BDropdownItem href="javascript:void(0)" data-test="sign-out" @click="auth.logout">Sign Out</BDropdownItem>
                    </BNavItemDropdown>
                </BNavbarNav>
            </BCollapse>
        </BNavbar>
        <main>
            <slot />
        </main>
    </div>

</template>

<style scoped>
li{
    list-style: none;
}
.nav-link {
    color: white !important;
}
</style>
