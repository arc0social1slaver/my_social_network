<script setup>
import { onMounted } from 'vue';
const emits = defineEmits(['loadMore']);
onMounted(() => {
    window.addEventListener('scroll', _.debounce((e) => {
        let pixelsFromBottom = document.documentElement.offsetHeight - document.documentElement.scrollTop - window.innerHeight
        if(pixelsFromBottom < 200) {
            emits('loadMore')
        }
    }, 100))
})
</script>
<template>
    <div>
        <slot></slot>
    </div>
</template>