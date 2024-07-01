<script setup>
// import AppLayout from '@/Layouts/AppLayout.vue';
import PagesLayout from '@/Layouts/PagesLayout.vue';
import CombinedPosts from '@/Components/PostComment/CombinedPosts.vue';
import PostForm from '@/Components/PostComment/PostForm.vue';
import SuggestionBlock from '@/Components/SuggestionBlock.vue';
import InfiniteScroll from '@/Components/InfiniteScroll.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import axios from 'axios';
import { computed, ref } from 'vue';
// import Welcome from '@/Components/Welcome.vue';
const props = defineProps(['combinedPosts', 'suggestions']);
const allPosts = ref(props.combinedPosts);
const page = usePage();
const form = useForm({
    user_id: page.props.auth.user.id,
    body: '',
    imgs: null,
});
const pagination = computed(() => {
    return allPosts.value = props.combinedPosts
});
const submit = () => {
    form.post(route('posts.store'), {
        preserveScroll: true,
        onSuccess: () => {
            Toast.fire({
                icon: "success",
                title: "Your post has successfully been published!"
            });
            form.body = null;
        }
    });
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
                <img :src="$page.props.auth.user.profile_photo_url" :alt="$page.props.auth.user.username" class="h-8 w-8 rounded-full object-cover">
                <span class="captialize ml-3">
                    Dashboard
                </span>
            </h2>
        </div>
        </template>
        <PostForm :method="submit" :form="form" :text="'Post'"></PostForm>
        <SuggestionBlock :suggestions="suggestions"></SuggestionBlock>
        <InfiniteScroll @loadMore="loadMorePosts">
            <CombinedPosts :posts="allPosts.data" :pagination="pagination"></CombinedPosts>
        </InfiniteScroll>
        <!-- <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <Welcome />
                </div>
            </div>
        </div> -->
    </PagesLayout>
</template>
