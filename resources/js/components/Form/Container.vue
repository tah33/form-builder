<script lang="ts" setup>
import { ref } from 'vue';
import Card from './Card.vue';
import { router, useForm, usePage } from '@inertiajs/vue3';

interface Item {
    id: number;
    text: string;
}

interface PageProps {
    fields?: Item[];
}

const page = usePage<PageProps>();
const fields = ref<Item[]>(page.props.fields);
const moveCard = (dragIndex: number, hoverIndex: number) => {
    const item = fields.value[dragIndex];
    fields.value.splice(dragIndex, 1);
    fields.value.splice(hoverIndex, 0, item);
};
function updateList() {
    let data = [];
    for (const formDataKey in fields.value) {
        data[fields.value[formDataKey].id] = parseInt(formDataKey);
    }

    router.post('/config/form', {data});
}
</script>

<template>
    <div style="width: 400px">
        <form @submit.prevent>
            <Card
                v-for="(field, index) in fields"
                :id="field.id"
                :type="field.type"
                :index="index"
                :move-card="moveCard"
                :name="field.name"
                :label="field.label"
                :placeholder="field.placeholder"
                :required="field.required"
                @drop="updateList"
            />
            <button type="submit" class="btn btn-primary">Check Form Validations</button>
        </form>
    </div>
</template>
