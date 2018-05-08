<?php
/**
 * @link https://github.com/vuongxuongminh/yii2-gatewayclients
 * @copyright Copyright (c) 2018 Vuong Xuong Minh
 * @license [New BSD License](http://www.opensource.org/licenses/bsd-license.php)
 */


namespace vxm\tests\unit\gatewayclients;

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

    const RC_CHARGE = 'charge';

    const RC_REFUND = 'refund';

    public $responseDataConfig = ['class' => ResponseData::class];

    public $requestDataConfig = ['class' => RequestData::class];

    public $clientConfig = ['class' => Client::class];

    public function getBaseUrl(): string
    {
        return 'http://test.app';
    }

    protected function requestInternal(\vxm\gatewayclients\RequestData $requestData, HttpClient $httpClient): array
    {
        $requestData->get();

        if ($requestData->getCommand() === self::RC_CHARGE) {
            $data = ['success' => true];
        } else{
            $data = ['success' => false];
        }

        $data['command'] = $requestData->getCommand();

        return $data;
    }

}
