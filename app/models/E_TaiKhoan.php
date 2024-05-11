<?php

    class TaiKhoan {
        private $username;
        private $password;
        private $priv;

        // Hàm khởi tạo để thiết lập giá trị khi khởi tạo đối tượng
        public function __construct($username, $password, $priv) {
            $this -> username = $username;
            $this -> password = $password;
            $this -> priv = $priv;
        }

        // Các phương thức get, set
        public function getUsername() {
            return $this -> username;
        }

        public function getPassword() {
            return $this -> password;
        }

        public function getPriv() {
            return $this -> priv;
        }

        public function setUsername($username) {
            $this -> username = $username;
        }

        public function setPassword($password) {
            $this -> password = $password;
        }

        public function setPriv($priv) {
            $this -> priv = $priv;
        }
    }
