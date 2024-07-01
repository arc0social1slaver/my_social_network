<script setup>
import {Link, } from '@inertiajs/vue3';
defineProps(['messages']);
</script>
<template>
    <div class="h-full overflow-y-auto border-b">
        <div class="border-b px-3 py-3" :class="message.user.id != $page.props.auth.user.id ? 'text-right' : 'text-left bg-gray-600 text-gray-200'" v-for="message in messages" :key="message.id">
            <div class="flex items-center" :class="message.user.id != $page.props.auth.user.id ? 'justify-end bg-gray-300' : ' '">
                <img :src="message.user.profile_photo_url" class="w-4 h-4 rounded-full object-cover" :alt="message.user.username">
                <Link :href="route('profiles.show', message.user.username)">
                    <span class="font-semibold tracking-wider ml-1" :class="message.user.id != $page.props.auth.user.id ? 'text-blue' : 'text-blue-300'">
                        {{ message.user.username }}
                    </span>
                </Link>
            </div>
            <div class="flex flex-col">
                <p class="text-sm">{{ message.body }}</p>
                <p class="text-xs italic">{{ $filters.timeAgo(message.created_at) }}</p>
            </div>
        </div>
    </div>
</template>