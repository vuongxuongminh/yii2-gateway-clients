<?php
/**
 * @link https://github.com/vuongxuongminh/yii2-gateway-clients
 * @copyright Copyright (c) 2018 Vuong Xuong Minh
 * @license [New BSD License](http://www.opensource.org/licenses/bsd-license.php)
 */


namespace vxm\gatewayclients;

use Yii;

use GatewayClients\DataInterface;
use GatewayClients\GatewayInterface;

use yii\base\Component;
use yii\base\InvalidArgumentException;
use yii\helpers\ArrayHelper;

/**
 * Class GatewayCollection. This is the collection of [[\vxm\gatewayclients\GatewayInterface]], it may set to application components for easily control gateways.
 *
 * @author Vuong Minh <vuongxuongminh@gmail.com>
 * @since 1.0
 */
class GatewayCollection extends Component
{

    /**
     * @var array Common config gateways when init it use to config gateway object.
     */
    public $gatewayConfig = [];

    /**
     * @var array|GatewayInterface[] the gateways list.
     */
    private $_gateways = [];

    /**
     * Return an array gateway lists.
     *
     * @return GatewayInterface[] the gateway lists.
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
     * @param array|GatewayInterface[] $gateways
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
     * @return GatewayInterface object gateway define by arg name.
     * @throws \yii\base\InvalidConfigException
     */
    public function getGateway($id): GatewayInterface
    {
        $gateway = $this->_gateways[$id] ?? null;

        if ($gateway === null) {
            throw new InvalidArgumentException("Gateway: `$id` not exist!");
        } elseif (!$gateway instanceof GatewayInterface) {

            if (is_string($gateway)) {
                $gateway = ['class' => $gateway];
            }

            if (is_array($gateway)) {
                $gateway = ArrayHelper::merge($this->gatewayConfig, $gateway);
            }

            $gateway = $this->_gateways[$id] = Yii::createObject($gateway);
        }

        return $gateway;
    }

    /**
     * Set gateway in to the gateway lists. This method called by [[setGateways()]].
     *
     * @param int|string $id An id of gateway in the gateway lists.
     * @param string|array|GatewayInterface $gateway config or object gateway value define by name.
     * @return bool Return TRUE when gateway has been set.
     */
    public function setGateway($id, $gateway): bool
    {
        $this->_gateways[$id] = $gateway;

        return true;
    }

    /**
     * Indicates if the gateway id has been set.
     *
     * @param $id
     * @return bool Return TRUE when gateway id exist.
     */
    public function hasGateway($id)
    {
        return array_key_exists($id, $this->_gateways);
    }

    /**
     * Magic getter method provide access gateway by unknown property name.
     *
     * @inheritdoc
     * @throws \yii\base\InvalidConfigException|\yii\base\UnknownPropertyException
     */
    public function __get($name)
    {
        if ($this->hasGateway($name)) {
            return $this->getGateway($name);
        } else {
            parent::__get($name);
        }
    }

    /**
     * This method is an alias of [[GatewayInterface::request()]].
     *
     * @param int|string $command The command of request
     * @param array $data An array data use to send to gateway server api.
     * @param int|string $gatewayId An id of gateway in the gateway lists.
     * @param null|int|string $clientId An id client of gateway in the client lists. If not set default client of gateway will be use to make request.
     * @return ResponseData|DataInterface An object data get from gateway server api.
     * @throws \yii\base\InvalidConfigException
     */
    public function request($command, array $data, $gatewayId, $clientId = null): DataInterface
    {
        return $this->getGateway($gatewayId)->request($command, $data, $clientId);
    }


}
