<template>
    <div class="mr-5 flex space-x-4">
        <div class="relative">
            <a v-bind:href="urlNotifications">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-pf-lightblue hover:text-white" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z" />
                </svg>
                
                <div v-if="notifications > 0" class="absolute rounded-full -right-1.5 -top-0.5 h-4 min-w-[1.5em] px-1 bg-red-500 shadow-sm pointer-events-none text-xs font-semibold text-white flex items-center justify-center">{{ notifications }}</div>
            </a>
        </div>
        <div class="relative">
            <a v-bind:href="urlMessages">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-pf-lightblue hover:text-white" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                </svg>

                <div v-if="messages > 0" class="absolute rounded-full -right-1.5 -top-0.5 h-4 min-w-[1.5em] px-1 bg-red-500 shadow-sm pointer-events-none text-xs font-semibold text-white flex items-center justify-center">{{ messages }}</div>
            </a>
        </div>
    </div>
</template>

<script>
import { ref, onMounted, computed, watch } from "vue";
import axios from 'axios';

export default {
    props: {
        url: String,
        urlNotifications: String,
        urlMessages: String,
        interval: {
            type: Number,
            default: 1000,
        }
    },
    setup(props) {
        const hasErrors = ref(false);
        const notifications = ref(-1);
        const messages = ref(-1);
        const urlMessages = ref('');

        const urlNotifications = computed(() => props.urlNotifications);

        function flashNewNotifications(count) {

        }

        function flashNewMessages(count) {

        }

        function updateBadges() {
            axios.get(props.url, {
                headers: {
                    'Accept': 'application/json'
                }
            }).then(function(response) {
                if (notifications.value != response.data.notifications) {
                    if (notifications.value >= 0 && response.data.notifications > notifications.value) {
                        flashNewNotifications(response.data.notifications);
                    }

                    notifications.value = response.data.notifications;
                }

                if (messages.value != response.data.messages) {
                    if (messages.value >= 0 && response.data.messages > messages.value) {
                        flashNewMessages(response.data.messages);
                    }

                    if (response.data.messages_url) {
                        urlMessages.value = response.data.messages_url;
                    }

                    messages.value = response.data.messages;
                }
            }).catch(function (e) {
                hasErrors.value = true;
                messages.value = 0;
                notifications.value = 0;
            });
        }

        function startHeartbeat() {
            setInterval(() => {
                if (!hasErrors.value) {
                    updateBadges();
                }
            }, props.interval);
        }

        onMounted(() => {
            urlMessages.value = props.urlMessages;

            updateBadges();
            startHeartbeat();
        });

        return {
            hasErrors,
            notifications,
            messages,
            urlNotifications,
            urlMessages,
        };
    },
};
</script>