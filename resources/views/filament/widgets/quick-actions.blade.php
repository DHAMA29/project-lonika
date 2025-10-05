<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            Aksi Cepat
        </x-slot>
        
        <x-slot name="description">
            Shortcut untuk tindakan yang sering dilakukan
        </x-slot>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($this->getViewData()['actions'] as $action)
                <a href="{{ $action['url'] }}" 
                   class="block p-4 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-750 hover:border-gray-300 dark:hover:border-gray-600 transition-all duration-200 group">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            @php
                                $bgClass = match($action['color']) {
                                    'primary' => 'bg-blue-100 dark:bg-blue-900/30 group-hover:bg-blue-200 dark:group-hover:bg-blue-900/50',
                                    'success' => 'bg-green-100 dark:bg-green-900/30 group-hover:bg-green-200 dark:group-hover:bg-green-900/50', 
                                    'info' => 'bg-cyan-100 dark:bg-cyan-900/30 group-hover:bg-cyan-200 dark:group-hover:bg-cyan-900/50',
                                    'warning' => 'bg-amber-100 dark:bg-amber-900/30 group-hover:bg-amber-200 dark:group-hover:bg-amber-900/50',
                                    default => 'bg-gray-100 dark:bg-gray-700/50 group-hover:bg-gray-200 dark:group-hover:bg-gray-700'
                                };
                                $iconClass = match($action['color']) {
                                    'primary' => 'text-blue-600 dark:text-blue-400',
                                    'success' => 'text-green-600 dark:text-green-400',
                                    'info' => 'text-cyan-600 dark:text-cyan-400', 
                                    'warning' => 'text-amber-600 dark:text-amber-400',
                                    default => 'text-gray-600 dark:text-gray-400'
                                };
                                $textClass = match($action['color']) {
                                    'primary' => 'group-hover:text-blue-700 dark:group-hover:text-blue-300',
                                    'success' => 'group-hover:text-green-700 dark:group-hover:text-green-300',
                                    'info' => 'group-hover:text-cyan-700 dark:group-hover:text-cyan-300',
                                    'warning' => 'group-hover:text-amber-700 dark:group-hover:text-amber-300',
                                    default => 'group-hover:text-gray-700 dark:group-hover:text-gray-300'
                                };
                            @endphp
                            <div class="w-10 h-10 rounded-lg {{ $bgClass }} flex items-center justify-center transition-colors">
                                @switch($action['icon'])
                                    @case('heroicon-o-plus-circle')
                                        <x-heroicon-o-plus-circle class="w-6 h-6 {{ $iconClass }}" />
                                        @break
                                    @case('heroicon-o-camera')
                                        <x-heroicon-o-camera class="w-6 h-6 {{ $iconClass }}" />
                                        @break
                                    @case('heroicon-o-user-plus')
                                        <x-heroicon-o-user-plus class="w-6 h-6 {{ $iconClass }}" />
                                        @break
                                    @case('heroicon-o-ticket')
                                        <x-heroicon-o-ticket class="w-6 h-6 {{ $iconClass }}" />
                                        @break
                                    @default
                                        <x-heroicon-o-squares-plus class="w-6 h-6 {{ $iconClass }}" />
                                @endswitch
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100 {{ $textClass }}">
                                {{ $action['label'] }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-300">
                                {{ $action['description'] }}
                            </p>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
        
        <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    Dashboard terakhir diperbarui: {{ now()->format('d M Y, H:i') }} WIB
                </div>
                <div class="flex space-x-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400">
                        <x-heroicon-m-check-circle class="w-4 h-4 mr-1" />
                        System Online
                    </span>
                </div>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
