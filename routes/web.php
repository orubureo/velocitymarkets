<?php

use App\Livewire\Account\Upgrade;
use App\Livewire\Admin\AccountTiers;
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
use App\Livewire\Admin\Signals;
use App\Livewire\Admin\SignalTiers;
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
use App\Livewire\Signal\Buy;
use App\Livewire\Support;
use App\Livewire\Trade\Markets as TradeMarkets;
use App\Livewire\Trade\Orders as TradeOrders;
use App\Livewire\Trade\Place;
use App\Livewire\Trade\Portfolio as TradePortfolio;
use App\Livewire\Wallet\Index as WalletIndex;
use App\Livewire\Wallet\Transactions;
use App\Models\InvestmentPlan;
use App\Models\User;
use App\Models\WalletTransaction;
use App\Services\NewsService;
use App\Services\PriceService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/', function (NewsService $news, PriceService $prices) {
    return view('marketing.home', [
        'headlines' => $news->latestHeadlines(3),
        'calculatorMarkets' => $prices->tickerMarkets(),
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
    Route::get('trade', TradeMarkets::class)->name('trade');
    Route::get('trade/portfolio', TradePortfolio::class)->name('trade.portfolio');
    Route::get('trade/orders', TradeOrders::class)->name('trade.orders');
    Route::get('trade/{symbol}', Place::class)->name('trade.show');
    Route::get('investment-plans', Plans::class)->name('investment.plans');
    Route::get('upgrade-account', Upgrade::class)->name('upgrade-account');
    Route::get('buy-signal', Buy::class)->name('buy-signal');
    Route::get('copy-trading', Traders::class)->name('copy-trading');
    Route::get('copy-trading/subscriptions', Subscriptions::class)->name('copy-trading.subscriptions');
    Route::get('referral', Referral::class)->name('referral');
    Route::get('support', Support::class)->name('support');
});

Route::middleware('auth')->group(function () {
    Route::post('impersonate/stop', function () {
        abort_unless(session()->has('impersonating_admin_id'), 403);

        Auth::guard('web')->logout();
        session()->forget(['impersonating_admin_id', 'impersonating_admin_name']);

        return redirect()->route('admin.users');
    })->name('impersonate.stop');
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
        Route::get('account-tiers', AccountTiers::class)->name('account-tiers');
        Route::get('signal-tiers', SignalTiers::class)->name('signal-tiers');
        Route::get('signals', Signals::class)->name('signals');
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
