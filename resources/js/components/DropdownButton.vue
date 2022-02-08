<template>
    <div class="relative" v-click-outside="closeDropdown">
        <slot name="trigger" :actionToggle="toggleDropdown"></slot>

        <div class="absolute bg-white border border-gray-300 rounded-md shadow-md w-full mt-1 overflow-hidden" v-if="isVisible">
            <slot name="dropdown"></slot>
        </div>
    </div>
</template>

<script>
import { ref, onMounted, computed, watch } from "vue";
import vClickOutside from "click-outside-vue3";

export default {
    directives: {
        clickOutside: vClickOutside.directive,
    },
    props: {},
    setup(props) {
        const isVisible = ref(false);

        function closeDropdown() {
            isVisible.value = false;
        }

        function toggleDropdown() {
            isVisible.value = !isVisible.value;
        }
    
        onMounted(() => {});

        return {
            isVisible,
            toggleDropdown,
            closeDropdown,
        };
    },
};
</script>

<style scoped>
</style>