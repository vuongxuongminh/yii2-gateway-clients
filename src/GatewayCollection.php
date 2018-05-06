<?php
/**
 * @link https://github.com/vuongxuongminh/yii2-gatewayclients
 * @copyright Copyright (c) 2018 Vuong Xuong Minh
 * @license [New BSD License](http://www.opensource.org/licenses/bsd-license.php)
 */


namespace vxm\gatewayclients;

use Yii;

use yii\base\BaseObject;
use yii\base\InvalidArgumentException;

/**
 * Class GatewayCollection. This is the collection of [[\vxm\gatewayclients\BaseGateway]], it may set to application components for easily control gateways.
 *
 * @author Vuong Minh <vuongxuongminh@gmail.com>
 * @since 1.0
 */
class GatewayCollection extends BaseObject
{

    /**
     * @var array|BaseGateway[] the gateways list.
     */
    private $_gateways = [];

    /**
     * Return an array gateway lists.
     *
     * @return BaseGateway[] the gateway lists.
     * @throws \yii\base\InvalidConfigException
     */
    public function getGateways(): array
    {
        $gateways = [];

        foreach ($this->_gateways as $id => $gateway) {
            $gateways[$id] = $this->getGateway($id);
        }

        return $gateways;
    }

    /**
     * @param array|BaseGateway[] $gateways
     * @return bool Return true when gateways has been set.
     */
    public function setGateways(array $gateways): bool
    {
        foreach ($gateways as $name => $gateway) {
            $this->setGateway($name, $gateway);
        }

        return true;
    }

    /**
     * Get gateway by id given.
     *
     * @param int|string $id An id of gateway need to get.
     * @return BaseGateway object gateway define by arg name.
     * @throws \yii\base\InvalidConfigException
     */
    public function getGateway($id): BaseGateway
    {
        $gateway = $this->_gateways[$id] ?? null;

        if ($gateway === null) {
            throw new InvalidArgumentException('Gateway: `name` not exist!');
        } elseif (!$gateway instanceof BaseGateway) {
            $gateway = $this->_gateways[$id] = Yii::createObject($gateway);
        }

        return $gateway;
    }

    /**
     * Set gateway in to the gateway lists. This method called by [[setGateways]].
     *
     * @param int|string $id An id of gateway in the gateway lists.
     * @param string|array|BaseGateway $gateway config or object gateway value define by name.
     * @return bool Return TRUE when gateway has been set.
     */
    protected function setGateway($id, $gateway): bool
    {
        $this->_gateways[$id] = $gateway;

        return true;
    }

    /**
     * This method is an alias of [[vxm\gatewayclients\BaseGateway::request()]].
     *
     * @param int|string $command The command of request
     * @param array $data An array data use to send to gateway server api.
     * @param int|string $gatewayId An id of gateway in the gateway lists.
     * @param int|string $clientId An id client of gateway in the client lists.
     * @return ResponseData An object data get from gateway server api.
     * @throws \yii\base\InvalidConfigException
     */
    public function request($command, array $data, $gatewayId, $clientId): ResponseData
    {
        return $this->getGateway($gatewayId)->request($command, $data, $clientId);
    }

}