<?php

namespace BusyPHP\apidoc\structures;

/**
 * 全局请求头结构对象
 * @author busy^life <busy.life@qq.com>
 * @copyright (c) 2015--2019 ShanXi Han Tuo Technology Co.,Ltd. All rights reserved.
 * @version $Id: 2020/10/6 下午10:45 上午 HeaderItem.php $
 */
class HeaderItem extends BaseStructure
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
     * 参数说明
     * @var string
     */
    public $desc;
    
    
    public function __construct(string $key = '', bool $must = false, string $desc = '')
    {
        $this->setKey($key);
        $this->setDesc($desc);
        $this->setMust($must);
    }
    
    
    /**
     * 设置参数名称
     * @param string $key
     */
    public function setKey(string $key)
    {
        $this->key = trim($key);
    }
    
    
    /**
     * 设置参数描述
     * @param string $desc
     */
    public function setDesc(string $desc)
    {
        $this->desc = trim($desc);
    }
    
    
    /**
     * 设置是否必传
     * @param bool $must
     */
    public function setMust(bool $must)
    {
        $this->must = $must;
    }
}