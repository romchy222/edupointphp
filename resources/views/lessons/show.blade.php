@extends('layouts.app')

@section('title', $lesson->title . ' - ' . $course->title)

@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('courses.index') }}">Курсы</a></li>
        <li class="breadcrumb-item"><a href="{{ route('courses.show', $course) }}">{{ $course->title }}</a></li>
        <li class="breadcrumb-item active">{{ $lesson->title }}</li>
    </ol>
</nav>

<div class="row">
    <div class="col-lg-9">
        <h1>{{ $lesson->title }}</h1>

        @if($lesson->video_url)
            <div class="ratio ratio-16x9 mb-4">
                @if(str_contains($lesson->video_url, 'youtube.com') || str_contains($lesson->video_url, 'youtu.be'))
                    @php
                        $videoId = '';
                        if (preg_match('/youtube\.com\/watch\?v=([^&]+)/', $lesson->video_url, $matches)) {
                            $videoId = $matches[1];
                        } elseif (preg_match('/youtu\.be\/([^?]+)/', $lesson->video_url, $matches)) {
                            $videoId = $matches[1];
                        }
                    @endphp
                    @if($videoId)
                        <iframe src="https://www.youtube.com/embed/{{ $videoId }}" allowfullscreen></iframe>
                    @endif
                @else
                    <video controls class="w-100">
                        <source src="{{ $lesson->video_url }}" type="video/mp4">
                    </video>
                @endif
            </div>
        @endif

        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title"><i class="bi bi-file-text"></i> Содержание урока</h5>
                <div class="lesson-content">
                    {!! nl2br(e($lesson->content)) !!}
                </div>
            </div>
        </div>

        @if($lesson->file_path)
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-download"></i> Материалы урока</h5>
                    <a href="{{ asset('storage/' . $lesson->file_path) }}" class="btn btn-outline-primary" download>
                        <i class="bi bi-file-earmark-arrow-down"></i> Скачать материалы
                    </a>
                </div>
            </div>
        @endif

        <div class="d-flex justify-content-between">
            @if($prevLesson)
                <a href="{{ route('lessons.show', [$course, $prevLesson]) }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Предыдущий урок
                </a>
            @else
                <div></div>
            @endif

            @if(!$isCompleted)
                <button type="button" class="btn btn-success" id="completeBtn">
                    <i class="bi bi-check-circle"></i> Отметить как завершенный
                </button>
            @else
                <span class="badge bg-success" style="font-size: 1rem; padding: 10px 20px;">
                    <i class="bi bi-check-circle-fill"></i> Завершено
                </span>
            @endif

            @if($nextLesson)
                <a href="{{ route('lessons.show', [$course, $nextLesson]) }}" class="btn btn-primary">
                    Следующий урок <i class="bi bi-arrow-right"></i>
                </a>
            @else
                <div></div>
            @endif
        </div>

        <!-- Комментарии -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-chat-dots"></i> Комментарии ({{ $lesson->comments->count() }})</h5>
            </div>
            <div class="card-body">
                @auth
                    <form action="{{ route('lessons.comments.store', $lesson) }}" method="POST" class="mb-4">
                        @csrf
                        <div class="mb-3">
                            <textarea name="comment" 
                                      class="form-control @error('comment') is-invalid @enderror" 
                                      rows="3" 
                                      placeholder="Оставьте свой комментарий..." 
                                      required></textarea>
                            @error('comment')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-send"></i> Отправить
                        </button>
                    </form>
                @else
                    <div class="alert alert-info">
                        <a href="{{ route('login') }}">Войдите</a>, чтобы оставить комментарий
                    </div>
                @endauth

                <hr>

                @forelse($lesson->comments as $comment)
                    <div class="mb-3 pb-3 border-bottom" id="comment-{{ $comment->id }}">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center">
                                    <strong>{{ $comment->user->name }}</strong>
                                    <div class="d-flex align-items-center gap-2">
                                        <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                        @auth
                                            @if(auth()->id() === $comment->user_id || auth()->user()->isAdmin())
                                                <div class="btn-group btn-group-sm">
                                                    <button class="btn btn-outline-primary btn-sm" 
                                                            onclick="editComment({{ $comment->id }}, '{{ addslashes($comment->comment) }}')">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    <form action="{{ route('lessons.comments.destroy', $comment) }}" 
                                                          method="POST" 
                                                          class="d-inline"
                                                          onsubmit="return confirm('Удалить комментарий?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger btn-sm">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            @endif
                                        @endauth
                                    </div>
                                </div>
                                <p class="mt-2 mb-0" id="comment-text-{{ $comment->id }}">{{ $comment->comment }}</p>
                                <form id="comment-edit-{{ $comment->id }}" 
                                      action="{{ route('lessons.comments.update', $comment) }}" 
                                      method="POST" 
                                      class="mt-2" 
                                      style="display: none;">
                                    @csrf
                                    @method('PUT')
                                    <textarea name="comment" class="form-control mb-2" rows="3" required>{{ $comment->comment }}</textarea>
                                    <button type="submit" class="btn btn-sm btn-primary">
                                        <i class="bi bi-check"></i> Сохранить
                                    </button>
                                    <button type="button" class="btn btn-sm btn-secondary" onclick="cancelEdit({{ $comment->id }})">
                                        <i class="bi bi-x"></i> Отмена
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-muted">Пока нет комментариев. Будьте первым!</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-lg-3">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h6 class="mb-0"><i class="bi bi-list-ul"></i> Содержание курса</h6>
            </div>
            <div class="list-group list-group-flush">
                @foreach($course->lessons as $l)
                    <a href="{{ route('lessons.show', [$course, $l]) }}" 
                       class="list-group-item list-group-item-action {{ $l->id == $lesson->id ? 'active' : '' }}">
                        <div class="d-flex justify-content-between align-items-center">
                            <small>{{ $l->title }}</small>
                            @if($l->isCompletedBy(auth()->user()))
                                <i class="bi bi-check-circle-fill text-success"></i>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('completeBtn')?.addEventListener('click', function() {
    fetch('{{ route("lessons.complete", $lesson) }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
});

// Comment editing
function editComment(id, text) {
    document.getElementById('comment-text-' + id).style.display = 'none';
    document.getElementById('comment-edit-' + id).style.display = 'block';
}

function cancelEdit(id) {
    document.getElementById('comment-text-' + id).style.display = 'block';
    document.getElementById('comment-edit-' + id).style.display = 'none';
}
</script>
@endpush
@endsection
