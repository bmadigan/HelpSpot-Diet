<?php

use App\Livewire\Dashboard\Index as DashboardIndex;
use App\Livewire\Tickets\Inbox;
use App\Livewire\Tickets\Show;
use Illuminate\Support\Facades\Route;

Route::get('/', DashboardIndex::class)->name('dashboard');

Route::get('/tickets', Inbox::class)->name('tickets.index');
Route::get('/tickets/{ticket}', Show::class)->name('tickets.show');
