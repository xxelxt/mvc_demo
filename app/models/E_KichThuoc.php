<?php

    class KichThuoc {
        private $makt;
        private $tenkt;

        // Hàm khởi tạo để thiết lập giá trị khi khởi tạo đối tượng
        public function __construct($makt, $tenkt) {
            $this -> makt = $makt;
            $this -> tenkt = $tenkt;
        }

        // Các phương thức get, set
        public function getMaKT() {
            return $this -> makt;
        }

        public function getTenKT() {
            return $this -> tenkt;
        }

        public function setMaKT($makt) {
            $this -> makt = $makt;
        }

        public function setTenKT($tenkt) {
            $this -> tenkt = $tenkt;
        }
    }
