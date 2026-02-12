@props([
    'type' => 'text', // text, circle, rectangle, card
    'width' => 'w-full',
    'height' => 'h-4',
    'class' => ''
])

@php
    $baseClass = "skeleton " . $class;
    
    $typeClasses = [
        'text' => 'rounded h-4',
        'circle' => 'rounded-full',
        'rectangle' => 'rounded-lg',
        'card' => 'rounded-2xl h-48',
    ][($type ?? 'text')] ?? 'rounded h-4';
    
    // Circle should have equal width and height if not specified
    if ($type === 'circle' && $width === 'w-full') {
        $width = 'w-12';
        $height = 'h-12';
    }
@endphp

<div class="{{ $baseClass }} {{ $typeClasses }} {{ $width }} {{ $height }}"></div>
