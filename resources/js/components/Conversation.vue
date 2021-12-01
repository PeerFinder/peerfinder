<template>
    <div class="space-y-2">
        <slot name="replies" :actionReply="reply" :actionEdit="edit" :reply="replyId" :editing="editingId" :isBusy="isBusy" :updates="updates"></slot>

        <teleport :to="'#reply-' + replyId + ' .edit-bar'" v-if="replyId">
            <slot name="reply-form" :reply="replyId"></slot>
        </teleport>

        <teleport :to="'#edit-bar-' + editingId" v-if="isBusy">
            <div class="w-4 h-4">
                <svg class="animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
        </teleport>

        <teleport :to="'#reply-' + editingId + ' .content'" v-if="editingId">
            <div class="bg-red-300 border-red-500 rounded-lg shadow border py-2 px-3 mb-1" v-if="error">
                {{ error }}
            </div>

            <slot name="editing-form" :edit="editingId" :actionSave="save" :actionCancel="cancel" :isBusy="isBusy" :message="rawMessage" v-if="rawMessage"></slot>
        </teleport>
    </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import axios from 'axios';

export default {
    props: {
        reply: String,
        highlighted: String,
        conversation: String,
    },
    setup(props) {
        const replyId = ref();
        const editingId = ref();
        const isBusy = ref(false);
        const error = ref();
        const rawMessage = ref();
        const updates = ref({});

        function reply(identifier) {
            replyId.value = identifier;
            editingId.value = null;
        }

        function edit(identifier) {
            replyId.value = null;
            isBusy.value = true;
            editingId.value = identifier;
            error.value = null;
            rawMessage.value = null;

            loadReplyFromServer(identifier)
                .then(function (response) {
                    rawMessage.value = response.data.message;
                })
                .catch(function (e) {
                    error.value = e.response.data.message;
                })
                .finally(function () {
                    isBusy.value = false;
                });
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

        async function sendReplyToServer(reply, data) {
            let url = "/conversations/" + props.conversation + '/replies/' + reply + '/update';

            return axios.put(url, data, {
                headers: {
                    'Accept': 'application/json'
                }
            });
        }        

        async function loadReplyFromServer(reply, raw = true) {
            let url = "/conversations/" + props.conversation + '/replies/' + reply + '/show?raw=' + raw;

            return axios.get(url, {
                headers: {
                    'Accept': 'application/json'
                }
            });
        }

        function save(event) {
            isBusy.value = true;

            let inputElement = document.getElementById('reply-content');

            if (inputElement.value) {
                rawMessage.value = inputElement.value;
            }

            sendReplyToServer(editingId.value, {
                'message': inputElement.value
            }).then(function (response) {
                    loadReplyFromServer(editingId.value, false)
                        .then(function (response) {
                            updates.value[editingId.value] = response.data.message;
                            editingId.value = null;
                        })
                        .catch(function (e) {
                            error.value = e.response.data.message;
                        })
                        .finally(function () {
                            isBusy.value = false;
                        });
                })
                .catch(function (e) {
                    if (Array.isArray(e.response.data.message)) {
                        error.value = e.response.data.message[0];
                    } else {
                        error.value = e.response.data.message;
                    }
                })
                .finally(function () {
                    isBusy.value = false;
                });
        }

        function cancel(event) {
            replyId.value = null;
            editingId.value = null;
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
            isBusy,
            error,
            rawMessage,
            updates,
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