<?php
/**
 * This file is part of the GeoAdapter software.
 * (c) 2011 Francesco Trucchia <francesco@trucchia.it>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Geo;

/**
 * Location is the model for a geo point
 *
 * @package    geoadapter
 * @subpackage search
 * @author     Francesco Trucchia <francesco@trucchia.it>
 */
class Location
{
  private $latitude;
  private $longitude;
  private $address;
  private $zipCode;
  private $locality;
  private $street;
  private $province;

  /**
   * Set latitude
   * 
   * @param float $v
   */
  public function setLatitude($v)
  {
    $this->latitude = $v;
  }

  /**
   * Get latitude
   * 
   * @return float
   */
  public function getLatitude()
  {
    return $this->latitude;
  }

  /**
   * Set longitude
   *
   * @param float $v
   */
  public function setLongitude($v)
  {
    $this->longitude = $v;
  }

  /**
   * Get longitude
   *
   * @return float
   */
  public function getLongitude()
  {
    return $this->longitude;
  }

  /**
   * Set address
   *
   * @param string $address
   */
  public function setAddress($address)
  {
    $this->address = $address;
  }

  /**
   * Get Address
   *
   * @return string
   */
  public function getAddress()
  {
    return $this->address;
  }

  /**
   * Set street
   *
   * @param string $street
   */
  public function setStreet($street)
  {
    $this->street = $street;
  }

  /**
   * Get Street
   *
   * @return string
   */
  public function getStreet()
  {
    return $this->street;
  }

  /**
   * Set locality
   *
   * @param string $locality
   */
  public function setLocality($locality)
  {
    $this->locality = $locality;
  }

  /**
   * Get Locality
   *
   * @return string
   */
  public function getLocality()
  {
    return $this->locality;
  }

  /**
   * Set province
   *
   * @param string $province
   */
  public function setProvince($province)
  {
    $this->province = $province;
  }

  /**
   * Get Province
   *
   * @return string
   */
  public function getProvince()
  {
    return $this->province;
  }

  /**
   * Set zip code
   *
   * @param string $zipCode
   */
  public function setZipCode($zipCode)
  {
    $this->zipCode = $zipCode;
  }

  /**
   * Get zip code
   *
   * @return string
   */
  public function getZipCode()
  {
    return $this->zipCode;
  }

  /**
   * Measure the distance between this point and another point
   * 
   * @param Location $location
   * @return float
   */
  public function distance(Location $location)
  {
    $latA = deg2rad($this->latitude);
    $lonA = deg2rad($this->longitude);
    $latB = deg2rad($location->getLatitude());
    $lonB = deg2rad($location->getLongitude());

    return sprintf('%.2f', acos(sin($latA)*sin($latB) + cos($latA) * cos($latB) * cos($lonB - $lonA)) * 6378.1370);
  }
}