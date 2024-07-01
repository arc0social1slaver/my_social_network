<script setup>
import BlueButton from '@/Components/Buttons/BlueButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import Accept from '@/Components/FriendStatus/Accept.vue';
import Ignore from '@/Components/FriendStatus/Ignore.vue';
import { FingerprintSpinner } from 'epic-spinners';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
const props = defineProps(['profile',  'isFriendsWith', 'friendRequestSentTo', 'friendRequestReceivedFrom']);
const loading = ref(false);
const addFriendForm = useForm({
    user: props.profile
});
const deleteFriendForm = useForm({
    user: props.profile,
});
const addFriend = () => {
    loading.value = true;
    addFriendForm.post(route('friends.store', props.profile.id), {
        preserveScroll: true,
        onSuccess: ()=>{
            Toast.fire({
                icon: "success",
                title: "Friend request sent!"
            });
            loading.value = false;
        }
    });
}
const deleteFriend = () => {
    loading.value = true
    deleteFriendForm.delete(route('friends.destroy', props.profile.id), {
        preserveScroll: true,
        onSuccess: () => {
            Toast.fire({
                icon: "success",
                title: "Friend has been removed!"
            });
            loading.value = false;
        }
    });
};
</script>
<template>
    <div class="flex mt-5 sm:mt-0">
        <template v-if="friendRequestReceivedFrom">
            <Accept :profile="profile"></Accept>
            <Ignore :profile="profile" class="ml-3"></Ignore>
        </template>
        <template v-else-if="friendRequestSentTo">
            <h3 class="font-semibold text-md text-gray-800 leading-tight">
                Pending
            </h3>
        </template>
        <template v-else-if="isFriendsWith"> 
            <form @submit.prevent="deleteFriend">
                <DangerButton type="submit">
                <FingerprintSpinner :animation-duration="1500" :size="20" class="text-white" v-if="loading.value">
                </FingerprintSpinner>
                <template v-else>                    
                    Unfriend
                    <Icon name="user-minus" class="w-4 h-4 fill-current ml-1"></Icon>
                </template>
            </DangerButton>
            </form>
        </template>
        <template v-else-if="$page.props.auth.user.id != profile.id">
            <form @submit.prevent="addFriend">
                <BlueButton type="submit" class="text-xs">
                    <FingerprintSpinner :animation-duration="1500" :size="20" class="text-white" v-if="loading.value">

                    </FingerprintSpinner>
                    <template v-else>
                        Add friend<icon name="user-plus" class="w-4 h-4 fill-current ml-1"></icon>
                    </template>
                </BlueButton>
            </form>
        </template>
    </div>
</template>