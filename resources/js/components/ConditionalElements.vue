<template>
  <div :class="this.class">
      <slot :state="state"></slot>
  </div>
</template>

<script>
export default {
    props: {
        trigger: String,
        class: String,
    },
    data() {
        return {
            state: 0,
            element: null,
        }
    },
    methods: {
        updateState() {
            if (this.element.type == 'checkbox') {
                this.state = this.element.checked;
            } else {
                this.state = this.element.value;
            }
        }
    },
    mounted() {
        let _this = this;

        this.element = window.document.getElementById(this.trigger);

        this.updateState();

        this.element.addEventListener('change', function (e) {
            _this.updateState();
        });
    }
}
</script>

<style>

</style>