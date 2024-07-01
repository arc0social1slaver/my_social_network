<script setup>
import NotificationItem from '@/Components/NotificationItem.vue';
defineProps(['unreads', 'reads']);
</script>
<template>
    <NotificationItem v-for="unread in unreads" :key="unread.id" :unread="unread" class="flex justify-between items-center bg-gray-300 text-sm text-gray-700 hover:bg-gray-700 hover:text-gray-300 px-4 py-2 transition duration-150 ease-in-out">
    </NotificationItem>
    <NotificationItem v-for="read in reads" :key="read.id" :read="read" class="flex justify-between items-center bg-gray-100 text-gray-400 hover:bg-gray-300 hover:text-gray-700 px-4 py-2 transition duration-150 ease-in-out">

    </NotificationItem>
</template>