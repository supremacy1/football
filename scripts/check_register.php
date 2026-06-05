<?php
$opts = stream_context_create(['http' => ['method' => 'GET', 'timeout' => 10]]);
$res = @file_get_contents('http://127.0.0.1:8000/register', false, $opts);
echo 'OK:' . ($res !== false ? '1' : '0') . PHP_EOL;
if (isset($http_response_header)) {
    foreach ($http_response_header as $line) {
        echo $line . PHP_EOL;
    }
}
if ($res !== false) {
    echo 'BODY_LEN:' . strlen($res) . PHP_EOL;
}
