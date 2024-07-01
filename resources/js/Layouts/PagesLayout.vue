<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
</script>
<template>
    <AppLayout>
        <div class="lg:px-10">
            <div>
                <slot name="title"></slot>
            </div>
            <div class="mt-5">
                <slot></slot>
            </div>
        </div>
    </AppLayout>
</template>