<?php

namespace BusyPHP\apidoc\scan;

use ReflectionClass;
use ReflectionException;
use ReflectionMethod;

/**
 * 扫描文档
 * @author busy^life <busy.life@qq.com>
 * @copyright (c) 2015--2019 ShanXi Han Tuo Technology Co.,Ltd. All rights reserved.
 * @version $Id: 2020/9/28 下午9:33 上午 ApiDocScan.php $
 */
class Scan
{
    /**
     * @var array
     */
    protected $classList = [];
    
    /**
     * 移除控制器后缀
     * @var string
     */
    protected $controllerSuffix = '';
    
    /**
     * 移除方法后缀
     * @var string
     */
    protected $methodSuffix = '';
    
    
    /**
     * Scan constructor.
     * @param $files
     */
    public function __construct($files)
    {
        $classList = [];
        foreach ($files as $file) {
            if (is_file($file)) {
                if ($class = self::fileToClass($file)) {
                    $classList[] = $class;
                }
            } else if (class_exists($file)) {
                $classList[] = $file;
            }
        }
        
        $this->classList = $classList;
    }
    
    
    /**
     * 移除后缀
     * @param string|callable $controller
     * @param string|callable $method
     */
    public function removeSuffix($controller = '', $method = '')
    {
        $this->controllerSuffix = $controller;
        $this->methodSuffix     = $method;
    }
    
    
    /**
     * 执行解析
     * @return array
     */
    public function parse()
    {
        $list = [];
        foreach ($this->classList as $class) {
            $item = $this->parseClass($class);
            if (!$item) {
                continue;
            }
            $list[] = $item;
        }
        
        return $list;
    }
    
    
    /**
     * 解析类
     * @param $class
     * @return array|bool
     */
    protected function parseClass($class)
    {
        try {
            $reflectionClass = new ReflectionClass($class);
            $list            = [];
            foreach ($reflectionClass->getMethods() as $method) {
                $item = $this->parseMethod($method);
                if (!$item) {
                    continue;
                }
                $list[] = $item;
            }
            
            if (!$list) {
                return false;
            }
            
            // 取分组名称
            $title        = '';
            $classComment = $reflectionClass->getDocComment();
            $classComment = str_replace('/**', '', $classComment);
            $classComment = str_replace('*/', '', $classComment);
            $classComment = str_replace('*', '', $classComment);
            if (false === strpos($classComment, '@')) {
                $title = trim($classComment);
            } else {
                if (preg_match('/(.*?)@.*/s', $classComment, $match)) {
                    $title = trim($match[1]);
                }
            }
            
            $controllerName = $this->parseControllerName($reflectionClass->getShortName(), $reflectionClass->getName());
            
            return [
                'title'   => $title,
                'module'  => $controllerName,
                'id'      => $controllerName,
                'class'   => $reflectionClass->getName(),
                'list'    => $list,
                'comment' => $reflectionClass->getDocComment()
            ];
        } catch (ReflectionException $e) {
            return false;
        }
    }
    
    
    /**
     * 解析方法
     * @param ReflectionMethod $method
     * @return array|false
     */
    protected function parseMethod(ReflectionMethod $method)
    {
        if (!$method->isPublic()) {
            return false;
        }
        
        
        // 判断是否标准注释
        $comment = $method->getDocComment();
        if (!preg_match('/^\/\*\*.*?@api.*?\*\/$/s', $comment)) {
            return false;
        }
        
        // 移除注释符号
        $comment = preg_replace('/\*(\w+)/', '!!!!!!\\1', $comment); // 必填替换
        $comment = str_replace('/**', '', $comment);
        $comment = str_replace('*/', '', $comment);
        $comment = str_replace('*', '', $comment);
        $comment = str_replace('#####', '*5', $comment);
        $comment = str_replace('####', '*4', $comment);
        $comment = str_replace('###', '*3', $comment);
        $comment = str_replace('##', '*2', $comment);
        $comment = str_replace('#', '*1', $comment);
        
        // 取接口名称
        $title = '';
        if (preg_match('/(.*?)@.*/s', $comment, $match)) {
            $title = trim($match[1]);
        }
        
        // 取出params、api、return
        $list    = explode('@', $comment);
        $params  = [];
        $return  = '';
        $type    = '';
        $example = '';
        foreach ($list as $item) {
            $item = trim($item);
            if (0 === stripos($item, 'return ')) {
                $return = $item;
            } elseif (0 === stripos($item, 'param ')) {
                $params[] = trim($item);
            } elseif (0 === stripos($item, 'api ')) {
                $type = $item;
            } elseif (0 === stripos($item, 'example ')) {
                $example = $item;
            }
        }
        
        // 请求方式
        $type = trim(substr($type, 4));
        $type = $type ?: 'get';
        
        // 输出示例
        $example        = trim(substr($example, 8));
        $controllerName = $this->parseControllerName($method->getDeclaringClass()->getShortName(), $method->getDeclaringClass());
        $methodName     = $this->parseMethodName($method->getName(), $method->getDeclaringClass());
        
        return [
            'title'   => $title,
            'path'    => $controllerName . '/' . $methodName,
            'id'      => $controllerName . '_' . $methodName,
            'type'    => strtoupper($type),
            'params'  => $this->parseParams($params),
            'return'  => $this->parseStructure(substr($return, 7)),
            'example' => $example,
            'comment' => $comment
        ];
    }
    
    
    protected function parseControllerName($name, $class)
    {
        if (!$this->controllerSuffix) {
            return $name;
        }
        
        if (is_callable($this->controllerSuffix)) {
            return call_user_func_array($this->controllerSuffix, [$name, $class]);
        } else {
            return substr($name, 0, -strlen($this->controllerSuffix));
        }
    }
    
    
    protected function parseMethodName($name, $class)
    {
        if (!$this->methodSuffix) {
            return $name;
        }
        
        if (is_callable($this->methodSuffix)) {
            return call_user_func_array($this->methodSuffix, [$name, $class]);
        } else {
            return substr($name, 0, -strlen($this->methodSuffix));
        }
    }
    
    
    /**
     * 解析请求参数
     * @param $params
     * @return array
     */
    protected function parseParams($params)
    {
        $list = [];
        foreach ($params as $item) {
            $item = substr(trim($item), 6);
            if (!preg_match('/(.*?)\s+(.*?)\s+(.*)/s', $item, $match)) {
                continue;
            }
            
            $key  = trim($match[2]);
            $must = false;
            if (0 === strpos($key, '!!!!!!')) {
                $key  = substr($key, 6);
                $must = true;
            }
            
            $list[] = [
                'key'  => $key,
                'must' => $must,
                'type' => strtolower(trim($match[1])),
                'data' => $this->parseStructure($match[3], 1, false)
            ];
        }
        
        return $list;
    }
    
    
    /**
     * 解析结构
     * @param string $content
     * @param int    $level
     * @param bool   $hasType
     * @return array
     */
    protected function parseStructure($content, $level = 1, $hasType = true)
    {
        if (preg_match('/(.*?)\[(.*]?)]/s', $content, $match)) {
            $returnType      = $match[1];
            $returnStructure = $match[2];
        } else {
            $returnType      = $content;
            $returnStructure = '';
        }
        
        $returnType      = trim($returnType);
        $returnStructure = trim($returnStructure);
        
        return [
            'title'     => $returnType,
            'structure' => $hasType ? $this->parseStructureItems($returnStructure, $level) : $this->parseParamStructureItems($returnStructure, $level)
        ];
    }
    
    
    protected function parseParamStructureItems($content, $level)
    {
        if ($level > 2) {
            return [];
        }
        
        $content = explode("*{$level}", $content);
        $content = $content ?? [];
        
        $list = [];
        foreach ($content as $item) {
            $item = trim($item);
            if (!$item) {
                continue;
            }
            
            if (!preg_match('/(.*?):(.*)/s', $item, $match)) {
                continue;
            }
            
            $key  = trim($match[1]);
            $desc = trim($match[2]);
            if (false !== strpos($desc, '[')) {
                $data = $this->parseStructure($desc, $level + 1);
            } else {
                $data = [
                    'title'     => $desc,
                    'structure' => []
                ];
            }
            
            $list[] = [
                'key'  => $key,
                'data' => $data
            ];
        }
        
        return $list;
    }
    
    
    /**
     * 解析结构items
     * @param string $content
     * @param int    $level
     * @return array
     */
    protected function parseStructureItems($content, $level)
    {
        if ($level > 5) {
            return [];
        }
        
        $content = explode("*{$level}", $content);
        $content = $content ?? [];
        
        $list = [];
        foreach ($content as $item) {
            $item = trim($item);
            if (!$item) {
                continue;
            }
            
            if (!preg_match('/(.*?)\s+(.*?):(.*)/s', $item, $match)) {
                continue;
            }
            
            $key  = trim($match[1]);
            $type = strtolower(trim($match[2]));
            $desc = trim($match[3]);
            if (in_array($type, ['object', 'array'])) {
                $data = $this->parseStructure($desc, $level + 1);
            } else {
                $data = [
                    'title'     => $desc,
                    'structure' => []
                ];
            }
            
            $list[] = [
                'key'  => $key,
                'type' => $type,
                'data' => $data
            ];
        }
        
        return $list;
    }
    
    
    /**
     * 将文件转换成类
     * @param $file
     * @return false|string
     */
    public static function fileToClass($file)
    {
        $content = file_get_contents($file);
        
        // 取namespace
        if (!preg_match('/<\?php.*?namespace\s*(.*?);/is', $content, $match)) {
            return false;
        }
        $namespace = trim($match[1] ?? '');
        if (!$namespace) {
            return false;
        }
        
        // 取类名称
        $className = trim(pathinfo($file, PATHINFO_FILENAME));
        if (!$className) {
            return false;
        }
        
        // 类名
        $class = $namespace . '\\' . $className;
        if (!class_exists($class)) {
            return false;
        }
        
        return $class;
    }
}