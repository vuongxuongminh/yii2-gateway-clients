<?php
/**
 * @link https://github.com/vuongxuongminh/yii2-gatewayclients
 * @copyright Copyright (c) 2018 Vuong Xuong Minh
 * @license [New BSD License](http://www.opensource.org/licenses/bsd-license.php)
 */


namespace vxm\examples\gatewayclients;

use vxm\gatewayclients\ResponseData as BaseResponseData;

/**
 * Class ResponseData
 *
 * @property bool $success
 *
 * @author Vuong Minh <vuongxuongminh@gmail.com>
 * @since 1.0
 */
class ResponseData extends BaseResponseData
{

    public function getIsOk(): bool
    {
        return $this->success === true;
    }

}