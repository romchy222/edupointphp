<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class CategoriesAndTagsSeeder extends Seeder
{
    public function run(): void
    {
        // Создание категорий
        $categories = [
            [
                'name' => 'Программирование',
                'slug' => 'programming',
                'description' => 'Курсы по программированию и разработке',
                'icon' => 'bi-code-slash',
                'color' => '#0d6efd',
                'order' => 1,
            ],
            [
                'name' => 'Дизайн',
                'slug' => 'design',
                'description' => 'Курсы по графическому и веб-дизайну',
                'icon' => 'bi-palette',
                'color' => '#d63384',
                'order' => 2,
            ],
            [
                'name' => 'Маркетинг',
                'slug' => 'marketing',
                'description' => 'Курсы по маркетингу и продвижению',
                'icon' => 'bi-megaphone',
                'color' => '#198754',
                'order' => 3,
            ],
            [
                'name' => 'Бизнес',
                'slug' => 'business',
                'description' => 'Курсы по бизнесу и управлению',
                'icon' => 'bi-briefcase',
                'color' => '#0dcaf0',
                'order' => 4,
            ],
            [
                'name' => 'Языки',
                'slug' => 'languages',
                'description' => 'Курсы иностранных языков',
                'icon' => 'bi-translate',
                'color' => '#ffc107',
                'order' => 5,
            ],
            [
                'name' => 'Наука',
                'slug' => 'science',
                'description' => 'Курсы по науке и технологиям',
                'icon' => 'bi-rocket',
                'color' => '#6f42c1',
                'order' => 6,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        // Создание тегов
        $tags = [
            'PHP', 'Laravel', 'Python', 'JavaScript', 'React', 'Vue.js',
            'Node.js', 'MySQL', 'PostgreSQL', 'MongoDB', 'Docker',
            'Git', 'HTML', 'CSS', 'Bootstrap', 'Tailwind',
            'SEO', 'SMM', 'Контекстная реклама', 'Email маркетинг',
            'Photoshop', 'Figma', 'Adobe Illustrator', 'UI/UX',
            'Английский', 'Немецкий', 'Французский', 'Испанский',
            'Математика', 'Физика', 'Химия', 'Биология',
            'Финансы', 'Менеджмент', 'HR', 'Предпринимательство',
        ];

        foreach ($tags as $tagName) {
            Tag::create(['name' => $tagName]);
        }
    }
}
