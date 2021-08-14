<template>
  <div class="relative" v-click-outside="closeMenu">
    <a @click.prevent="toggleMenu" href="#"><slot name="trigger"></slot></a>
    <div :class="!isVisible && 'sr-only'" class="dropdown absolute right-3 w-60">
        <div class="after:bg-gradient-to-r after:from-yellow-400 after:to-yellow-600 after:h-1 after:block overflow-hidden rounded-md bg-white shadow-lg">
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

<style scoped>
.dropdown::before {
    position: absolute;
    content: "";
    top: 0;
    right: 15px;
    transform: translateY(-100%);
    width: 10px; 
    height: 10px; 
    border-left: 10px solid transparent;
    border-right: 10px solid transparent;
    border-bottom: 10px solid #fff;
}
</style>