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
 * Search is the main class that expones interfaces to search through the registered services
 *
 * @package    geoadapter
 * @subpackage search
 * @author     Francesco Trucchia <francesco@trucchia.it>
 */
class Search
{
  /**
   * @var ArrayObject
   */
  private $services;

  /**
   * @var array
   */
  private $results = array();

  /**
   * Use this method to register available services
   */
  protected function configure() {}

  /**
   * Construct method can inject available services
   *
   * @param array $services
   */
  public function __construct($services = array())
  {
    $this->services = new \ArrayObject($services);
    $this->configure();
  }

  /**
   * Append a new service between the registered services
   * 
   * @param Service $service
   */
  public function addService(Service $service)
  {
    $this->services->append($service);
  }

  /**
   * Set results
   * 
   * @param array $results
   */
  public function setResults($results)
  {
    $this->results = $results;
  }

  /**
   * Get results
   *
   * @return array
   */
  public function getResults()
  {
    return $this->results;
  }

  /**
   * Query the chain of geo searching services
   * 
   * @param string $q
   * @param int $service_index
   * @param Exception $e
   */
  protected function call($method, $q, $service_index, $e)
  {    
    if (!isset($this->services[$service_index]))
    {
      throw $e !== null?$e:new Exception\InvalidService('Service is not set');;
    }

    try
    {
      $this->services[$service_index]->search($q);
      $this->results = $this->services[$service_index]->getResults();
    }
    catch(\Exception $e)
    {
      $this->$method($q, ++$service_index, $e);
    }

  }
  
  /**
   * Query for geo code an address. You usually will use only the first argument.
   * @param string $q the address query you want to geocode e.g.: "via montenapoleone, milano, italy"
   * @param int $service_index
   * @param Exception $e 
   */
  public function query($q, $service_index = 0, $e = null)
  {
      $this->call('query', $q, $service_index, $e);
  }

  /**
   * Get a specific result
   *
   * @param integer $index
   * @return Geo\Location
   */
  public function getResult($index)
  {
    if (!isset($this->results[$index]))
    {
      return;
    }
    
    return $this->results[$index];
  }

  /**
   * Get the first result
   *
   * @return Geo\Location
   */
  public function getFirst()
  {
    return !isset($this->results[0])?:$this->results[0];
  }
}

