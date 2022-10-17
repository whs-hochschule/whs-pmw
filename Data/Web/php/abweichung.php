<?php
header("Content-Type: application/json");

$v = json_decode(stripslashes(file_get_contents("php://input")), JSON_OBJECT_AS_ARRAY);

function abweichung($array)
{
    $array = array_filter($array, function ($v) {
        return $v !== null;
    });

    $num = count($array);
    $avg = array_sum($array) / $num;
    $abw = 0;

    foreach ($array as $elem) {
        $abw += ($elem - $avg) * ($elem - $avg);
    }

    return sqrt((1 / ($num - 1)) * $abw);
}

$x = abweichung($v['x']);
$y = abweichung($v['y']);

echo json_encode([0 => $x, 1 => $y]);