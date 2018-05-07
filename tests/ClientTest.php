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
 * Class ClientTest
 *
 * @author Vuong Minh <vuongxuongminh@gmail.com>
 * @since 1.0
 */
class ClientTest extends TestCase
{

    public function testEnsureGateway()
    {
        $gateway = Yii::createObject(Gateway::class);
        $client = Yii::createObject([
            'class' => Client::class,
            'username' => 'test02'
        ], [$gateway]);

        $result = $gateway->setClient($client);
        $this->assertTrue($result);
        $this->assertEquals($gateway, $gateway->getClient()->getGateway());
    }
}