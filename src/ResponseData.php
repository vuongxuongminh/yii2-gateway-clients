<?php
/**
 * @link https://github.com/vuongxuongminh/yii2-gateway-clients
 * @copyright Copyright (c) 2018 Vuong Xuong Minh
 * @license [New BSD License](http://www.opensource.org/licenses/bsd-license.php)
 */

namespace vxm\gatewayclients;

/**
 * Class RequestData provide data get from gateway server api after requested.
 * See [[BaseGateway::request()]].
 *
 * @property bool $isOk
 *
 * @author Vuong Minh <vuongxuongminh@gmail.com>
 * @since 1.0
 */
abstract class ResponseData extends Data
{

    /**
     * Check response data is ok or not. Ok meaning a result is valid you (transaction completed, query result is valid).
     *
     * @return bool
     */
    abstract public function getIsOk(): bool;


}