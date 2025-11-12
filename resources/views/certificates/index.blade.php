@extends('layouts.app')

@section('title', 'Мои сертификаты - EduPoint')

@section('content')
<h1><i class="bi bi-award"></i> Мои сертификаты</h1>

<div class="row mt-4">
    @forelse($certificates as $certificate)
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ $certificate->course->title }}</h5>
                    <p class="text-muted mb-2">
                        <i class="bi bi-calendar"></i> Выдан: {{ $certificate->issued_at->format('d.m.Y') }}
                    </p>
                    <p class="text-muted mb-3">
                        <i class="bi bi-hash"></i> Номер: {{ $certificate->certificate_number }}
                    </p>

                    <div class="d-flex gap-2">
                        <a href="{{ route('certificates.view', $certificate) }}" 
                           class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-eye"></i> Просмотреть
                        </a>
                        <a href="{{ route('certificates.download', $certificate) }}" 
                           class="btn btn-primary btn-sm">
                            <i class="bi bi-download"></i> Скачать PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i> У вас пока нет сертификатов. Завершите курс, чтобы получить сертификат!
            </div>
            <a href="{{ route('courses.index') }}" class="btn btn-primary">
                <i class="bi bi-book"></i> Просмотреть курсы
            </a>
        </div>
    @endforelse
</div>
@endsection
