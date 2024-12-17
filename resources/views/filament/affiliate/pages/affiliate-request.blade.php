<x-filament-panels::page.simple>
    <div class="space-y-6">
        <img class="w-20" src={{$affiliate_program->app->image}} alt="">
        <h1 class="text-3xl font-semibold text-gray-800">
            <p>Become affiliate for</p>
            {{ $affiliate_program->app->name }}
        </h1>
        <p class="text-lg text-gray-600">
            Sign up now to start earning <span class="font-semibold">{{ $affiliate_program->commission_rate }}%</span> commission for life and <span class="font-semibold">{{ $affiliate_program->amount_per_install }}$</span> when your referrals install the app.
        </p>
        <x-filament::button wire:click="redirectToAffiliateRegister" class="bg-primary-600 text-white hover:bg-primary-700 focus:outline-none">
            Become an affiliate
        </x-filament::button>
    </div>
</x-filament-panels::page.simple>
