<?php

namespace BusyPHP\apidoc;

use BusyPHP\apidoc\scan\Scan;
use BusyPHP\apidoc\structs\InfoStructure;
use BusyPHP\apidoc\test\Test;
use BusyPHP\App;
use Exception;
use Parsedown;
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
     * 接口扫描器
     * @var Scan
     */
    protected $scan;
    
    /**
     * 接口数据
     * @var array
     */
    protected $moduleList = [];
    
    /**
     * 页面数据
     * @var InfoStructure
     */
    protected $info;
    
    
    /**
     * ApiDoc constructor.
     * @param mixed ...$files
     */
    public function __construct(...$files)
    {
        if (App::getInstance()->request->isAjax()) {
            $test = new Test();
            $test->exec();
        }
        
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
        $this->info = new InfoStructure();
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
     * 获取信息
     * @return InfoStructure
     */
    public function getInfo() : InfoStructure
    {
        return $this->info;
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
     * 渲染成HTML
     * @param string $pageTitle
     * @return View
     */
    public function renderToHTML($pageTitle = 'API接口文档')
    {
        $pageTitle = $this->info->title ?: $pageTitle;
        
        if (!$this->info->releaseRootUrl) {
            $this->info->releaseRootUrl = $this->info->debugRootUrl;
        }
        if (!$this->info->debugRootUrl) {
            $this->info->debugRootUrl = $this->info->releaseRootUrl;
        }
        
        /** @var View $view */
        $view = Response::create(__DIR__ . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'template.html', 'view', 200);
        $view->assign('list', $this->getModuleList());
        $view->assign('page_title', $pageTitle);
        $view->assign('print', request()->get('print') > 0);
        $view->assign('info', $this->info);
        
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
    public static function parseToMarkdownHtml($data)
    {
        $data = preg_replace('/`(.*?)`/', '<span class="badge badge-pill badge-dark">\\1</span>', $data);
        $data = Parsedown::instance()->line($data);
        
        return $data;
    }
    
    
    /**
     * 解析无html字符
     * @param $data
     * @return string
     */
    public static function parseNoHtml($data)
    {
        $data = preg_replace('/`(.*?)`/', '\\1', $data);
        $data = str_replace('"', '′', $data);
        $data = str_replace('<', '[', $data);
        $data = str_replace('>', ']', $data);
        $data = str_replace('\'', '′', $data);
        $data = str_replace(["\r", "\n", "\t"], ' ', $data);
        
        return $data;
    }
}