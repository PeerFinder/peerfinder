<template>
    <div class="lg:w-2/3 mx-auto px-5 py-4 border bg-sky-200 border-sky-300 lg:rounded-md shadow-md flex items-center space-x-2" v-if="visible">
        <div class="flex-1">
            <h2 class="mb-2 font-semibold" v-if="title">{{ title }}</h2>
            <div class="prose">
                <slot></slot>
            </div>
        </div>
        <div v-if="closable">
            <button class="px-3 py-1 rounded-md bg-sky-300 text-sky-900 hover:bg-sky-400 hover:text-sky-100 shadow-sm" @click="close">{{ closeCaption }}</button>
        </div>
    </div>
</template>

<script>
import { ref } from 'vue';
import axios from 'axios';

export default {
    props: {
        title: String,
        body: String,
        closable: Number,
        closeUrl: String,
        closeCaption: String,
    },
    setup(props) {
        const title = ref('');
        const body = ref('');
        const closeCaption = ref('');
        const visible = ref(true);
        const closable = ref(true);

        title.value = props.title;
        body.value = props.body;
        closeCaption.value = props.closeCaption;
        closable.value = props.closable;

        function close() {
            axios.post(props.closeUrl, {
                headers: {
                    'Accept': 'application/json'
                }
            }).then(function(response) {
                visible.value = false;
            });
        }

        return {
            title,
            body,
            closeCaption,
            visible,
            closable,
            close,
        };
    }
}
</script>

<style>

</style>