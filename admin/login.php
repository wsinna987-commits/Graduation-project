<?
   session_start();
require "../public/conn.php";

header("Content-Type:text/html;charset=utf-8");

if(isset($_POST['username'])&&isset($_POST['password'])){
    
    //验证账号密码：
   // 获取并清理用户输入
    $username = trim($_POST['username']);
    $pwd = trim($_POST['password']);

    // 验证输入不为空
    if(empty($username) || empty($pwd)){
        echo "<script>alert('账号或密码不能为空'); window.location.href='login.html';</script>";
        exit;
    }

    $sql = "select * from users where username='$username' and password = '$pwd';";
    $result = mysqli_query($conn,$sql);
    if($result){
        $row =  mysqli_num_rows($result);
    }
    if($row>0){
        $_SESSION['ischecked']="ok";
        $_SESSION['username']=$_POST['username'];
        echo "<script>alert('登录成功,欢迎".addslashes($username)."'); window.location.href='index.php'</script>";
        exit;
    }else{
       echo "<script>alert('账号不存在或密码输入错误'); window.location.href ='login.html'</script>";
       exit;
    }
}

mysqli_close($conn);
?>