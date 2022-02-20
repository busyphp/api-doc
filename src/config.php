<?php
return [
    'docs' => [
        'api' => [
            // 文档名称
            'name'     => 'API接口文档',
            
            // 文档描述，支持markdown语法
            'desc'     => '`busyphp/api-doc` 一款方便快速书写接口文档的工具，开发人员可更专注于业务层，高效开发。',
            
            // 访问密码，不设置请留空
            'password' => '',
            
            // 接口入口地址，结尾要填写 "/"
            'url'      => [
                // 测试环境入口
                'debug'   => 'https://debug.domain.com/api/',
                
                // 正式环境入口
                'release' => 'https://www.domain.com/api/',
            ],
            
            // 公共请求头定义
            'headers'  => [
                [
                    // 参数名称
                    'name' => 'App-Auth-Key',
                    
                    // 是否必填
                    'must' => true,
                    
                    // 参数说明，支持markdown语法
                    'desc' => '全局通信秘钥'
                ]
            ],
            
            // 公共参数
            'params'   => [
                [
                    // 参数名称
                    'key'  => 'timestamp',
                    
                    // 数据类型
                    'type' => 'int',
                    
                    // 是否必填
                    'must' => true,
                    
                    // 参数说明，支持markdown语法
                    'desc' => '当前时间戳'
                ]
            ],
            
            // 响应结构
            'response' => [
                [
                    // 响应字段
                    'key'  => 'code',
                    
                    // 数据类型
                    'type' => 'int',
                    
                    // 响应说明，支持markdown语法
                    'desc' => '状态码，0为成功，非0失败'
                ]
            ],
            
            // 公共错误代码定义
            'codes'    => [
                [
                    // 错误代码
                    'code' => 4040001,
                    
                    // 错误说明
                    'name' => '密码错误',
                    
                    // 解决方案，支持markdown语法
                    'desc' => '请输入正确的密码'
                ]
            ],
            
            // 扩展文档
            'appendix' => [
                [
                    // 标题
                    'title'   => '附录1',
                    
                    // 内容，支持markdown语法
                    'content' => '附录`1`的内容'
                ]
            ],
            
            // 要扫描的类目录路径
            'dirs'     => [],
            
            // 要扫描的文件路径或类名
            'files'    => [],
            
            // 自定义处理闭包
            'handler'  => ''
        ]
    ]
];