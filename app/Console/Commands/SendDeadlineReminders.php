<?php

namespace App\Console\Commands;

use App\Models\Course;
use App\Notifications\CourseDeadlineReminder;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendDeadlineReminders extends Command
{
    protected $signature = 'deadlines:remind';
    protected $description = 'Send deadline reminders to enrolled students';

    public function handle()
    {
        $this->info('Проверка дедлайнов курсов...');

        // Напоминания за 7, 3 и 1 день до дедлайна
        $reminderDays = [7, 3, 1];
        $sentCount = 0;

        foreach ($reminderDays as $days) {
            $targetDate = Carbon::today()->addDays($days);
            
            $courses = Course::whereDate('deadline', $targetDate)
                ->with('students')
                ->get();

            foreach ($courses as $course) {
                foreach ($course->students as $student) {
                    // Проверяем, завершен ли курс
                    $enrollment = $course->enrollments()
                        ->where('user_id', $student->id)
                        ->first();
                    
                    if (!$enrollment->completed_at) {
                        $student->notify(new CourseDeadlineReminder($course, $days));
                        $sentCount++;
                    }
                }
            }

            $this->info("Отправлено {$courses->count()} напоминаний за {$days} дней");
        }

        // Напоминания в день дедлайна
        $todayCourses = Course::whereDate('deadline', Carbon::today())
            ->with('students')
            ->get();

        foreach ($todayCourses as $course) {
            foreach ($course->students as $student) {
                $enrollment = $course->enrollments()
                    ->where('user_id', $student->id)
                    ->first();
                
                if (!$enrollment->completed_at) {
                    $student->notify(new CourseDeadlineReminder($course, 0));
                    $sentCount++;
                }
            }
        }

        $this->info("Отправлено {$todayCourses->count()} напоминаний на сегодня");
        $this->info("Всего отправлено: {$sentCount} уведомлений");

        return Command::SUCCESS;
    }
}
