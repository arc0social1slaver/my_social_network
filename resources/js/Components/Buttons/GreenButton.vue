<script setup>
import { router } from '@inertiajs/vue3';
const props = defineProps({
    type: {
        type: String,
        default: 'button'
    },
    href: {},
});
const visit = (event) => {
    if(!props.href) {        
        return
    }
    event.preventDefault()
    router.visit(props.href)
    
};
</script>
<template>
    <button :type="type" class="text-xs inline-fex items-center justify-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-white uppercase tracking-widest hover:bg-green-500 focus:outline-none focus:border-green-700 focus:shadow-outline-green active:bg-green-600 transition: ease-in-out duration-150" @click="visit">
        <slot></slot>
    </button>
</template>