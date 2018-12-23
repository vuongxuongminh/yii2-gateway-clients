<?php
/**
 * @link https://github.com/vuongxuongminh/yii2-gatewayclients
 * @copyright Copyright (c) 2018 Vuong Xuong Minh
 * @license [New BSD License](http://www.opensource.org/licenses/bsd-license.php)
 */

namespace vxm\tests\unit\gatewayclients;

use Yii;

use PHPUnit\Framework\TestCase;

/**
 * Class GatewayTest
 *
 * @author Vuong Minh <vuongxuongminh@gmail.com>
 * @since 1.0
 */
class GatewayTest extends TestCase
{
    /**
     * @var Gateway
     */
    protected $_gateway;


    public function setUp()
    {
        $this->_gateway = Yii::createObject(Gateway::class);
    }

    public function tearDown()/* The :void return type declaration that should be here would cause a BC issue */
    {
        $this->_gateway = null;
    }

    /**
     * @dataProvider clientsProvider
     */
    public function testSetGetClients(array $config)
    {
        $result = $this->_gateway->setClients($config);
        $this->assertTrue($result);

        foreach (array_keys($config) as $id) {
            $this->assertTrue($this->_gateway->hasClient($id));
            $this->assertInstanceOf(Client::class, $this->_gateway->getClient($id));
        }
    }

    public function clientsProvider()
    {
        return [
            [
                [
                    'client01' => ['username' => 'client01']
                ]
            ],
            [
                [
                    'client02' => ['username' => 'client02']
                ]
            ]
        ];
    }

    /**
     * @dataProvider clientProvider
     */
    public function testSetGetClient(string $clientId, array $config)
    {
        $result = $this->_gateway->setClient($clientId, $config);
        $this->assertTrue($result);
        $this->assertTrue($this->_gateway->hasClient($clientId));
        $this->assertInstanceOf(Client::class, $this->_gateway->getClient($clientId));

        $this->_gateway->setClient($config);
        $this->assertEquals($this->_gateway->getClient(), $this->_gateway->getDefaultClient());
    }

    public function clientProvider()
    {
        return [
            ['client01', ['username' => 'client01']],
            ['client02', ['username' => 'client02']]
        ];
    }

    /**
     * @dataProvider clientProvider
     * @depends      testSetGetClient
     */
    public function testValidRequest(string $clientId, array $config)
    {
        $this->_gateway->setClient($clientId, $config);

        $response = $this->_gateway->charge(['amount' => 5], $clientId);
        $this->assertTrue($response->getIsOk());
        $response = $this->_gateway->refund(['amount' => 5], $clientId);
        $this->assertFalse($response->getIsOk());
        $this->assertTrue(!empty($response->get()));

        $this->_gateway->setClient($config);

        $response = $this->_gateway->charge(['amount' => 5]);
        $this->assertEquals($this->_gateway->getClient(), $response->getClient());
    }

    /**
     * @depends testValidRequest
     * @expectedException \yii\base\InvalidArgumentException
     */
    public function testUnknownRequestCommand()
    {
        $this->_gateway->request('bonus', []);
    }

    /**
     * @depends      testValidRequest
     * @expectedException \yii\base\InvalidConfigException
     */
    public function testUnValidRequestData()
    {
        $this->_gateway->setClient(['username' => 'test01']);
        $this->_gateway->refund([]);
    }

    /**
     * @expectedException \yii\base\NotSupportedException
     */
    public function testVersion()
    {
        $this->assertArraySubset(['1.0', '1.0.1', '2.0', '3.0'], $this->_gateway->getSupportedVersions());

        // Valid
        $this->_gateway->setVersion('1.0.1');
        $this->assertEquals('1.0.1', $this->_gateway->getVersion());

        // Throws
        $this->_gateway->setVersion('4.0');
    }


}
