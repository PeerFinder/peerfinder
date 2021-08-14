<template>
  <div class="relative" v-click-outside="closeMenu">
    <a @click.prevent="toggleMenu" href="#"><slot name="trigger"></slot></a>
    <div :class="!isVisible && 'sr-only'" class="absolute right-3 top-2/3 w-60 bg-white rounded-md overflow-hidden shadow-md">
        <div class="after:bg-gradient-to-r after:from-yellow-400 after:to-yellow-600 after:h-1 after:block">
            <slot name="content"></slot>
        </div>
    </div>
  </div>
</template>

<script>
import vClickOutside from 'click-outside-vue3'

export default {
    directives: {
      clickOutside: vClickOutside.directive
    },
    data() {
        return {
            isVisible: false,
        }
    },
    methods: {
        toggleMenu() {
            this.isVisible = !this.isVisible;
        },
        closeMenu() {
            if (this.isVisible) {
                this.isVisible = false;
            }
        }
    }
};
</script>
