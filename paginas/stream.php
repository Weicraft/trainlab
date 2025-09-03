<?php
$id_curso = $_GET['id_curso'] ?? 1;
$id_content = $_GET['id_content'] ?? 1;
$video_dir = 'paginas/videos/';
$video_file = $video_dir . "curso" . $id_curso . "_contenido" . $id_content . ".mp4";

if (!file_exists($video_file)) {
    header("HTTP/1.0 404 Not Found");
    exit;
}

$fp = @fopen($video_file, 'rb');
$size = filesize($video_file);
$length = $size;
$start = 0;
$end = $size - 1;

header("Content-Type: video/mp4");
header("Accept-Ranges: bytes");

if (isset($_SERVER['HTTP_RANGE'])) {
    $c_start = $start;
    $c_end = $end;

    list(, $range) = explode('=', $_SERVER['HTTP_RANGE'], 2);
    if (strpos($range, ',') !== false) {
        header("HTTP/1.1 416 Requested Range Not Satisfiable");
        exit;
    }

    if ($range == '-') {
        $c_start = $size - substr($range, 1);
    } else {
        $range = explode('-', $range);
        $c_start = $range[0];
        $c_end = isset($range[1]) && is_numeric($range[1]) ? $range[1] : $size;
    }

    $c_end = ($c_end > $end) ? $end : $c_end;
    if ($c_start > $c_end || $c_start > $size - 1 || $c_end >= $size) {
        header("HTTP/1.1 416 Requested Range Not Satisfiable");
        exit;
    }

    $start = $c_start;
    $end = $c_end;
    $length = $end - $start + 1;
    fseek($fp, $start);

    header("HTTP/1.1 206 Partial Content");
}

header("Content-Length: " . $length);
header("Content-Range: bytes $start-$end/$size");

$buffer = 1024 * 8;
while (!feof($fp) && ($p = ftell($fp)) <= $end) {
    if ($p + $buffer > $end) {
        $buffer = $end - $p + 1;
    }
    set_time_limit(0);
    echo fread($fp, $buffer);
    flush();
}
fclose($fp);
exit;
