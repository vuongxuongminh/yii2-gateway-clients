<?php
/**
 * @link https://github.com/vuongxuongminh/yii2-gatewayclients
 * @copyright Copyright (c) 2018 Vuong Xuong Minh
 * @license [New BSD License](http://www.opensource.org/licenses/bsd-license.php)
 */

namespace vxm\gatewayclients;


/**
 * GatewayInterface designed for (1-n) rest api client model, one gateway control one or multi client.
 * Example:
 * Config for (1-1) model:
 * ```php
 *      $gateway = new Gateway([
 *          'client' => [
 *              'username' => 'vuongminh'
 *          ]
 *      ]);
 *
 *      $gateway->request("refund", ['amount' => 300, 'email' => 'abc@nothing.z']);
 * ```
 *
 * Config for (1-n) model:
 * ```php
 *      $gateway = new Gateway([
 *          'clients' => [
 *              'gold' => [
 *                  'username' => 'vuongminh'
 *              ],
 *              'sliver' => [
 *                  'username' => 'yiiviet'
 *              ]
 *          ]
 *      ]);
 *
 *      $gateway->request("refund", ['amount' => 300, 'email' => 'abc@nothing.z'], 'sliver');
 *      $gateway->request("refund", ['amount' => 3000, 'email' => 'abc@nothing.z'], 'gold');
 * ```
 *
 * @property array|ClientInterface[] $clients
 * @property ClientInterface $client
 *
 * @author Vuong Minh <vuongxuongminh@gmail.com>
 * @since 1.0
 */
interface GatewayInterface
{

    /**
     * Returns the version, which may be used in `request` method for collect data.
     *
     * @return string
     */
    public function getVersion(): string;

    /**
     * Returns the base URL of gateway server api. This method called by [[request()]].
     *
     * @return string
     */
    public function getBaseUrl(): string;

    /**
     * Get the clients list.
     *
     * @return array|ClientInterface[]
     */
    public function getClients(): array;

    /**
     * Set clients to the client list.
     *
     * @param array|ClientInterface[] $clients The client lists of gateway.
     * @return bool
     */
    public function setClients(array $clients): bool;

    /**
     * Get client by an id.
     *
     * @param string|int $id An id of client in client lists. If not set it will return client get from [[getDefaultClient()]].
     * @return ClientInterface Client get by an id.
     */
    public function getClient($id = null): ClientInterface;

    /**
     * Set the client to the clients list.
     *
     * @param string|int|array|ClientInterface $id An id of client use to define client set to the client lists.
     * If arg `$client` not set it will be a client use to parse to [[setDefaultClient()]].
     * @param array|string|ClientInterface $client The client config or an object value set to client lists by an id.
     * @return bool Return TRUE when `$client` has been defined by `$id` arg.
     */
    public function setClient($id, $client = null): bool;

    /**
     * Get default client. It called by [[getClient()]], [[request()]] when arg `$id` is null.
     * It designed for (1-1) model.
     *
     * @return ClientInterface
     */
    public function getDefaultClient(): ClientInterface;

    /**
     * Set default client. It called by [[setClient]] when arg `$client` of this method is null, an `$id` arg of [[setClient()]] will be parse to `$client` arg.
     * It designed for (1-1) model.
     *
     * @param array|string|ClientInterface $client The client config or an object value set to default client.
     * @return bool Return TRUE when `$client` has been set default.
     */
    public function setDefaultClient($client): bool;

    /**
     * Indicates if the client id has been set.
     *
     * @param $id
     * @return bool
     */
    public function hasClient($id): bool;


    /**
     * Make a request to api server by client in the clients list detect from id, command and data depend on custom business of server api.
     * An example:
     * ```php
     *      $gateway->setClient('vxm', ['token' => 123456]);
     *      $gateway->request('addMoney', ['money' => 100], 'vxm');
     * ```
     *
     * @param int|string $command Command of current request.
     * @param array $data An array data of current request will be send to gateway server api.
     * @param null|int|string $clientId An id of client use to make request. If not set it will return client get from [[getDefaultClient()]].
     * @return DataInterface
     */
    public function request($command, array $data, $clientId = null): DataInterface;

    /**
     * An array request commands supported. Which should be used in `request` method to ensure arg `command` is valid or not.
     *
     * @return array
     */
    public function requestCommands(): array;

}
