<template>
    <div>
        <slot v-for="item in items" name="item" :actionRemove="removeItem"></slot>

        <slot name="add-button" :actionAdd="addItem"></slot>
    </div>
</template>

<script>
import { ref } from 'vue';

export default {
    setup() {
        const items = ref([]);

        function addItem() {
            items.value.push({});
        }

        function removeItem() {
            
        }

        return {
            addItem,
            removeItem,
            items
        };
    }
}
</script>

<style>

</style>