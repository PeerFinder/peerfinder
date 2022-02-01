<template>
  <div>
      <input name="timezone" id="timezone" type="text" v-bind:value="timezone" class="hidden" />
  </div>
</template>

<script>
export default {
    data() {
        return {
            timezone: null,
        }
    },
    mounted() {
        this.timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
    },
}
</script>