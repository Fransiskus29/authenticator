@props(['value' => ''])

<input {{ $attributes->merge(['value' => $value])->class(['w-full bg-surface-container-lowest/80 border border-outline-variant/50 rounded-xl px-sm py-3 text-body-md text-on-surface focus:outline-none input-glow transition-all duration-300 placeholder:text-on-surface-variant/50']) }}>
