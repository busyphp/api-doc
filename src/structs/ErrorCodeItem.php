<?php

namespace BusyPHP\apidoc\structs;

/**
 * 错误代码结构对象
 * @author busy^life <busy.life@qq.com>
 * @copyright (c) 2015--2021 ShanXi Han Tuo Technology Co.,Ltd. All rights reserved.
 * @version $Id: 2020/10/6 下午10:45 上午 ErrorCodeItem.php $
 */
class ErrorCodeItem extends BaseStructure
{
    /**
     * 错误代码
     * @var string
     */
    public $code;
    
    /**
     * 代码说明
     * @var string
     */
    public $name;
    
    /**
     * 解决方式
     * @var string
     */
    public $desc;
    
    
    /**
     * ErrorCodeItem constructor.
     * @param string $code
     * @param string $name
     * @param string $desc
     */
    public function __construct($code = '', $name = '', $desc = '')
    {
        $this->setCode($code);
        $this->setName($name);
        $this->setDesc($desc);
    }
    
    
    /**
     * 设置解决方式
     * @param string $desc
     */
    public function setDesc(string $desc)
    {
        $this->desc = trim($desc);
    }
    
    
    /**
     * 设置错误代码
     * @param string $code
     */
    public function setCode(string $code)
    {
        $this->code = trim($code);
    }
    
    
    /**
     * 设置错误名称
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = trim($name);
    }
}