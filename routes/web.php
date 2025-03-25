<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\IncomingLetterController;
use App\Http\Controllers\LetterClassificationController;
use App\Http\Controllers\OutgoingLetterApprovalController;
use App\Http\Controllers\OutgoingLetterController;
use App\Http\Controllers\OutgoingLetterDispositionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VillageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

// Auth Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Public Routes
Route::get('incoming-letters/{incomingLetter}/verify', [IncomingLetterController::class, 'verify'])
    ->name('incoming-letters.verify');

// Protected Routes
Route::middleware(['auth', 'role:Menandatangani Surat,Memparaf Surat,Pembuat Surat,Bagian Umum,Admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('incoming-letters', IncomingLetterController::class);
    Route::resource('outgoing-letters', OutgoingLetterController::class);
});

Route::middleware(['auth', 'role:Menandatangani Surat'])->group(function () {
    Route::post('/incoming-letters/{letter}/sign', [IncomingLetterController::class, 'sign'])->name('incoming-letters.sign');
    Route::post('/outgoing-letters/{letter}/sign', [OutgoingLetterController::class, 'sign'])->name('outgoing-letters.sign');
});

Route::middleware(['auth', 'role:Memparaf Surat'])->group(function () {
    Route::post('/incoming-letters/{letter}/paraf', [IncomingLetterController::class, 'paraf'])->name('incoming-letters.paraf');
    Route::post('/outgoing-letters/{letter}/paraf', [OutgoingLetterController::class, 'paraf'])->name('outgoing-letters.paraf');
});

Route::middleware(['auth', 'role:Pembuat Surat'])->group(function () {
    // Routes for Pembuat Surat
});

Route::middleware(['auth', 'role:Bagian Umum'])->group(function () {
    // Routes for Bagian Umum
});

// Master Data Routes (Admin Only)
Route::middleware(['role:Admin'])->group(function () {
    Route::resource('roles', RoleController::class);
    Route::resource('villages', VillageController::class);
    Route::resource('departments', DepartmentController::class);
    Route::resource('letter-classifications', LetterClassificationController::class);
    Route::resource('users', UserController::class);
    Route::patch('users/{user}/activate', [UserController::class, 'activate'])->name('users.activate');
    Route::patch('users/{user}/deactivate', [UserController::class, 'deactivate'])->name('users.deactivate');
});

// Signature Management Routes (Admin & Bagian Umum)
Route::middleware(['role:Admin,Bagian Umum'])->group(function () {
    Route::get('users/{user}/signature', [UserController::class, 'showSignature'])->name('users.signature');
    Route::post('users/{user}/signature/upload', [UserController::class, 'uploadSignature'])->name('users.signature.upload');
    Route::post('users/{user}/signature/draw', [UserController::class, 'saveDrawnSignature'])->name('users.signature.draw');
});

// Letter Routes
Route::get('incoming-letters/{incomingLetter}/download-attachment', [IncomingLetterController::class, 'downloadAttachment'])
    ->name('incoming-letters.download-attachment');
Route::get('incoming-letters/{incomingLetter}/download-final', [IncomingLetterController::class, 'downloadFinal'])
    ->name('incoming-letters.download-final');
Route::get('incoming-letters/{incomingLetter}/download-signature', [IncomingLetterController::class, 'downloadSignature'])
    ->name('incoming-letters.download-signature');
Route::post('incoming-letters/{incomingLetter}/approve', [IncomingLetterController::class, 'approve'])
    ->name('incoming-letters.approve');
Route::post('incoming-letters/{incomingLetter}/reject', [IncomingLetterController::class, 'reject'])
    ->name('incoming-letters.reject');
Route::post('incoming-letters/{incomingLetter}/create-disposition', [IncomingLetterController::class, 'createDisposition'])
    ->name('incoming-letters.create-disposition');
Route::post('incoming-letters/{incomingLetter}/store-disposition', [IncomingLetterController::class, 'storeDisposition'])
    ->name('incoming-letters.store-disposition');

// Sekdes actions for incoming letters
Route::middleware(['role:Memparaf Surat'])->group(function () {
    Route::post('incoming-letters/{incomingLetter}/sekdes-approve', [IncomingLetterController::class, 'sekdesApprove'])
        ->name('incoming-letters.sekdes-approve');
    Route::post('incoming-letters/{incomingLetter}/sekdes-reject', [IncomingLetterController::class, 'sekdesReject'])
        ->name('incoming-letters.sekdes-reject');
    Route::post('incoming-letters/{incomingLetter}/forward-to-kades', [IncomingLetterController::class, 'forwardToKades'])
        ->name('incoming-letters.forward-to-kades');
    Route::post('incoming-letters/{incomingLetter}/forward-to-user', [IncomingLetterController::class, 'forwardToUser'])
        ->name('incoming-letters.forward-to-user');
});

// Kades actions for incoming letters
Route::middleware(['role:Menandatangani Surat'])->group(function () {
    Route::post('incoming-letters/{incomingLetter}/kades-sign', [IncomingLetterController::class, 'kadesSign'])
        ->name('incoming-letters.kades-sign');
});

// Umum Desa actions for incoming letters
Route::middleware(['role:Bagian Umum'])->group(function () {
    Route::post('incoming-letters/{incomingLetter}/process', [IncomingLetterController::class, 'process'])
        ->name('incoming-letters.process');
});

// Disposition actions
Route::post('incoming-letters/dispositions/{disposition}/mark-as-read', [IncomingLetterController::class, 'markDispositionAsRead'])
    ->name('incoming-letters.dispositions.mark-as-read');
Route::delete('incoming-letters/dispositions/{disposition}', [IncomingLetterController::class, 'deleteDisposition'])
    ->name('incoming-letters.dispositions.delete');

Route::get('outgoing-letters/{outgoingLetter}/download-attachment', [OutgoingLetterController::class, 'downloadAttachment'])
    ->name('outgoing-letters.download-attachment');
Route::get('outgoing-letters/{outgoingLetter}/download-letter-file', [OutgoingLetterController::class, 'downloadLetterFile'])
    ->name('outgoing-letters.download-letter-file');
Route::get('outgoing-letters/{outgoingLetter}/verify', [OutgoingLetterController::class, 'verify'])
    ->name('outgoing-letters.verify');

// Outgoing Letter Approval Routes (Pembuat Surat Only)
Route::middleware(['role:Pembuat Surat'])->group(function () {
    Route::post('/outgoing-letters/{outgoingLetter}/submit', [OutgoingLetterController::class, 'submit'])
        ->name('outgoing-letters.submit');
});

// Sekdes Approval Routes (Memparaf Surat Only)
Route::middleware(['role:Memparaf Surat'])->group(function () {
    Route::get('/sekdes/approval', [OutgoingLetterApprovalController::class, 'sekdesApprovalList'])
        ->name('outgoing-letters.sekdes-approval');
    Route::post('/sekdes/approval/{letter}/approve', [OutgoingLetterApprovalController::class, 'sekdesApprove'])
        ->name('outgoing-letters.sekdes-approve');
    Route::post('/sekdes/approval/{letter}/reject', [OutgoingLetterApprovalController::class, 'sekdesReject'])
        ->name('outgoing-letters.sekdes-reject');
});

// Kades Approval Routes (Menandatangani Surat Only)
Route::middleware(['role:Menandatangani Surat'])->group(function () {
    Route::get('/kades/approval', [OutgoingLetterApprovalController::class, 'kadesApprovalList'])
        ->name('outgoing-letters.kades-approval');
    Route::post('/kades/approval/{letter}/approve', [OutgoingLetterApprovalController::class, 'kadesApprove'])
        ->name('outgoing-letters.kades-approve');
    Route::post('/kades/approval/{letter}/reject', [OutgoingLetterApprovalController::class, 'kadesReject'])
        ->name('outgoing-letters.kades-reject');
});

// Umum Desa Processing Routes (Bagian Umum Only)
Route::middleware(['role:Bagian Umum'])->group(function () {
    Route::get('/umum/processing', [OutgoingLetterApprovalController::class, 'umumProcessingList'])
        ->name('outgoing-letters.umum-processing');
    Route::post('/umum/processing/{outgoingLetter}/process', [OutgoingLetterApprovalController::class, 'umumProcess'])
        ->name('outgoing-letters.umum-process');
});

// Outgoing Letter Disposition Routes
Route::post('/outgoing-letters/{outgoingLetter}/dispositions', [OutgoingLetterDispositionController::class, 'store'])
    ->name('outgoing-letters.dispositions.store');
Route::post('/dispositions/{disposition}/mark-as-read', [OutgoingLetterDispositionController::class, 'markAsRead'])
    ->name('outgoing-letters.dispositions.mark-as-read');
Route::delete('/dispositions/{disposition}', [OutgoingLetterDispositionController::class, 'destroy'])
    ->name('outgoing-letters.dispositions.destroy');

Route::get('/incoming-letters/users-by-village/{village}', [IncomingLetterController::class, 'getUsersByVillage'])
    ->name('incoming-letters.users-by-village')
    ->where('village', 'all|\d+');

// Incoming Letter Approval Routes
Route::middleware(['role:Pembuat Surat'])->group(function () {
    Route::get('/incoming-letters/{incomingLetter}/submit', [IncomingLetterController::class, 'submit'])
        ->name('incoming-letters.submit');
});

// Sekdes Approval Routes for Incoming Letters (Memparaf Surat Only)
Route::middleware(['role:Memparaf Surat'])->group(function () {
    Route::post('/incoming-letters/{incomingLetter}/sekdes-approve', [IncomingLetterController::class, 'sekdesApprove'])
        ->name('incoming-letters.sekdes-approve');
    Route::post('/incoming-letters/{incomingLetter}/sekdes-reject', [IncomingLetterController::class, 'sekdesReject'])
        ->name('incoming-letters.sekdes-reject');
});

// Kades Approval Routes for Incoming Letters (Menandatangani Surat Only)
Route::middleware(['role:Menandatangani Surat'])->group(function () {
    Route::post('/incoming-letters/{incomingLetter}/kades-approve', [IncomingLetterController::class, 'kadesApprove'])
        ->name('incoming-letters.kades-approve');
    Route::post('/incoming-letters/{incomingLetter}/kades-reject', [IncomingLetterController::class, 'kadesReject'])
        ->name('incoming-letters.kades-reject');
});

// Incoming Letter Disposition Routes
Route::post('/incoming-letters/{incomingLetter}/dispositions', [IncomingLetterController::class, 'createDisposition'])
    ->name('incoming-letters.dispositions.create');
Route::post('/incoming-letters/dispositions/{disposition}/mark-as-read', [IncomingLetterController::class, 'markDispositionAsRead'])
    ->name('incoming-letters.dispositions.mark-as-read');
Route::delete('/incoming-letters/dispositions/{disposition}', [IncomingLetterController::class, 'deleteDisposition'])
    ->name('incoming-letters.dispositions.delete');
