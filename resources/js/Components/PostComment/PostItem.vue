<script setup>
import {Link, useForm} from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import Like from '@/Components/PostComment/Likes/Like.vue';
import Dislike from '@/Components/PostComment/Likes/Dislike.vue';
import CombinedComments from '@/Components/PostComment/CombinedComments.vue';
import PostForm from '@/Components/PostComment/PostForm.vue';
const props = defineProps(['post']);
const openMenu = ref(false);
const form = useForm({
    body: '',
    user_id: props.post.user_id,
});
const deleteForm = useForm({
    userPost: props.post,
});
const likeForm = useForm({
    userPost: props.post,
});
const dislikeForm = useForm({
    userPost: props.post,
});
const submit = () => {
    form.post(route('comments.store', props.post), {
        preserveScroll: true,
        onSuccess: () => {
            Toast.fire({
                icon: 'success',
                title: 'Your comment has successfully been published!'
            });
            form.body = null;
        }
    })
};
const deletePost = () => {
    openMenu.value = false;
    deleteForm.delete(route('posts.destroy', props.post), {
        preserveScroll: true,
        onError: () => {
            Toast.fire({
                icon: 'error',
                title: 'You do not have permission to delete this post!',
            });
        },
        onSuccess: () => {
            Toast.fire({
                icon: 'success',
                title: 'Post has successfully been deleted!'
            });
        }
    });
};
const submitLike = () => {
    likeForm.post(route('post-like.store', props.post), {
        preserveScroll: true,
        onSuccess: () => {}
    });
};
const submitDislike = () => {
    dislikeForm.delete(route('post-like.destroy', props.post), {
        preserveScroll: true,
        onSuccess: () => {}
    });
};
// const img_url = (img_name) => {
//     return 'public/images/' + img_name
// }
</script>
<template>
    <div class="flex border-t border-gray-400 mt-5 py-5 space-x-5">
       <div class="flex-shirnk-0">
        <Link :href="route('profiles.show', props.post.user.username)">
            <img class="h-8 w-8 rounded-full object-cover" :src="props.post.user.profile_photo_url" :alt="props.post.user.username">
        </Link>
       </div>
        <div class="flex-1">
            <div>
                <div class="flex justify-between">
                    <h2 class="text-lg font-semibold underline">
                        <Link :href="route('profiles.show', props.post.user.username)">
                            {{ props.post.user.username }}
                        </Link>
                    </h2>
                    <div class="relative">
                        <button type="button" class="focus:outline-none" @click="openMenu = !openMenu">
                            <Icon name="ellipsis-h" class="w-8 h-8 fill-current"></Icon>
                        </button>
                        <div v-if="openMenu" class="bg-gray-300 text-sm absolute w-48 right-0 text-gray-700 shadow-lg rounded-md px-4 py-2 hover:bg-gray-700 hover:text-gray-300 transition duration-150 ease-in-out">
                            <form @submit.prevent="deletePost">
                                <button type="submit" class="flex justify-between items-center w-full focus:outline-none">
                                    Delete Post
                                    <Icon name="trash" class="w-3 h-3 fill-current"></Icon>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <p class="bg-gray-100 rounded mt-3 px-3 py-2">
                    {{ props.post.body }}
                    <div class="grid gap-4 grid-cols-3" v-if="props.post.images.length > 0">
                        <div v-for="(image, id) in props.post.images" :key="id">
                            <Link :href="image.profile_photo_url">
                                <img class="w-40 h-40 fill-current object-cover" :src="image.profile_photo_url" alt="">
                            </Link>
                        </div>
                    </div>
                </p>
            </div>
            <div class="flex items-end my-3">
                <div>
                    <span class="text-sm italic">
                        {{ $filters.timeAgo(post.created_at) }}
                    </span>
                </div>
                <div class="flex ml-3">
                    <Like :item="post" :method="submitLike"></Like>
                    <Dislike :item="post" :method="submitDislike" class="ml-2"></Dislike>                </div>
            </div>
            <PostForm :method="submit" :form="form" :text="'Comment'"></PostForm>
            <CombinedComments :comments="post.comments"></CombinedComments>            
        </div>
    </div>
</template>