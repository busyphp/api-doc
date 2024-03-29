<?php

namespace BusyPHP\apidoc\structs;

/**
 * 请求参数结构对象
 * @author busy^life <busy.life@qq.com>
 * @copyright (c) 2015--2021 ShanXi Han Tuo Technology Co.,Ltd. All rights reserved.
 * @version $Id: 2020/10/6 下午10:26 上午 ParamItem.php $
 */
class ParamItem extends BaseStructure
{
    /**
     * 参数名称
     * @var string
     */
    public $key;
    
    /**
     * 是否必填
     * @var bool
     */
    public $must;
    
    /**
     * 参数类型
     * @var string
     */
    public $type;
    
    /**
     * 参数说明
     * @var string
     */
    public $data;
    
    
    /**
     * ParamStructure constructor.
     * @param string                    $key
     * @param string                    $type
     * @param bool                      $must
     * @param DataStructure|string|null $data
     */
    public function __construct(string $key = '', string $type = '', bool $must = false, $data = null)
    {
        $this->key  = $key;
        $this->type = $type;
        $this->must = $must;
        
        if (is_string($data) && $data) {
            $data = new DataStructure($data);
        }
        
        $this->data = $data ?? new DataStructure();
    }
}