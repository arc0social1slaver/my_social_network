<script setup>
import CommentItem from '@/Components/PostComment/CommentItem.vue';
defineProps(['comments']);
</script>
<template>
    <div>
        <div v-for="(comment, index) in comments" :key="index">
            <CommentItem :comment="comment"></CommentItem>
        </div>
    </div>
</template>