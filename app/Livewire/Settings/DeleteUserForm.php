<?php

namespace App\Livewire\Settings;

use App\Concerns\PasswordValidationRules;
use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class DeleteUserForm extends Component
{
    use PasswordValidationRules;

    public string $password = '';

    /**
     * Delete the currently authenticated user.
     */
    public function deleteUser(Logout $logout): void
    {
        $this->validate([
            'password' => $this->currentPasswordRules(),
        ]);

        $user = Auth::guard('web')->user();

        // Not cleaned up by any DB cascade — these live on disk, keyed by
        // path, not by a foreign key the users table's own deletion touches.
        collect([$user->kyc_document_path, $user->kyc_selfie_path])
            ->filter()
            ->each(fn (string $path) => Storage::disk('local')->delete($path));

        tap($user, $logout(...))->delete();

        $this->redirect('/', navigate: true);
    }
}
