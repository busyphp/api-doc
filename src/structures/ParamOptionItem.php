<?php

namespace BusyPHP\apidoc\structures;

/**
 * 请求参数选项结构对象
 * @author busy^life <busy.life@qq.com>
 * @copyright (c) 2015--2019 ShanXi Han Tuo Technology Co.,Ltd. All rights reserved.
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
    
    
    public function __construct($key = '', $data = null)
    {
        $this->key  = $key;
        $this->data = $data;
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