<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(): View
    {
        $user = auth()->user();
        $user->load(['enrolledCourses', 'certificates']);

        return view('profile.show', compact('user'));
    }

    public function edit(): View
    {
        return view('profile.edit');
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'avatar' => 'nullable|image|max:2048',
            'bio' => 'nullable|string|max:1000',
            'phone' => 'nullable|string|max:20',
            'website' => 'nullable|url|max:255',
        ]);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar) {
                \Storage::disk('public')->delete($user->avatar);
            }
            
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($validated);

        return back()->with('success', 'Профиль обновлен!');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|confirmed|min:8',
        ]);

        auth()->user()->update([
            'password' => bcrypt($validated['password']),
        ]);

        return back()->with('success', 'Пароль изменен!');
    }

    public function updateNotifications(Request $request)
    {
        $validated = $request->validate([
            'email_notifications' => 'boolean',
            'notify_new_lessons' => 'boolean',
            'notify_deadlines' => 'boolean',
            'notify_comments' => 'boolean',
        ]);

        // Конвертируем checkbox значения (null = false)
        $settings = [
            'email_notifications' => $request->has('email_notifications'),
            'notify_new_lessons' => $request->has('notify_new_lessons'),
            'notify_deadlines' => $request->has('notify_deadlines'),
            'notify_comments' => $request->has('notify_comments'),
        ];

        auth()->user()->update($settings);

        return back()->with('success', 'Настройки уведомлений сохранены!');
    }
}
