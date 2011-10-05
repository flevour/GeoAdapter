<?php
/**
 * This file is part of the GeoAdapter software.
 * (c) 2011 Francesco Trucchia <francesco@trucchia.it>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Geo\Service\MapQuestApi;

require_once dirname(__FILE__) . '/../../../../../lib/Geo/Location.php';
require_once dirname(__FILE__) . '/../../../../../lib/Geo/Service.php';
require_once dirname(__FILE__) . '/../../../../../lib/Geo/Service/MapQuestApi/Nominatim.php';

/**
 * @group online
 */
class NominatimTest extends \PHPUnit_Framework_TestCase
{
  public function setUp()
  {
    $this->client = $this->getMockBuilder('\Geo\HttpClient')
            ->setMethods(array('get'))
            ->getMock();
    $this->service = new Nominatim($this->client, 'fooBaseUrl');
    $this->service->setRegion('IT');
  }

  public function testSearch()
  {
    $json = file_get_contents(__DIR__ . "/responseSearch.txt");
    $this->client->expects($this->once())
            ->method('get')
            ->with('fooBaseUrl/search?format=json&&q=Milano&countrycodes=IT&addressdetails=1')
            ->will($this->returnValue($json));
    $this->service->search('query', 'Milano');
    $results = $this->service->getResults();

    $this->assertEquals('9', count($results));

    $this->assertInstanceOf('\Geo\Location', $results['0']);
    $this->assertEquals('45.466621', number_format($results['0']->getLatitude(), 6));
    $this->assertEquals('9.190617', number_format($results['0']->getLongitude(), 6));
    $this->assertEquals('Milano, Lombardia, Italia, Europe', $results['0']->getAddress());
  }

  public function testReverse()
  {
    $json = file_get_contents(__DIR__ . "/responseReverse.txt");
    $this->client->expects($this->once())
            ->method('get')
            ->with('fooBaseUrl/reverse?format=json&lat=45.466621&lon=9.190617&countrycodes=IT&addressdetails=1')
            ->will($this->returnValue($json));
    $this->service->search('reverse', '45.466621', '9.190617');
    $results = $this->service->getResults();

    $this->assertEquals('1', count($results));

    $location = $results[0];
    $this->assertInstanceOf('\Geo\Location', $location);
    $this->assertEquals('45.466938', number_format($location->getLatitude(), 6));
    $this->assertEquals('9.190050', number_format($location->getLongitude(), 6));
    $this->assertEquals('Piazza della Scala, Greco, Milano, Lombardia, 20121, Italia', $location->getAddress());
    $this->assertEquals('Piazza della Scala', $location->getStreet());
    $this->assertEquals('20121', $location->getZipCode());
    $this->assertEquals('Milano', $location->getLocality());
  }
}
