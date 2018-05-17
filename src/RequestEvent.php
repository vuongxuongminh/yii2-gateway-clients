<?php
/**
 * @link https://github.com/vuongxuongminh/yii2-gateway-clients
 * @copyright Copyright (c) 2018 Vuong Xuong Minh
 * @license [New BSD License](http://www.opensource.org/licenses/bsd-license.php)
 */

namespace vxm\gatewayclients;

use yii\base\Event;

/**
 * Class RequestEvent represents the event parameter used for an request event.
 *
 * @author Vuong Minh <vuongxuongminh@gmail.com>
 * @since 1.0
 */
class RequestEvent extends Event
{
    /**
     * @var string|int command of current request.
     */
    public $command;

    /**
     * @var BaseClient object client of current request
     */
    public $client;

    /**
     * @var RequestData data of current request use to collect array data send to gateway server api.
     */
    public $requestData;

    /**
     * @var ResponseData data of current response use to collect array data get from gateway server api.
     */
    public $responseData;


}