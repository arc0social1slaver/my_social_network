<script setup>
import PagesLayout from '@/Layouts/PagesLayout.vue';
import Status from '@/Components/FriendStatus/Status.vue';
import CombinedPosts from '@/Components/PostComment/CombinedPosts.vue';
import PostForm from '@/Components/PostComment/PostForm.vue';
import InfiniteScroll from '@/Components/InfiniteScroll.vue';
import { useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
const props = defineProps(['profile', 'isFriendsWith', 'friendRequestSentTo', 'friendRequestReceivedFrom', 'posts']);
const allPosts = ref(props.posts);
const form = useForm({
    body: '',
    imgs: null,
    user_id: props.profile.id,
});
const pagination = computed(() => {
    return allPosts.value = props.posts
});
const submit = () => {
    form.post(route('posts.store'), {
        preserveScroll: true,
        onSuccess: () => {
            Toast.fire({
                icon:'success',
                title: 'Your post has successfully been published!'
            });
            form.body = null;
            form.imgs = null;
        }
    })
};
const loadMorePosts = () => {
    if(!allPosts.value.next_page_url)
    {
        return
    }
    return axios.get(allPosts.value.next_page_url)
                .then(resp => {
                    allPosts.value = {
                        ...resp.data,
                        data: [
                            ...allPosts.value.data, ...resp.data.data
                        ]
                    }
                })
};
</script>
<template>
    <PagesLayout title="Dashboard">
        <template #title>
        <div class="flex justify-between items-center w-full">
            <h2 class=" flex items-end font-semibold text-xl text-gray-800 leading-tight">
                <img :src="profile.profile_photo_url" :alt="profile.username" class="h-8 w-8 rounded-full object-cover">
                <span class="captialize ml-3">
                    {{ `${profile.username}'s Profile` }}
                </span>
            </h2>
            <Status :profile="profile" :isFriendsWith="isFriendsWith" :friendRequestSentTo="friendRequestSentTo" :friendRequestReceivedFrom="friendRequestReceivedFrom"></Status>
        </div>
        </template>
        <PostForm :method="submit" :form="form" :text="'Post'"></PostForm>
        <InfiniteScroll @loadMore="loadMorePosts">
            <CombinedPosts :posts="allPosts.data" :pagination="pagination"></CombinedPosts>        </InfiniteScroll>
    </PagesLayout>
</template>