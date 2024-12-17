<x-filament-panels::page.simple>
    <x-filament-panels::form wire:submit="Register">
        {{ $this->form }}
        <x-filament-panels::form.actions
            :actions="$this->getFormActions()"
        />
    </x-filament-panels::form>

    <div class="mt-4 text-center">
        <span class="text-gray-500">or</span>
        <a href="/affiliate/login"
           class="text-orange-500 underline hover:text-orange-600"
           style="font-weight: normal; font-size: 14px;">
            sign in to your account
        </a>
    </div>
</x-filament-panels::page.simple>
