<?php

use App\Livewire\Admin\Auth\Login;
use App\Livewire\Admin\CopySubscriptions;
use App\Livewire\Admin\CryptoWallets;
use App\Livewire\Admin\Deposits;
use App\Livewire\Admin\InvestmentPlans;
use App\Livewire\Admin\Investments;
use App\Livewire\Admin\Kyc;
use App\Livewire\Admin\Markets;
use App\Livewire\Admin\Notifications;
use App\Livewire\Admin\Settings;
use App\Livewire\Admin\SupportTickets;
use App\Livewire\Admin\Users;
use App\Livewire\Admin\UserShow;
use App\Livewire\Admin\Withdrawals;
use App\Livewire\CopyTrading\Subscriptions;
use App\Livewire\CopyTrading\Traders;
use App\Livewire\Dashboard;
use App\Livewire\Investment\Plans;
use App\Livewire\Marketing\Contact;
use App\Livewire\News;
use App\Livewire\Referral;
use App\Livewire\Support;
use App\Livewire\Trade\Place;
use App\Livewire\Wallet\Index as WalletIndex;
use App\Livewire\Wallet\Transactions;
use App\Models\InvestmentPlan;
use App\Models\User;
use App\Models\WalletTransaction;
use App\Services\NewsService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/', function (NewsService $news) {
    return view('marketing.home', [
        'headlines' => $news->latestHeadlines(3),
    ]);
})->name('home');
Route::view('about', 'marketing.about')->name('about');
Route::view('platform', 'marketing.services')->name('services');
Route::get('pricing', function () {
    return view('marketing.pricing', [
        'plans' => InvestmentPlan::where('is_active', true)->orderBy('sort_order')->get(),
    ]);
})->name('pricing');
Route::view('faq', 'marketing.faq')->name('faq');
Route::view('terms', 'marketing.terms')->name('terms');
Route::get('contact', Contact::class)->name('contact');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', Dashboard::class)->name('dashboard');
    Route::get('news', News::class)->name('news');
    Route::get('wallet', WalletIndex::class)->name('wallet');
    Route::get('transactions', Transactions::class)->name('transactions');
    Route::get('trade', Place::class)->name('trade');
    Route::get('investment-plans', Plans::class)->name('investment.plans');
    Route::get('copy-trading', Traders::class)->name('copy-trading');
    Route::get('copy-trading/subscriptions', Subscriptions::class)->name('copy-trading.subscriptions');
    Route::get('referral', Referral::class)->name('referral');
    Route::get('support', Support::class)->name('support');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest:admin')->group(function () {
        Route::get('login', Login::class)->name('login');
    });

    Route::middleware('auth:admin')->group(function () {
        Route::get('dashboard', App\Livewire\Admin\Dashboard::class)->name('dashboard');
        Route::get('deposits', Deposits::class)->name('deposits');
        Route::get('withdrawals', Withdrawals::class)->name('withdrawals');
        Route::get('markets', Markets::class)->name('markets');
        Route::get('users', Users::class)->name('users');
        Route::get('users/{user}', UserShow::class)->name('users.show');
        Route::get('investment-plans', InvestmentPlans::class)->name('investment-plans');
        Route::get('traders', App\Livewire\Admin\Traders::class)->name('traders');
        Route::get('crypto-wallets', CryptoWallets::class)->name('crypto-wallets');
        Route::get('investments', Investments::class)->name('investments');
        Route::get('copy-subscriptions', CopySubscriptions::class)->name('copy-subscriptions');
        Route::get('kyc', Kyc::class)->name('kyc');
        Route::get('support-tickets', SupportTickets::class)->name('support-tickets');
        Route::get('settings', Settings::class)->name('settings');
        Route::get('notifications', Notifications::class)->name('notifications');
        Route::get('deposits/{transaction}/proof', function (WalletTransaction $transaction) {
            abort_unless(filled($transaction->proof_path), 404);

            return Storage::disk('local')->download($transaction->proof_path);
        })->name('deposits.proof');
        Route::get('kyc/{user}/document', function (User $user) {
            abort_unless(filled($user->kyc_document_path), 404);

            return Storage::disk('local')->download($user->kyc_document_path);
        })->name('kyc.document');
        Route::post('logout', function () {
            Auth::guard('admin')->logout();
            request()->session()->invalidate();

            return redirect()->route('admin.login');
        })->name('logout');
    });
});

require __DIR__.'/settings.php';
