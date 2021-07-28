<p align="center">
  <img width="320" src="https://cdn-images-1.medium.com/max/2600/0*rHWrSMikANaGuc11">
</p>
<p align="center">
  <a href="https://github.com/vuejs/vue">
    <img src="https://img.shields.io/badge/laravel-8.x-red.svg" alt="laravel-8.x">
  </a>
  <a href="https://laravel.com/docs/5.8">
    <img src="https://img.shields.io/badge/vue-2.6.12-brightgreen.svg" alt="vue">
  </a>
  <a href="https://github.com/ElemeFE/element">
    <img src="https://img.shields.io/badge/element--ui-2.15.1-brightgreen.svg" alt="element-ui">
  </a>
  <a href="https://tailwindcss.com/docs/installation">
    <img src="https://img.shields.io/badge/tailwindcss-2.1.1-brightgreen.svg" alt="element-ui">
  </a>
  <a href="https://github.com/PanJiaChen/vue-element-admin/blob/master/LICENSE">
    <img src="https://img.shields.io/badge/license-MIT-brightgreen.svg" alt="license">
  </a>
</p>

# LaraJS

[LaraJS]() is a beautiful dashboard combination of [Laravel](https://laravel.com/), [Vue.js](https://github.com/vuejs/vue) and the UI Toolkit [Element](https://github.com/ElemeFE/element). Especially with the code generator function(CREATE, EDIT, DELETE, API, CMS, DATABASE,...) with 100% api and a few other functions and can save about 50% time for the whole project

# Demo

https://youtu.be/toI59rLyw_8

## Preparation

\*\* [Node](http://nodejs.org/)

\*\* [Git](https://git-scm.com/)

\*\* [Laravel](https://laravel.com/)

\*\* [Composer](https://getcomposer.org/)

The project is built on top of [Laravel](https://laravel.com), [vue](https://cn.vuejs.org/index.html), [vuex](https://vuex.vuejs.org/zh-cn/), [vue-router](https://router.vuejs.org/zh-cn/), [axios](https://github.com/axios/axios) and [element-ui](https://github.com/ElemeFE/element). Since this is positioned as an enterprise management solution, it is recommended to use it to start a project.

## API

API will be served by Laravel. In this project, you need to run migration and data feeder to generate sample data for authentication/authorization, other APIs will be faked.

## Getting started

This project is built on top of fresh latest version Laravel 8. You should check the installation guide of Laravel [here](https://laravel.com/docs/8.x)

## Built with

-   [Laravel](https://laravel.com/) - The PHP Framework For Web Artisans
-   [Laravel Passport](https://github.com/laravel/passport) - Laravel Sanctum provides a featherweight authentication system for SPAs and simple APIs.
-   [Laravel Permission](https://github.com/spatie/laravel-permission) - Associate users with permissions and roles.
-   [Swagger](https://github.com/zircote/swagger-php) - Generate interactive OpenAPI documentation for your RESTful API
-   [Laravel File Manager](https://github.com/UniSharp/laravel-filemanager) - Integration with [TinyMCE](https://www.tiny.cloud/docs/)
-   [VueJS](https://vuejs.org/) - The Progressive JavaScript Framework
-   [Element](https://element.eleme.io/) - A Vue 2.0 based component library for developers, designers and product managers
-   [Vue Admin Template](https://github.com/PanJiaChen/vue-element-admin) - A minimal vue admin template with Element UI

```bash
# Clone the project and run composer
git clone git remote add origin https://github.com/maingocthanhtan96/LaraJS.git

# Init project
# https://github.com/beyondcode/laravel-er-diagram-generator
# Generate diagram erd (Optional)
https://graphviz.org/download/ #(install graphviz)
php artisan generate:erd public/images/diagram-erd.png

# Run bash script to install project (recommend)
chmod u+x setup.sh && ./setup.sh

# OR hand install
composer install
composer dump-autoload

# Create .env from .env.example
cp .env.example .env

# Generate application key
php artisan key:generate

# Migration and DB seeder (after changing your DB settings in .env)
php artisan migrate --seed

# install dependency
npm install && npm install -g cross-env && npm rebuild node-sass

# Generate file lang
php artisan vue-i18n:generate

# Generate ide
php artisan ide-helper:generate

# Config Virtual host
Exemple: http://local.larajs.com
# Generate Passport secret key
php artisan passport:install

# Copy and paste passport client secret with id=2
PASSPORT_CLIENT_SECRET

# Develop for be
npm run dev # or npm run watch
# Build on production
npm run prod
# Develop for fe
npm run dev-fe # or npm run watch-fe
# Build on production
npm run prod-fe
# Build pro all(be,fe)
npm run prod-all

# Run swagger
./swagger.sh

# username, password
- Amin
username: admin@larajs.com
password: Admin@123!
- Manager
username: manager@larajs.com
password: Admin@123!
- Visitor
username: visitor@larajs.com
password: Admin@123!
- Creator
username: creator@larajs.com
password: Admin@123!
- Editor
username: editor@larajs.com
password: Admin@123!
- Developer
username: developer@larajs.com
password: Admin@123!
```

## Format code with prettier

\*\* In phpstorm install plugin prettier

\*\* On mac use shot key: shift + option + command + P
