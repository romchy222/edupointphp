<?php

namespace App\Notifications;

use App\Models\Course;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CourseDeadlineReminder extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Course $course,
        public int $daysLeft
    ) {}

    public function via(object $notifiable): array
    {
        $channels = ['database'];
        
        if ($notifiable->email_notifications && $notifiable->notify_deadlines) {
            $channels[] = 'mail';
        }
        
        return $channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        $message = (new MailMessage)
            ->subject('Напоминание о дедлайне: ' . $this->course->title)
            ->greeting('Здравствуйте, ' . $notifiable->name . '!')
            ->line('Напоминаем о приближающемся дедлайне курса "' . $this->course->title . '"');

        if ($this->daysLeft > 0) {
            $message->line('До окончания курса осталось: **' . $this->daysLeft . ' ' . $this->getDaysWord($this->daysLeft) . '**');
        } else {
            $message->line('**Дедлайн курса сегодня!**');
        }

        return $message
            ->action('Продолжить обучение', route('courses.show', $this->course))
            ->line('Не упустите возможность завершить курс вовремя!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'course_id' => $this->course->id,
            'title' => 'Напоминание о дедлайне',
            'message' => 'Дедлайн курса "' . $this->course->title . '" через ' . $this->daysLeft . ' ' . $this->getDaysWord($this->daysLeft),
            'url' => route('courses.show', $this->course),
        ];
    }

    private function getDaysWord(int $days): string
    {
        if ($days === 0) return 'дней';
        
        $lastDigit = $days % 10;
        $lastTwoDigits = $days % 100;

        if ($lastTwoDigits >= 11 && $lastTwoDigits <= 19) {
            return 'дней';
        }

        return match ($lastDigit) {
            1 => 'день',
            2, 3, 4 => 'дня',
            default => 'дней',
        };
    }
}
