<?php
/**
 * @link https://github.com/vuongxuongminh/yii2-gateway-clients
 * @copyright Copyright (c) 2018 Vuong Xuong Minh
 * @license [New BSD License](http://www.opensource.org/licenses/bsd-license.php)
 */

namespace vxm\gatewayclients;

/**
 * ClientInterface is an interface for all clients of the gateway.
 *
 * @author Vuong Minh <vuongxuongminh@gmail.com>
 * @since 1.0
 */
interface ClientInterface
{
    /**
     * Return the gateway object of this client. It should be use for make request to gateway server api.
     * @return GatewayInterface
     */
    public function getGateway(): GatewayInterface;


}