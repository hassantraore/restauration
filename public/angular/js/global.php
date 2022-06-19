<?php

// Cache ----------------------------------------------------------------------
header('Content-type: text/javascript');
header('Cache-Control: max-age=2592000');

include 'global/client.php';

$js = file_get_contents('app.js');

foreach ($directories as $file) {
    $length = strlen($file);
    $extension = substr($file, $length - 2, 2);

    if ($extension == 'js') {
        $js .= file_get_contents($file)."\n";
    }
}

echo $js;
