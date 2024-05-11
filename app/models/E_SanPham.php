<?php

    class SanPham {
        private $masp;
        private $tensp;
        private $gia;
        private $soluong;
        private $hinhanh;
        private $maloai;
        private $makt = [];
        private $mamau = [];

        // Hàm khởi tạo để thiết lập giá trị khi khởi tạo đối tượng
        public function __construct($masp, $tensp, $gia, $soluong, $hinhanh = null, $maloai) {
            $this -> masp = $masp;
            $this -> tensp = $tensp;
            $this -> gia = $gia;
            $this -> soluong = $soluong;
            $this -> hinhanh = $hinhanh;
            $this -> maloai = $maloai;
        }

        // Các phương thức get, set
        public function getMaSP() {
            return $this -> masp;
        }

        public function getTenSP() {
            return $this -> tensp;
        }

        public function getGia() {
            return $this -> gia;
        }

        public function getSoLuong() {
            return $this -> soluong;
        }

        public function getHinhAnh() {
            return $this -> hinhanh;
        }

        public function getMaLoai() {
            return $this -> maloai;
        }

        public function getMaKT() {
            return $this -> makt;
        }

        public function getMaMau() {
            return $this -> mamau;
        }

        public function setMaSP($masp) {
            $this -> masp = $masp;
        }

        public function setTenSP($tensp) {
            $this -> tensp = $tensp;
        }

        public function setGia($gia) {
            $this -> gia = $gia;
        }

        public function setSoLuong($soluong) {
            $this -> soluong = $soluong;
        }

        public function setHinhAnh($hinhanh) {
            $this -> hinhanh = $hinhanh;
        }

        public function setMaLoai($maloai) {
            $this -> maloai = $maloai;
        }

        public function setMaKT($makt) {
            $this -> makt = $makt;
        }

        public function setMaMau($mamau) {
            $this -> mamau = $mamau;
        }

        public function addMaKT($makt) {
            $this -> makt[] = $makt;
        }

        public function addMaMau($mamau) {
            $this -> mamau[] = $mamau;
        }

        public function removeMaKT($makt) {
            $index = array_search($makt, $this -> makt);
            if ($index !== false) {
                unset($this -> makt[$index]);
            }
        }

        public function removeMaMau($mamau) {
            $index = array_search($mamau, $this -> mamau);
            if ($index !== false) {
                unset($this -> mamau[$index]);
            }
        }
    }
