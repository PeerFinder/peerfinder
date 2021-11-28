<template>
    <div class="space-y-6">
        <slot name="replies" :actionReply="reply" :reply="replyId"></slot>
        <teleport :to="'#reply-' + replyId + ' .edit-bar'" v-if="replyId">
            <slot name="reply-form" :reply="replyId"></slot>
        </teleport>
    </div>
</template>

<script>
import { ref, onMounted } from 'vue';

export default {
    props: {
        reply: String,
        highlighted: String,
    },
    setup(props) {
        const replyId = ref();

        function reply(identifier) {
            replyId.value = identifier;
        }

        function scrollToReply(reply) {
            var highlightedReply = document.getElementById('reply-' + reply);

            if (typeof(highlightedReply) != undefined) {
                highlightedReply.scrollIntoView({block: "end", inline: "nearest"});
            }
        }

        onMounted(() => {
            if (props.reply) {
                reply(props.reply);
                scrollToReply(props.reply);
            } else if (props.highlighted) {
                scrollToReply(props.highlighted);
            }
        });

        return {
            replyId,
            reply
        }
    }
}
</script>

<style>

</style>