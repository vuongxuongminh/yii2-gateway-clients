<?php
/**
 * @link https://github.com/vuongxuongminh/yii2-gateway-clients
 * @copyright Copyright (c) 2018 Vuong Xuong Minh
 * @license [New BSD License](http://www.opensource.org/licenses/bsd-license.php)
 */

namespace vxm\gatewayclients;

use Yii;
use ReflectionClass;

use GatewayClients\ClientInterface;
use GatewayClients\DataInterface;
use GatewayClients\GatewayInterface;

use yii\base\Component;
use yii\base\InvalidArgumentException;
use yii\base\InvalidConfigException;
use yii\base\NotSupportedException;
use yii\helpers\ArrayHelper;
use yii\httpclient\Client as HttpClient;


/**
 * Class BaseClient a base class that implements the [[GatewayInterface]].
 * It is an abstraction layer, implements classes will be add more properties to support create request to gateway server api.
 *
 * @property BaseClient $defaultClient Default client of gateway.
 * @property BaseClient $client Default client of gateway.
 * @property array|string[] $supportedVersions The list supported versions of gateway.
 *
 * @author Vuong Minh <vuongxuongminh@gmail.com>
 * @since 1.0
 */
abstract class BaseGateway extends Component implements GatewayInterface
{

    /**
     * @event RequestEvent an event that is triggered at the beginning of request to gateway server api.
     */
    const EVENT_BEFORE_REQUEST = 'beforeRequest';

    /**
     * @event RequestEvent an event that is triggered at the end of requested to gateway server api.
     */
    const EVENT_AFTER_REQUEST = 'afterRequest';

    /**
     * @var array config use for setup properties of all request data to send to gateway server api. It called by [[request()]].
     * An extend class must be override it for config default request data class.
     */
    public $requestDataConfig = [];

    /**
     * @var array config use for setup properties of all response data get from gateway server api. It called by [[request()]].
     * An extend class must be override it for config default response data class.
     */
    public $responseDataConfig = [];

    /**
     * @var array config of client use for setup properties of the clients list.
     * An extend class must be override it for config default client class.
     */
    public $clientConfig = [];

    /**
     * @var array config of `yii\httpclient\Client` use for send request to api server.
     * @since 2.0.1
     */
    public $httpClientConfig = [];

    /**
     * The clients list.
     *
     * @var array|BaseClient[]
     */
    private $_clients = [];

    /**
     * @var string A currently version of gateway
     */
    private $_version;

    /**
     * @inheritdoc
     */
    public function getVersion(): string
    {
        if ($this->_version === null) {
            return $this->_version = $this->defaultVersion();
        } else {
            return $this->_version;
        }
    }

    /**
     * This method return list supported versions of gateway.
     *
     * @return array|string[] List supported versions.
     */
    public function getSupportedVersions(): array
    {
        return [$this->getVersion()];
    }

    /**
     * This method support set version of gateway.
     *
     * @param string $version of gateway
     * @return bool Return TRUE if version has been set.
     * @throws NotSupportedException
     */
    public function setVersion(string $version): bool
    {
        $supportedVersions = $this->getSupportedVersions();

        if (in_array($version, $supportedVersions, true)) {
            $this->_version = $version;

            return true;
        } else {
            throw new NotSupportedException("Version `$version` is not supported in " . __CLASS__ . "! Supported versions: " . implode(' - ', $supportedVersions));
        }
    }

    /**
     * This method return default version of gateway if `version` is not set.
     *
     * @return string
     */
    protected function defaultVersion(): string
    {
        return '1.0';
    }

    /**
     * @inheritdoc
     * @throws InvalidConfigException
     */
    public function getClients(): array
    {
        $clients = [];
        foreach ($this->_clients as $id => $client) {
            $clients[$id] = $this->getClient($id);
        }

        return $clients;
    }

    /**
     * @inheritdoc
     * @throws InvalidConfigException
     */
    public function setClients(array $clients): bool
    {
        foreach ($clients as $id => $client) {
            $this->setClient($id, $client);
        }

        return true;
    }

    /**
     * @inheritdoc
     * @throws InvalidConfigException|InvalidArgumentException
     */
    public function getClient($id = null): ClientInterface
    {
        if ($id === null) {
            return $this->getDefaultClient();
        } elseif ($this->hasClient($id)) {
            $client = $this->_clients[$id];

            if (is_string($client)) {
                $client = ['class' => $client];
            }

            if (is_array($client)) {
                $client = ArrayHelper::merge($this->clientConfig, $client);
            }

            if (!$client instanceof BaseClient) {
                $client = $this->_clients[$id] = Yii::createObject($client, [$this]);
            }

            return $client;
        } else {
            throw new InvalidArgumentException("An id client: `$id` not exist!");
        }
    }

    /**
     * @var BaseClient|ClientInterface The default client value.
     */
    private $_defaultClient;

    /**
     * @inheritdoc
     * @return BaseClient|ClientInterface
     * @throws InvalidConfigException
     */
    public function getDefaultClient(): ClientInterface
    {
        if ($this->_defaultClient === null) {
            if (!empty($this->_clients)) {
                $ids = array_keys($this->_clients);
                $id = array_pop($ids);

                return $this->_defaultClient = $this->getClient($id);
            } else {
                throw new InvalidConfigException('Can not detect default client from an empty client lists!');
            }
        } else {
            return $this->_defaultClient;
        }
    }

    /**
     * @inheritdoc
     * @throws InvalidConfigException
     */
    public function setClient($id, $client = null): bool
    {
        if ($client === null) {
            $this->setDefaultClient($id);
        } else {
            $this->_clients[$id] = $client;
        }

        return true;
    }

    /**
     * @inheritdoc
     * @throws InvalidConfigException
     */
    public function setDefaultClient($client): bool
    {
        array_push($this->_clients, $client);
        $this->_defaultClient = null;
        $this->getDefaultClient();

        return true;
    }

    /**
     * @inheritdoc
     */
    public function hasClient($id): bool
    {
        return array_key_exists($id, $this->_clients);
    }

    /**
     * @inheritdoc
     * @return ResponseData|DataInterface
     * @throws InvalidConfigException|InvalidArgumentException|\ReflectionException
     */
    public function request($command, array $data, $clientId = null): DataInterface
    {
        if (in_array($command, $this->requestCommands(), true)) {
            $client = $this->getClient($clientId);

            /**
             * @var RequestData $requestData
             * @var RequestEvent $event
             * @var ResponseData $responseData
             */

            $requestData = Yii::createObject($this->requestDataConfig, [$command, $data, $client]);
            $event = Yii::createObject([
                'class' => RequestEvent::class,
                'command' => $command,
                'client' => $client,
                'requestData' => $requestData
            ]);

            $this->beforeRequest($event);
            $httpClient = $this->getHttpClient();
            $data = $this->requestInternal($requestData, $httpClient);
            $responseData = Yii::createObject($this->responseDataConfig, [$command, $data, $client]);
            $event->responseData = $responseData;
            $this->afterRequest($event);
            Yii::debug(__CLASS__ . " requested sent with command: `$command` - version: " . $this->getVersion());

            return $responseData;
        } else {
            throw new InvalidArgumentException("Unknown request command `$command`");
        }
    }

    /**
     * An array store all request commands supported in gateway [[request()]].
     *
     * @see requestCommands
     * @var array
     */
    private $_requestCommands;

    /**
     * This method automatically detect request commands supported via constants name prefix with `RC_`.
     * `RC` meaning Request Command.
     *
     * @return array An array constants value have name prefix with `RC_`
     * @throws \ReflectionException
     */
    public function requestCommands(): array
    {
        if ($this->_requestCommands === null) {
            $reflection = new ReflectionClass($this);

            $commands = [];
            foreach ($reflection->getConstants() as $name => $value) {
                if (strpos($name, 'RC_') === 0) {
                    $commands[] = $value;
                }
            }

            return $this->_requestCommands = $commands;
        } else {
            return $this->_requestCommands;
        }
    }

    /**
     * This method is called at the beginning of requesting data to gateway server api.
     *
     * The default implementation will trigger an [[EVENT_BEFORE_REQUEST]] event.
     * When overriding this method, make sure you call the parent implementation like the following:
     *
     * ```php
     * public function beforeRequest(RequestEvent $event)
     * {
     *     // ...custom code here...
     *
     *     parent::beforeRequest($event);
     * }
     * ```
     *
     * @param RequestEvent $event an event will be trigger in this method.
     */
    public function beforeRequest(RequestEvent $event)
    {
        $this->trigger(self::EVENT_BEFORE_REQUEST, $event);
    }

    /**
     * @var HttpClient will be use to send request to gateway server api.
     */
    private $_httpClient;

    /**
     * This method is called in [[request()]] invoke client and use it make request send to gateway server api.
     *
     * @param bool $force whether to force new instance
     * @return object|HttpClient instance use to send request
     * @throws InvalidConfigException
     */
    protected function getHttpClient(bool $force = false): HttpClient
    {
        if ($this->_httpClient === null || $force) {
            /** @var HttpClient $client */
            $config = ArrayHelper::merge(['class' => HttpClient::class], $this->httpClientConfig);
            $config['baseUrl'] = $this->getBaseUrl();

            return $this->_httpClient = Yii::createObject($config);
        } else {
            return $this->_httpClient;
        }
    }

    /**
     * Returns the config for the HttpClient.
     * An implement class may override this method for add default headers, transport, options...
     *
     * @return array
     * @deprecated since 2.0.1 move config to public property `httpClientConfig`
     */
    protected function getHttpClientConfig(): array
    {
        return [];
    }

    /**
     * An internal method get and send customize data depend on gateway server api. This method called by [[request()]] .
     *
     * @param RequestData $requestData use to get data, command, client for prepare request to send.
     * @param HttpClient $httpClient use for make request to gateway server api.
     * @return array response requested data.
     */
    abstract protected function requestInternal(RequestData $requestData, HttpClient $httpClient): array;


    /**
     * This method is called at the end of requesting data to gateway server api.
     * The default implementation will trigger an [[EVENT_AFTER_REQUEST]] event.
     * When overriding this method, make sure you call the parent implementation at the end like the following:
     *
     * ```php
     * public function afterRequest(RequestEvent $event)
     * {
     *     // ...custom code here...
     *
     *     parent::afterRequest($event);
     * }
     * ```
     *
     * @param RequestEvent $event an event will be trigger in this method.
     */
    public function afterRequest(RequestEvent $event)
    {
        $this->trigger(self::EVENT_AFTER_REQUEST, $event);
    }

}
