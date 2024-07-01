<script setup>
import GreenButton from '@/Components/Buttons/GreenButton.vue';
import { FingerprintSpinner } from 'epic-spinners';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
const props = defineProps(['profile']);
const acceptForm = useForm({
    user: props.profile,
});
const loading = ref(false);
const acceptFriend = () => {
    loading.value = true;
    acceptForm.patch(route('friends.update', props.profile.id), {
        preserveScroll: true,
        onSuccess: () => {
            Toast.fire({
                icon: "success",
                title: "Succcessfully accepted request!"
            });
            loading.value = false;
        }
    });
}
</script>
<template>
    <form @submit.prevent="acceptFriend">
        <GreenButton type="submit" class="text-xs">
            <FingerprintSpinner :animation-duration="1500" :size="20" class="text-white" v-if="loading.value">

            </FingerprintSpinner>
            <template v-else>
            Accept
            <Icon name="user-plus" class="w-4 h-4 fill-current ml-1"></Icon>
            </template>
        </GreenButton>
    </form>
</template>