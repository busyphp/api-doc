<?php

namespace BusyPHP\apidoc\structures;

use ArrayAccess;

/**
 * 基本结构类
 * @author busy^life <busy.life@qq.com>
 * @copyright (c) 2015--2019 ShanXi Han Tuo Technology Co.,Ltd. All rights reserved.
 * @version $Id: 2020/10/6 下午10:41 上午 BaseStructure.php $
 */
abstract class BaseStructure implements ArrayAccess
{
    public function offsetExists($offset)
    {
        return isset($this->$offset);
    }
    
    
    public function offsetGet($offset)
    {
        return $this->$offset ?? null;
    }
    
    
    public function offsetSet($offset, $value)
    {
        $this->$offset = $value;
    }
    
    
    public function offsetUnset($offset)
    {
        if (isset($this->$offset)) {
            $this->$offset = null;
        }
    }
}