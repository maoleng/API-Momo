<?php

// Lớp database
class DB
{
        // Các biến thông tin kết nối
    private $hostname = 'localhost',
            $username = 'tmomohost5k_demban',
            $password = 'tmomohost5k_demban',
            $dbname = 'tmomohost5k_demban';
    // Biến lưu trữ kết nối
    public $cn = NULL;
 
    // Hàm kết nối
    public function connect()
    {
        $this->cn = mysqli_connect($this->hostname, $this->username, $this->password, $this->dbname);

        if (!$this->cn) {
            echo "Bảo trì hệ thống. Quay lại sau 5 phút !";
            exit;
        }
        mysqli_set_charset($this->cn, "utf8");
        return $this->cn;
    }
    // Hàm kết nối
    public function get_config()
    {
        $config = array("hostname" => $this->hostname,"username" => $this->username,"password" => $this->password,"dbname" => $this->dbname);
        return $config;
    }
    // Hàm chống sql injection
    public function real_escape_string($sql = null) 
    {
        if ($this->cn) return mysqli_real_escape_string($this->cn, $sql);
    }
    // Hàm ngắt kết nối
    public function close()
    {
        if ($this->cn)
        {
            mysqli_close($this->cn);
        }
    }
 
    // Hàm truy vấn
    public function query($sql = null) 
    {       
        if ($this->cn)
        {
            mysqli_query($this->cn, $sql);
        }
    }
 
    // Hàm đếm số hàng
    public function num_rows($sql = null) 
    {
        if ($this->cn)
        {
            $query = mysqli_query($this->cn, $sql);
            if ($query)
            {
                $row = mysqli_num_rows($query);
                return $row;
            }   
        }       
    }

    // Hàm đếm tổng số hàng
    public function fetch_row($sql = null) 
    {
        if ($this->cn)
        {
            $query = mysqli_query($this->cn, $sql);
            if ($query)
            {
                $row = $query->fetch_row();
                return $row[0];
            }   
        }       
    }

    // Hàm lấy dữ liệu
    public function fetch_assoc($sql = null, $type)
    {
        if ($this->cn)
        {
            $query = mysqli_query($this->cn, $sql);
            if ($query)
            {
                if ($type == 0)
                {
                    // Lấy nhiều dữ liệu gán vào mảng
                    while ($row = mysqli_fetch_assoc($query))
                    {
                        $data[] = $row;
                    }
                    return $data;
                }
                else if ($type == 1)
                {
                    // Lấy một hàng dữ liệu gán vào biến
                    $data = mysqli_fetch_assoc($query);
                    return $data;
                }
            }       
        }
    }
    public function insert($tableName, $insData){
    $columns = implode(", ",array_keys($insData));
    $escaped_values = array_map('mysql_real_escape_string', array_values($insData));
    foreach ($escaped_values as $idx=>$data) $escaped_values[$idx] = "'".$data."'";
    $values  = implode(", ", $escaped_values);
    $query = "INSERT INTO $tableName ($columns) VALUES ($values)";
    $insert =  mysqli_query($this->cn,$query);
    if(!$insert) {
        return mysqli_error($this->cn);
    }
    }
    // Hàm lấy ID cao nhất
    public function insert_id()
    {
        if ($this->cn)
        {
            $count = mysqli_insert_id($this->cn);
            if ($count == '0')
            {
                $count = '1';
            }
            else
            {
                $count = $count;
            }
            return $count;
        }
    }
    
    // Hàm charset cho database
    public function set_char($uni)
    {
        if ($this->cn)
        {
            mysqli_set_charset($this->cn, $uni);
        }
    }
}
 
?>