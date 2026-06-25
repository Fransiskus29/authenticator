@props(['disabled' => false])

<button {{ $disabled ? 'disabled' : '' }} {{ $attributes->class(['inline-flex items-center px-md py-sm bg-surface-container-low border border-outline-variant/50 rounded-xl font-label-sm text-label-sm text-on-surface tracking-wide btn-press hover:bg-surface-container transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 disabled:opacity-25']) }}>
    {{ $slot }}
</button>
