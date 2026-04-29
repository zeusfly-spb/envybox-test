<template>
    <v-app class="app-layout">
        <v-main class="app-layout__main">
            <v-container fluid>
                <slot />
            </v-container>
        </v-main>
        <footer class="app-layout__footer">
            <slot name="footer">
                <small>&copy; 2026 Сайфутдинов О.А.</small>
            </slot>
        </footer>
    </v-app>
</template>
<script setup>
</script>

<style scoped>
.app-layout {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
}

.app-layout__main {
    flex: 1;
}

.app-layout__footer {
    padding: 1rem 1.5rem;
    background: #ffffff;
    border-top: 1px solid #e5e7eb;
}
</style>