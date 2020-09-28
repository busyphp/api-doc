<?php

namespace BusyPHP\apidoc;

use BusyPHP\apidoc\scan\Scan;
use Exception;
use think\Response;
use think\View;

/**
 * 接口文档类
 * @author busy^life <busy.life@qq.com>
 * @copyright (c) 2015--2019 ShanXi Han Tuo Technology Co.,Ltd. All rights reserved.
 * @version $Id: 2020/9/28 下午9:17 上午 ApiDoc.php $
 */
class ApiDoc
{
    /**
     * @var Scan
     */
    protected $scan;
    
    /**
     * @var array
     */
    protected $moduleList = [];
    
    
    /**
     * ApiDoc constructor.
     * @param mixed ...$files
     */
    public function __construct(...$files)
    {
        $fileList = [];
        foreach ($files as $file) {
            if (is_array($file)) {
                foreach ($file as $item) {
                    $fileList[] = $item;
                }
            } else {
                $fileList[] = $file;
            }
        }
        
        $this->scan = new Scan($fileList);
    }
    
    
    /**
     * 获取扫描器
     * @return Scan
     */
    public function getScan() : Scan
    {
        return $this->scan;
    }
    
    
    /**
     * 获取文档分组数据
     * @return array
     */
    public function getModuleList()
    {
        if (!$this->moduleList) {
            $this->moduleList = $this->scan->parse();
        }
        
        return $this->moduleList;
    }
    
    
    /**
     * @return string
     * @todo 渲染成MarkDown
     */
    public function renderToMarkDown()
    {
    }
    
    
    /**
     * 渲染成HTML
     * @param string $pageTitle
     * @return View
     */
    public function renderToHTML($pageTitle = 'API接口文档')
    {
        /** @var View $view */
        $view = Response::create(__DIR__ . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'template.html', 'view', 200);
        $view->assign('list', $this->getModuleList());
        $view->assign('page_title', $pageTitle);
        
        return $view;
    }
    
    
    public static function renderTree($data, $level = 1, $ttId = '')
    {
        try {
            return \think\facade\View::fetch(__DIR__ . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'tree_item.html', [
                'list'  => $data,
                'level' => $level,
                'tt_id' => $ttId,
            ]);
        } catch (Exception $e) {
            return '';
        }
    }
    
    
    /**
     * 解析span
     * @param $data
     * @return mixed
     */
    public static function renderSpan($data)
    {
        $data = preg_replace('/`(.*?)`/', '<span class="badge badge-pill badge-dark">\\1</span>', $data);
        
        return $data;
    }
}