<?php
$file = 'resources/views/layouts/app.blade.php';
$content = file_get_contents($file);

// Strip comments
$content = preg_replace('/\{\{--.*?--\}\}/s', '', $content);

// Count directives
$startDirectives = ['if', 'foreach', 'forelse', 'auth', 'guest', 'can', 'cannot', 'error', 'switch', 'while', 'for', 'unless', 'isset', 'empty', 'production', 'env', 'once', 'component', 'slot', 'push', 'prepend'];
$endDirectives = ['endif', 'endforeach', 'endforelse', 'endauth', 'endguest', 'endcan', 'endcannot', 'enderror', 'endswitch', 'endwhile', 'endfor', 'endunless', 'endisset', 'endempty', 'endproduction', 'endenv', 'endonce', 'endcomponent', 'endslot', 'endpush', 'endprepend'];

echo "Checking $file...\n";

foreach ($startDirectives as $i => $start) {
    // Special handling for section (can be inline)
    $end = $endDirectives[$i];
    
    preg_match_all("/@$start\b/i", $content, $starts);
    preg_match_all("/@$end\b/i", $content, $ends);
    
    $countStart = count($starts[0]);
    $countEnd = count($ends[0]);
    
    if ($countStart !== $countEnd) {
        echo "MISMATCH: @$start ($countStart) != @$end ($countEnd)\n";
    }
}

// Check section specifically
// Count @section not self closing? Hard.
// Count total @section vs @endsection
preg_match_all("/@section\b/i", $content, $sections);
preg_match_all("/@endsection\b/i", $content, $endsections);
echo "@section: " . count($sections[0]) . ", @endsection: " . count($endsections[0]) . "\n";

