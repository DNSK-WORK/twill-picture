@props([
    'src'           => '',
    'alt'           => '',
    'loading'       => 'lazy',
    'fetchpriority' => null,
    'width'         => null,
    'height'        => null,
    'sizes'         => '100vw',
])
@php
    $swap = function(string $url, string $param, string $value): string {
        if (preg_match('/[?&]' . preg_quote($param, '/') . '=/', $url)) {
            return preg_replace('/([?&]' . preg_quote($param, '/') . '=)[^&]+/', '${1}' . $value, $url);
        }
        return $url . (str_contains($url, '?') ? '&' : '?') . $param . '=' . $value;
    };

    $widths     = [400, 800, 1200, 1600, 2400];
    $webpSrcset = collect($widths)->map(fn($w) => $swap($swap($src, 'fm', 'webp'), 'w', $w) . " {$w}w")->implode(', ');
    $webpSrc    = $swap($src, 'fm', 'webp');
    $lqip       = $swap($swap($swap($src, 'w', '40'), 'q', '10'), 'fm', 'webp');
@endphp
<div {{ $attributes->only('class') }} style="position: relative; overflow: hidden;">
    {{-- Blurred LQIP placeholder — inset:-10% hides the blur-edge glow --}}
    <div aria-hidden="true" style="
        position: absolute;
        inset: -10%;
        background-image: url('{{ $lqip }}');
        background-size: cover;
        background-position: center;
        filter: blur(24px);
        transform: scale(1.1);
    "></div>
    <picture style="display: contents">
        <source srcset="{{ $webpSrcset }}" sizes="{{ $sizes }}" type="image/webp">
        <img
            src="{{ $webpSrc }}"
            alt="{{ $alt }}"
            loading="{{ $loading }}"
            @if($fetchpriority) fetchpriority="{{ $fetchpriority }}" @endif
            @if($width) width="{{ $width }}" @endif
            @if($height) height="{{ $height }}" @endif
            style="position: relative; display: block; width: 100%; height: 100%; object-fit: cover; opacity: 0; transition: opacity 0.4s ease;"
            onload="this.style.opacity='1'; this.closest('div').querySelector('[aria-hidden]').remove();"
            {{ $attributes->except('class') }}
        >
    </picture>
</div>
