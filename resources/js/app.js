require('./bootstrap');

import { createApp } from 'vue';
import HeaderMenu from './components/HeaderMenu.vue';
import TimezoneSelect from './components/TimezoneSelect.vue';
import ConditionalElements from './components/ConditionalElements.vue';
import BookmarksEditor from './components/BookmarksEditor.vue';
import vClickOutside from "click-outside-vue3";

const app = createApp({
    'components': {
        'header-menu': HeaderMenu,
        'timezone-select': TimezoneSelect,
        'conditional-elements': ConditionalElements,
        'bookmarks-editor': BookmarksEditor,
    }
});

app.mount('#app');
app.use(vClickOutside);