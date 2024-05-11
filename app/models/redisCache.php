<?php

    class redisCache {
        private $redis;

        public function __construct() {
            $this -> redis = new Redis();
            $this -> redis -> connect('127.0.0.1', 6379);
        }

        public function set($key, $data) {
            $this -> redis -> set($key, json_encode($data));
            $this -> redis -> expire($key, 86400);
        }

        public function get($key) {
            $data = $this -> redis -> get($key);
            return json_decode($data, true);
        }

        public function delete($key) {
            $this -> redis -> del($key);
        }
    }