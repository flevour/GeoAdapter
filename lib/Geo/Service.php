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
 * Service is the adapter for the external geo searching services
 *
 * @package    geoadapter
 * @subpackage service
 * @author     Francesco Trucchia <francesco@trucchia.it>
 */
abstract class Service
{
  private $results;

  protected $region;

  protected $language;
  
  protected $client;

  /**
   * @param string
   * @return array
   */
  abstract protected function query($q);
  
  /**
   * @param string
   * @return array
   */
  abstract protected function reverse($lat, $lng);

  abstract protected function initLocation($values);
  
  private function hydrate($results)
  {
    foreach($results as $result)
    {
      $this->addResult($this->initLocation($result));
    }
  }

  private function addResult(\Geo\Location $location)
  {
    $this->results->append($location);
  }

  public function __construct(HttpClient $client)
  {
    $this->results = new \ArrayObject();
    $this->client = $client;
  }

  public function getResults()
  {
    return $this->results;
  }

  public function setRegion($region)
  {
    $this->region = $region;
  }

  public function setLanguage($language)
  {
    $this->language = $language;
  }
  
  public function search($method, $args)
  {
    $args = func_get_args();
    $method = array_shift($args);
    
    $this->results->exchangeArray(array());
    
    $results = call_user_func_array(array($this, $method), $args);
    
    if (!is_array($results))
    {
      throw new \Exception('Query method need to return an array');
    }
    
    if (empty($results) || count($results) == 0)
    {
      throw new Exception\NoResults(sprintf('No results "%s" found', print_r($args, true)));
    }

    $this->hydrate($results);
  }
}

