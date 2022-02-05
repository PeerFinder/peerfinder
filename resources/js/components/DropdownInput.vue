<template>
    <label :for="inputName" v-if="label" :class="'block mb-1 font-medium ' + (hasErrors ? 'text-red-500' : '')">
        {{ label }}
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block" viewBox="0 0 20 20" fill="currentColor" v-if="hasErrors">
            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
        </svg>
    </label>

    <div class="relative" v-click-outside="closeDropDown">
        <div @click="setFocus" class="w-full border border-gray-300 px-2 py-1 rounded-md shadow-sm bg-gray-50 flex flex-wrap items-center focus-within:outline-none focus-within:ring-2 focus-within:ring-pf-midblue focus-within:border-transparent focus-within:bg-white">
            <div v-for="item in selectedItems" :key="item.id" :class="'px-2 py-1 rounded-md mr-2 my-1 flex items-center border ' + (item.error ? 'bg-red-200 border-red-500' : 'border-gray-300 bg-gray-200')">
                <a class="inline-block pr-1 text-gray-500 hover:text-red-600" href="#" @click.prevent="removeSelectedItem(item)">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </a>
                {{ item.value }}
                <input :name="inputName + '[]'" :value="item.id" type="hidden" />
            </div>

            <input type="text" :id="inputName" autocomplete="off"
                    @keydown.esc.prevent="closeDropDown"
                    @keydown.enter.prevent="processEnter"
                    @keydown.down.prevent="processDown"
                    @blur="processBlur"
                    @keydown.up.prevent="processUp"
                    @keydown.prevent.,="processEnter"
                    @keydown.prevent.space="processEnter"
                    ref="inputField" v-model="inputText" :placeholder="placeholder"
                    class="ml-1 px-0 py-1 my-1 border border-transparent focus:border-transparent focus:ring-0 flex-1 bg-transparent"
                    v-if="canShowInputField()" />
        </div>

        <div class="border border-gray-300 absolute mt-1.5 w-full rounded-md shadow-md divide-y divide-solid overflow-hidden bg-white z-20" v-if="canShowDropDown()">
            <div v-for="item in unselectedItems" :key="item.id">
                <a @click.prevent="selectItem(item)" href="#" tabindex="0"
                    :class="'focus:outline-none block p-1 pl-3 ' + (highlightedItem && (highlightedItem.id == item.id) ? 'bg-gray-200' : 'hover:bg-gray-100')">{{ item.value }}</a>
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
        // Delay before getting the date from URL after last key stroke
        lookupDelay: {
            type: Number,
            default: 1000,
        },
        // Where to get the list of items
        url: String,
        // JSON-field storing the items
        itemsField: String,
        // JSON-field storing the ID of an item
        itemsId: String,
        // JSON-field storing the value of an item
        itemsValue: String,
        // How many items can be max selected
        maxSelected: {
            type: Number,
            default: 0,
        },
        // Name of the input field
        inputName: String,
        placeholder: String,
        // Items shown when page loaded
        items: Array,
        // Text of the input label
        label: String,
        // Only values from autosuggestion allowed
        strict: Boolean,
        minSearchLength: {
            type: Number,
            default: 1,
        }
    },
    setup(props) {
        const items = ref([]);
        const selectedItems = ref([]);
        const inputText = ref("");
        const inputField = ref(null);
        const highlightedIndex = ref(-1);
        const hasErrors = ref(false);

        var lookupTimer = null;
        var lastValue = null;

        const unselectedItems = computed(() => {
            return items.value.filter((item) => !itemAlreadySelected(item));
        });

        const inputName = computed(() => props.inputName);
        const placeholder = computed(() => props.placeholder);
        const label = computed(() => props.label);
        const highlightedItem = computed(() => highlightedIndex.value >= 0 ? unselectedItems.value[highlightedIndex.value] : null);

        function resetDropDown() {
            items.value = [];
            highlightedIndex.value = -1;
        }

        function lookup() {
            let url = props.url.replace('$1', lastValue);
            
            resetDropDown();

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

        function canShowDropDown() {
            return unselectedItems.value.length > 0 && canAddMore();
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

                if (highlightedIndex.value >= unselectedItems.value.length) {
                    if (unselectedItems.value.length > 0) {
                        highlightedIndex.value = unselectedItems.value.length - 1;
                    } else {
                        highlightedIndex.value = 0;
                    }
                }

                inputText.value = '';

                setFocus();
            }
        }

        function removeSelectedItem(item) {
            selectedItems.value = selectedItems.value.filter((it) => it.id != item.id);
        }

        function closeDropDown() {
            resetDropDown();
        }

        function processEnter() {
            if (unselectedItems.value.length > 0 && highlightedItem.value != undefined) {
                selectItem(highlightedItem.value);
            } else {
                var input = cleanInput(inputText.value);

                if (!props.strict && input) {
                    selectItem({
                        id: input,
                        value: input,
                    });
                }
            }
        }
        
        function processUp() {
            if (highlightedIndex.value > 0) {
                highlightedIndex.value--;
            }
        }

        function processDown() {
            if (highlightedIndex.value < unselectedItems.value.length - 1) {
                highlightedIndex.value++;
            }
        }

        function processBlur(e) {
            if (!props.strict) {
                if (!e.relatedTarget || e.relatedTarget.tagName != 'A') {
                    processEnter();
                }
            }
        }

        function cleanInput(value) {
            value = value.replace(/[\&\/\\\#\,\+\(\)\$\~\%\.\'\"\:\*\?\!\<\>\{\}]/g, "");
            value = value.replace(/\s+/g, "-");
            return value;
        }        

        watch(inputText, function(val) {
            if (lookupTimer) {
                clearTimeout(lookupTimer);
                lookupTimer = null;
            }

            lastValue = val.trim();

            if (lastValue.length > props.minSearchLength) {
                lookupTimer = setTimeout(lookup, props.lookupDelay);
            } else {
                resetDropDown();
            }
        });

        function setFocus() {
            if (canShowInputField()) {
                inputField.value.focus();
            }
        }

        onMounted(() => {
            if (props.items.length > 0) {
                selectedItems.value = props.items;

                props.items.forEach(item => {
                    if (item.error) {
                        hasErrors.value = true;
                    }
                });
            } /* else {
                selectedItems.value = [
                    { value: "Max Mustermann", id: "USER1", error: true },
                    { value: "Vera Paula Müller", id: "USER2"  },
                    { value: "Alexander Spitz", id: "USER3", error: true },
                ];
            } */
        });

        return {
            inputText,
            inputField,
            items,
            selectedItems,
            unselectedItems,
            lookup,
            selectItem,
            inputName,
            highlightedIndex,
            highlightedItem,
            placeholder,
            label,
            hasErrors,
            closeDropDown,
            canAddMore,
            itemAlreadySelected,
            removeSelectedItem,
            setFocus,
            canShowInputField,
            canShowDropDown,
            processEnter,
            processUp,
            processDown,
            processBlur,
        }
    }
}
</script>

<style scoped>

</style>