<x-app-layout>
    <div class="py-12">
        @livewire('plan-show-component', ['plan' => request()->route('plan')])
    </div>
</x-app-layout>