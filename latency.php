<?php
    /*
    *    latency.php
    *    Author: Thesp
    *    Version: 2.0.1
    *    Returns:
    *       0 : Offline
    *       2 : Invalid URL
    *       3 : $S not set
    *       Anything else : latency in milliseconds
    */
    class hLatency {
        public function newPing($s) {
            if(isset($s) && $s != "") {
                $server = $this->clean($s);
                if($server == -2) {
                    return -2;
                }
                $r = $this->ping($server);
                if($r != -1) {
                    return $r;
                } else {
                    return -1;
                }
            } else {
                return -3;
            }
        }
        public function ping($server) {
            $str = exec("ping6 -c 3 -w 3 ".escapeshellarg($server), $ect, $result);
            $o = explode("/", $str);
            if($result == 0) {
                return $o[4];
            } else {
                return -1;
            }
        }
        public function clean($ip) {
            if (stristr($ip, "//")) {
                $start = strpos($ip, '//')+2;
                $length = strlen($ip) - $start;
                $output = substr($ip, $start,$length);
                $ip =  $output;
            }
            if (stristr($ip, "/")) {
                $end = strpos($ip, '/');
                $output = substr($ip, 0,$end);
                $ip =  $output;
            }
            $ip = str_replace(array("[","]"),"",$ip);
            $input = filter_var($ip, FILTER_SANITIZE_URL);
            $ip = $this->resolve($input);
            if ($ip == "NULL") {
                $ip =  $input;
            }
            if (strtolower(substr($ip, 0, 2)) === "fc") {
                return $ip;
            }
            return -2;
        }
        public function resolve($url) {
            if(isset($url)) {
                $result = dns_get_record($url, DNS_AAAA);
                if(!isset($result[0]['ipv6'])) {
                    return "NULL";
                }
                return $result[0]['ipv6'];
            } else {
                return "NULL";
            }
        }
    }
?>