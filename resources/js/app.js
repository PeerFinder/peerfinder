require('./bootstrap');

import { createApp } from 'vue';
import HeaderMenu from './components/HeaderMenu.vue';
import vClickOutside from "click-outside-vue3";

const app = createApp({
    'components': {
        'header-menu': HeaderMenu
    }
});

app.mount('#app');
app.use(vClickOutside);