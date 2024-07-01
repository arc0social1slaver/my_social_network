<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PagesLayout from '@/Layouts/PagesLayout.vue';
import UserBlock from '@/Components/UserBlock.vue';
import InfiniteScroll from '@/Components/InfiniteScroll.vue';
import { onMounted, ref } from 'vue';
import axios from 'axios';
const props = defineProps(['members']);
const allMembers = ref(props.members);
const loadMoreMembers = () => {
    if(!allMembers.value.next_page_url)
    {
        return
    }
    return axios.get(allMembers.value.next_page_url)
                .then(resp => {
                    allMembers.value = {
                        ...resp.data,
                        data: [
                            ...allMembers.value.data, ...resp.data.data
                        ]
                    }
                })
};
</script>
<template>
    <PagesLayout title="Dashboard">
        <template #title>
        <div class="flex flex-col items-center sm:flex-row sm:justify-between">
            <h2 class="flex items-end font-semibold text-xl text-gray-800 leading-tight">
                <Link :href="route('members.index')" class="capitalize underline">
                    Members
                </Link>
            </h2>
            <span class="text-gray-800 leading-tight text-lg capitalize mt-5 sm:mt-0">
                Visit your 
                <Link href="#" class="underline hover:text-green-500">friends</Link>
            </span>
        </div>
        </template>
        <InfiniteScroll @loadMore="loadMoreMembers">
        <UserBlock :items="allMembers.data"></UserBlock>
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