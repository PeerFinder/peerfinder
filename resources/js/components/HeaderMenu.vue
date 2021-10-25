<template>
  <div class="relative" v-click-outside="closeMenu">
    <a @click.prevent="toggleMenu" href="#"><slot name="trigger"></slot></a>
    <div class="dropdown transition-opacity absolute w-60" :class="(!isVisible && 'sr-only opacity-0') + ' ' + positionClass">
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
            positionClass: 'right-3',
        }
    },
    methods: {
        toggleMenu(e) {
            this.isVisible = !this.isVisible;

            const vw = Math.max(document.documentElement.clientWidth || 0, window.innerWidth || 0);

            if (e.clientX < vw / 3) {
                this.positionClass = 'left-0 dropdown-left';
            } else if (e.clientX > vw / 3 * 2) {
                this.positionClass = 'right-0 dropdown-right';
            } else {
                this.positionClass = 'left-1/2 -translate-x-1/2 dropdown-middle';
            }
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
    transform: translateY(-100%);
    width: 10px; 
    height: 10px; 
    border-left: 10px solid transparent;
    border-right: 10px solid transparent;
    border-bottom: 10px solid #fff;
}
.dropdown-right::before {
    right: 20px;
}
.dropdown-middle::before {
    left: 50%;
    margin-left: -10px;
}
.dropdown-left::before {
    left: 20px;
}
</style>