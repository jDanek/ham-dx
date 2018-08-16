<?php declare(strict_types=1);

namespace Toolia\HamDx;

/**
 * Class Locator
 *
 * @author Jirka Daněk <jdanek.eu>
 */
class Locator
{
    const PATTERN = "[a-rA-R]{2}([0-9]{2}([a-xA-X]{2}([0-9]{2}([a-xA-X]{2})?)?)?)?";

    /** @var Convertor */
    protected $convertor;

    /** @var string */
    protected $qth = "";
    /** @var array */
    protected $coords = [];


    protected function __construct(Convertor $convertor)
    {
        $this->convertor = $convertor;
    }

    /**
     * Validation of the QTH locator format
     *
     * @param string $qth
     * @return bool
     */
    static function validation(string $qth): bool
    {
        $qth = strtoupper($qth);
        return preg_match("{" . self::PATTERN . "+$}AD", $qth) === 1;
    }

    /**
     * Create locator instance from lat/lon coordinate
     *
     * @param float $lat
     * @param float $lon
     * @return Locator
     */
    static function fromCoords(float $lat, float $lon): Locator
    {

        $lat = Convertor::roundUp($lat, Convertor::ROUND_PRECISION);
        $lon = Convertor::roundUp($lon, Convertor::ROUND_PRECISION);

        if ($lat < -90.0 || $lat > 90.0) {
            throw new \InvalidArgumentException("The value of variable '\$lat' must be in the range from -90.0 to 90.0");
        }

        if ($lon < -180.0 || $lon > 180.0) {
            throw new \InvalidArgumentException("The value of variable '\$lon' must be in the range from -180.0 to 180.0");
        }

        $instance = new static(new Convertor());
        $instance->setCoords($lat, $lon);
        $instance->setQth($instance->convertor->coordsToQth($lat, $lon));

        return $instance;
    }

    /**
     * Create locator instance from QTH Maidenhead Grid code
     *
     * @param string $qth
     * @return Locator
     */
    static function fromQth(string $qth): Locator
    {
        $qth = strtoupper($qth);

        // format validation
        if (!static::validation($qth)) {
            throw new \InvalidArgumentException("QTH Locator '" . $qth . "' don't have the required format 'AA12', 'AA12CD', 'AA12CD34' or 'AB12CD34XX'.");
        }

        // create Locator
        $instance = new static(new Convertor());
        $instance->setQth($qth);
        $instance->setCoordsArr($instance->convertor->qthToCoords($qth));

        return $instance;
    }

    /**
     * @return Convertor
     */
    function getConvertor(): Convertor
    {
        return $this->convertor;
    }

    /**
     * @return bool
     */
    function isValid(): bool
    {
        return static::validation($this->qth);
    }

    /**
     * @return string
     */
    function getQth(): string
    {
        return $this->qth;
    }

    /**
     * @param string $qth
     */
    function setQth(string $qth): void
    {
        $this->qth = $qth;
    }

    /**
     * @return array
     */
    function getCoords(): array
    {
        return $this->coords;
    }

    /**
     * @return float
     */
    function getLatitude(): float
    {
        return (float)$this->coords[0];
    }

    /**
     * @return float
     */
    function getLongitude(): float
    {
        return (float)$this->coords[1];
    }

    /**
     * @param float $lat
     * @param float $lon
     */
    function setCoords(float $lat, float $lon): void
    {
        $this->coords = [$lat, $lon];
    }

    /**
     * @param array $coords
     */
    function setCoordsArr(array $coords): void
    {
        if (count($coords) === 2) {
            $this->setCoords((float)$coords[0], (float)$coords[1]);
        } else {
            throw new \InvalidArgumentException(__METHOD__ . " expects an array with 2 parameters");
        }
    }

    /**
     * ======================================
     *  Comparator
     * ======================================
     */

    /**
     * @param Locator $locator
     * @return float
     */
    function distanceTo(Locator $locator): float
    {
        $comparator = new Comparator();
        return $comparator->between(
            $this->getLatitude(),
            $this->getLongitude(),
            $locator->getLatitude(),
            $locator->getLongitude()
        );
    }

    /**
     * @param Locator $locator2
     * @return float
     */
    function azimuth(Locator $locator2): float
    {
        $comparator = new Comparator();
        return $comparator->azimut(
            $this->getLatitude(),
            $this->getLongitude(),
            $locator2->getLatitude(),
            $locator2->getLongitude()
        );
    }

    /**
     * ======================================
     *  Convertor
     * ======================================
     */

    /**
     * Returns degrees format (degrees, minutes, seconds, direction)
     *
     * @return array
     */
    function getDms(): array
    {
        return $this->convertor->coordsToDms($this->getLatitude(), $this->getLongitude());
    }


}
