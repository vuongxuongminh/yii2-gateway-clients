<?php
/**
 * @link https://github.com/vuongxuongminh/yii2-gateway-clients
 * @copyright Copyright (c) 2018 Vuong Xuong Minh
 * @license [New BSD License](http://www.opensource.org/licenses/bsd-license.php)
 */

namespace vxm\gatewayclients;

use GatewayClients\DataInterface;

use yii\base\DynamicModel;
use yii\base\InvalidConfigException;

/**
 * Data is the base class that implements the DataInterface provide data for send or get from gateway server api.
 *
 * @property BaseClient $client The client of data.
 * @property string $command The command of data useful when detect attributes and scenario.
 *
 * @author Vuong Minh <vuongxuongminh@gmail.com>
 * @since 1.0
 */
class Data extends DynamicModel implements DataInterface
{

    /**
     * Constructor.
     *
     * @param string|int $command of this data
     * @param array $attributes use for parse to [[DynamicModel::construct()]]
     * @param BaseClient $client use for make data it may use for add fixed data.
     * @param array $config configurations to be applied to the newly created data object.
     */
    public function __construct($command, array $attributes = [], BaseClient $client, array $config = [])
    {
        $this->_command = $command;
        $this->_client = $client;

        $this->setScenario($this->getCommand());
        $this->ensureAttributes($attributes);

        parent::__construct($attributes, $config);
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return array_merge([$this->getCommand() => []], parent::scenarios());
    }

    /**
     * @param array $attributes for ensure value or add more attribute from client.
     */
    protected function ensureAttributes(array &$attributes)
    {
        $activeAttributes = array_fill_keys($this->activeAttributes(), null);
        $attributes = array_merge($activeAttributes, $attributes);
    }

    /**
     * @var string command of this data
     */
    private $_command;

    /**
     * Return command of this data. This method may called by [[ensureAttributes()]].
     * @return string
     */
    public function getCommand(): string
    {
        return $this->_command;
    }

    /**
     * @var BaseClient of this data
     */
    private $_client;

    /**
     * Return client of this data. This method may called by [[ensureAttributes()]] for add fixed data from client properties.
     *
     * @return BaseClient
     */
    public function getClient(): BaseClient
    {
        return $this->_client;
    }

    /**
     * @inheritdoc
     * @throws InvalidConfigException
     */
    public function get(bool $validate = true): array
    {
        if (!$validate || $this->validate()) {
            return $this->toArray();
        } else {
            throw new InvalidConfigException(current($this->getFirstErrors()));
        }
    }


}
