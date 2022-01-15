<template>
    <div class="relative" v-click-outside="closeDropDown">

        <div @click="setFocus" class="w-full border px-2 py-1 rounded-md shadow-sm bg-gray-50 flex flex-wrap items-center focus-within:outline-none focus-within:ring-2 focus-within:ring-pf-midblue focus-within:border-transparent focus-within:bg-white">

            <div v-for="item in selectedItems" :key="item.id" class="bg-gray-200 px-2 py-1 rounded-md mr-1 my-1 flex items-center">
                <a class="inline-block pr-1 text-gray-500 hover:text-red-600" href="#" @click.prevent="removeSelectedItem(item)">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </a>
                {{ item.value }}
            </div>

            <input type="text" ref="inputField" v-model="inputText" class="ml-1 px-0 py-1 my-1 border-0 focus:ring-0 flex-1 bg-transparent" v-if="canShowInputField()" />
        </div>

        <div class="border border-gray-400 absolute mt-1.5 w-full rounded-md shadow divide-y divide-solid overflow-hidden" v-if="unselectedItems.length > 0 && canAddMore()">
            <div v-for="item in unselectedItems" :key="item.id">
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
        const inputField = ref(null);

        var lookupTimer = null;
        var lastValue = null;

        var unselectedItems = computed(() => {
            return items.value.filter((item) => !itemAlreadySelected(item));
        });

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

        function canShowInputField() {
            return canAddMore();
        }

        function getItem(id) {
            return selectedItems.value.find((item) => item.id == id);
        }

        function itemAlreadySelected(item) {
            return getItem(item.id) != undefined;
        }

        function selectItem(item) {
            if (canAddMore()) {
                if (!itemAlreadySelected(item)) {
                    selectedItems.value.push(item);
                }
            }
        }

        function removeSelectedItem(item) {
            selectedItems.value = selectedItems.value.filter((it) => it.id != item.id);
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

        function setFocus() {
            if (canShowInputField()) {
                inputField.value.focus();
            }
        }

        onMounted(() => {
            items.value = [
                { value: "Max Mustermann", id: "USER1" },
                { value: "Vera Paula Müller", id: "USER2"  },
                { value: "Alexander Spitz", id: "USER3" },
            ];
        });

        return {
            inputText,
            inputField,
            items,
            selectedItems,
            unselectedItems,
            lookup,
            selectItem,
            closeDropDown,
            canAddMore,
            itemAlreadySelected,
            removeSelectedItem,
            setFocus,
            canShowInputField,
        }
    }
}
</script>

<style scoped>

</style>