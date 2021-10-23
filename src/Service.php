<?php

namespace BusyPHP\apidoc;

use BusyPHP\helper\FileHelper;
use think\Route;

/**
 * 服务类
 * @author busy^life <busy.life@qq.com>
 * @copyright (c) 2015--2021 ShanXi Han Tuo Technology Co.,Ltd. All rights reserved.
 * @version $Id: 2021/10/24 下午上午12:37 Service.php $
 */
class Service extends \think\Service
{
    public function boot()
    {
        $this->registerRoutes(function(Route $route) {
            // 资源路由
            $route->rule('assets/plugins/apidoc/<path>', function($path) {
                $parse = parse_url($path);
                $path  = $parse['path'] ?? '';
                
                return FileHelper::responseAssets(__DIR__ . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'static' . DIRECTORY_SEPARATOR . ltrim($path, '/'));
            })->pattern(['path' => '.*']);
        });
    }
}
