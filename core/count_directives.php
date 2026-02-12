<?php

$file = 'resources/views/layouts/app.blade.php';
$content = file_get_contents($file);

// Strip comments properly
$content = preg_replace('/\{\{--.*?--\}\}/s', '', $content);

$directives = [
    'if', 'foreach', 'forelse', 'auth', 'guest', 'can', 'cannot', 'unless', 'isset', 'empty', 'error', 'production', 'env', 'once', 'section', 'push',
];

foreach ($directives as $d) {
    $starts = preg_match_all('/@'.$d.'\b/i', $content);
    $ends = preg_match_all('/@end'.$d.'\b/i', $content);

    // Check for self-closing sections
    if ($d === 'section') {
        preg_match_all('/@section\s*\(\'[^\']+\'\s*,\s*[^\)]+\)/', $content, $selfClosing);
        $starts -= count($selfClosing[0]);
    }

    echo "$d: $starts starts, $ends ends\n";
}
