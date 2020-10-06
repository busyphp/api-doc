<?php

namespace BusyPHP\apidoc\structures;

/**
 * 接口参数对象
 * @author busy^life <busy.life@qq.com>
 * @copyright (c) 2015--2019 ShanXi Han Tuo Technology Co.,Ltd. All rights reserved.
 * @version $Id: 2020/10/6 下午10:45 上午 ApiItem.php $
 */
class ApiItem extends BaseStructure
{
    /**
     * 接口名称
     * @var string
     */
    public $title;
    
    /**
     * 接口路径
     * @var string
     */
    public $path;
    
    /**
     * 接口ID
     * @var string
     */
    public $id;
    
    /**
     * 接口类型
     * @var string
     */
    public $type;
    
    /**
     * 接口参数
     * @var ParamList
     */
    public $params;
    
    /**
     * 返回参数
     * @var ReturnList
     */
    public $return;
    
    /**
     * 返回示例
     * @var string
     */
    public $example;
    
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