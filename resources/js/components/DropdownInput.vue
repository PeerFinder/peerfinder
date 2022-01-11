<template>
    <div class="relative">
        <input type="text" v-model="inputText" class="w-full border px-4 py-2 rounded-md shadow-sm bg-gray-50 flex flex-wrap items-center focus-within:outline-none focus-within:ring-2 focus-within:ring-pf-midblue focus-within:border-transparent focus-within:bg-white" />

        <div class="absolute mt-1 w-full rounded-md shadow divide-y divide-solid overflow-hidden" v-if="items.length > 0">
            <div v-for="item in items" :key="item.name">
                <a href="#" class="block p-1 pl-3 hover:bg-gray-100">{{ item.name }}</a>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, onMounted, computed, watch } from 'vue';
import axios from 'axios';

export default {
    props: {
        lookupDelay: {
            type: Number,
            default: 1000,
        },
        url: String,
        itemsField: String,
    },
    setup(props) {
        const items = ref([]);
        const inputText = ref("");

        var lookupTimer = null;
        var lastValue = null;

        function lookup() {
            let url = props.url.replace('$1', lastValue);

            console.log('lookup: ' + url);

            axios.get(url, {
                headers: {
                    'Accept': 'application/json'
                }
            }).then(function(response) {
                items.value = response.data[props.itemsField];

            }).catch(function (e) {

            }).finally(function() {

            });
        }

        watch(inputText, function(val) {
            if (lookupTimer) {
                clearTimeout(lookupTimer);
                lookupTimer = null;
            }

            lastValue = val.trim();

            if (lastValue.length > 1) {
                lookupTimer = setTimeout(lookup, props.lookupDelay);
            }
        });

        onMounted(() => {
            items.value = [
                { name: "Max Mustermann" },
                { name: "Vera Paula Müller" },
                { name: "Alexander Spitz" },
            ];
        });

        return {
            inputText,
            items,
            lookup,
        }
    }
}
</script>

<style scoped>

</style>