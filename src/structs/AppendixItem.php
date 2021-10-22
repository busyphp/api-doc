<?php

namespace BusyPHP\apidoc\structs;

/**
 * 附录结构
 * @author busy^life <busy.life@qq.com>
 * @copyright (c) 2015--2019 ShanXi Han Tuo Technology Co.,Ltd. All rights reserved.
 * @version $Id: 2020/10/6 下午10:45 上午 AppendixItem.php $
 */
class AppendixItem extends BaseStructure
{
    /**
     * 附录名称
     * @var string
     */
    public $name;
    
    /**
     * 附录内容
     * @var string
     */
    public $content;
    
    /**
     * ID
     * @var string
     */
    public $id;
    
    
    /**
     * AppendixItem constructor.
     * @param string $name
     * @param string $content
     */
    public function __construct($name = '', $content = '')
    {
        $this->setName($name);
        $this->setContent($content);
        $this->setId(md5($this->name . $this->content));
    }
    
    
    /**
     * 设置附录名称
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = trim($name);
    }
    
    
    /**
     * 设置附录内容
     * @param string $content
     */
    public function setContent(string $content)
    {
        $this->content = trim($content);
    }
    
    
    /**
     * @param string $id
     */
    public function setId(string $id)
    {
        $this->id = $id;
    }
}