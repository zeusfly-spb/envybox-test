<template>
    <AppLayout />
</template>
<script setup>
import { onMounted } from 'vue';
import { useRouter } from 'vue-router';
import AppLayout from '@/layouts/AppLayout.vue';

const router = useRouter();

onMounted(() => {
    router.replace('/create');
});
</script>