<?php

header('Content-type: text/javascript');
header('Cache-Control: max-age=2592000');

$dir = [
    'angular.min.js',
    'angular-sanitize.min.js',
    'bindNotifier.js',
];

$js = '';

foreach ($dir as $file) {
    $length = strlen($file);
    $extension = substr($file, $length - 2, 2);

    $js .= file_get_contents($file)."\n";
}

echo $js;
