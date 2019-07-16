<html>
<head>
<link rel="stylesheet" type="text/css" href="data.css">
<script src="jquery-3.4.1.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
$(document).ready(function () {
    // uploadfile
    $(".file1").click(function () {
        $(".import").show();
    });
    // student slideToggle
    $("*#SSD-detiles").click(function () {
        console.log($(this).attr('class'));
        var className = $(this).attr('class');
        $("tr."+className).slideToggle("slow");
    });
    //next and prev button
$(".SSD-tables table.panel").each(function(SSD_numerdisplay) {
if (SSD_numerdisplay > 9)
$(this).hide();
console.log(SSD_numerdisplay);
});
$("#next").click(function(){
    if ($(".SSD-tables table.panel:visible:last").next().length != 0){
        $(".SSD-tables table.panel:visible:last").next().show();
        $(".SSD-tables table.panel:visible:last").next().show();
        $(".SSD-tables table.panel:visible:first").hide();
        $(".SSD-tables table.panel:visible:first").hide();
    }
    else {
        //either hide the next button or show 1st two again. :)
    }
    return false;
});
$("#prev").click(function(){
    if ($(".SSD-tables table.panel:visible:first").prev().length != 0){
        var curVisLen = $(".SSD-tables table.panel:visible").length;
        $(".SSD-tables table.panel:visible:first").prev().show();
        $(".SSD-tables table.panel:visible:first").prev().show();
        $(".SSD-tables table.panel:visible:last").hide();
        if(curVisLen == 10){
        $(".SSD-tables table.panel:visible:last").hide();
        }
    }
    else {
        //either hide the button or show last two divs
    }
    return false;
});
    // hide student have exams in same day
    $(".hide").click(function(){
        $(".SSD-tables").hide();
    });
    // search ajax
    $('#Search-btn').click(function(event) {
        console.log("click event fired")
        event.preventDefault();
        var searchtxt =$('#search').val();
        if($.trim(searchtxt)!= ''){
            $.ajax({
                url:'SSD_search.php', 
                method:"POST",
                data:{search:searchtxt},
                success:function(data)
                {
                    $('#SSD_search').html(data);
                    console.log(data);
                }
            });
        }
    });
}); 
</script>
</head>
<!-- UPload File -->
<?php
require_once './vendor/autoload.php';
$dotenv = Dotenv\Dotenv::create(__DIR__);
$dotenv->load();
// نستطيع الان قراءة المتغيرات من الملف
$db_host = getenv('DB_host'); //استدعاء البيانات من الملف المحتوي على البيانات الحساسه
$db_username = getenv('DB_username');
$db_password = getenv('DB_password');
$Database = getenv('DB');
$connection = mysqli_connect($db_host, $db_username, $db_password, $Database, '8889');
$queryempty = 'select * From Examdata';
$query = mysqli_query($connection, $queryempty) or die("Error in query: $query. " . mysqli_error());
if (mysqli_num_rows($query) > 0) {
    $message = "There is data";
} else {
    if (isset($_POST["import"])) {
        $fileName = $_FILES["file"]["tmp_name"];
        if ($_FILES["file"]["size"] > 0) {
            $file = fopen($fileName, "r");
            while (($column = fgetcsv($file, 110000, ";")) !== false) {
                $sqlInsert = "INSERT into Examdata (Class_ID,Subject_ID,Student_ID,Subject_name,exam_days,exam_dates,exam_times)
                values ('" . $column[0] . "','" . $column[1] . "','" . $column[2] . "','" . $column[3] . "','" . $column[4] . "','" . $column[5] . "','" . $column[6] . "')";
                $result = mysqli_query($connection, $sqlInsert);
                if (!empty($result)) {
                    $message = "Upload Done ";
                } else {
                    $message = "Problem In Upload Data";
                }
            }
        }
    }
}
if (isset($_POST['delete'])) {
    $sqldelete = "delete from Examdata";
    $result1 = mysqli_query($connection, $sqldelete);
    if (!empty($result1)) {
        $message = "Deleted Done";
    }
}
?>
<body id="body">
<div id="bar">
<div id="wlcomeadmin">Welcome Admin
</div>
</div>
<div id="uploadfile">
<br>
<div id="outer-scontainer">
<div id="uploaddiv">
<div id="response"><?php if (!empty($message)) {echo $message;}?></div>
    <div class="row">
            <form class="form-horizontal" action="" method="post"
            name="frmCSVImport" id="frmCSVImport" enctype="multipart/form-data">
                <div class="input-row">
                    <label>
                        <input type="file" name="file"id="file" class="file1"accept=".csv">
                        <img src="images/upload-2.png"id="file"><br>
                    </label>
                    <br>
                    <table id="tablebtn">
                    <tr> 
                    <td><label class="col-md-4 control-label">Choose CSV File to Upload </label></td>
                    <td><input  style="display:none"type='submit'id="import" name='import' class="import"value='Import'></td>
                    </tr>
                    <tr>
                    <td> <label> Delete The Current Data </label> </td>
                    <td> <input type="submit"value="Delete"name="delete" id="delete"></td>
                    </tr>
                    </table>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
<!--End upload file-->
<!-- SSD->student in same day-->
<div id="ssd_div">
<form method="POST">
<div id="search_div">
<h3 id="ssd_title"> Student Who Have Exames In The Same Day</h3>
<div id="inpout">
<input type="text"id="search" name="search" style="height:25px;border-radius: 25px;margin:40px;">
<input type="submit"class="hide" id="Search-btn"name="btn"value="Search">
</div>
</div>
</form> 
<table id="Mainـhead">
<thead>
<tr>
    <th width="8%">Class ID</th>
    <th width="4%">Subject_ID</th>
    <th width="20%">Subject_name</th>
    <th width="10%">exam_days</th>
    <th width="8%">exam_dates</th>
    <th width="10%">exam_dates</th>
</tr>
<thead>
</table>
<div id="ssd_scroll">
<div id="SSD_search" >
</div>
<div class="SSD-tables">
<?php
$SSD_sql = $connection->query("SELECT * from Examdata join (SELECT Student_ID, count(*), exam_dates FROM Examdata group by Student_ID, exam_dates having count(*) > 1) Examdata1 on Examdata.Student_ID=Examdata1.Student_ID and Examdata1.exam_dates=Examdata.exam_dates order by Examdata1.Student_ID,Examdata1.exam_dates ;")->fetch_all(MYSQLI_ASSOC);
$student = "";
foreach ($SSD_sql as $sq) {
    if ($student != $sq["Student_ID"]) {
        $student = $sq["Student_ID"];
        echo '<table id="ssd_student"  class="panel"> 
        <tr>
        <td width="70px">
        <label>
            <input type="submit" class="' . $student . '" style="onclick="Function();"" id="SSD-detiles">
            <img src="images/up-arrow.png"id="img_arrow">
            <br>
        </label>
        </td>';
        echo '<td> Student ID: ' . $sq["Student_ID"] . '</td></tr>';
    }
    echo '<tr id="ssd_data" class="'. $student . '" >';
    echo '<td class="myDIV">' . $sq["Class_ID"] . '</td>';
    echo '<td class="myDIV">' . $sq["Subject_ID"] . '</td>';
    echo '<td class="myDIV">' . $sq["Subject_name"] . '</td>';
    echo '<td class="myDIV">' . $sq["exam_days"] . '</td>';
    echo '<td class="myDIV">' . $sq["exam_dates"] . '</td>';
    echo '<td class="myDIV">' . $sq["exam_times"] . '</td></tr>';
}
?>
</table>
</div>
</div>
<div id="nextprev">
<label id="nextlabel">
    <a id="next" >
    <img  width="7%" src="images/prev.png"></a>
</label>
<label>
<a id="prev">
    <img width="7%" src="images/next.png"></a>
</label>
</div>
</div>
<!-- end SSD->student in same day-->