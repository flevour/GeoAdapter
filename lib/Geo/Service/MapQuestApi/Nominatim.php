<?php
/**
 * This file is part of the GeoAdapter software.
 * (c) 2011 Francesco Trucchia <francesco@trucchia.it>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Geo\Service\MapQuestApi;

use Geo\Service;
use Geo\Exception\NotImplemented;

/**
 * Nominatim service wrap the Nominatim OpenStreetMap Service
 *
 * @package    geoadapter
 * @subpackage service
 * @author     Francesco Trucchia <francesco@trucchia.it>
 */
class Nominatim extends Service
{
  protected function initLocation($values)
  {
    $location = new \Geo\Location;
    !isset($values['lat'])?:$location->setLatitude($values['lat']);
    !isset($values['lon'])?:$location->setLongitude($values['lon']);
    !isset($values['display_name'])?:$location->setAddress($values['display_name']);
    !isset($values['address']['pedestrian'])?:$location->setStreet($values['address']['pedestrian']);
    !isset($values['address']['postcode'])?:$location->setZipCode($values['address']['postcode']);
    !isset($values['address']['city'])?:$location->setLocality($values['address']['city']);
    !isset($values['address']['county'])?:$location->setProvince($values['address']['county']);

    return $location;
  }
  
  protected function query($q)
  {
    $baseUrl = $this->baseUrl . '/search?format=json';
    $data = $this->client->get(sprintf("%s&q=%s&countrycodes=%s&addressdetails=1", $baseUrl, rawurlencode($q), $this->region));
    return json_decode($data, true);
  }
  
  protected function reverse($lat, $lng)
  {
        $baseUrl = $this->baseUrl . '/reverse?format=json';
        $data = $this->client->get(sprintf("%s&lat=%s&lon=%s&countrycodes=%s&addressdetails=1", $baseUrl, $lat, $lng, $this->region));
        return array(json_decode($data, true));
  }
}