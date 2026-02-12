<?php
$file = 'storage/framework/views/bfb531cf66bff1cd67a15c7c46e30de1.php';
$content = file_get_contents($file);

// Regex patterns for PHP alternative syntax control structures
$patterns = [
    // Openings: keyword(...):
    // Note: We match the keyword and look for a colon at the end of the statement
    // But regex for balanced parentheses is hard.
    // Blade generates: <?php if(...): ?>
    // So we can assume they are well formed in that regard.
    
    // Match "if (...):", "foreach (...):", "for (...):", "while (...):", "switch (...):"
    'opening' => '/(?<!\w)(if|foreach|for|while|switch)\s*\(.*?\)\s*:/s',
    
    // Match "else:", "try:" (try doesn't use colon usually in alt syntax but let's be safe), "finally:"
    'intermediate_simple' => '/(?<!\w)(else)\s*:/',
    
    // Match "elseif (...):", "catch (...):"
    'intermediate_complex' => '/(?<!\w)(elseif)\s*\(.*?\)\s*:/s',
    
    // Match "endif;", "endforeach;", etc.
    'closing' => '/(?<!\w)(endif|endforeach|endfor|endwhile|endswitch)\s*;/'
];

// Combine all into one regex with capturing groups?
// Or just match all colon/semicolon ending statements inside php tags.

// Let's iterate all matches of keywords
$allPattern = '/(?<!\w)(if|foreach|for|while|switch|elseif|else|endif|endforeach|endfor|endwhile|endswitch)\b.*?(?::|;)/s';

// This is still fragile.
// Better approaches:
// Blade always wraps these in <?php ... ?> 
// So search for `<?php if(...): ?>` exactly? No, whitespace varies.

// Let's use the simplest reliable indicator: The keywords followed by colon or semicolon.
// PHP Parser would be best but simple regex:

preg_match_all('/(?<!\w)(?:(if|foreach|for|while|switch)\s*\(.*?\)\s*:|(else)\s*:|(elseif)\s*\(.*?\)\s*:|(endif|endforeach|endfor|endwhile|endswitch)\s*;)/s', $content, $matches, PREG_OFFSET_CAPTURE);

// $matches[0] contains full strings
// $matches[1] -> opening (if, foreach...)
// $matches[2] -> else
// $matches[3] -> elseif
// $matches[4] -> closing (endif, endforeach...)

$stack = [];

foreach ($matches[0] as $i => $fullMatch) {
    $matchStr = $fullMatch[0];
    $offset = $fullMatch[1];
    $line = substr_count(substr($content, 0, $offset), "\n") + 1;
    
    $token = '';
    $category = '';
    
    if (!empty($matches[1][$i][0])) {
        $token = $matches[1][$i][0];
        $category = 'opening';
    } elseif (!empty($matches[2][$i][0])) {
        $token = $matches[2][$i][0];
        $category = 'else';
    } elseif (!empty($matches[3][$i][0])) {
        $token = $matches[3][$i][0];
        $category = 'elseif';
    } elseif (!empty($matches[4][$i][0])) {
        $token = $matches[4][$i][0];
        $category = 'closing';
    }
    
    if ($category === 'opening') {
        $stack[] = ['token' => $token, 'line' => $line];
    } elseif ($category === 'closing') {
        if (empty($stack)) {
            echo "ERROR: Unexpected $token at line $line (Stack underflow)\n";
            continue;
        }
        $last = array_pop($stack);
        $expected = 'end' . $last['token'];
        
        if ($token !== $expected) {
            echo "ERROR: Mismatch at line $line. Found $token, expected $expected (opened at {$last['line']})\n";
        }
    } elseif ($category === 'else' || $category === 'elseif') {
        if (empty($stack)) {
            echo "ERROR: Unexpected $token at line $line (Stack underflow)\n";
        } else {
             // Peek
             $last = end($stack);
             // Verify parent is valid
             // e.g. else can belong to if, foreach(no), switch(no)
             // else belongs to if only (in alt syntax? foreach else exists in Blade but compiles to if/empty logic usually)
             // In pure PHP: if/elseif/else. foreach/else does not exist natively in PHP alt syntax (Blade handles loop->else specially)
             
             if ($last['token'] !== 'if' && $last['token'] !== 'elseif') {
                  // Actually elseif is not on stack. Just 'if'.
                  if ($last['token'] !== 'if') {
                       // Blade @can compiles to <?php if ... ?> so it is an 'if'.
                       echo "ERROR: Unexpected $token at line $line. Parent is {$last['token']}\n";
                  }
             }
        }
    }
}

if (!empty($stack)) {
    echo "Unclosed structures at EOF:\n";
    foreach ($stack as $s) {
        echo "{$s['token']} at line {$s['line']}\n";
    }
} else {
    echo "Structure seems clear.\n";
}
