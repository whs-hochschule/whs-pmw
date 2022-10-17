<?php
require 'classes/TerminierungRow.php';

header("Content-Type: application/json");
$v = json_decode(stripslashes(file_get_contents("php://input")), JSON_OBJECT_AS_ARRAY);

$output = [];
$periode = 1;
$arbeitszwischenzeitEndeAgVorher = 0;
$endeHours = 0;
$terminRow = null;

$workingHoursPerDay = $v['hoursPerDay'];
$formContent = $v['formContent'];

$i = 0;

foreach ($formContent as $item) {
    $terminRow = new TerminierungRow(
        $item['arbeitsgang'],
        $item['arbeitsplatz'],
        $item['einzelzeitProStueck'],
        $item['ruestzeit'],
        $item['stueck'],
        $item['startAg'],
        $item['endeAg'],
        $periode,
        $arbeitszwischenzeitEndeAgVorher,
        $endeHours,
        $workingHoursPerDay
    );

    $output[$i] = $terminRow->getResult();

    $periode = $terminRow->getEndePeriode();
    $arbeitszwischenzeitEndeAgVorher = $terminRow->getArbeitszwischenzeitEndAG();
    $endeHours = $terminRow->getEndeHours();

    $i++;
}

$endZeit = round(($terminRow->getEndeHours() * 60 + $terminRow->getArbeitszwischenzeitEndAG()) / 60, 2);

echo json_encode([
    'table' => $output,
    'result' => [
        'periode' => $terminRow->getEndePeriode(),
        'endZeit' => $endZeit
    ]
]);
