<?php

namespace BusyPHP\apidoc\structs;

/**
 * 返回参数结构对象
 * @author busy^life <busy.life@qq.com>
 * @copyright (c) 2015--2021 ShanXi Han Tuo Technology Co.,Ltd. All rights reserved.
 * @version $Id: 2020/10/6 下午10:48 上午 ReturnItem.php $
 */
class ReturnItem extends BaseStructure
{
    /**
     * 参数
     * @var string
     */
    public $key;
    
    /**
     * 类型
     * @var string
     */
    public $type;
    
    /**
     * 说明结构
     * @var DataStructure
     */
    public $data;
    
    
    /**
     * @param string                    $key
     * @param string                    $type
     * @param DataStructure|null|string $data
     */
    public function __construct(string $key = '', string $type = '', $data = null)
    {
        $this->key  = $key;
        $this->type = $type;
        
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
     * @param string $type
     */
    public function setType(string $type)
    {
        $this->type = $type;
    }
    
    
    /**
     * @param DataStructure $data
     */
    public function setData(DataStructure $data)
    {
        $this->data = $data;
    }
}