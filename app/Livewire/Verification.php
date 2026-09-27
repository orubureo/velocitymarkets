<?php

namespace App\Livewire;

use App\Models\User;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\JpegEncoder;
use Intervention\Image\ImageManager;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

#[Title('Identity verification')]
class Verification extends Component
{
    use WithFileUploads;

    public string $documentType = 'passport';

    public ?TemporaryUploadedFile $documentFile = null;

    public ?TemporaryUploadedFile $selfieFile = null;

    /**
     * Normalize an uploaded photo — EXIF-orient it and cap its longest side,
     * without cropping, so we don't silently discard part of an ID document
     * the way a cover-fit avatar thumbnail would.
     */
    private function storeKycPhoto(string $realPath, int $userId, string $kind): string
    {
        $manager = new ImageManager(new Driver);

        $encoded = $manager->decodePath($realPath)
            ->orient()
            ->scaleDown(width: 2000, height: 2000)
            ->encode(new JpegEncoder(quality: 90));

        $path = "kyc/{$userId}/{$kind}-".Str::random(40).'.jpg';

        Storage::disk('local')->put($path, (string) $encoded);

        return $path;
    }

    public function submit(): void
    {
        // Re-fetch rather than trust the guard's cached user instance — it may
        // already be stale by the time we get here (e.g. this same user's kyc
        // status just changed in another request), and the whole point of this
        // check is to catch exactly that.
        $user = User::findOrFail(Auth::guard('web')->id());

        abort_unless(in_array($user->kyc_status, ['none', 'rejected'], true), 403);

        // The dimensions rule bounds decoded pixel size (via a cheap getimagesize()
        // read) before Intervention/GD ever decodes the file — without it, a small,
        // highly-compressible file (e.g. a solid-color PNG) can pass the size cap
        // below yet still make GD allocate gigabytes of memory decoding it.
        $this->validate([
            'documentType' => ['required', 'in:passport,national_id,drivers_license'],
            'documentFile' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:10240', 'dimensions:max_width=8000,max_height=8000'],
            'selfieFile' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:10240', 'dimensions:max_width=8000,max_height=8000'],
        ]);

        $oldDocumentPath = $user->kyc_document_path;
        $oldSelfiePath = $user->kyc_selfie_path;

        $documentPath = $this->storeKycPhoto($this->documentFile->getRealPath(), $user->id, 'document');

        try {
            $selfiePath = $this->storeKycPhoto($this->selfieFile->getRealPath(), $user->id, 'selfie');
        } catch (\Throwable $e) {
            // Don't leave the document file orphaned on disk if the selfie
            // fails to process after it — neither is saved to the user yet.
            Storage::disk('local')->delete($documentPath);

            throw $e;
        }

        $user->update([
            'kyc_status' => 'pending',
            'kyc_document_type' => $this->documentType,
            'kyc_document_path' => $documentPath,
            'kyc_selfie_path' => $selfiePath,
            'kyc_submitted_at' => now(),
            'kyc_reviewed_at' => null,
            'kyc_rejection_reason' => null,
        ]);

        if ($oldDocumentPath) {
            Storage::disk('local')->delete($oldDocumentPath);
        }
        if ($oldSelfiePath) {
            Storage::disk('local')->delete($oldSelfiePath);
        }

        $this->reset(['documentFile', 'selfieFile']);

        Flux::toast(variant: 'success', text: __('Verification submitted — we\'ll review it shortly.'));
    }

    public function render(): View
    {
        return view('livewire.verification', [
            // Fresh fetch, not the guard's cached instance — must reflect the
            // status change submit() just made within the same request/render cycle.
            'user' => User::findOrFail(Auth::guard('web')->id()),
        ]);
    }
}
