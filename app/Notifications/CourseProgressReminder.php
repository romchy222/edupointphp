<?php

namespace App\Notifications;

use App\Models\Course;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CourseProgressReminder extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Course $course,
        public int $progressPercentage
    ) {}

    public function via(object $notifiable): array
    {
        $channels = ['database'];
        
        if ($notifiable->email_notifications ?? true) {
            $channels[] = 'mail';
        }
        
        return $channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Напоминание о прогрессе курса')
            ->greeting('Здравствуйте, ' . $notifiable->name . '!')
            ->line('Вы начали курс **"' . $this->course->title . '"**, но еще не завершили его.')
            ->line('Ваш прогресс: **' . $this->progressPercentage . '%**')
            ->line('Продолжайте обучение, чтобы получить сертификат!')
            ->action('Продолжить обучение', route('courses.show', $this->course))
            ->line('Мы верим в вас! Продолжайте учиться.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'course_id' => $this->course->id,
            'title' => 'Напоминание о прогрессе',
            'message' => 'Продолжите обучение по курсу "' . $this->course->title . '" (прогресс: ' . $this->progressPercentage . '%)',
            'url' => route('courses.show', $this->course),
        ];
    }
}
