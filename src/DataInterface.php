<?php
/**
 * @link https://github.com/vuongxuongminh/yii2-gatewayclients
 * @copyright Copyright (c) 2018 Vuong Xuong Minh
 * @license [New BSD License](http://www.opensource.org/licenses/bsd-license.php)
 */

namespace vxm\gatewayclients;

/**
 * DataInterface is designed to get data validated use it for make request or get data from gateway server api.
 *
 * @author Vuong Minh <vuongxuongminh@gmail.com>
 * @since 1.0
 */
interface DataInterface
{

    /**
     * Get an array data.
     *
     * @param bool $validate whether to perform validation.
     * @return array data validated or not.
     */
    public function get(bool $validate = true): array;

}