<?php

namespace BusyPHP\apidoc\structs;

/**
 * 接口信息结构对象
 * @author busy^life <busy.life@qq.com>
 * @copyright (c) 2015--2021 ShanXi Han Tuo Technology Co.,Ltd. All rights reserved.
 * @version $Id: 2020/10/6 下午10:45 上午 InfoStructure.php $
 */
class InfoStructure extends BaseStructure
{
    /**
     * 接口入口
     * @var string
     */
    public $debugRootUrl;
    
    /**
     * 正式入口
     * @var string
     */
    public $releaseRootUrl;
    
    /**
     * 文档标题
     * @var string
     */
    public $title;
    
    /**
     * 文档说明
     * @var string
     */
    public $description;
    
    /**
     * @var ParamList
     */
    public $paramList;
    
    /**
     * @var ReturnList
     */
    public $returnList;
    
    /**
     * 公共请求头
     * @var HeaderList
     */
    public $headerList;
    
    /**
     * 公共错误代码
     * @var ErrorCodeList
     */
    public $errorCodeList;
    
    /**
     * 附录列表
     * @var AppendixList
     */
    public $appendixList;
    
    
    public function __construct()
    {
        $this->headerList    = HeaderList::make();
        $this->returnList    = ReturnList::make();
        $this->paramList     = ParamList::make();
        $this->errorCodeList = ErrorCodeList::make();
        $this->appendixList  = AppendixList::make();
    }
    
    
    /**
     * 设置文档标题
     * @param string $title
     */
    public function setTitle(string $title)
    {
        $this->title = trim($title);
    }
    
    
    /**
     * 设置测试模式接口入口
     * @param string $debugRootUrl
     */
    public function setDebugRootUrl(string $debugRootUrl)
    {
        $this->debugRootUrl = rtrim($debugRootUrl, '/') . '/';
    }
    
    
    /**
     * 设置发布模式接口入口
     * @param string $releaseRootUrl
     */
    public function setReleaseRootUrl(string $releaseRootUrl)
    {
        $this->releaseRootUrl = rtrim($releaseRootUrl, '/') . '/';
    }
    
    
    /**
     * 设置文档说明
     * @param string $description
     */
    public function setDescription(string $description)
    {
        $this->description = trim($description);
    }
    
    
    /**
     * 设置全局参数
     * @param ParamList $paramList
     */
    public function setParamList(ParamList $paramList)
    {
        $this->paramList = $paramList;
    }
    
    
    /**
     * 添加全局请求参数
     * @param ParamItem $item
     */
    public function addParam(ParamItem $item)
    {
        $this->paramList->push($item);
    }
    
    
    /**
     * 设置全局返回参数
     * @param ReturnList $returnList
     */
    public function setReturnList(ReturnList $returnList)
    {
        $this->returnList = $returnList;
    }
    
    
    /**
     * 添加返回参数
     * @param ReturnItem $item
     */
    public function addReturn(ReturnItem $item)
    {
        $this->returnList->push($item);
    }
    
    
    /**
     * 设置全局请求头
     * @param HeaderList $headerList
     */
    public function setHeaderList(HeaderList $headerList)
    {
        $this->headerList = $headerList;
    }
    
    
    /**
     * 添加全局请求头
     * @param string $key 参数
     * @param bool   $must 是否必传
     * @param string $desc 参数说明
     */
    public function addHeader($key, $must, $desc)
    {
        $this->headerList->push(new HeaderItem($key, $must, $desc));
    }
    
    
    /**
     * 设置错误代码
     * @param ErrorCodeList $errorCodeList
     */
    public function setErrorCodeList(ErrorCodeList $errorCodeList)
    {
        $this->errorCodeList = $errorCodeList;
    }
    
    
    /**
     * 添加错误代码
     * @param string $code 错误代码
     * @param string $name 错误说明
     * @param string $desc 解决方案
     */
    public function addErrorCode($code, $name, $desc)
    {
        $this->errorCodeList->push(new ErrorCodeItem($code, $name, $desc));
    }
    
    
    /**
     * 设置附录列表
     * @param AppendixList $appendixList
     */
    public function setAppendixList(AppendixList $appendixList)
    {
        $this->appendixList = $appendixList;
    }
    
    
    /**
     * 添加附录
     * @param AppendixItem $item
     */
    public function addAppendix(AppendixItem $item)
    {
        $this->appendixList->push($item);
    }
}