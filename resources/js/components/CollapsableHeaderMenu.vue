<template>
    <div class="relative" v-if="collapsed" v-click-outside="closeMenu">
        <div>
            <a @click.prevent="toggleMenu" href="#" ref="trigger"><slot name="trigger"></slot></a>
        </div>
        <div class="dropdown z-10 transition-opacity absolute collapsed" :class="containerClasses">
            <div class="overflow-hidden rounded-md bg-white shadow-lg">
                <slot name="content"></slot>
            </div>
        </div>
    </div>

    <div class="not-collapsed" v-if="!collapsed">
        <slot name="content"></slot>
    </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import vClickOutside from 'click-outside-vue3';

export default {
    directives: {
      clickOutside: vClickOutside.directive
    },
    props: {
        breakPoint: {
            default: 0
        },
        dropdownClass: String,
    },
    setup(props) {
        const trigger = ref(null);
        const isVisible = ref(false);
        const collapsable = ref(false);
        const collapsed = ref(true);
        const containerClasses = ref('');

        function toggleMenu() {
            isVisible.value = !isVisible.value;
            makeContainerClasses();
        }

        function makeContainerClasses() {
            const vw = Math.max(document.documentElement.clientWidth || 0, window.innerWidth || 0);
            var classes = [];

            if (!isVisible.value) {
                classes.push('sr-only', 'opacity-0', 'dropdown-visible');
            } else {
                classes.push('dropdown-hidden');
            }

            const left = trigger.value.getBoundingClientRect().left;

            if (left < vw / 3) {
                classes.push('left-0 dropdown-left');
            } else if (left > vw / 3 * 2) {
                classes.push('right-0 dropdown-right');
            } else {
                classes.push('left-1/2 -translate-x-1/2 dropdown-middle');
            }

            if (props.dropdownClass) {
                classes.push(props.dropdownClass);
            }

            containerClasses.value = classes.join(' ');
        }

        function closeMenu() {
            if (isVisible.value) {
                isVisible.value = false;
                makeContainerClasses();
            }
        }

        function onResize() {
            if (window.innerWidth < props.breakPoint) {
                collapsed.value = true;
            } else {
                collapsed.value = false;
            }
        }

        onMounted(() => {
            if (props.breakPoint > 0) {
                collapsable.value = true;
                window.addEventListener('resize', onResize, { passive: true });
                onResize();
            } else {
                collapsable.value = false;
                collapsed.value = true;
            }

            isVisible.value = false;
            makeContainerClasses();
        });

        return {
            trigger,
            isVisible,
            collapsable,
            collapsed,
            toggleMenu,
            closeMenu,
            containerClasses,
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