@props([
    'width' => '100%',
    'height' => '100%',
    'type'=> 'rect',
    'class' => ''
])

<div class="skeleton {{ $class }}" style="width:{{ $width }}; height:{{ $height }}; {{ $type != 'rect' ? "border-radius:999px;": '' }} "></div>
