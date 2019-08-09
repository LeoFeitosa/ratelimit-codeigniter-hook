<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class ratelimit
{
    public function limit_all()
    {
        $CI = &get_instance();
        $max_requests = 100;
        $sec = 300;
        $ip = $CI->input->ip_address();

        $free_ips = array(
            '127.0.0.1'
        );

        // check if request ip is not in whitelist
        if (!in_array($ip, $free_ips)) {

            $CI->load->driver('cache', array('adapter' => 'file'));
            $cache_key = $ip . "_key";
            $cache_remain_time = $ip . "_tmp";
            $current_time = date("Y-m-d H:i:s");
            // if it's first request
            if ($CI->cache->get($cache_key) === false) {
                $current_time_plus = date("Y-m-d H:i:s", strtotime("+" . $sec . " seconds"));
                $CI->cache->save($cache_key, 1, $sec);
                $CI->cache->save($cache_remain_time, $current_time_plus, $sec * 2);
            } else {
                $requests = $CI->cache->get($cache_key);
                $time_lost = $CI->cache->get($cache_remain_time);
                if ($current_time > $time_lost) {
                    // as first request
                    $current_time_plus = date("Y-m-d H:i:s", strtotime("+" . $sec . " seconds"));
                    $CI->cache->save($cache_key, 1, $sec);
                    $CI->cache->save($cache_remain_time, $current_time_plus, $sec * 2);
                } else {
                    $CI->cache->save($cache_key, $requests + 1, $sec);
                }
                $requests = $CI->cache->get($cache_key);
                if ($requests > $max_requests) {
                    header("HTTP/1.0 429 Too Many Requests");
                    exit;
                }
            }
        }
    }
}
