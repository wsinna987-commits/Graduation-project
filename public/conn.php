<?php 
    //创建数据库连接对象
$conn=mysqli_connect("172.17.0.3","root","123456");
//如果数据库连接对象创建失败，抛出错误信息
if(!$conn)
{
	die('数据库连接失败:' .mysqli_error());
}
//选择要操作的数据库对象
$dbselect=mysqli_select_db($conn,"manger");
//如果数据库选择失败，抛出错误信息
if(!$dbselect)
{
	die('数据库不可用:' .mysqli_error());
}
//设置编码为utf8
mysqli_query($conn,"set names utf8");
132
?>