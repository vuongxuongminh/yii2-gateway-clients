<?php
/**
 * @link https://github.com/vuongxuongminh/yii2-gateway-clients
 * @copyright Copyright (c) 2018 Vuong Xuong Minh
 * @license [New BSD License](http://www.opensource.org/licenses/bsd-license.php)
 */


namespace vxm\examples\gatewayclients;

use vxm\gatewayclients\RequestData as BaseRequestData;

/**
 * Class RequestData
 *
 * @author Vuong Minh <vuongxuongminh@gmail.com>
 * @since 1.0
 */
class RequestData extends BaseRequestData
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['amount'], 'required', 'on' => ['refund', 'charge']]
        ];
    }

}