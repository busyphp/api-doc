<?php

namespace BusyPHP\apidoc\structs;

/**
 * 分组结构对象
 * @author busy^life <busy.life@qq.com>
 * @copyright (c) 2015--2019 ShanXi Han Tuo Technology Co.,Ltd. All rights reserved.
 * @version $Id: 2020/10/6 下午10:45 上午 GroupItem.php $
 */
class GroupItem extends BaseStructure
{
    /**
     * 分组名称
     * @var string
     */
    public $title;
    
    /**
     * 分组英文名称
     * @var string
     */
    public $module;
    
    /**
     * 分组ID
     * @var string
     */
    public $id;
    
    /**
     * 分组类名
     * @var string
     */
    public $class;
    
    /**
     * 接口集合
     * @var ApiList
     */
    public $list;
    
    /**
     * 原注释
     * @var string
     */
    public $comment;
    
    /**
     * 说明
     * @var string
     */
    public $desc;
}