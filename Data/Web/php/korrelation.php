<?php
header("Content-Type: application/json");

$v = json_decode(stripslashes(file_get_contents("php://input")), JSON_OBJECT_AS_ARRAY);

$nachfrage = $v['nachfrage'];
$periode = $v['periode'];

//korrelation([1,2,3,4,5,6,7], [400,420,450,450,460,490,490]);

function korrelation($array1, $array2)
{
    return shell_exec('python3 ../python/korrelation.py -p ' . implode(',', $array1) . ' -n ' . implode(',', $array2));
}

echo korrelation($periode, $nachfrage);