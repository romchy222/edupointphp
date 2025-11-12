<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PageController extends Controller
{
    public function about(): View
    {
        return view('pages.about');
    }

    public function contact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // Сохраняем заявку в БД
        ContactMessage::create($validated);

        // Здесь можно добавить отправку email
        // Mail::to('admin@edupoint.ru')->send(new ContactMessage($validated));

        return back()->with('success', 'Спасибо за ваше сообщение! Мы свяжемся с вами в ближайшее время.');
    }
}
