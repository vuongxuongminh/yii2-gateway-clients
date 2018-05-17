<?php
/**
 * @link https://github.com/vuongxuongminh/yii2-gateway-clients
 * @copyright Copyright (c) 2018 Vuong Xuong Minh
 * @license [New BSD License](http://www.opensource.org/licenses/bsd-license.php)
 */


namespace vxm\examples\gatewayclients;

use yii\httpclient\Client as HttpClient;

use vxm\gatewayclients\BaseGateway;

/**
 * Class Gateway
 *
 * @property Client $client
 *
 * @author Vuong Minh <vuongxuongminh@gmail.com>
 * @since 1.0
 */
class Gateway extends BaseGateway
{

    public $responseDataConfig = ['class' => ResponseData::class];

    public $requestDataConfig = ['class' => RequestData::class];

    public $clientConfig = ['class' => Client::class];


    public function getVersion(): string
    {
        return '1.0';
    }

    public function getBaseUrl(): string
    {
        return 'http://test.app';
    }

    public function requestCommands(): array
    {
        return ['charge', 'refund'];
    }

    protected function requestInternal(\vxm\gatewayclients\RequestData $requestData, HttpClient $httpClient): array
    {
        return $httpClient->post('', $requestData->get())->getData();
    }

}