@props(['flag' => null, 'size' => 'w-5 h-3.5'])

@php
    // Flags are stored as a regional-indicator-symbol emoji (e.g. 🇵🇱), but
    // Windows' default emoji font often has no real flag glyph for less-common
    // countries and silently falls back to rendering the two letters as plain
    // text ("PL") instead. Decoding the emoji back to its ISO code and showing
    // an actual flag image renders identically everywhere.
    $code = null;

    if ($flag) {
        $letters = [];
        $len = mb_strlen($flag);

        for ($i = 0; $i < $len; $i++) {
            $point = mb_ord(mb_substr($flag, $i, 1));

            if ($point !== false && $point >= 127462 && $point <= 127487) {
                $letters[] = chr($point - 127397);
            }
        }

        if (count($letters) === 2) {
            $code = strtolower(implode('', $letters));
        }
    }
@endphp

@if ($code)
    <img
        src="https://flagcdn.com/w40/{{ $code }}.png"
        alt="{{ strtoupper($code) }}"
        loading="lazy"
        {{ $attributes->merge(['class' => $size.' shrink-0 rounded-sm object-cover align-middle shadow-xs']) }}
        onerror="this.outerHTML='<span class=&quot;align-middle&quot;>{{ $flag }}</span>'"
    >
@else
    <span {{ $attributes->merge(['class' => 'align-middle']) }}>{{ $flag ?: '🌐' }}</span>
@endif
