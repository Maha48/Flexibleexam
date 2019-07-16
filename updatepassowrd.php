<?php
$AdminEmail=$_POST['emailadmin'];
$Adminpassword=$_POST['passwordadmin'];
$Adminpassword2=$_POST['passwordadmin2'];


require_once './vendor/autoload.php';
$dotenv = Dotenv\Dotenv::create(__DIR__);
$dotenv->load();
// نستطيع الان قراءة المتغيرات من الملف
$db_host = getenv('DB_host'); //استدعاء البيانات من الملف المحتوي على البيانات الحساسه
$db_username = getenv('DB_username');
$db_password = getenv('DB_password');
$Database = getenv('DB');


$connection=mysqli_connect($db_host,$db_username,$db_password,$Database,'8889');
$Email = mysqli_real_escape_string($connection, $AdminEmail);//تأمين المدخلات قبل ادخالها قاعدة البيانات
$Password = mysqli_real_escape_string($connection, $Adminpassword);
$Password2 = mysqli_real_escape_string($connection, $Adminpassword2);


$query = mysqli_query($connection, "select * from Admin where Username='$Username'")
            or die("failed to query database " . mysqli_error($connection));
$result = mysqli_fetch_array($query);
$db_adminpassword = $result['Password'];// جلب كلمة المرور من قاعدة البيانات
            
            //التحقق من ان اسم المستخدم وكلمة المرور موجوده بقاعدة البيانات وان كلمة المرور المدخله تماثل كلمة المرور بقاعدة البيانات
if($Adminpassword == $Adminpassword2){
$update=mysqli_query("Update Admin set Password='$Password' where Email='$Email'");
echo"good";
}

?>
   

