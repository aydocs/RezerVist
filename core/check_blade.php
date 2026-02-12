<?php

$file = 'resources/views/business/show.blade.php';
$content = file_get_contents($file);

$directives = [
    'if' => 'endif',
    'foreach' => 'endforeach',
    'forelse' => 'endforelse',
    'auth' => 'endauth',
    'guest' => 'endguest',
    'section' => 'endsection',
    'push' => 'endpush',
    'can' => 'endcan',
    'cannot' => 'endcannot',
    'unless' => 'endunless',
    'isset' => 'endisset',
    'empty' => 'endempty', // @empty is used with @forelse or as @empty($var)
    'error' => 'enderror',
    'production' => 'endproduction',
    'env' => 'endenv',
    'once' => 'endonce',
];

$stack = [];
$tokens = [];
$pattern = '/@(if|endif|foreach|endforeach|forelse|endforelse|auth|endauth|guest|endguest|section|endsection|push|endpush|can|endcan|cannot|endcannot|unless|endunless|isset|endisset|empty|endempty|error|enderror|production|endproduction|env|endenv|once|endonce)\b/i';

preg_match_all($pattern, $content, $matches, PREG_OFFSET_CAPTURE);

echo 'Token Count: '.count($matches[0])."\n";

foreach ($matches[0] as $match) {
    $token = strtolower($match[0]);
    $offset = $match[1];
    $line = substr_count(substr($content, 0, $offset), "\n") + 1;

    if (strpos($token, '@end') === 0) {
        $expectedType = substr($token, 4);
        if (empty($stack)) {
            echo "Error: Unexpected $token at line $line\n";
        } else {
            $last = array_pop($stack);
            if ($last['type'] !== $expectedType) {
                // Section might be self-closing @section('title', '...')
                if ($last['type'] === 'section') {
                    // Try to see if current matches something deeper
                    // This is simple so we just report mismatch
                    echo "Mismatch: Found $token at line $line, expected end{$last['type']} from line {$last['line']}\n";
                } else {
                    echo "Mismatch: Found $token at line $line, expected end{$last['type']} from line {$last['line']}\n";
                }
            }
        }
    } else {
        // Opening tag. Check if it's a self-closing section
        $isSelfClosingSection = false;
        if ($token === '@section') {
            // Very basic check: @section('title', 'stuff')
            // Match @section followed by ( and two arguments separated by comma
            $after = substr($content, $offset);
            if (preg_match('/^@section\s*\(\'[^\']+\'\s*,\s*.*?\)/', $after)) {
                $isSelfClosingSection = true;
            }
        }

        $isMarkerEmpty = false;
        if ($token === '@empty') {
            // Check if it's @empty($var) or @empty marker for @forelse
            $after = substr($content, $offset);
            if (! preg_match('/^@empty\s*\(/', $after)) {
                $isMarkerEmpty = true;
            }
        }

        if (! $isSelfClosingSection && ! $isMarkerEmpty) {
            $stack[] = ['type' => substr($token, 1), 'line' => $line];
        }
    }
}

if (! empty($stack)) {
    echo "Unclosed directives:\n";
    foreach ($stack as $s) {
        echo "@{$s['type']} at line {$s['line']}\n";
    }
} else {
    echo "All directives closed correctly (base check).\n";
}
