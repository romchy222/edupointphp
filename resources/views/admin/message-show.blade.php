@extends('layouts.app')

@section('title', 'Заявка #' . $message->id . ' - Админ')

@section('content')
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="bi bi-envelope"></i> Заявка #{{ $message->id }}</h1>
            <a href="{{ route('admin.messages') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Назад
            </a>
        </div>

        <!-- Информация о заявке -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">{{ $message->subject }}</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>От:</strong> {{ $message->name }}<br>
                        <strong>Email:</strong> {{ $message->email }}<br>
                        <strong>Дата:</strong> {{ $message->created_at->format('d.m.Y H:i') }}
                    </div>
                    <div class="col-md-6 text-end">
                        <form method="POST" action="{{ route('admin.messages.status', $message) }}" class="d-inline">
                            @csrf
                            @method('PUT')
                            <label>Статус:</label>
                            <select name="status" class="form-select form-select-sm d-inline w-auto" onchange="this.form.submit()">
                                <option value="new" {{ $message->status == 'new' ? 'selected' : '' }}>Новая</option>
                                <option value="in_progress" {{ $message->status == 'in_progress' ? 'selected' : '' }}>В работе</option>
                                <option value="resolved" {{ $message->status == 'resolved' ? 'selected' : '' }}>Решена</option>
                                <option value="closed" {{ $message->status == 'closed' ? 'selected' : '' }}>Закрыта</option>
                            </select>
                        </form>
                    </div>
                </div>

                <hr>

                <h6>Сообщение:</h6>
                <p style="white-space: pre-wrap;">{{ $message->message }}</p>
            </div>
        </div>

        <!-- Ответ администратора -->
        @if($message->admin_reply)
            <div class="card mb-4 border-success">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Ответ администратора</h5>
                </div>
                <div class="card-body">
                    <p style="white-space: pre-wrap;">{{ $message->admin_reply }}</p>
                    <small class="text-muted">Отправлено: {{ $message->replied_at->format('d.m.Y H:i') }}</small>
                </div>
            </div>
        @else
            <!-- Форма ответа -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Ответить на заявку</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.messages.reply', $message) }}">
                        @csrf
                        <div class="mb-3">
                            <label for="admin_reply" class="form-label">Ваш ответ</label>
                            <textarea class="form-control @error('admin_reply') is-invalid @enderror" 
                                      id="admin_reply" 
                                      name="admin_reply" 
                                      rows="5" 
                                      required></textarea>
                            @error('admin_reply')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-send"></i> Отправить ответ
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
