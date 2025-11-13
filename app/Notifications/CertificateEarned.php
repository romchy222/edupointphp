<?php

namespace App\Notifications;

use App\Models\Certificate;
use App\Models\Course;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CertificateEarned extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Certificate $certificate,
        public Course $course
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
            ->subject('🎉 Поздравляем! Вы получили сертификат!')
            ->greeting('Поздравляем, ' . $notifiable->name . '! 🎓')
            ->line('Вы успешно завершили курс **"' . $this->course->title . '"**')
            ->line('Сертификат № ' . $this->certificate->certificate_number)
            ->line('Это большое достижение! Продолжайте учиться и развиваться.')
            ->action('Скачать сертификат', route('certificates.download', $this->certificate))
            ->line('Поздравляем с успешным завершением курса! 🎉');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'certificate_id' => $this->certificate->id,
            'course_id' => $this->course->id,
            'title' => '🎉 Сертификат получен!',
            'message' => 'Вы успешно завершили курс "' . $this->course->title . '" и получили сертификат',
            'url' => route('certificates.download', $this->certificate),
        ];
    }
}
