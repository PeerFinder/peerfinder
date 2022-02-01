<template>
    <div>
        <slot v-for="item in items" :key="item.id" name="list-item" :item="item" :actionRemove="removeItem"></slot>
        <slot name="add-button" :actionAdd="addItem"></slot>
    </div>
</template>

<script>
import { ref } from 'vue';

export default {
    props: {
        initial: Array,
    },
    setup(props) {
        const items = ref([]);
        var last_id = 0;

        props.initial.forEach(function (item, key) {
            addItem(item);
        });

        function addItem(data) {
            items.value.push({
                id: last_id,
                data: data,
            });

            last_id++;
        }

        function removeItem(item) {
            items.value = items.value.filter(function(element) {
                return element.id !== item.id;
            });
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