<?php

$file = 'storage/framework/views/bfb531cf66bff1cd67a15c7c46e30de1.php';
$content = file_get_contents($file);

preg_match_all('~<\?php\s+(if|foreach|for|while|switch)\b.*?:|(?<!\w)(endif|endforeach|endfor|endwhile|endswitch)\s*;~s', $content, $allMatches, PREG_OFFSET_CAPTURE);

$stack = [];

foreach ($allMatches[0] as $index => $matchInfo) {
    $fullString = $matchInfo[0];
    $offset = $matchInfo[1];
    $line = substr_count(substr($content, 0, $offset), "\n") + 1;

    $type = 'unknown';
    $isOpening = false;

    if (! empty($allMatches[1][$index][0])) {
        $type = $allMatches[1][$index][0];
        $isOpening = true;
    } elseif (! empty($allMatches[2][$index][0])) {
        $type = $allMatches[2][$index][0];
        $isOpening = false;
    }

    if ($isOpening) {
        $stack[] = ['type' => $type, 'line' => $line, 'content' => substr($fullString, 0, 50)];
    } else {
        if (empty($stack)) {
            echo "ERROR: Unexpected $type at line $line (Stack empty)\n";

            continue;
        }

        $last = array_pop($stack);
        $expected = 'end'.$last['type'];

        if ($type !== $expected) {
            echo "ERROR: Mismatch at line $line. Found $type, expected $expected (opened at {$last['line']})\n";
        }
    }
}

if (! empty($stack)) {
    echo "UNCLOSED BLOCKS:\n";
    foreach ($stack as $s) {
        echo "{$s['type']} at line {$s['line']} ({$s['content']})\n";
    }
} else {
    echo "All blocks balanced.\n";
}
