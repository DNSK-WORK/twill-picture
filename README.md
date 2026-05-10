# twill-picture

A progressive blur-up `<x-picture>` Blade component for Laravel. Shows a blurred low-quality image placeholder (LQIP) while the full image loads, then fades it in and removes the placeholder from the DOM.

Works with any image service that accepts `w`, `q`, and `fm` as query parameters — Glide, imgproxy, Cloudinary, imgix, and others.

## Installation

```bash
composer require dnsk-work/twill-picture
```

The component is auto-registered as `<x-twill-picture::picture>`.

To use the shorter `<x-picture>` tag, publish the view to your app:

```bash
php artisan vendor:publish --tag=twill-picture-views
```

## Usage

```blade
<x-twill-picture::picture
    src="{{ $block->image('hero_image', 'desktop') }}"
    alt="{{ $title }}"
    loading="eager"
    fetchpriority="high"
    sizes="100vw"
    class="w-full h-full"
/>
```

## Props

| Prop | Default | Description |
|---|---|---|
| `src` | `''` | Image URL. Query params already on it are preserved. |
| `alt` | `''` | Alt text. |
| `loading` | `lazy` | Set to `eager` for above-the-fold / LCP images. |
| `fetchpriority` | `null` | Set to `high` for LCP images. |
| `width` | `null` | Passed to `<img>` for layout stability (CLS). |
| `height` | `null` | Passed to `<img>` for layout stability (CLS). |
| `sizes` | `100vw` | CSS `sizes` attribute for responsive image selection. |
| `class` | — | Applied to the outer wrapper `<div>`. |

Any other attributes are forwarded to the `<img>` element.

## How it works

1. Renders a wrapper `<div>` with `position: relative; overflow: hidden`.
2. Inside it, an absolutely-positioned `<div>` displays a 40×auto WebP LQIP (`w=40&q=10&fm=webp`) scaled up with `blur(24px)`. `inset: -10%` hides the blur-edge glow.
3. A `<picture>` element provides a WebP `<source>` with srcset at `400w 800w 1200w 1600w 2400w`, plus a fallback `<img>` starting at `opacity: 0`.
4. On `img.onload`, opacity transitions to `1` and the LQIP element is removed from the DOM.

## Requirements

- PHP 8.1+
- Laravel 10, 11, or 12

## License

MIT
