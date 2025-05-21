<?php
$filepath = realpath(dirname(__FILE__));
include_once($filepath . '/../lib/database.php');
include_once($filepath . '/../helpers/format.php');

class discount
{
    private $db;
    private $fm;

    public function __construct()
    {
        $this->db = new Database();
        $this->fm = new Format();
    }

    // ✅ Thêm mã giảm giá
    public function insertDiscount($data)
    {
        $coupon_name = mysqli_real_escape_string($this->db->link, $data["coupon_name"]);
        $coupon_time = mysqli_real_escape_string($this->db->link, $data["coupon_time"]);
        $coupon_conditions = mysqli_real_escape_string($this->db->link, $data["coupon_conditions"]);
        $coupon_number = mysqli_real_escape_string($this->db->link, $data["coupon_number"]);
        $coupon_code = mysqli_real_escape_string($this->db->link, $data["coupon_code"]);

        if ($coupon_name == "" || $coupon_time == "" || $coupon_number == "" || $coupon_code == "") {
            return "Vui lòng nhập đầy đủ thông tin";
        }

        $sql = "INSERT INTO tbl_discount(coupon_name, coupon_time, coupon_conditions, coupon_number, coupon_code) 
                VALUES ('$coupon_name','$coupon_time','$coupon_conditions','$coupon_number','$coupon_code')";

        $result = $this->db->insert($sql);
        if ($result) {
            Session::set('coupon_id', true); // Sửa lỗi: bỏ dấu cách trong key
            return "Thêm thành công";
        } else {
            return "Thêm không thành công";
        }
    }

    // ✅ Lấy danh sách mã giảm giá
    public function showDiscount()
    {
        $sql = "SELECT * FROM tbl_discount ORDER BY coupon_id DESC";
        return $this->db->select($sql);
    }

    // ✅ Xóa mã giảm giá
    public function del_Discount($id)
    {
        $query = "DELETE FROM tbl_discount WHERE coupon_id = '$id'";
        $result = $this->db->delete($query);
        if ($result) {
            return "<span class='success'>Xóa thành công</span>";
        } else {
            return "<span class='error'>Xóa không thành công</span>";
        }
    }

    // ✅ Tìm mã giảm giá theo coupon_code (dành cho thanhtoan.php)
    public function checkCoupon($code)
    {
        $code = mysqli_real_escape_string($this->db->link, $code);
        $query = "SELECT * FROM tbl_discount WHERE coupon_code = '$code' AND coupon_time > 0";
        return $this->db->select($query);
    }

    // ❌ Cũ: getDiscountByID sai cú pháp và thừa dấu cách trong điều kiện
    // Có thể xóa nếu không dùng
    public function getDiscountByID($coupon_code)
    {
        $code = mysqli_real_escape_string($this->db->link, $coupon_code);
        $query = "SELECT * FROM tbl_discount WHERE coupon_code = '$code'";
        return $this->db->select($query);
    }
}
?>
