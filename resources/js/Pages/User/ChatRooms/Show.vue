<script setup>
import {Link, useForm} from '@inertiajs/vue3';
import PagesLayout from '@/Layouts/PagesLayout.vue';
import ChatBox from '@/Components/Chat/ChatBox.vue';
import { nextTick, onBeforeUnmount, onMounted, onUnmounted, ref, watch } from 'vue';
import ChatInput from '@/Components/Chat/ChatInput.vue';
import ChatActives from '@/Components/Chat/ChatActives.vue';
const props = defineProps(['room', 'messages']);
const form = useForm({
    body: '',
    room: '',
});
const updateUserForm = useForm({
    room: '',
    count: 0,
});
const activeCount = ref(0);
const my_users = ref([]);
const scrollToBottom = () => {
    let container = document.querySelector('#container');
    container.scrollTop = container.scrollHeight
};
const connect = () => {
    //Echo.private(`chat.${props.room.id}`)
    //    .listen('NewChatMessageEvent', (e) => {
    //        let newMessage = {
    //            body: e.message.body,
    //            user: e.user
    //        }
    //        props.messages.push(newMessage)
    //    })
    Echo.join(`chat.${props.room.id}`)
        .here((users) => {
            activeCount.value = users.length
            my_users.value = users
            updateUserForm.count = activeCount.value
            getActive()
        })
        .joining((user) => {
            activeCount.value += 1
            my_users.value.push(user)
            updateUserForm.count = activeCount.value
            getActive()
        })
        .leaving((user) => {
            activeCount.value -= 1
            my_users.value.splice(my_users.value.indexOf(user), 1)
            updateUserForm.count = activeCount.value
            getActive()
        })
        .listen('NewChatMessageEvent', (e) => {
            let newMessage = {
                body: e.message.body,
                user: e.user
            }
            props.messages.push(newMessage)
            scrollToBottom()
        })
    }
onMounted(() => {
    connect()
    scrollToBottom()
    getActive()
});
const submitMessage = () => {
    form.post(route('chat-rooms.store', props.room) , {
        preserveScroll: true,
        onSuccess: () => {
            scrollToBottom()
            form.body = null;
        },
    })
}
onBeforeUnmount(() => {
    if(my_users.value.length == 1)
    {
        updateUserForm.count = 0
        getActive()
    }
    else {
        getActive()
    }
});
onUnmounted(() => {
    Echo.leave(`chat.${props.room.id}`)
});
watch(() => props.messages,  () => {
    nextTick(function() {
        scrollToBottom()
    })
}, {
    deep: true,
});
const getActive = () => {
    updateUserForm.post(route('chat-rooms.update', props.room), {
        preserveScroll: true,
        onSuccess: () => {}
    })
}
</script>
<template>
    <PagesLayout>
        <template #title>
            <div class="flex flex-col">
                <Link :href="route('chat-rooms.index')" class="flex text-gray-800">
                    <Icon name="angle-left" class="w-3 h-3 fill-current"></Icon>
                    <span class="font-semibold text-lg capitalize underline ml-2">Back</span>
                </Link>
                <div class="flex justify-between items-center mt-5">
                    <h3 class="font-semibold text-lg text-gray-800 leading-tight">
                        Welcome to: {{ room.name }}
                    </h3>
                    <h4 v-if="activeCount > 0">
                       {{ activeCount }} active users 
                    </h4>
                </div>

                <div class="flex space-x-4 mt-5">
                    <div class="flex-shrink-0 rounded-md border border-gray-300 h-128">
                        <ChatActives :users="my_users"></ChatActives>
                    </div>
                    <div class="flex-1 rounded-md border border-gray-800 flex flex-col h-128">
                        <ChatBox :messages="messages" id="container"></ChatBox>
                        <ChatInput :method="submitMessage" :form="form" :item="room"></ChatInput>
                    </div>
                </div>
            </div>
        </template>
    </PagesLayout>
</template>