<?php

namespace BusyPHP\apidoc\structs;

use think\Collection;

/**
 * 说明结构
 * @author busy^life <busy.life@qq.com>
 * @copyright (c) 2015--2021 ShanXi Han Tuo Technology Co.,Ltd. All rights reserved.
 * @version $Id: 2020/10/6 下午10:45 上午 DataStructure.php $
 */
class DataStructure extends BaseStructure
{
    /**
     * 说明文案
     * @var string
     */
    public $title;
    
    /**
     * 子结构
     * @var Collection
     */
    public $structure;
    
    
    /**
     * DataStructure constructor.
     * @param string     $title
     * @param Collection $structure
     */
    public function __construct($title = '', $structure = null)
    {
        $this->title     = $title;
        $this->structure = $structure ?? Collection::make();
    }
    
    
    /**
     * @param string $title
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
    }
    
    
    /**
     * @param Collection $structure
     */
    public function setStructure(Collection $structure)
    {
        $this->structure = $structure;
    }
}