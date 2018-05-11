<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index(Request $request)
    {
        $time = strtotime($request->date);
        if (empty($time)) return response(array('status' => false, 'message' => '日期格式错误'),  415);
        $timeEnd = $time + 24 * 60 * 60;
        $logs = $this->getLogs();
        if (empty($logs)) return response(array('status' => false, 'message' => '日志文件不存在'),  415);
        $result = $logs->where('time', '>', $time)->where('time', '<', $timeEnd);
        return $result;
    }

    public function getLogs()
    {
        $content = $this->content();
        if (empty($content)) return false;
        $logs = $this->dataMake($content);
        $logs = $this->LogArray($logs);
        return collect($logs);
    }

    public function content()
    {
        try {
            return file_get_contents(storage_path() . '/logs/' . 'laravel.log');
        } catch (\Exception $e) {
            return false;
        }
    }

    public function dataMake($content)
    {
        // 去除换行
        $content = trim(str_replace("\n", '', $content));
        // 去除空格
        $content = trim(str_replace("", '', $content));
        // 将字符串转为 单个错误的数组
        $arr = explode("\"}", $content);
        if (end($arr) === "") array_pop($arr);
        return $arr;
    }

    // 将单个日志转换为标准格式
    public function singleLog($log)
    {
        $log = trim($log);
        $index = strpos($log, ']');
        // 时间
        $time = substr($log, 0, $index + 1);
        $timeStamp = $this->time($time);
        $errInfo = str_replace($time, '', $log);
        $level = $this->errorLevel($errInfo);

        return array('time' => $timeStamp, 'level' => $level, 'log' => trim($log));
    }

    // 将总日志数组返回为标准格式
    public function LogArray(Array $logs)
    {
        $array = array();
        foreach ($logs as $log) {
            $array[] = $this->singleLog($log);
        }
        return $array;
    }

    // 获取单个错误的等级
    public function errorLevel($errInfo)
    {
        $index = strpos(trim($errInfo), ':');
        $level = substr($errInfo, 0, $index + 1);
        $level = str_replace('local.', '', $level);
        return trim($level);
    }

    public function time($time)
    {
        $str = str_replace('[', '', $time);
        $str = str_replace(']', '', $str);
        // 转成时间戳
        $time = strtotime($str);
        return $time;
    }
}
