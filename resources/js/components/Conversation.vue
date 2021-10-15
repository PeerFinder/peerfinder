<template>
    <div class="space-y-6">
        <slot name="replies" :actionReply="reply"></slot>
        <slot name="reply-form" :reply="replyId"></slot>

    </div>
</template>

<script>
import { ref } from 'vue';

export default {
    setup() {
        const replyId = ref();

        function reply(identifier) {
            console.log('Reply: ' + identifier);
            replyId.value = identifier;
        }

        return {
            replyId,
            reply
        }
    }
}
</script>

<style>

</style>