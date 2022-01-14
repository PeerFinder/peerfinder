<template>
    <div class="relative" v-click-outside="closeDropDown">
        <input type="text" v-model="inputText" class="w-full border px-4 py-2 rounded-md shadow-sm bg-gray-50 flex flex-wrap items-center focus-within:outline-none focus-within:ring-2 focus-within:ring-pf-midblue focus-within:border-transparent focus-within:bg-white" />

        <div v-for="item in selectedItems" :key="item.id">
            {{ item.value }}
        </div>

        <div class="border border-gray-400 absolute mt-1.5 w-full rounded-md shadow divide-y divide-solid overflow-hidden" v-if="items.length > 0 && canAddMore()">
            <div v-for="item in items" :key="item.id">
                <a @click.prevent="selectItem(item)" href="#" class="block p-1 pl-3 hover:bg-gray-100">{{ item.value }}</a>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, onMounted, computed, watch } from 'vue';
import axios from 'axios';
import vClickOutside from 'click-outside-vue3';

export default {
    directives: {
      clickOutside: vClickOutside.directive
    },
    props: {
        lookupDelay: {
            type: Number,
            default: 1000,
        },
        url: String,
        itemsField: String,
        itemsId: String,
        itemsValue: String,
        maxSelected: {
            type: Number,
            default: 0,
        }
    },
    setup(props) {
        const items = ref([]);
        const selectedItems = ref([]);
        const inputText = ref("");

        var lookupTimer = null;
        var lastValue = null;

        function lookup() {
            let url = props.url.replace('$1', lastValue);
            items.value = [];

            axios.get(url, {
                headers: {
                    'Accept': 'application/json'
                }
            }).then(function(response) {
                response.data[props.itemsField].forEach(item => {
                    items.value.push({
                        'id': item[props.itemsId],
                        'value': item[props.itemsValue],
                    });
                });
            }).catch(function (e) {

            }).finally(function() {

            });
        }

        function canAddMore() {
            return (props.maxSelected == 0) || (selectedItems.value.length < props.maxSelected);
        }

        function getItem(id) {
            return selectedItems.value.find((item) => item.id == id);
        }

        function selectItem(item) {
            if (canAddMore()) {
                if (getItem(item.id) == undefined) {
                    selectedItems.value.push(item);
                }
            }
        }

        function closeDropDown() {
            items.value = [];
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
                { value: "Max Mustermann", id: "USER1" },
                { value: "Vera Paula Müller", id: "USER2"  },
                { value: "Alexander Spitz", id: "USER3" },
            ];
        });

        return {
            inputText,
            items,
            selectedItems,
            lookup,
            selectItem,
            closeDropDown,
            canAddMore,
        }
    }
}
</script>

<style scoped>

</style>