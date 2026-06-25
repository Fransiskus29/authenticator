@props(['disabled' => false])

<button {{ $disabled ? 'disabled' : '' }} {{ $attributes->class(['inline-flex items-center px-md py-sm bg-error text-on-error border border-transparent rounded-xl font-label-sm text-label-sm tracking-wide btn-press hover:shadow-lg hover:shadow-error/20 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-error focus:ring-offset-2 disabled:opacity-25']) }}>
    {{ $slot }}
</button>
