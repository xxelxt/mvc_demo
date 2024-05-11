<?php

    class MauSac {
        private $mamau;
        private $tenmau;

        // Hàm khởi tạo để thiết lập giá trị khi khởi tạo đối tượng
        public function __construct($mamau, $tenmau) {
            $this -> mamau = $mamau;
            $this -> tenmau = $tenmau;
        }

        // Các phương thức get, set
        public function getMaMau() {
            return $this -> mamau;
        }

        public function getTenMau() {
            return $this -> tenmau;
        }

        public function setMaMau($mamau) {
            $this -> mamau = $mamau;
        }

        public function setTenMau($tenmau) {
            $this -> tenmau = $tenmau;
        }
    }
