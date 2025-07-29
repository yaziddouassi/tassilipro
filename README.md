# 🧱 TASSILI - Laravel Inertia CRUD Generator

**TASSILI** is a full-featured CRUD generator package built with Laravel + Inertia.js + Vue 3. It comes with powerful tools such as Pinia for state management, Quill.js for rich text editing, and Chart.js for data visualization. Here is the web site https://rabahdouassi.net/tassili-free/docs/1.x/installation

---

## 📋 Requirements

- PHP `^8.2`
- Node.js and npm
- Laravel `^12` with Breeze (Inertia.js stack)
- Vite properly configured

---

## 🚀 Installation

### 1. Install Front-End Dependencies

```bash
npm install quill@^2.0.3
npm install vue-chartjs chart.js
npm install pinia
composer require spatie/laravel-route-attributes
```

---

### 2. Configure if you are not using typescript `resources/js/app.js`

```js
import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import { createPinia } from 'pinia';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';
const pinia = createPinia();

createInertiaApp({
  title: (title) => `${title} - ${appName}`,
  resolve: (name) =>
    resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
  setup: ({ el, App, props, plugin }) => {
    return createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(ZiggyVue)
      .use(pinia)
      .mount(el);
  },
  progress: {
    color: '#4B5563'
  }
});
```

---

### 2-b. Configure if you are using typescript `resources/js/app.ts` 

```js
import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp,DefineComponent,h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import { createPinia } from 'pinia';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';
const pinia = createPinia();

createInertiaApp({
  title: (title) => `${title} - ${appName}`,
  resolve: (name) =>
    resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob<DefineComponent>('./Pages/**/*.vue')),
  setup({ el, App, props, plugin }) {
  createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(ZiggyVue)
      .use(pinia)
      .mount(el);
  },
  progress: {
    color: '#4B5563'
  }
});
```

---

### 3. Update Blade Template if you are not using typescript

Update your `resources/views/app.blade.php`:

```blade
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title inertia>{{ config('app.name', 'Laravel') }}</title>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
  <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet">

  @routes
  @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
  @inertiaHead
</head>
<body class="font-sans antialiased">
  @inertia
</body>
</html>
```

---

### 3-b. Update Blade Template if you are using typescript

Update your `resources/views/app.blade.php`:

```blade
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title inertia>{{ config('app.name', 'Laravel') }}</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet">

    @routes
    @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
    @inertiaHead
</head>
<body class="font-sans antialiased">
    @inertia
</body>
</html>
```

---

### 4. Storage Configuration (example for `public` disk)

```env
TASSILI_STORAGE_DISK=public
TASSILI_STORAGE_URL=http://127.0.0.1:8000/storage/
GUMROAD_TASSILI_LICENSE_KEY=Your Tassili Gumroad Key 
```

---

### 5. Install Hoggar

```bash
composer require tassili/tassili
php artisan migrate
php artisan tassili:install
php artisan vendor:publish --tag=tassili-config
php artisan storage:link
```

---


## 🧩 Features

- 🎨 Inertia Vue 3 interface
- 🧠 State management with **Pinia**
- 📝 Rich text editing with **Quill.js**
- 📊 Charts with **Chart.js**
- ⚡️ Full **CRUD Generator**
- 🔒 Wizard Form System

---

## 📘 License

This project is licensed under a commercial license via [Gumroad](https://yazid4.gumroad.com/l/yyfte).

---

**Crafted with ❤️ by [Your Name or Brand]**