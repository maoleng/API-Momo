<?php
/*
╔═════════════════════════════════════════════╗
║             Design by LuaUyTin              ║
║      Facebook: facebook.com/luauytin        ║
║   Hotline: 0984.459.954 - 0899.91.31.91     ║
╚═════════════════════════════════════════════╝
*/
// Lớp session
class Session {
    // Hàm bắt đầu session
    public function start()
    {
        @session_start();
    }
 
    // Hàm lưu session 
    public function send($user)
    {
        $_SESSION['user'] = $user;
    }
 
    // Hàm lấy dữ liệu session
    public function get($type) 
    {
        if (isset($_SESSION[''.$type.'']))
        {
            $user = $_SESSION[''.$type.''];
        }
        else
        {
            $user = '';
        }
        return $user;
    }
 
    // Hàm xoá session
    public function destroy() 
    {
        @session_destroy();
    }
}
 
?>