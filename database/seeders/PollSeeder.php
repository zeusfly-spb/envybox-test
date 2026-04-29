<?php

namespace Database\Seeders;

use Database\Factories\PollFactory;
use Illuminate\Database\Seeder;

class PollSeeder extends Seeder
{
    private $pollFactory;
    private $questions = [
        [
            'title' => 'Какой ваш любимый язык программирования?',
            'options' => ['PHP', 'JavaScript', 'Python', 'Java', 'Go', 'Rust']
        ],
        [
            'title' => 'Как часто вы используете Laravel в работе?',
            'options' => ['Ежедневно', 'Несколько раз в неделю', 'Несколько раз в месяц', 'Редко', 'Никогда']
        ],
        [
            'title' => 'Какой фронтенд-фреймворк вы предпочитаете?',
            'options' => ['React', 'Vue.js', 'Angular', 'Alpine.js', 'Livewire', 'Не использую']
        ],
        [
            'title' => 'Что для вас важнее всего при выборе технологий?',
            'options' => ['Производительность', 'Скорость разработки', 'Надёжность', 'Сообщество', 'Лёгкость обучения', 'Масштабирование']
        ],
        [
            'title' => 'Какой опыт работы с базами данных вы имеете?',
            'options' => ['MySQL/MariaDB', 'PostgreSQL', 'SQLite', 'MongoDB', 'Redis', 'Другое']
        ],
        [
            'title' => 'Как вы предпочитаете тестировать код?',
            'options' => ['PHPUnit', 'Pest', 'Codeception', 'Вручную', 'Не тестирую', 'Другое']
        ],
        [
            'title' => 'Какой ваш уровень владения Laravel?',
            'options' => ['Начинающий (<6 мес)', 'Средний (6 мес-2 года)', 'Продвинутый (2-5 лет)', 'Эксперт (>5 лет)', 'Только начинаю']
        ]
    ];

    public function __construct()
    {
        $this->pollFactory = new PollFactory();
    }
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->questions as $question) {
            $this->pollFactory->create(
                $question['title'],
                $question['options']
            );
        }
    }
}
