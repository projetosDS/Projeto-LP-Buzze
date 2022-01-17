<?php
set_time_limit(0);

if (empty($_GET['url']) || preg_match('#^(http|https)://[a-z0-9]#i', $_GET['url']) === 0) {
    echo 'URL inválida';
    exit;
}

$url = $_GET['url'];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HEADER, false);

//Envia o user agente do navegador atual
curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

//Pega os dados
$data = curl_exec($ch);

//Fecha o curl
curl_close($ch);

$ch = NULL;

$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if($data === false) {
    http_response_code(404);
    echo 'Curl error: ' . curl_error($ch);
} elseif ($httpcode !== 200) {
    http_response_code($httpcode);
} else {
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    header('Content-Type: ' . $finfo->buffer($data));
    echo $data;
}