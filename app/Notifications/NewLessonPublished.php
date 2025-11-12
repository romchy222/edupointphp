<?php

namespace App\Notifications;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewLessonPublished extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Lesson $lesson,
        public Course $course
    ) {}

    public function via(object $notifiable): array
    {
        $channels = ['database'];
        
        if ($notifiable->email_notifications && $notifiable->notify_new_lessons) {
            $channels[] = 'mail';
        }
        
        return $channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Новый урок: ' . $this->lesson->title)
            ->greeting('Здравствуйте, ' . $notifiable->name . '!')
            ->line('Опубликован новый урок в курсе "' . $this->course->title . '"')
            ->line('**' . $this->lesson->title . '**')
            ->action('Перейти к уроку', route('lessons.show', [$this->course, $this->lesson]))
            ->line('Продолжайте обучение!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'lesson_id' => $this->lesson->id,
            'course_id' => $this->course->id,
            'title' => 'Новый урок',
            'message' => 'Урок "' . $this->lesson->title . '" в курсе "' . $this->course->title . '"',
            'url' => route('lessons.show', [$this->course, $this->lesson]),
        ];
    }
}