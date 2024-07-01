<script setup>
import {Link, useForm} from '@inertiajs/vue3';
import Like from '@/Components/PostComment/Likes/Like.vue';
import Dislike from '@/Components/PostComment/Likes/Dislike.vue';
const props = defineProps(['comment']);
const likeForm = useForm({
    comment: props.comment,
});
const dislikeForm = useForm({
    comment: props.comment,
});
const submitLike = () => {
    likeForm.post(route('comment-like.store', props.comment.id), {
        preserveScroll: true,
        onSuccess: () => {

        },
    });
};
const submitDislike = () => {
    dislikeForm.delete(route('comment-like.destroy', props.comment.id), {
        preserveScroll: true,
        onSuccess: () => {

        },
    });
}
</script>
<template>
    <div class="flex border-t border-gray-400 mt-5 py-5 space-x-5">
        <div class="flex-shrink-0">
            <Link :href="route('profiles.show', comment.user.username)">
                <img class="w-8 h-8 rounded-full object-cover" :src="comment.user.profile_photo_url" :alt="comment.user.username">
            </Link>
        </div>
        <div class="flex-1">
            <div>
                <h2 class="text-lg font-semibold underline">
                    <Link :href="route('profiles.show', comment.user.username)">
                        {{ comment.user.username }}
                    </Link>
                </h2>
                <p class="bg-gray-100 rounded mt-3 px-3 py-2">
                    {{ comment.body }}
                </p>
            </div>
            <div class="flex items-end my-3">
               <div>
                <span class="text-sm italic">
                    {{ $filters.timeAgo(comment.created_at) }}
                </span>
               </div> 
               <div class="flex ml-3">
                <Like :item="comment" :method="submitLike"></Like>
                <Dislike :item="comment" :method="submitDislike" class="ml-2"></Dislike>    
               </div>
            </div>
        </div>
    </div>
</template>