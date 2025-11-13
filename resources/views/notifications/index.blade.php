@extends('layouts.app')

@section('title', 'Уведомления - EduPoint')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="bi bi-bell"></i> Уведомления</h2>
                <div>
                    @if($unreadCount > 0)
                        <form action="{{ route('notifications.mark-all-read') }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="bi bi-check-all"></i> Отметить все как прочитанные
                            </button>
                        </form>
                    @endif
                    <a href="{{ route('notifications.settings') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-gear"></i> Настройки
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($notifications->isEmpty())
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> У вас пока нет уведомлений
                </div>
            @else
                <div class="list-group">
                    @foreach($notifications as $notification)
                        <div class="list-group-item list-group-item-action {{ $notification->read_at ? '' : 'list-group-item-primary' }}">
                            <div class="d-flex w-100 justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <h5 class="mb-1">
                                        @if(!$notification->read_at)
                                            <span class="badge bg-primary me-2">Новое</span>
                                        @endif
                                        {{ $notification->data['title'] ?? 'Уведомление' }}
                                    </h5>
                                    <p class="mb-1">{{ $notification->data['message'] ?? '' }}</p>
                                    <small class="text-muted">
                                        <i class="bi bi-clock"></i> {{ $notification->created_at->diffForHumans() }}
                                    </small>
                                </div>
                                <div class="btn-group ms-3" role="group">
                                    @if(!$notification->read_at && isset($notification->data['url']))
                                        <form action="{{ route('notifications.mark-read', $notification->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success" title="Открыть">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </form>
                                    @elseif(isset($notification->data['url']))
                                        <a href="{{ $notification->data['url'] }}" class="btn btn-sm btn-outline-primary" title="Перейти">
                                            <i class="bi bi-box-arrow-up-right"></i>
                                        </a>
                                    @endif
                                    <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Удалить" onclick="return confirm('Удалить это уведомление?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
