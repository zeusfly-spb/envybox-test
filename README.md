## Локальный запуск

**Требования:** PHP 8.4 с расширениями, необходимыми для Laravel и npm.

1. Скопируйте окружение и при необходимости отредактируйте настройки БД в `.env`:

   ```bash
   cp .env.example .env
   ```

2. Установите зависимости PHP и сгенерируйте ключ приложения:

   ```bash
   composer install
   php artisan key:generate
   ```

3. Примените миграции:

   ```bash
   php artisan migrate
   ```

4. Установите зависимости фронтенда и поднимите Vite (в отдельном терминале):

   ```bash
   cd front
   cp .env.example .env
   npm install
   npm run dev
   ```

5. Запустите встроенный сервер Laravel (в другом терминале):

   ```bash
   php artisan serve
   ```

   Сайт обычно доступен по адресу http://localhost 
   Адрес Vite подставится в шаблоны автоматически при работе `npm run dev`.

