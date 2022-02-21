<?php

namespace BusyPHP\apidoc\app\controller;

use BusyPHP\apidoc\ApiDoc;
use BusyPHP\apidoc\structs\AppendixItem;
use BusyPHP\apidoc\structs\DataStructure;
use BusyPHP\apidoc\structs\ErrorCodeItem;
use BusyPHP\apidoc\structs\HeaderItem;
use BusyPHP\apidoc\structs\InfoStructure;
use BusyPHP\apidoc\structs\ParamItem;
use BusyPHP\apidoc\structs\ReturnItem;
use BusyPHP\Controller;
use BusyPHP\helper\TripleDesHelper;
use Closure;
use RuntimeException;
use think\exception\HttpResponseException;
use think\facade\Cookie;

/**
 * IndexController
 * @author busy^life <busy.life@qq.com>
 * @copyright (c) 2015--2022 ShanXi Han Tuo Technology Co.,Ltd. All rights reserved.
 * @version $Id: 2022/2/20 4:14 PM IndexController.php $
 */
class IndexController extends Controller
{
    const SECRET = '1sdfa3241HU@#*(jfasdfsa2';
    
    
    public function index()
    {
        $docName = $this->post('doc_name/s', 'trim');
        if (!$docName) {
            $docName = $this->param('doc_name/s', 'trim');
        }
        // 验证配置
        if (!$docName) {
            $docs = $this->getConfig('');
            foreach ($docs as $key => $config) {
                $docName = $key;
                break;
            }
        }
        
        // 验证密码
        $pageTitle = $this->getDocConfig($docName, 'name') ?: 'API接口文档';
        $password  = trim($this->getDocConfig($docName, 'password') ?: '');
        if ($password !== '' && !$this->checkLogin($password, $docName)) {
            $this->assign('page_title', $pageTitle);
            $this->assign('doc_name', $docName);
            
            return $this->display(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'index.html');
        }
        
        
        if (!$this->getConfig($docName)) {
            throw new RuntimeException('请配置 config/busy-apidoc.php');
        }
        
        
        $files = [];
        foreach ((array) ($this->getDocConfig($docName, 'dirs') ?: []) as $dir) {
            $dir = rtrim($dir, '\\') . DIRECTORY_SEPARATOR;
            $dir = rtrim($dir, '/') . DIRECTORY_SEPARATOR;
            if (!is_dir($dir)) {
                continue;
            }
            
            foreach ((array) glob("$dir*.php") as $file) {
                if (!is_file($file)) {
                    continue;
                }
                
                $files[] = $file;
            }
        }
        
        foreach ((array) ($this->getDocConfig($docName, 'files') ?: []) as $file) {
            $files[] = $file;
        }
        
        /** @var ApiDoc $doc */
        $doc  = $this->app->make(ApiDoc::class, [$files]);
        $info = $doc->getInfo();
        $this->app->bind(InfoStructure::class, $info);
        
        // 入口地址
        if ($debugRoot = $this->getDocConfig($docName, 'url.debug')) {
            if ($debugRoot instanceof Closure) {
                $debugRoot = $this->app->invokeFunction($debugRoot);
            }
            $info->setDebugRootUrl($debugRoot);
        }
        if ($releaseRoot = $this->getDocConfig($docName, 'url.release')) {
            if ($releaseRoot instanceof Closure) {
                $releaseRoot = $this->app->invokeFunction($releaseRoot);
            }
            $info->setReleaseRootUrl($releaseRoot);
        }
        
        // 描述
        $description = $this->getDocConfig($docName, 'desc');
        if ($description instanceof Closure) {
            $description = $this->app->invokeFunction($description);
        }
        if ($description) {
            $info->setDescription($description);
        }
        
        // 公共头
        $headers = $this->getDocConfig($docName, 'headers') ?: [];
        if ($headers instanceof Closure) {
            $headers = $this->app->invokeFunction($headers);
        }
        foreach ((array) $headers as $item) {
            if ($item instanceof HeaderItem) {
                $info->addHeaderItem($item);
            } else {
                $name = trim($item['name'] ?? '');
                if (!$name) {
                    continue;
                }
                
                $info->addHeader($name, $item['must'] ?? false, $item['desc'] ?? '');
            }
        }
        
        // 错误码
        $codes = $this->getDocConfig($docName, 'codes') ?: [];
        if ($codes instanceof Closure) {
            $codes = $this->app->invokeFunction($codes);
        }
        foreach ((array) $codes as $item) {
            if ($item instanceof ErrorCodeItem) {
                $info->addErrorCodeItem($item);
            } else {
                $code = trim($item['code'] ?? '');
                if ($code === '') {
                    continue;
                }
                $info->addErrorCode($code, $item['name'] ?? '', $item['desc'] ?? '');
            }
        }
        
        // 全局参数
        $params = $this->getDocConfig($docName, 'params') ?: [];
        if ($params instanceof Closure) {
            $params = $this->app->invokeFunction($params);
        }
        foreach ((array) $params as $item) {
            if ($item instanceof ParamItem) {
                $info->addParam($item);
            } else {
                $key = trim($item['key'] ?? '');
                if (!$key) {
                    continue;
                }
                $desc = $item['desc'] ?? '';
                if ($desc instanceof DataStructure) {
                    $data = $desc;
                } else {
                    $data = new DataStructure($desc);
                }
                $info->addParam(new ParamItem($key, $item['type'] ?? 'string', $item['must'] ?? false, $data));
            }
        }
        
        // 响应结构
        $responses = $this->getDocConfig($docName, 'response') ?: [];
        if ($responses instanceof Closure) {
            $responses = $this->app->invokeFunction($responses);
        }
        foreach ((array) $responses as $item) {
            if ($item instanceof ReturnItem) {
                $info->addReturn($item);
            } else {
                $key = trim($item['key'] ?? '');
                if (!$key) {
                    continue;
                }
                $desc = $item['desc'] ?? '';
                if ($desc instanceof DataStructure) {
                    $data = $desc;
                } else {
                    $data = new DataStructure($desc);
                }
                $info->addReturn(new ReturnItem($key, $item['type'] ?? 'string', $data));
            }
        }
        
        
        // 扩展文档
        $appendix = $this->getDocConfig($docName, 'appendix') ?: [];
        if ($appendix instanceof Closure) {
            $appendix = $this->app->invokeFunction($appendix);
        }
        foreach ((array) $appendix as $item) {
            if ($item instanceof AppendixItem) {
                $info->addAppendix($item);
            } else {
                $title = trim($item['title'] ?? '');
                if (!$title) {
                    continue;
                }
                $content = $item['content'] ?? '';
                if ($content instanceof Closure) {
                    $content = $this->app->invokeFunction($content, [$title]);
                }
                $info->addAppendix(new AppendixItem($title, $content));
            }
        }
        
        $handle = $this->getDocConfig($docName, 'handler');
        if ($handle instanceof Closure) {
            $this->app->invokeFunction($handle);
        }
        
        return $doc->renderToHTML($docName, $pageTitle);
    }
    
    
    /**
     * 获取配置
     * @param string $name
     * @param mixed  $default
     * @return mixed
     */
    protected function getConfig(string $name, $default = null)
    {
        return $this->app->config->get("busy-apidoc.docs" . ($name ? ".$name" : ''), $default);
    }
    
    
    /**
     * 获取doc配置
     * @param string $name
     * @param string $key
     * @param mixed  $default
     * @return mixed
     */
    protected function getDocConfig(string $name, string $key, $default = null)
    {
        return $this->getConfig("$name.$key", $default);
    }
    
    
    /**
     * 校验登录
     * @param string $password
     * @param string $name
     * @return bool
     */
    protected function checkLogin(string $password, string $name) : bool
    {
        if ($this->param('__api_doc_root__')) {
            return true;
        }
        
        if ($this->isPost()) {
            $inputPwd = $this->post('password/s', 'trim');
            if (!$inputPwd) {
                throw new HttpResponseException($this->json([
                    'status' => false,
                    'msg'    => '请输入密码'
                ]));
            }
            
            if ($password !== $inputPwd) {
                throw new HttpResponseException($this->json([
                    'status' => false,
                    'msg'    => '密码错误'
                ]));
            }
            
            Cookie::set(
                'api_doc_auth_' . $name,
                TripleDesHelper::encrypt(md5($password) . '@' . time(), self::SECRET),
                86400 * 365
            );
            
            throw new HttpResponseException($this->json([
                'status' => true,
                'msg'    => ''
            ]));
        }
        
        $auth = Cookie::get('api_doc_auth_' . $name, '');
        if (!$auth) {
            return false;
        }
        $auth = TripleDesHelper::decrypt($auth, self::SECRET);
        $auth = explode('@', $auth);
        $pwd  = $auth[0] ?? '';
        if ($pwd != md5($password)) {
            return false;
        }
        
        return true;
    }
}