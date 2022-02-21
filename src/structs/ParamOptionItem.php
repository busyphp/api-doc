<?php

namespace BusyPHP\apidoc\structs;

/**
 * 请求参数选项结构对象
 * @author busy^life <busy.life@qq.com>
 * @copyright (c) 2015--2021 ShanXi Han Tuo Technology Co.,Ltd. All rights reserved.
 * @version $Id: 2020/10/6 下午10:48 上午 ParamOptionItem.php $
 */
class ParamOptionItem extends BaseStructure
{
    /**
     * 参数
     * @var string
     */
    public $key;
    
    /**
     * 说明结构
     * @var DataStructure
     */
    public $data;
    
    
    /**
     * @param string                    $key
     * @param DataStructure|string|null $data
     */
    public function __construct(string $key = '', $data = null)
    {
        $this->key = $key;
        
        if (is_string($data) && $data) {
            $data = new DataStructure($data);
        }
        
        $this->data = $data ?? new DataStructure();
    }
    
    
    /**
     * @param string $key
     */
    public function setKey(string $key)
    {
        $this->key = $key;
    }
    
    
    /**
     * @param DataStructure $data
     */
    public function setData(DataStructure $data)
    {
        $this->data = $data;
    }
}