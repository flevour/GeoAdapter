<?php

/**
 * This file is part of the GeoAdapter software.
 * (c) 2011 Francesco Trucchia <francesco@trucchia.it>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Geo\Service\GoogleMap;

require_once dirname(__FILE__) . '/../../../../../lib/Geo/Location.php';
require_once dirname(__FILE__) . '/../../../../../lib/Geo/Service.php';
require_once dirname(__FILE__) . '/../../../../../lib/Geo/Service/GoogleMap/GeoCode.php';

/**
 * @group online
 */
class GeoCodeTest extends \PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        $this->client = $this->getMockBuilder('\Geo\HttpClient')
                ->setMethods(array('get'))
                ->getMock();
        $this->service = new GeoCode($this->client);
        $this->service->setLanguage('IT');
    }

    public function testSearch()
    {
        $json = file_get_contents(__DIR__ . "/responseSearch.txt");
        $this->client->expects($this->once())
                ->method('get')
                ->with('http://maps.googleapis.com/maps/api/geocode/json?address=Milano&region=&sensor=false&language=IT')
                ->will($this->returnValue($json));
        $this->service->search('query', 'Milano');
        $results = $this->service->getResults();

        $this->assertEquals('1', count($results));

        $this->assertInstanceOf('\Geo\Location', $results['0']);
        $this->assertEquals('46.8269627', $results['0']->getLatitude());
        $this->assertEquals('11.7706444', $results['0']->getLongitude());
        $this->assertEquals('Via San Zeno, 1, 39030 Terento BZ, Italia', $results['0']->getAddress());
    }

}
