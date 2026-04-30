<template>
    <AppLayout>
        <div class="page-shell">
            <section class="poll-card" v-if="poll?.title">
                <h1 class="poll-title">{{ poll.title }}</h1>

                <template v-if="!votesAfterSubmit">
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
                            :disabled="!selectedOptionId || submitting"
                        >
                            {{ submitting ? 'Отправка…' : 'Проголосовать' }}
                        </button>
                    </form>
                </template>

                <template v-else>
                    <p class="poll-subtitle poll-subtitle--success">
                        Спасибо, ваш голос учтён. Ниже — сводка по всем голосам.
                    </p>

                    <div class="results-total">
                        <span class="results-total__label">Всего проголосовало</span>
                        <span class="results-total__value">{{ totalVotes }}</span>
                    </div>

                    <ul class="results-list" aria-label="Результаты по вариантам">
                        <li
                            v-for="row in resultRows"
                            :key="row.optionId"
                            class="result-row"
                            :class="{ 'result-row--user': row.optionId === userChosenOptionId }"
                        >
                            <div class="result-row__head">
                                <span class="result-row__text">{{ row.text }}</span>
                                <span class="result-row__nums">
                                    {{ row.count }}
                                    <span class="result-row__pct">{{ row.percentLabel }}</span>
                                </span>
                            </div>
                            <div class="result-bar" role="presentation">
                                <div
                                    class="result-bar__fill"
                                    :style="{ width: row.barWidthPercent + '%' }"
                                />
                            </div>
                        </li>
                    </ul>
                </template>
            </section>

            <section v-else class="poll-card">
                <p class="poll-subtitle">Загружаем опрос...</p>
            </section>
        </div>
    </AppLayout>
</template>

<script setup>
import { computed, ref } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { useApi } from '@/composables/useApi';
import { useStore } from 'vuex';

const props = defineProps({ code: String });
const api = useApi();
const store = useStore();
const poll = ref({});
const selectedOptionId = ref(null);
const votesAfterSubmit = ref(null);
const userChosenOptionId = ref(null);
const submitting = ref(false);
const voted = ref(false);

const totalVotes = computed(() => votesAfterSubmit.value?.length ?? 0);

const resultRows = computed(() => {
    const votes = votesAfterSubmit.value;
    const options = poll.value?.options ?? [];
    if (!votes?.length || !options.length) {
        return [];
    }
    const counts = {};
    for (const v of votes) {
        const id = v.option_id;
        counts[id] = (counts[id] ?? 0) + 1;
    }
    const total = votes.length;
    return options.map((opt) => {
        const count = counts[opt.id] ?? 0;
        const pct = total ? (count / total) * 100 : 0;
        const rounded = Math.round(pct * 10) / 10;
        const barWidthPercent = total && count > 0 ? Math.max(rounded, 4) : 0;
        return {
            optionId: opt.id,
            text: opt.text,
            count,
            percentLabel: `${rounded % 1 === 0 ? Math.round(rounded) : rounded}%`,
            barWidthPercent,
        };
    });
});

const getPoll = async () => {
    try {
        const { data } = await api.get(`/polls/${props.code}`);
        poll.value = data.poll;
        store.commit('setCurrentPoll', data.poll);

        if (data.voted) {
            votesAfterSubmit.value = Array.isArray(data.poll.votes) ? data.poll.votes : [];
            store.commit('setVoteStatus', 'closed');
            store.commit('setPollStatistics', resultRows.value);
        } else {
            store.commit('setVoteStatus', 'open');
        }
    } catch (e) {
        store.commit('setVoteStatus', 'failed');
        console.error('Ошибка загрузки опроса: ', e.message);
    }
};

const submit = async () => {
    submitting.value = true;
    try {
        const { data } = await api.post(`/polls/${props.code}/vote`, {
            option_id: selectedOptionId.value,
        });
        userChosenOptionId.value = selectedOptionId.value;
        votesAfterSubmit.value = Array.isArray(data) ? data : [];
        store.commit('setVoteStatus', 'closed');
        store.commit('setPollStatistics', resultRows.value);
    } catch (e) {
        console.error('Ошибка при голосовании: ', e.message);
    } finally {
        submitting.value = false;
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

.poll-subtitle--success {
    color: #166534;
    background: #f0fdf4;
    padding: 0.75rem 1rem;
    border-radius: 0.75rem;
    border: 1px solid #bbf7d0;
}

.results-total {
    margin-top: 1.25rem;
    display: flex;
    align-items: baseline;
    justify-content: space-between;
    gap: 1rem;
    padding: 1rem 1.125rem;
    background: linear-gradient(135deg, #eff6ff 0%, #f8fafc 100%);
    border: 1px solid #e2e8f0;
    border-radius: 0.75rem;
}

.results-total__label {
    font-size: 0.95rem;
    color: #475569;
}

.results-total__value {
    font-size: 1.75rem;
    font-weight: 700;
    color: #1e40af;
    letter-spacing: -0.02em;
}

.results-list {
    margin: 1.25rem 0 0;
    padding: 0;
    list-style: none;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.result-row {
    padding: 0.875rem 1rem;
    border-radius: 0.75rem;
    border: 1px solid #e2e8f0;
    background: #fafafa;
    transition: border-color 0.15s ease, box-shadow 0.15s ease;
}

.result-row--user {
    border-color: #2563eb;
    background: #eff6ff;
    box-shadow: 0 0 0 1px rgba(37, 99, 235, 0.12);
}

.result-row__head {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 0.75rem;
    margin-bottom: 0.5rem;
}

.result-row__text {
    font-size: 0.95rem;
    font-weight: 600;
    color: #0f172a;
    line-height: 1.35;
}

.result-row__nums {
    flex-shrink: 0;
    font-size: 0.9rem;
    font-weight: 600;
    color: #334155;
    text-align: right;
}

.result-row__pct {
    margin-left: 0.35rem;
    font-weight: 500;
    color: #64748b;
}

.result-bar {
    height: 0.5rem;
    border-radius: 999px;
    background: #e2e8f0;
    overflow: hidden;
}

.result-bar__fill {
    height: 100%;
    border-radius: 999px;
    background: linear-gradient(90deg, #3b82f6, #2563eb);
    transition: width 0.45s ease;
}

.result-row--user .result-bar__fill {
    background: linear-gradient(90deg, #1d4ed8, #1e3a8a);
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