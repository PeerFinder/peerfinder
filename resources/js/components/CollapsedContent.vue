<template>
  <div>
    <a @click.prevent="toggleMenu" href="#" v-if="collapsable"><slot name="trigger"></slot></a>

    <div :class="((!isVisible && collapsable) && 'sr-only')">
        <div>
            <slot name="content"></slot>
        </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue';

export default {
    props: {
        breakPoint: {
            default: 0
        },
    },
    setup(props) {
        const isVisible = ref(false);
        const collapsable = ref(false);

        function toggleMenu(e) {
            isVisible.value = !isVisible.value;
        }

        function closeMenu() {
            if (isVisible.value) {
                isVisible.value = false;
            }
        }

        function onResize() {
            if (window.innerWidth < props.breakPoint) {
                collapsable.value = true;
            } else {
                collapsable.value = false;
            }
        }

        onMounted(() => {
            if (props.breakPoint > 0) {
                window.addEventListener('resize', onResize, { passive: true });
                onResize();
            } else {
                collapsable.value = true;
            }

            isVisible.value = false;
        });

        return {
            isVisible,
            collapsable,
            toggleMenu,
            closeMenu,
        }
    }
};
</script>