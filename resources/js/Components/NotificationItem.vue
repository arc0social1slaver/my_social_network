<script setup>
import {Link} from '@inertiajs/vue3';
import axios from 'axios';
defineProps(['unread', 'read']);
const markOneAsRead = (id) => {
    axios.post(route('notifications.store', id));
};
const markForDelete = (id) => {
    axios.get(route('notifications.destroy', id))
        .then(() => {
            Toast.fire({
                icon: 'success',
                title: 'Your notification has successfully been deleted!'
            });
        })
};
</script>
<template>
<div>
    <template v-if="unread">
        <Link :href="unread.data.info.link" class="flex leading-5" @click="markOneAsRead(unread.id)">
            <img :src="unread.data.info.avatar" class="h-8 w-8 rounded-full object-cover">
            <div class="flex flex-col items-start text-left">
                <span class="text-sm mx-2">
                    {{ unread.data.info.message }}
                </span>
                <span class="text-xs mx-2">
                    {{ $filters.timeAgo(unread.data.info.sent) }}
                </span>
            </div>
        </Link>
        <Link href="#" class="p-2 rounded-md text-gray-500 hover:bg-gray-500 hover:text-gray-300" @click="markForDelete(unread.id)">
            <Icon name="trash" class="w-4 h-4 fill-current"></Icon>
        </Link>
    </template>

    <template v-if="read">
        <Link :href="read.data.info.link" class="flex leading-5">
            <img :src="read.data.info.avatar" class="h-8 w-8 rounded-full object-cover">
            <div class="flex flex-col items-start text-left">
                <span class="text-sm mx-2">
                    {{ read.data.info.message }}
                </span>
                <span class="text-xs mx-2">
                    {{ $filters.timeAgo(read.data.info.sent) }}
                </span>
            </div>
        </Link>
        <Link href="#" class="p-2 rounded-md text-gray-500 hover:bg-gray-500 hover:text-gray-300" @click="markForDelete(read.id)">
            <Icon name="trash" class="w-4 h-4 fill-current"></Icon>
        </Link>
    </template>
</div>
</template>