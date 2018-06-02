<?php
/**
 * @link https://github.com/vuongxuongminh/yii2-gateway-clients
 * @copyright Copyright (c) 2018 Vuong Xuong Minh
 * @license [New BSD License](http://www.opensource.org/licenses/bsd-license.php)
 */

namespace vxm\gatewayclients;

use yii\base\Component;

use GatewayClients\ClientInterface;
use GatewayClients\GatewayInterface;

/**
 * Class BaseClient a base class that implements the [[ClientInterface]].
 * It is an abstraction layer, implements classes will be add more properties to support create request to gateway server api.
 *
 * @property BaseGateway $gateway
 *
 * @author Vuong Minh <vuongxuongminh@gmail.com>
 * @since 1.0
 */
abstract class BaseClient extends Component implements ClientInterface
{

    /**
     * Constructor.
     *
     * @param BaseGateway $gateway the gateway object of this client.
     * @param array $config configurations to be applied to the newly created client object.
     */
    public function __construct(BaseGateway $gateway, array $config = [])
    {
        $this->_gateway = $gateway;

        parent::__construct($config);
    }

    /**
     * @var BaseGateway|GatewayInterface the gateway object of this client.
     */
    private $_gateway;

    /**
     * @inheritdoc
     * @return BaseGateway|GatewayInterface
     */
    public function getGateway(): GatewayInterface
    {
        return $this->_gateway;
    }


}
