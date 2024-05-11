<?php

class LoaiSanPham // Lớp LoaiSanPham đại diện cho đối tượng loại sản phẩm
{
    private $maloai;
    private $tenloai;

    // Hàm khởi tạo để thiết lập giá trị khi khởi tạo đối tượng
    public function __construct($maloai, $tenloai)
    {
        $this->maloai = $maloai;
        $this->tenloai = $tenloai;
    }

    // Các phương thức get, set
    public function getMaLoai()
    {
        return $this->maloai;
    }

    public function getTenLoai()
    {
        return $this->tenloai;
    }

    public function setMaLoai($maloai)
    {
        $this->maloai = $maloai;
    }

    public function setTenLoai($tenloai)
    {
        $this->tenloai = $tenloai;
    }
}
