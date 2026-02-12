<?php

$files = glob('storage/framework/views/*.php');
foreach ($files as $file) {
    echo "Checking $file...\n";
    $content = file_get_contents($file);

    // Count if(...): and endif;
    // Note: Blade compiles @if to if(...): or if(...) {
    // But since it's "expecting endif", it means it used the if(...): syntax.

    $ifs = preg_match_all('/if\s*\(.*?\)\s*:/s', $content);
    $endifs = preg_match_all('/endif\s*;/', $content);

    if ($ifs !== $endifs) {
        echo "  MISMATCH in $file: if: $ifs, endif: $endifs\n";
    }
}
