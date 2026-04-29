<template>
    <AppLayout>
        <div class="poll-create">
            <v-row justify="center">
                <v-col cols="12" md="10" lg="8">
                    <v-card class="poll-create__card" elevation="2">
                        <v-card-item class="pb-0">
                            <div class="text-overline text-primary mb-1">
                                Создание опроса
                            </div>
                            <div class="text-h5 font-weight-bold">
                                Новый опрос
                            </div>
                            <p class="text-body-2 text-medium-emphasis mt-2 mb-0">
                                Заполните основную информацию и добавьте варианты ответа.
                            </p>
                        </v-card-item>

                        <v-card-text>
                            <v-form 
                                class="poll-create__form"
                                @submit.prevent="submit"
                            >
                                <v-text-field
                                    v-model="form.title"
                                    label="Название опроса"
                                    placeholder="Например: Какая функция нужна в следующем релизе?"
                                    variant="outlined"
                                    density="comfortable"
                                    prepend-inner-icon="mdi-format-title"
                                />
                                <PollOptionsEditor v-model="form.options" />
                            </v-form>
                        </v-card-text>

                        <v-divider />

                        <v-card-actions class="pa-4 d-flex justify-space-between">
                            <v-btn
                                variant="text"
                                color="secondary"
                                prepend-icon="mdi-refresh"
                                @click="resetForm"
                            >
                                Очистить
                            </v-btn>
                            <v-btn
                                type="submit"
                                color="primary"
                                size="large"
                                prepend-icon="mdi-content-save-outline"
                            >
                                Сохранить опрос
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-col>
            </v-row>
        </div>        
    </AppLayout>   
</template>

<script setup>
import { reactive } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import PollOptionsEditor from '@/components/PollOptionsEditor.vue';

const createFormState = () => ({
    title: '',
    options: [{ text: '' }, { text: '' }],
});

const form = reactive(createFormState());

const resetForm = () => {
    Object.assign(form, createFormState());
};

const submit = async () => {

};
</script>

<style scoped>
.poll-create {
    min-height: calc(100vh - 130px);
    display: flex;
    align-items: flex-start;
    padding: 2rem 0;
    background: linear-gradient(180deg, #f7f9fc 0%, #eef2ff 100%);
}

.poll-create__card {
    border-radius: 20px;
    overflow: hidden;
}

.poll-create__form {
    display: grid;
    gap: 0.75rem;
}
</style>