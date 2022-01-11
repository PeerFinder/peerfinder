require('./bootstrap');

import { createApp } from 'vue';
import HeaderMenu from './components/HeaderMenu.vue';
import CollapsableHeaderMenu from './components/CollapsableHeaderMenu.vue';
import TimezoneSelect from './components/TimezoneSelect.vue';
import ConditionalElements from './components/ConditionalElements.vue';
import EditableList from './components/EditableList.vue';
import Conversation from './components/Conversation.vue';
import TagsInput from './components/TagsInput.vue';
import CollapsedContent from './components/CollapsedContent.vue';
import DropdownInput from './components/DropdownInput.vue';
import vClickOutside from "click-outside-vue3";

const app = createApp({
    'components': {
        'header-menu': HeaderMenu,
        'collapsable-header-menu': CollapsableHeaderMenu,
        'timezone-select': TimezoneSelect,
        'conditional-elements': ConditionalElements,
        'editable-list': EditableList,
        'conversation': Conversation,
        'collapsed-content': CollapsedContent,
        'tags-input': TagsInput,
        'dropdown-input': DropdownInput,
    }
});

app.mount('#app');
app.use(vClickOutside);