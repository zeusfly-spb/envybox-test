<template>
    <AppLayout>
        <div class="page-shell">
            <section class="poll-card" v-if="poll?.title">
                <h1 class="poll-title">{{ poll.title }}</h1>
                <p class="poll-subtitle">Выберите один вариант ответа:</p>

                <form 
                    class="poll-form" 
                    @submit.prevent="submit"
                >
                    <label
                        v-for="option in poll.options ?? []"
                        :key="option.id"
                        class="option-item"
                    >
                        <input
                            v-model="selectedOptionId"
                            type="radio"
                            name="poll-option"
                            class="option-radio"
                            :value="option.id"
                        />
                        <span class="option-text">{{ option.text }}</span>
                    </label>

                    <button
                        type="submit"
                        class="vote-button"
                        :disabled="!selectedOptionId"
                    >
                        Проголосовать
                    </button>
                </form>
            </section>

            <section v-else class="poll-card">
                <p class="poll-subtitle">Загружаем опрос...</p>
            </section>
        </div>
    </AppLayout>    
</template>

<script setup>
import { ref } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { useApi } from '@/composables/useApi';
import { useStore } from 'vuex';

const props = defineProps({code: String});
const api = useApi();
const store = useStore();
const poll = ref({});
const selectedOptionId = ref(null);

const getPoll = async () => {
    try {
        const { data } = await api.get(`/polls/${props.code}`);
        poll.value = data;
        store.commit('setCurrentPoll', data);
        store.commit('setVoteStatus', 'open');
    } catch(e) {
        store.commit('setVoteStatus', 'failed');
        console.error('Ошибка загрузки опроса: ', e.message);
    }
};

const submit = async () => {
    try {
        const { data } = await api.post(`/polls/${props.code}/vote`, {
            option_id: selectedOptionId.value
        });
        store.commit('setVoteStatus', 'closed');
        console.log(data);
    } catch(e) {
        console.error('Ошибка при голосовании: ', e.message);
    }
};

getPoll();
</script>

<style scoped>
.poll-card {
    max-width: 44rem;
    margin: 0 auto;
    padding: 2rem;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 1rem;
    box-shadow: 0 8px 24px rgba(15, 23, 42, 0.08);
}

.poll-title {
    margin: 0;
    font-size: 1.5rem;
    line-height: 1.25;
    color: #0f172a;
}

.poll-subtitle {
    margin: 0.75rem 0 0;
    font-size: 0.95rem;
    color: #475569;
}

.poll-form {
    margin-top: 1.25rem;
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.option-item {
    display: flex;
    align-items: center;
    gap: 0.625rem;
    padding: 0.75rem 0.875rem;
    border: 1px solid #cbd5e1;
    border-radius: 0.75rem;
    background: #f8fafc;
    cursor: pointer;
    transition: border-color 0.15s ease, background-color 0.15s ease;
}

.option-item:has(.option-radio:checked) {
    border-color: #2563eb;
    background: #eff6ff;
}

.option-radio {
    accent-color: #2563eb;
}

.option-text {
    color: #0f172a;
    font-size: 0.95rem;
}

.vote-button {
    margin-top: 0.5rem;
    height: 2.625rem;
    border: 0;
    border-radius: 0.75rem;
    background: #2563eb;
    color: #ffffff;
    font-weight: 600;
    cursor: pointer;
    transition: opacity 0.15s ease;
}

.vote-button:disabled {
    opacity: 0.55;
    cursor: not-allowed;
}
</style>