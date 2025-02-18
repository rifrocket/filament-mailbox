<?php

use Illuminate\Support\Facades\Route;
use RifRocket\FilamentMailbox\Http\Controllers\MailboxController;

/*
|--------------------------------------------------------------------------
| Mailbox Plugin Web Routes
|--------------------------------------------------------------------------
|
| Here you can register web routes for the mailbox plugin. These routes
| are loaded by the package's service provider and are typically assigned
| to the "web" middleware group, ensuring they receive session state, CSRF
| protection, etc.
|
*/

// Route::middleware(['web', 'auth'])
//     ->prefix('mailbox')
//     ->name('mailbox.')
//     ->group(function () {
//         // Example route: manually trigger email synchronization.
//         Route::get('/sync', [MailboxController::class, 'sync'])->name('sync');
//     });

