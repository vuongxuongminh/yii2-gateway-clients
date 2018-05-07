<?php
/**
 * @link https://github.com/vuongxuongminh/yii2-gatewayclients
 * @copyright Copyright (c) 2018 Vuong Xuong Minh
 * @license [New BSD License](http://www.opensource.org/licenses/bsd-license.php)
 */


namespace vxm\tests\unit\gatewayclients;

use Yii;

use vxm\gatewayclients\GatewayCollection;

use PHPUnit\Framework\TestCase;

/**
 * Class GatewayCollectionTest
 *
 * @author Vuong Minh <vuongxuongminh@gmail.com>
 * @since 1.0
 */
class GatewayCollectionTest extends TestCase
{

    /**
     * @var GatewayCollection
     */
    public $_collection;

    public function setUp()/* The :void return type declaration that should be here would cause a BC issue */
    {
        $this->_collection = Yii::createObject(GatewayCollection::class);
    }

    /**
     * @dataProvider gatewayProvider
     * @param $gateway
     * @param array|string $config
     * @throws \yii\base\InvalidConfigException
     */
    public function testSetGetGateway(string $gateway, $config)
    {
        $result = $this->_collection->setGateway($gateway, $config);

        $this->assertTrue($result);
        $this->assertTrue($this->_collection->hasGateway($gateway));
        $this->assertInstanceOf(Gateway::class, $this->_collection->getGateway($gateway));
    }

    public function gatewayProvider()
    {
        return [
            ['gateway01', Gateway::class],
            ['gateway02', function () {
                return Yii::createObject(Gateway::class);
            }],
            ['gateway03', ['class' => Gateway::class]]
        ];
    }

    /**
     * @depends      testSetGetGateway
     * @dataProvider gatewayProvider
     * @param string $gateway
     * @param $config
     * @throws \yii\base\InvalidConfigException
     */
    public function testValidRequest(string $gateway, $config)
    {
        $this->_collection->setGateway($gateway, $config);
        $this->_collection->getGateway($gateway)->setClient(['username' => 'client01']);
        $response = $this->_collection->request('charge', ['amount' => 5], $gateway);
        $this->assertTrue($response->getIsOk());
        $response = $this->_collection->request('refund', ['amount' => 5], $gateway);
        $this->assertFalse($response->getIsOk());
        $this->assertTrue(!empty($response->get()));
    }

    /**
     * @expectedException \yii\base\InvalidArgumentException
     * @depends      testValidRequest
     * @dataProvider gatewayProvider
     * @param string $gateway
     * @param $config
     * @throws \yii\base\InvalidConfigException
     */
    public function testUnknownRequestCommand(string $gateway, $config)
    {
        $this->_collection->setGateway($gateway, $config);
        $this->_collection->request('bonus', [], $gateway);
    }

    /**
     * @expectedException \yii\base\InvalidConfigException
     * @depends      testValidRequest
     * @dataProvider gatewayProvider
     * @param string $gateway
     * @param $config
     * @throws \yii\base\InvalidConfigException
     */
    public function testUnValidRequestData(string $gateway, $config)
    {
        $this->_collection->setGateway($gateway, $config);
        $this->_collection->request('refund', [], $gateway);
    }

    /**
     * @dataProvider gatewaysProvider
     * @param $config
     * @throws \yii\base\InvalidConfigException
     */
    public function testSetGetGateways(array $config)
    {
        $result = $this->_collection->setGateways($config);
        $this->assertTrue($result);

        foreach (array_keys($config) as $id) {
            $this->assertTrue($this->_collection->hasGateway($id));
            $this->assertInstanceOf(Gateway::class, $this->_collection->getGateway($id));
        }
    }

    public function gatewaysProvider()
    {
        return [
            [
                [
                    'gateway01' => [
                        'class' => Gateway::class
                    ]
                ]
            ],
            [
                [
                    'gateway02' => function () {
                        return Yii::createObject(Gateway::class);
                    }
                ]
            ],
            [
                [
                    'gateway03' => Gateway::class
                ]
            ],
        ];
    }

}