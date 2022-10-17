<?php

class TerminierungRow
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
     * @var float
     */
    protected float $arbeitszwischenzeitEndeAgVorher = 1;

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
     * @var float
     */
    protected float $endeHoursVorher = 0;

    /**
     * @var int
     */
    protected int $workingHoursPerDay = 8;

    /**
     * TerminierungRow constructor.
     * @param float $arbeitsgang
     * @param string $arbeitsplatz
     * @param float $einzelzeitProStueck
     * @param float $ruestzeit
     * @param float $stueck
     * @param float $arbeitszwischenzeitStartAG
     * @param float $arbeitszwischenzeitEndAG
     * @param int|null $beginnPeriode
     * @param float $arbeitszwischenzeitEndeAgVorher
     */
    public function __construct(float $arbeitsgang, string $arbeitsplatz, float $einzelzeitProStueck, float $ruestzeit, float $stueck, float $arbeitszwischenzeitStartAG, float $arbeitszwischenzeitEndAG, int $beginnPeriode = 1, float $arbeitszwischenzeitEndeAgVorher = 1, float $endeHoursVorher = 0, int $workingHoursPerDay = 8)
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
        $this->beginnPeriode = $beginnPeriode;
        $this->arbeitszwischenzeitEndeAgVorher = $arbeitszwischenzeitEndeAgVorher;
        $this->endeHoursVorher = $endeHoursVorher;
        $this->workingHoursPerDay = $workingHoursPerDay;

        $this->setBeginnHours();
        $this->setEndeHours();
    }

    /**
     * Setze Beginn AG
     */
    protected function setBeginnHours() : void
    {
        $calc = (($this->endeHoursVorher * self::$time) + $this->arbeitszwischenzeitStartAG + $this->arbeitszwischenzeitEndeAgVorher) / self::$time;

        $temp = false;

        if ( $calc > 9 && $calc < 11) {
            $temp = true;
        }

        $i = 0;

        if ($calc > $this->workingHoursPerDay) {
            while ($calc > $this->workingHoursPerDay) {
                $i++;

                $calc = $calc - $this->workingHoursPerDay;
            }
        }
        $this->beginnPeriode = $this->beginnPeriode + $i;
        $this->beginnHours = round($calc, 2);
    }

    /**
     * Setze Ende AG
     */
    protected function setEndeHours() : void
    {
        $calc = round(($this->beginnHours * self::$time + $this->agZeit) / self::$time, 2);

        $i = 0;

        if ($calc > $this->workingHoursPerDay) {
            while ($calc > $this->workingHoursPerDay) {
                $i++;

                $calc = $calc - $this->workingHoursPerDay;
            }
        }

        $this->endeHours = round($calc, 2);
        $this->endePeriode = $this->beginnPeriode + $i;
    }

    /**
     * @return float
     */
    public function getArbeitszwischenzeitEndAG() : float
    {
        return $this->arbeitszwischenzeitEndAG;
    }

    /**
     * @return int|null
     */
    public function getEndePeriode() : ?int
    {
        return $this->endePeriode;
    }

    /**
     * @return float|null
     */
    public function getEndeHours() : ?float
    {
        return $this->endeHours;
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
            '8_1_arbeitszwischenzeitEndeAgVorher' => $this->arbeitszwischenzeitEndeAgVorher,
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