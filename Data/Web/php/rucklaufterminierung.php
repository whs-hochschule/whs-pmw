<?php
require 'classes/TerminierungRowRuecklauf.php';

//header("Content-Type: application/json");
$v = json_decode(stripslashes(file_get_contents("php://input")), JSON_OBJECT_AS_ARRAY);

$startPeriode = $v['periodeEnde'];
$startHours = 8;
$workingHoursPerDay = $v['hoursPerDay']; // Arbeitszeit am Tag
$formContent = array_reverse($v['formContent']);

$output = [];
$terminRow = null;
$i = 0;
$arbeitszwischenzeitEndeAgDanach = 0;

foreach ($formContent as $item) {
    $terminRow = new TerminierungRowRuecklauf(
        $item['arbeitsgang'],
        $item['arbeitsplatz'],
        $item['einzelzeitProStueck'],
        $item['ruestzeit'],
        $item['stueck'],
        $item['startAg'],
        $item['endeAg'],
        $arbeitszwischenzeitEndeAgDanach,
        $startPeriode,
        $startHours,
        $workingHoursPerDay
    );

    $output[$i] = $terminRow->getResult();

    $startPeriode = $terminRow->getBeginnPeriode();
    $arbeitszwischenzeitEndeAgDanach = $terminRow->getArbeitszwischenzeitStartAG();
    $startHours = $terminRow->getBeginnHours();

    $i++;
}

$beginnZeit = round(($terminRow->getBeginnHours() * 60 - $terminRow->getArbeitszwischenzeitStartAG()) / 60, 2);

echo json_encode([
    'table' => array_reverse($output),
    'result' => [
        'periode' => $terminRow->getBeginnPeriode(),
        'endZeit' => $beginnZeit
    ]
]);
