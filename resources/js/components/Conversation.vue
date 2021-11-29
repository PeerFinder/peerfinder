<template>
    <div class="space-y-2">
        <slot name="replies" :actionReply="reply" :actionEdit="edit" :reply="replyId" :editing="editingId"></slot>

        <teleport :to="'#reply-' + replyId + ' .edit-bar'" v-if="replyId">
            <slot name="reply-form" :reply="replyId"></slot>
        </teleport>

        <teleport :to="'#reply-' + editingId + ' .content'" v-if="editingId">
            <slot name="editing-form" :edit="editingId" :actionSave="save" :actionCancel="cancel"></slot>
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

        function isInViewport(element) {
            const rect = element.getBoundingClientRect();

            return (
                rect.top >= 0 &&
                rect.left >= 0 &&
                rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
                rect.right <= (window.innerWidth || document.documentElement.clientWidth)
            );
        }

        function scrollToReply(reply) {
            var highlightedReply = document.getElementById('reply-' + reply);

            if (typeof(highlightedReply) != undefined && !isInViewport(highlightedReply)) {
                highlightedReply.scrollIntoView({inline: "nearest"});
            }
        }

        function save() {
            alert("save");
        }

        function cancel() {
            alert("cancel");
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
            save,
            cancel,
        }
    }
}
</script>

<style scoped>

</style>