<?php

use App\Livewire\Tickets\Inbox;
use App\Livewire\Tickets\Show;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('tickets.index');
});

Route::get('/tickets', Inbox::class)->name('tickets.index');
Route::get('/tickets/{ticket}', Show::class)->name('tickets.show');
