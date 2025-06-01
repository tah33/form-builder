<script lang="ts" setup>
import Container from '../../../store/FormDnD'
import { DndProvider } from 'vue3-dnd'
import { HTML5Backend } from 'react-dnd-html5-backend'
import { Head } from '@inertiajs/vue3';
</script>
<template>
    <Head title="Form Builder" />
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 mt-5">
                <DndProvider :backend="HTML5Backend">
                    <Container></Container>
                </DndProvider>
            </div>
        </div>
    </div>
</template>
