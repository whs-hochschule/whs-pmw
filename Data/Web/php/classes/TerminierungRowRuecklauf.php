<?php

class TerminierungRowRuecklauf
{
    static int $time = 60;

    /**
     * @var float
     */
    protected float $arbeitsgang;

    /**
     * @var string
     */
    protected string $arbeitsplatz;

    /**
     * @var float
     */
    protected float $einzelzeitProStueck;

    /**
     * @var float
     */
    protected float $ruestzeit;

    /**
     * @var float
     */
    protected float $stueck;

    /**
     * @var float|int
     */
    protected float $bearbeitungszeit;

    /**
     * @var float|int
     */
    protected float $agZeit;

    /**
     * @var float
     */
    protected float $arbeitszwischenzeitStartAG;

    /**
     * @var float
     */
    protected float $arbeitszwischenzeitEndAG;

    /**
     * @var int
     */
    protected int $startPeriodeVorher;

    /**
     * @var float
     */
    protected float $startHoursVorher;

    /**
     * @var float
     */
    protected float $workingHoursPerDay;

    /**
     * @var float
     */
    protected float $arbeitszwischenzeitStartAGVorher = 0;

    /**
     * @var int|null
     */
    protected ?int $beginnPeriode;

    /**
     * @var float|null
     */
    protected ?float $beginnHours;

    /**
     * @var int|null
     */
    protected ?int $endePeriode;

    /**
     * @var float|null
     */
    protected ?float $endeHours;


    /**
     * TerminierungRow constructor.
     * @param float $arbeitsgang
     * @param string $arbeitsplatz
     * @param float $einzelzeitProStueck
     * @param float $ruestzeit
     * @param float $stueck
     * @param float $arbeitszwischenzeitStartAG
     * @param float $arbeitszwischenzeitEndAG
     * @param float $arbeitszwischenzeitStartAGVorher
     * @param int $startPeriodeVorher
     * @param float $startHoursVorher
     * @param int $workingHoursPerDay
     */
    public function __construct(
        float $arbeitsgang,
        string $arbeitsplatz,
        float $einzelzeitProStueck,
        float $ruestzeit,
        float $stueck,
        float $arbeitszwischenzeitStartAG,
        float $arbeitszwischenzeitEndAG,
        float $arbeitszwischenzeitStartAGVorher = 0,
        int $startPeriodeVorher = 6,
        float $startHoursVorher = 8,
        int $workingHoursPerDay = 8
    )
    {
        $this->arbeitsgang = $arbeitsgang;
        $this->arbeitsplatz = $arbeitsplatz;
        $this->einzelzeitProStueck = $einzelzeitProStueck;
        $this->ruestzeit = $ruestzeit;
        $this->stueck = $stueck;
        $this->bearbeitungszeit = $einzelzeitProStueck * $stueck;
        $this->agZeit = $ruestzeit + $this->bearbeitungszeit;
        $this->arbeitszwischenzeitStartAG = $arbeitszwischenzeitStartAG;
        $this->arbeitszwischenzeitEndAG = $arbeitszwischenzeitEndAG;
        $this->arbeitszwischenzeitStartAGVorher = $arbeitszwischenzeitStartAGVorher;
        $this->startPeriodeVorher = $startPeriodeVorher;
        $this->startHoursVorher = $startHoursVorher;
        $this->workingHoursPerDay = $workingHoursPerDay;



        $this->setEndeHours();
        $this->setBeginnHours();
    }

    /**
     * Setze Ende AG
     */
    protected function setEndeHours() : void
    {
        $calc = ($this->startHoursVorher * self::$time - $this->arbeitszwischenzeitEndAG - $this->arbeitszwischenzeitStartAGVorher) / self::$time;

        $periodenAbzug = 0;

        if ($calc < 0) {
            while ($calc < 0) {
                $periodenAbzug++;

                $calc = $calc + $this->workingHoursPerDay;
            }
        }

        $this->endeHours = round($calc, 2);
        $this->endePeriode = $this->startPeriodeVorher - $periodenAbzug;
    }

    /**
     * Setze Beginn AG
     */
    protected function setBeginnHours() : void
    {
        $calc = ($this->endeHours * self::$time - $this->agZeit) / self::$time;

        $periodenAbzug = 0;

        if ($calc < 0) {
            while ($calc < 0) {
                $periodenAbzug++;

                $calc = $calc + $this->workingHoursPerDay;
            }
        }

        $this->beginnPeriode = $this->endePeriode - $periodenAbzug;
        $this->beginnHours = round($calc, 2);
    }

    /**
     * @return float
     */
    public function getArbeitszwischenzeitStartAG() : float
    {
        return $this->arbeitszwischenzeitStartAG;
    }

    /**
     * @return int|null
     */
    public function getBeginnPeriode() : ?int
    {
        return $this->beginnPeriode;
    }

    /**
     * @return float|null
     */
    public function getBeginnHours() : ?float
    {
        return $this->beginnHours;
    }

    /**
     * Klassen-Werte als JSON-String
     *
     * @return string
     */
    public function __toString() :string
    {
        return json_encode([
            '0_arbeitsgang' => $this->arbeitsgang,
            '1_arbeitsplatz' => $this->arbeitsplatz,
            '2_einzelzeitProStueck' => $this->einzelzeitProStueck,
            '3_ruestzeit' => $this->ruestzeit,
            '4_stueck' => $this->stueck,
            '5_bearbeitungszeit' => $this->bearbeitungszeit,
            '6_agZeit' => $this->agZeit,
            '7_arbeitszwischenzeitStartAG' => $this->arbeitszwischenzeitStartAG,
            '8_arbeitszwischenzeitEndAG' => $this->arbeitszwischenzeitEndAG,
            '8_1_arbeitszwischenzeitEndeAgVorher' => $this->arbeitszwischenzeitStartAGVorher,
            '9_beginnPeriode' => $this->beginnPeriode,
            '10_beginnHours' => $this->beginnHours,
            '11_endePeriode' => $this->endePeriode,
            '12_endeHours' => $this->endeHours
        ]);
    }

    /**
     * Gebe Tabellen-Ergebnis zurück
     *
     * @return array
     */
    public function getResult() :array
    {
        return [
            'arbeitsgang' => $this->arbeitsgang,
            'arbeitsplatz' => $this->arbeitsplatz,
            'einzelzeitProStueck' => $this->einzelzeitProStueck,
            'ruestzeit' => $this->ruestzeit,
            'stueck' => $this->stueck,
            'bearbeitungszeit' => $this->bearbeitungszeit,
            'agZeit' => $this->agZeit,
            'arbeitszwischenzeitStartAG' => $this->arbeitszwischenzeitStartAG,
            'arbeitszwischenzeitEndAG' => $this->arbeitszwischenzeitEndAG,
            'beginnPeriode' => $this->beginnPeriode,
            'beginnHours' => $this->beginnHours,
            'endePeriode' => $this->endePeriode,
            'endeHours' => $this->endeHours
        ];
    }
}