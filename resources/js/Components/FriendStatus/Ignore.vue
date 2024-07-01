<script setup>
import DangerButton from '@/Components/DangerButton.vue';
import { FingerprintSpinner } from 'epic-spinners';
import {router} from '@inertiajs/vue3';
import { ref } from 'vue';
const props = defineProps(['profile']);
const loading = ref(false);
const ignoreRequest = () => {
    loading.value = true;
    router.get(route('friends.deny', props.profile.id, {
    onSuccess: () => {
        Toast.fire({
                icon: "success",
                title: "Friend request successfully ignored!"
        });
        loading.value = false;
    }
}));
};
</script>
<template>
    <form @submit.prevent="ignoreRequest">
        <FingerprintSpinner :animation-duration="1500" :size="20" class="text-white" v-if="loading.value">

        </FingerprintSpinner>
        <template v-else>
        <DangerButton type="submit" class="text-xs">
            Ignore
            <Icon name="user-minus" class="w-4 h-4 fill-current ml-1"></Icon>
        </DangerButton>
        </template>

    </form>
</template>