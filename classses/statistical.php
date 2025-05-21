<?php
$filepath = realpath(dirname(__FILE__));
include_once($filepath . '/../lib/database.php');
include_once($filepath . '/../helpers/format.php');

class statistical
{
    private $db;
    private $fm;

    public function __construct()
    {
        $this->db = new Database();
        $this->fm = new Format();
    }

    // ✅ 1. Tổng doanh thu (đã fix)
    public function gettongDoanhThu()
    {
        $query = "SELECT SUM(thanhtien) AS total_revenue FROM tbl_order WHERE status = 2";
        return $this->db->select($query);
    }

    // ✅ 2. Tổng khách hàng
    public function gettongKhachHang()
    {
        $query = "SELECT * FROM tbl_uer";
        return $this->db->select($query);
    }

    // ✅ 3. Tổng sản phẩm
    public function gettongSP()
    {
        $query = "SELECT * FROM tbl_product";
        return $this->db->select($query);
    }

    // ✅ 4. Tổng quản trị viên
    public function gettongAdmin()
    {
        $query = "SELECT COUNT(*) AS countadmin FROM tbl_admin";
        return $this->db->select($query);
    }

    // ✅ 5. Thống kê sản phẩm theo ngày
    public function gettongSPTheoNgay($data)
    {
        $date1 = mysqli_real_escape_string($this->db->link, $data['date1']);
        $date2 = mysqli_real_escape_string($this->db->link, $data['date2']);

        $query = "SELECT od.*, 
                        SUM(od.thanhtien) AS value_sumTT, 
                        SUM(od.quantity) AS value_count, 
                        pd.productName, 
                        pd.image, 
                        pd.price
                  FROM tbl_order AS od 
                  INNER JOIN tbl_product AS pd ON od.productId = pd.productId
                  WHERE (order_time BETWEEN '$date1' AND '$date2') AND od.status = 2
                  GROUP BY od.productId
                  ORDER BY od.productId";
        return $this->db->select($query);
    }

    // ✅ 6. Thống kê sản phẩm theo năm
    public function gettongSPTheoNam($year)
    {
        $year = mysqli_real_escape_string($this->db->link, $year);
        $query = "SELECT SUM(thanhtien) AS value_sumTT, 
                         SUM(quantity) AS value_count 
                  FROM tbl_order
                  WHERE YEAR(order_time) = '$year' AND status = 2";
        return $this->db->select($query);
    }
}
?>
