<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // Общие настройки
            ['key' => 'site_name', 'value' => 'EduPoint', 'type' => 'text', 'group' => 'general'],
            ['key' => 'site_description', 'value' => 'Современная образовательная платформа для онлайн-обучения', 'type' => 'textarea', 'group' => 'general'],
            ['key' => 'admin_email', 'value' => 'admin@edupoint.local', 'type' => 'text', 'group' => 'general'],
            ['key' => 'contact_email', 'value' => 'info@edupoint.local', 'type' => 'text', 'group' => 'general'],
            ['key' => 'phone', 'value' => '+7 (999) 123-45-67', 'type' => 'text', 'group' => 'general'],
            ['key' => 'address', 'value' => 'г. Москва, ул. Примерная, д. 123', 'type' => 'text', 'group' => 'general'],

            // Настройки главной страницы
            ['key' => 'hero_title', 'value' => 'Учись с удовольствием', 'type' => 'text', 'group' => 'homepage'],
            ['key' => 'hero_subtitle', 'value' => 'Получи новые знания и навыки вместе с EduPoint. Более 100 курсов от лучших преподавателей!', 'type' => 'textarea', 'group' => 'homepage'],
            ['key' => 'hero_button', 'value' => 'Начать обучение', 'type' => 'text', 'group' => 'homepage'],
            ['key' => 'show_stats', 'value' => '1', 'type' => 'boolean', 'group' => 'homepage'],
            ['key' => 'show_popular_courses', 'value' => '1', 'type' => 'boolean', 'group' => 'homepage'],
            ['key' => 'popular_courses_count', 'value' => '6', 'type' => 'number', 'group' => 'homepage'],

            // Настройки страницы "О нас"
            ['key' => 'title', 'value' => 'О платформе EduPoint', 'type' => 'text', 'group' => 'about'],
            ['key' => 'description', 'value' => 'EduPoint - это современная образовательная платформа, которая объединяет студентов и преподавателей со всего мира. Мы предлагаем качественное онлайн-образование по различным направлениям.', 'type' => 'textarea', 'group' => 'about'],
            ['key' => 'mission', 'value' => 'Наша миссия - сделать качественное образование доступным для каждого, независимо от местоположения и финансовых возможностей.', 'type' => 'textarea', 'group' => 'about'],
            ['key' => 'vision', 'value' => 'Мы стремимся стать ведущей платформой онлайн-образования, где каждый может найти курс по душе и развить свои навыки.', 'type' => 'textarea', 'group' => 'about'],

            // Социальные сети
            ['key' => 'facebook_url', 'value' => '', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'instagram_url', 'value' => '', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'twitter_url', 'value' => '', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'linkedin_url', 'value' => '', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'youtube_url', 'value' => '', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'telegram', 'value' => '@edupoint', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'whatsapp', 'value' => '+7 999 123-45-67', 'type' => 'text', 'group' => 'contact'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key'], 'group' => $setting['group']],
                $setting
            );
        }
    }
}
