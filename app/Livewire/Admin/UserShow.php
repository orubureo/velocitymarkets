<?php

namespace App\Livewire\Admin;

use App\Models\CopyTradeSubscription;
use App\Models\SupportTicket;
use App\Models\Trade;
use App\Models\User;
use App\Models\UserInvestment;
use App\Models\UserSignal;
use App\Models\WalletTransaction;
use App\Notifications\AccountStatusChangedNotification;
use App\Notifications\PasswordResetByAdminNotification;
use App\Notifications\WalletAdjustmentNotification;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('User Details')]
#[Layout('layouts.admin')]
class UserShow extends Component
{
    private const SETTLED_STATUSES = ['approved', 'completed'];

    /**
     * What a credit/debit is "for" — drives the wallet_transactions type and
     * status it's recorded under, so it shows up in the user's own history
     * (and in the relevant totals) as that category instead of a generic,
     * admin-flavored line. Deposit isn't debitable, matching how a real
     * deposit total shouldn't be walked back by a balance correction.
     */
    private const CATEGORIES = [
        'balance' => ['label' => 'Account Balance', 'type' => 'admin_adjustment', 'status' => 'completed', 'debitable' => true],
        'deposit' => ['label' => 'Deposit', 'type' => 'deposit', 'status' => 'approved', 'debitable' => false],
        'profit' => ['label' => 'Profit', 'type' => 'trade_profit', 'status' => 'completed', 'debitable' => true],
        'referral_bonus' => ['label' => 'Referral Bonus', 'type' => 'referral_bonus', 'status' => 'completed', 'debitable' => true],
        'bonus' => ['label' => 'Bonus', 'type' => 'bonus', 'status' => 'completed', 'debitable' => true],
    ];

    public User $user;

    public bool $showAdjustModal = false;

    public string $adjustType = 'credit';

    public string $adjustCategory = 'balance';

    public string $adjustAmount = '';

    public string $adjustNote = '';

    public bool $showEditModal = false;

    public string $editName = '';

    public string $editEmail = '';

    public string $editPhone = '';

    public string $editCountry = '';

    public bool $showResetPasswordModal = false;

    public string $generatedPassword = '';

    public bool $showDeleteModal = false;

    public string $deleteConfirmation = '';

    public function mount(User $user): void
    {
        $this->user = $user;
    }

    public function openAdjustModal(string $type): void
    {
        $this->adjustType = $type;
        $this->adjustCategory = 'balance';
        $this->adjustAmount = '';
        $this->adjustNote = '';
        $this->resetValidation();
        $this->showAdjustModal = true;
    }

    public function closeAdjustModal(): void
    {
        $this->showAdjustModal = false;
        $this->reset(['adjustAmount', 'adjustNote']);
        $this->adjustType = 'credit';
        $this->adjustCategory = 'balance';
    }

    public function adjustBalance(): void
    {
        $wallet = $this->user->wallet;

        $this->validate([
            'adjustCategory' => ['required', Rule::in(array_keys(self::CATEGORIES))],
            'adjustAmount' => $this->adjustType === 'credit'
                ? ['required', 'numeric', 'min:0.01']
                : ['required', 'numeric', 'min:0.01', 'max:'.$wallet->balance],
            'adjustNote' => ['required', 'string', 'max:255'],
        ], [
            'adjustAmount.max' => 'Debit amount exceeds the user\'s wallet balance.',
        ]);

        $category = self::CATEGORIES[$this->adjustCategory];

        if ($this->adjustType === 'debit' && ! $category['debitable']) {
            $this->addError('adjustCategory', "You can't debit the {$category['label']} category.");

            return;
        }

        $amount = (float) $this->adjustAmount;
        $signedAmount = $this->adjustType === 'credit' ? $amount : -$amount;

        $transaction = WalletTransaction::create([
            'wallet_id' => $wallet->id,
            'type' => $category['type'],
            'amount' => $signedAmount,
            'status' => $category['status'],
            'approved_by' => Auth::guard('admin')->id(),
            'approved_at' => now(),
            'note' => $this->adjustNote,
        ]);

        $wallet->increment('balance', $signedAmount);
        $this->user->unsetRelation('wallet');

        $this->user->notify(new WalletAdjustmentNotification($transaction, $category['label']));

        $this->closeAdjustModal();
    }

    public function openEditModal(): void
    {
        $this->editName = $this->user->name;
        $this->editEmail = $this->user->email;
        $this->editPhone = (string) $this->user->phone;
        $this->editCountry = (string) $this->user->country;
        $this->resetValidation();
        $this->showEditModal = true;
    }

    public function closeEditModal(): void
    {
        $this->showEditModal = false;
        $this->reset(['editName', 'editEmail', 'editPhone', 'editCountry']);
    }

    public function saveEdit(): void
    {
        $this->validate([
            'editName' => ['required', 'string', 'max:255'],
            'editEmail' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->user->id)],
            'editPhone' => ['nullable', 'string', 'max:50'],
            'editCountry' => ['nullable', 'string', 'max:100'],
        ]);

        $this->user->update([
            'name' => $this->editName,
            'email' => $this->editEmail,
            'phone' => $this->editPhone ?: null,
            'country' => $this->editCountry ?: null,
        ]);

        $this->closeEditModal();
    }

    public function toggleBlock(): void
    {
        $this->user->update(['is_blocked' => ! $this->user->is_blocked]);

        $this->user->notify(new AccountStatusChangedNotification($this->user->is_blocked));
    }

    public function togglePauseWithdrawals(): void
    {
        $this->user->update(['withdrawals_paused' => ! $this->user->withdrawals_paused]);
    }

    public function verifyEmail(): void
    {
        if ($this->user->email_verified_at) {
            return;
        }

        $this->user->forceFill(['email_verified_at' => now()])->save();
    }

    public function resetPassword(): void
    {
        $this->generatedPassword = Str::password(12);

        $this->user->update(['password' => $this->generatedPassword]);

        $this->user->notify(new PasswordResetByAdminNotification($this->generatedPassword));

        $this->showResetPasswordModal = true;
    }

    public function closeResetPasswordModal(): void
    {
        $this->showResetPasswordModal = false;
        $this->generatedPassword = '';
    }

    public function loginAsUser(): void
    {
        session([
            'impersonating_admin_id' => Auth::guard('admin')->id(),
            'impersonating_admin_name' => Auth::guard('admin')->user()->name,
        ]);

        Auth::guard('web')->login($this->user);

        $this->redirect(route('dashboard'), navigate: false);
    }

    public function confirmDelete(): void
    {
        $this->deleteConfirmation = '';
        $this->resetValidation();
        $this->showDeleteModal = true;
    }

    public function closeDeleteModal(): void
    {
        $this->showDeleteModal = false;
        $this->reset(['deleteConfirmation']);
    }

    public function deleteUser(): void
    {
        $this->validate([
            'deleteConfirmation' => ['required', 'in:DELETE'],
        ], [
            'deleteConfirmation.in' => 'Type DELETE (all caps) to confirm.',
        ]);

        $userId = $this->user->id;

        User::findOrFail($userId)->delete();

        $this->redirect(route('admin.users'), navigate: true);
    }

    public function render(): View
    {
        $this->user->loadMissing('wallet', 'referrer');

        $wallet = $this->user->wallet;

        $totalDeposits = (float) WalletTransaction::where('wallet_id', $wallet?->id)
            ->where('type', 'deposit')
            ->whereIn('status', self::SETTLED_STATUSES)
            ->sum('amount');

        $totalWithdrawals = abs((float) WalletTransaction::where('wallet_id', $wallet?->id)
            ->where('type', 'withdrawal')
            ->whereIn('status', self::SETTLED_STATUSES)
            ->sum('amount'));

        return view('livewire.admin.user-show', [
            'wallet' => $wallet,
            'adjustCategories' => self::CATEGORIES,
            'totalDeposits' => $totalDeposits,
            'totalWithdrawals' => $totalWithdrawals,
            'tradesCount' => Trade::where('user_id', $this->user->id)->count(),
            'openTickets' => SupportTicket::where('user_id', $this->user->id)
                ->whereIn('status', ['open', 'in_progress'])->count(),
            'deletingRelatedCounts' => $this->showDeleteModal ? [
                'trade' => Trade::where('user_id', $this->user->id)->count(),
                'investment' => UserInvestment::where('user_id', $this->user->id)->count(),
                'copy subscription' => CopyTradeSubscription::where('user_id', $this->user->id)->count(),
                'support ticket' => SupportTicket::where('user_id', $this->user->id)->count(),
                'signal purchase' => UserSignal::where('user_id', $this->user->id)->count(),
            ] : [],
        ]);
    }
}
