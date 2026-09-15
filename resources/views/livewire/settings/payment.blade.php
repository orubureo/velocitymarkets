<section class="w-full">
    @include('partials.settings-heading')

    <flux:heading class="sr-only">{{ __('Payment settings') }}</flux:heading>

    <x-settings.layout :heading="__('Payment methods')" :subheading="__('Manage your bank and crypto withdrawal addresses.')">
        <form wire:submit="updatePaymentInformation" class="my-6 w-full space-y-6">
            <flux:heading size="lg" class="font-semibold">{{ __('Bank transfer') }}</flux:heading>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <flux:input wire:model="bank_name" :label="__('Bank name')" placeholder="Enter bank name" type="text" />
                <flux:input wire:model="bank_account_name" :label="__('Account name')" placeholder="Enter account name" type="text" />
                <flux:input wire:model="bank_account_number" :label="__('Account number')" placeholder="Enter account number" type="text" />
                <flux:input wire:model="swift_code" :label="__('SWIFT code')" placeholder="Enter SWIFT code" type="text" />
            </div>

            <flux:heading size="lg" class="mt-8 font-semibold">{{ __('Cryptocurrency wallets') }}</flux:heading>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <flux:input wire:model="btc_address" :label="__('Bitcoin')" placeholder="Bitcoin address" type="text" description="Used for Bitcoin withdrawals." />
                <flux:input wire:model="eth_address" :label="__('Ethereum')" placeholder="Ethereum address" type="text" description="Used for Ethereum withdrawals." />
                <flux:input wire:model="ltc_address" :label="__('Litecoin')" placeholder="Litecoin address" type="text" description="Used for Litecoin withdrawals." />
                <flux:input wire:model="usdt_address" :label="__('USDT (TRC20)')" placeholder="USDT TRC20 address" type="text" description="Used for USDT TRC20 withdrawals." />
            </div>

            <div class="flex items-center gap-4 justify-end">
                <flux:button variant="primary" type="submit">{{ __('Save changes') }}</flux:button>
            </div>
        </form>
    </x-settings.layout>
</section>
