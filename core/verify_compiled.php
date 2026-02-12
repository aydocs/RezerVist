<?php
$file = 'storage/framework/views/bfb531cf66bff1cd67a15c7c46e30de1.php';
$content = file_get_contents($file);

// Match Openings: <?php if(...):, <?php foreach(...):, etc.
// Match Closings: <?php endif;, <?php endforeach;, etc.

// Pattern to catch all block directives in Blade compiled output.
// They generally follow: <?php keyword(...): ?> or <?php endkeyword; ?>
// Note: "switch" is slightly different but follows pattern. "empty" is part of forelse logic.

// We capture the KEYWORD to verify matching (if -> endif)

$pattern = '/<\?php\s+(if|foreach|for|while|switch)\s*\(.*?\)\s*:\s*\?>|<\?php\s+(endif|endforeach|endfor|endwhile|endswitch)\s*;\s*\?>/is';

// Actually, Blade output might be: <?php if(...): ?> (with space) or not.
// Let's use a simpler regex that catches the keyword and the colon/semicolon.
// Open:  /<\?php\s+(if|foreach|for|while|switch)\b[^:]*:\s*\?>/
// Close: /<\?php\s+(endif|endforeach|endfor|endwhile|endswitch)\b[^;]*;\s*\?>/

preg_match_all('/<\?php\s+(if|foreach|for|while|switch)\b.*?:|(?<!\w)(endif|endforeach|endfor|endwhile|endswitch)\s*;/', $content, $matches, PREG_OFFSET_CAPTURE);

$stack = [];

foreach ($matches[0] as $i => $fullMatch) {
    if (empty($matches[1][$i])) {
        // This is a closing tag (Group 2)
        $token = $matches[2][$i];
        $offset = $matches[0][$i][1]; // Correction: PREG_OFFSET_CAPTURE structure
        // $matches is array of arrays. $matches[0] is array of [string, offset].
        // Actually $matches[1] is array of [string, offset] or ["" , -1]
    }
}

// Re-do correctly with simple parsing
preg_match_all('/<\?php\s+(if|foreach|for|while|switch)\b.*?:|(?<!\w)(endif|endforeach|endfor|endwhile|endswitch)\s*;/', $content, $allMatches, PREG_OFFSET_CAPTURE);

foreach ($allMatches[0] as $index => $matchInfo) {
    $fullString = $matchInfo[0];
    $offset = $matchInfo[1];
    $line = substr_count(substr($content, 0, $offset), "\n") + 1;
    
    // Determine type
    $type = 'unknown';
    $isOpening = false;
    
    // Check capturing groups
    if (!empty($allMatches[1][$index][0])) {
        $type = $allMatches[1][$index][0];
        $isOpening = true;
    } elseif (!empty($allMatches[2][$index][0])) {
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
        $expected = 'end' . $last['type'];
        
        // Handle special cases if any? No, direct mapping usually.
        
        if ($type !== $expected) {
            echo "ERROR: Mismatch at line $line. Found $type, expected $expected (opened at {$last['line']})\n";
        }
    }
}

if (!empty($stack)) {
    echo "UNCLOSED BLOCKS:\n";
    foreach ($stack as $s) {
        echo "{$s['type']} at line {$s['line']} ({$s['content']})\n";
    }
} else {
    echo "All blocks balanced.\n";
}
