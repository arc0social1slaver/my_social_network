<script setup>
import PostItem from '@/Components/PostComment/PostItem.vue';
const props = defineProps(['posts']);
</script>
<template>
    <div>
        <div v-for="(post, key) in props.posts" :key="key">
            <PostItem :post="post"></PostItem>
        </div>
    </div>
</template>