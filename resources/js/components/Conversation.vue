<template>
    <div class="space-y-4">
        <slot name="replies" :actionReply="reply" :actionEdit="edit" :reply="replyId" :editing="editingId"></slot>

        <teleport :to="'#reply-' + replyId + ' .edit-bar'" v-if="replyId">
            <slot name="reply-form" :reply="replyId"></slot>
        </teleport>

        <teleport :to="'#reply-' + editingId + ' .content'" v-if="editingId">
            <slot name="editing-form" :edit="editingId"></slot>
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
        const editingId = ref();

        function reply(identifier) {
            replyId.value = identifier;
            editingId.value = null;
        }

        function edit(identifier) {
            editingId.value = identifier;
            replyId.value = null;
        }

        function scrollToReply(reply) {
            var highlightedReply = document.getElementById('reply-' + reply);

            if (typeof(highlightedReply) != undefined) {
                highlightedReply.scrollIntoView({inline: "nearest"});
            }
        }

        onMounted(() => {
            if (history.scrollRestoration) {
                history.scrollRestoration = 'manual';
            }

            if (props.reply) {
                reply(props.reply);
                scrollToReply(props.reply);
            } else if (props.highlighted) {
                scrollToReply(props.highlighted);
            }
        });

        return {
            replyId,
            editingId,
            reply,
            edit,
        }
    }
}
</script>

<style scoped>

</style>