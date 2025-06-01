<script lang="ts" setup>
import { computed, ref, unref } from 'vue'
import { useDrag, useDrop } from 'vue3-dnd'
import { ItemTypes } from '../../store/ItemTypes'
import type { XYCoord, Identifier } from 'dnd-core'
import { toRefs } from '@vueuse/core'

const props = defineProps<{
    id: any
    type: string
    name: string
    label: string
    placeholder: string|null
    required: boolean
    index: number
    moveCard: (dragIndex: number, hoverIndex: number) => void
}>()
interface DragItem {
    index: number
    id: string
    type: string
}
const hasDropped = ref(false)
const card = ref<HTMLDivElement>()
const [dropCollect, drop] = useDrop<
    DragItem,
    void,
    { handlerId: Identifier | null }
>({
    accept: ItemTypes.CARD,
    collect(monitor) {
        return {
            handlerId: monitor.getHandlerId(),
        }
    },
    drop(_item: unknown, monitor) {
        const didDrop = monitor.didDrop()
        if (didDrop) {
            return
        }
        hasDropped.value = true
    },
    hover(item: DragItem, monitor) {
        if (!card.value) {
            return
        }
        const dragIndex = item.index
        const hoverIndex = props.index
        // Don't replace items with themselves
        if (dragIndex === hoverIndex) {
            return
        }
        const hoverBoundingRect = card.value?.getBoundingClientRect()
        const hoverMiddleY = (hoverBoundingRect.bottom - hoverBoundingRect.top) / 2
        const clientOffset = monitor.getClientOffset()
        const hoverClientY = (clientOffset as XYCoord).y - hoverBoundingRect.top

        if (dragIndex < hoverIndex && hoverClientY < hoverMiddleY) {
            return
        }
        if (dragIndex > hoverIndex && hoverClientY > hoverMiddleY) {
            return
        }
        props.moveCard(dragIndex, hoverIndex)
        item.index = hoverIndex
    },
})

const [collect, drag] = useDrag({
    type: ItemTypes.CARD,
    item: () => {
        return { id: props.id, index: props.index }
    },
    collect: (monitor: any) => ({
        isDragging: monitor.isDragging(),
    }),
})

const { handlerId } = toRefs(dropCollect)
const { isDragging } = toRefs(collect)
const opacity = computed(() => (unref(isDragging) ? 0 : 1))

const setRef = (el: HTMLDivElement) => {
    card.value = drag(drop(el)) as HTMLDivElement
}
</script>

<template>
    <div class="form-group card" :ref="setRef" :style="{ opacity }"
         :data-handler-id="handlerId">
        <label :for="id">{{ label }}</label>
        <textarea v-if="type === 'textarea'" :name="name" class="form-control" :required="required" :placeholder="placeholder" :id="id" cols="30" rows="10"></textarea>
        <input v-else :type="type" :name="name" :placeholder="placeholder" :required="required" :class="type === 'checkbox' || type === 'radio' ? 'form-check' : 'form-control'" :id="id"/>
    </div>

</template>

<style scoped>
.card {
    margin-bottom: 0.5rem;
    padding: 0.5rem 1rem;
    background-color: white;
    border: 1px dashed gray;
    cursor: move;
}
</style>
