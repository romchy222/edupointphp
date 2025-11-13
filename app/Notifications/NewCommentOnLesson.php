<?php

namespace App\Notifications;

use App\Models\Comment;
use App\Models\Lesson;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewCommentOnLesson extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Comment $comment,
        public Lesson $lesson
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
            ->subject('Новый комментарий к уроку')
            ->greeting('Здравствуйте, ' . $notifiable->name . '!')
            ->line('Новый комментарий к уроку "' . $this->lesson->title . '"')
            ->line('**' . $this->comment->user->name . '** написал:')
            ->line('"' . \Str::limit($this->comment->content, 150) . '"')
            ->action('Посмотреть комментарий', route('lessons.show', [$this->lesson->course_id, $this->lesson]))
            ->line('Спасибо за участие в обсуждениях!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'comment_id' => $this->comment->id,
            'lesson_id' => $this->lesson->id,
            'title' => 'Новый комментарий',
            'message' => $this->comment->user->name . ' оставил комментарий к уроку "' . $this->lesson->title . '"',
            'url' => route('lessons.show', [$this->lesson->course_id, $this->lesson]),
        ];
    }
}
