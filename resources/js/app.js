require('./bootstrap');

import { createApp } from 'vue';
import HeaderMenu from './components/HeaderMenu.vue';
import TimezoneSelect from './components/TimezoneSelect.vue';
import ConditionalElements from './components/ConditionalElements.vue';
import EditableList from './components/EditableList.vue';
import Conversation from './components/Conversation.vue';
import vClickOutside from "click-outside-vue3";

const app = createApp({
    'components': {
        'header-menu': HeaderMenu,
        'timezone-select': TimezoneSelect,
        'conditional-elements': ConditionalElements,
        'editable-list': EditableList,
        'conversation': Conversation,
    }
});

app.mount('#app');
app.use(vClickOutside);