@extends('layouts.app')

@section('title', 'Сертификат - ' . $certificate->course->title)

@section('content')
<div class="text-center mb-4">
    <h1><i class="bi bi-award"></i> Сертификат об окончании курса</h1>
</div>

<div class="card shadow-lg mx-auto" style="max-width: 800px;">
    <div class="card-body p-5 text-center">
        <div style="border: 3px solid #667eea; padding: 40px; border-radius: 10px;">
            <h2 class="display-4 mb-4" style="color: #667eea;">Сертификат</h2>
            
            <p class="lead">Настоящим подтверждается, что</p>
            
            <h3 class="my-4" style="color: #667eea; font-size: 2.5rem;">
                {{ $certificate->user->name }}
            </h3>
            
            <p class="lead">успешно завершил(а) курс</p>
            
            <h4 class="my-4" style="color: #764ba2; font-size: 1.8rem;">
                "{{ $certificate->course->title }}"
            </h4>
            
            <div class="mt-5 pt-4">
                <p class="mb-1">Дата выдачи: <strong>{{ $certificate->issued_at->format('d.m.Y') }}</strong></p>
                <p class="mb-1">Платформа: <strong>EduPoint</strong></p>
                <p class="mb-1">Преподаватель: <strong>{{ $certificate->course->teacher->name }}</strong></p>
            </div>
            
            <div class="mt-4 pt-4 border-top">
                <small class="text-muted">
                    Номер сертификата: {{ $certificate->certificate_number }}
                </small>
            </div>
        </div>
    </div>
</div>

<div class="text-center mt-4">
    <a href="{{ route('certificates.download', $certificate) }}" class="btn btn-primary btn-lg">
        <i class="bi bi-download"></i> Скачать PDF
    </a>
    <a href="{{ route('certificates.index') }}" class="btn btn-outline-secondary btn-lg">
        <i class="bi bi-arrow-left"></i> Назад к сертификатам
    </a>
</div>
@endsection
