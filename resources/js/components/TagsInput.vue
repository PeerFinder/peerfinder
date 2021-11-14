<template>
    <div class="w-full border px-4 py-2 rounded-md shadow-sm bg-gray-50 flex flex-wrap items-center focus-within:outline-none focus-within:ring-2 focus-within:ring-pf-midblue focus-within:border-transparent focus-within:bg-white">
        <div v-for="(tag, index) in tags" :key="tag" class="bg-gray-200 px-2 py-1 rounded-md mr-1 my-1 flex items-center">
            <a class="inline-block pr-1 text-gray-500 hover:text-red-600" href="#" @click.prevent="removeTag(index)">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
            </a>
            {{ tag }}
            <input :name="name + '[]'" :value="tag" type="hidden" />
        </div>
        <input v-if="!isFull" type="text" :id="name" class="ml-1 px-0 py-1 my-1 border-0 focus:ring-0 flex-1 bg-transparent" @keydown.prevent.enter="addTag" @keydown.prevent.,="addTag" @blur="addTag" @keydown.prevent.space="addTag" />
    </div>
</template>

<script>
import { ref, onMounted, computed } from 'vue';

export default {
    props: {
        limit: String,
        old: Array,
        name: String,
    },
    setup(props) {
        const tags = ref([]);
        const name = ref(props.name);

        const isFull = computed({
            get: () => props.limit > 0 && tags.value.length >= props.limit
        });

        function cleanInput(value) {
            value = value.replace(/[\&\/\\\#\,\+\(\)\$\~\%\.\'\"\:\*\?\!\<\>\{\}]/g, "");
            value = value.replace(/\s+/g, "-");
            return value;
        }

        function addTag(e) {
            var val = cleanInput(e.target.value.trim());
            
            if (val.length > 0) {
                if (!isFull.value) {
                    tags.value.push(val);
                    e.target.value = "";
                }
            }
        }

        function removeTag(index) {
            tags.value.splice(index, 1);
        }

        onMounted(function (){
            console.log(props.old);
            
            if (props.old) {
                props.old.forEach(element => {
                    tags.value.push(element);
                });
            }
        });

        return {
            tags,
            addTag,
            removeTag,
            isFull,
            name,
        }
    }
}
</script>