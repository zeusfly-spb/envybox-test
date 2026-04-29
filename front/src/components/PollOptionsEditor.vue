<template>
    <div class="options-editor">
        <div class="options-editor__header">
            <div>
                <h3 class="text-h6">Варианты ответа</h3>
                <p class="text-body-2 text-medium-emphasis">
                    Добавьте варианты ответа для участников опроса.
                </p>
            </div>
            <v-chip color="primary" variant="tonal">
                {{ localOptions.length }}
            </v-chip>
        </div>

        <div class="options-editor__list">
            <v-row
                v-for="(option, index) in localOptions"
                :key="option.id"
                class="options-editor__row"
                align="center"
                no-gutters
            >
                <v-col cols="12" sm="10">
                    <v-text-field
                        v-model="option.text"
                        :label="`Вариант ${index + 1}`"
                        placeholder="Например: Да, это отличный вариант"
                        density="comfortable"
                        variant="outlined"
                        hide-details="auto"
                    />
                </v-col>
                <v-col cols="12" sm="2" class="d-flex justify-sm-end">
                    <v-btn
                        icon="mdi-delete-outline"
                        color="error"
                        variant="text"
                        :disabled="localOptions.length <= minOptions"
                        @click="removeOption(option.id)"
                    />
                </v-col>
            </v-row>
        </div>

        <div class="options-editor__footer">
            <v-btn
                prepend-icon="mdi-plus"
                color="primary"
                variant="tonal"
                :disabled="!canAdd"
                @click="addOption"
            >
                Добавить вариант
            </v-btn>
        </div>
    </div>
</template>

<script setup>
import { ref, watch, computed } from 'vue';

const props = defineProps({
    modelValue: {
        type: Array,
        default: () => [],
    },
    minOptions: {
        type: Number,
        default: 2,
    },
});

const emit = defineEmits(['update:modelValue']);

let optionId = 0;

const toOption = (item) => {
    optionId += 1;
    return {
        id: optionId,
        text: item?.text ?? '',
    };
};

const toModelValue = (items) => items.map((item) => ({ text: item.text }));
const isSameValue = (a, b) => JSON.stringify(a) === JSON.stringify(b);

const localOptions = ref(props.modelValue.map(toOption));

const canAdd = computed(() => localOptions.value.length < 4);

const addOption = () => {
    localOptions.value.push(toOption({ text: '' }));
};

const removeOption = (id) => {
    if (localOptions.value.length <= props.minOptions) {
        return;
    }
    localOptions.value = localOptions.value.filter((item) => item.id !== id);
};

watch(
    () => props.modelValue,
    (value) => {
        const current = toModelValue(localOptions.value);
        if (isSameValue(current, value)) {
            return;
        }
        localOptions.value = value.map((item) => toOption(item));
    },
    { deep: true },
);

watch(
    localOptions,
    (value) => {
        const next = toModelValue(value);
        if (isSameValue(next, props.modelValue)) {
            return;
        }
        emit('update:modelValue', next);
    },
    { deep: true, immediate: true },
);
</script>

<style scoped>
.options-editor {
    border: 1px solid #e5e7eb;
    border-radius: 16px;
    padding: 1rem;
    background: #fff;
}

.options-editor__header {
    display: flex;
    justify-content: space-between;
    gap: 1rem;
    margin-bottom: 0.75rem;
}

.options-editor__row {
    margin-bottom: 0.5rem;
}

.options-editor__footer {
    margin-top: 0.75rem;
}
</style>
