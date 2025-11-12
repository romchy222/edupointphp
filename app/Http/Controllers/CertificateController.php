<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Course;
use App\Services\CertificateService;
use Illuminate\Http\Response;
use Illuminate\View\View;

class CertificateController extends Controller
{
    public function __construct(private CertificateService $certificateService)
    {
    }

    public function index(): View
    {
        $certificates = auth()->user()->certificates()->with('course')->get();
        return view('certificates.index', compact('certificates'));
    }

    public function generate(Course $course)
    {
        $user = auth()->user();

        // Проверяем, завершен ли курс
        $enrollment = $course->enrollments()->where('user_id', $user->id)->first();
        
        if (!$enrollment || !$enrollment->completed_at) {
            return back()->with('error', 'Вы должны завершить курс для получения сертификата!');
        }

        // Проверяем, существует ли уже сертификат
        $certificate = Certificate::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if (!$certificate) {
            $certificate = Certificate::create([
                'user_id' => $user->id,
                'course_id' => $course->id,
                'certificate_number' => Certificate::generateCertificateNumber(),
                'issued_at' => now(),
            ]);
        }

        return redirect()->route('certificates.download', $certificate);
    }

    public function download(Certificate $certificate): Response
    {
        // Проверяем права доступа
        if ($certificate->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $pdf = $this->certificateService->generate($certificate);

        return response($pdf)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="certificate-' . $certificate->certificate_number . '.pdf"');
    }

    public function view(Certificate $certificate): View
    {
        if ($certificate->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $certificate->load(['user', 'course']);
        return view('certificates.view', compact('certificate'));
    }
}
