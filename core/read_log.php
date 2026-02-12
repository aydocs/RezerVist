<?php
$file = 'storage/logs/laravel.log';
if (!file_exists($file)) {
    echo "Log file not found.";
    exit;
}
$fp = fopen($file, 'r');
if (!$fp) {
    echo "Cannot open file.";
    exit;
}
$size = filesize($file);
$offset = max(0, $size - 10000); // Read last 10KB
fseek($fp, $offset);
echo fread($fp, 10000);
fclose($fp);
?>
