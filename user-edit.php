<?php
session_start();
$userName = $_SESSION['user_id'];
$userPass = $_SESSION['user_pass'];
$userRole = $_SESSION['user_role'];
$id = $_SESSION['id'];

if(!isset($userName) || !isset($userRole)){
    header('Location: logout.php');
    exit;
}else{
    require 'connection.php';
	$loginState = mysqli_query($conn,"SELECT state FROM users WHERE username = '$userName' AND passhash = '$userPass';");
	$data = mysqli_fetch_array($loginState);
    if($data['state'] == 0){
        header('Location: logout.php');
        exit;
    }
}

// if($id == ''){
//     //send them back
//     header("Location: ../admin.php");
//     $_SESSION['id'] = '';
//  }
//  else{
//     //reset the variable
//     $_SESSION['id'] = '';
//  }

if($userRole !== 'admin'){
    echo "<script>
            alert('غير مسموح لغير المشرفين!');
            window.location.href='index.php';
        </script>";
    exit;
}

function userInfo() {
    global $id;
    require('connection.php');
    $sql = "SELECT * FROM users WHERE id = $id";
    $list = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($list);
    //
    $sql = "SELECT * FROM user_type";
    $list1 = mysqli_query($conn, $sql);
    //
    $options = "<form method='post' autocomplete='off' action='editor.php'>
    <input autocomplete='false' name='hidden' type='text' style='display:none;'>
    <div hidden class='form-group'>
        <label>رقم المستخدم</label>
        <input disabled class='form-control' name='id' value= $row[0]>
    </div>
    <div class='form-group'>
        <label>اسم الموظف</label>
        <input disabled class='form-control' value= '$row[1]'>
        <input name='name' value= '$row[1]' minlength='4' maxlength='32' type='text' class='form-control'>
    </div>
    <div class='form-group'>
        <label>اسم المستخدم</label>
        <input disabled class='form-control' value= '$row[2]'>
        <input name='user' value= '$row[2]' type='text' minlength='4' maxlength='32' class='form-control'>
    </div>
    <div class='form-group' style = 'margin-bottom: 30px'>
		<label for='password'class='text-info'>كلمة السر:</label><br>
		<input type='password' id='password' name='password' minlength='4' maxlength='20' autocomplete='false' class='form-control text-right'>
		<input type='checkbox' id='checkbox' onclick='show()'>
		<label for='checkbox'>عرض الرقم السري</label>
	</div>
    <div class='form-group'>
        <label for='role' class='text-info'> تابع ل:</label><br>
        <select name='role' id='role' class='form-control' required>";        
        while ($row1 = mysqli_fetch_array($list1)) {
            $options = $options."<option value='$row1[1]'>$row1[1]</option>";
        }
    $options = $options."</select>
    </div>
    <div class='form-group text-right'>
        <input type='hidden' class='form-control' name='id' value= $row[0]>
        <input type='submit' name='action' onClick='editConfirmation(event)' class='btn btn-primary' value='تعديل الموظف'>
    </div>";
    return $options;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../images/haya-logo.jpg">
    <title>تعديل مستخدمين - حياة كريمة</title>
    <!-- Bootstrap Core CSS -->
    <link href="css/rtl/bootstrap.min.css" rel="stylesheet">
    <!-- not use this in ltr -->
    <link href="css/rtl/bootstrap.rtl.css" rel="stylesheet">
    <!-- MetisMenu CSS -->
    <link href="css/plugins/metisMenu/metisMenu.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link href="css/plugins/dataTables.bootstrap.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="css/rtl/sb-admin-2.css" rel="stylesheet">
    <!-- Custom Fonts -->
    <link href="css/font-awesome/font-awesome.min.css" rel="stylesheet" type="text/css">
    <style>
        .form-group {
            margin-left: 5px !important;
        }
    </style>
</head>

<body>
    <div id="wrapper">
        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <?php require('component/admin-header.php'); ?>
        </nav>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">تعديل المستخدمين</h1>
                </div>
            </div>

            <div class="column">
                <div class="col-lg-5">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            تعديل المستخدم
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <?php echo userinfo(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php require('component/footer.php'); ?>
    <!-- jQuery Version 1.11.0 -->
    <script src="js/jquery-1.11.0.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
    <!-- Metis Menu Plugin JavaScript -->
    <script src="js/metisMenu/metisMenu.min.js"></script>
    <!-- DataTables JavaScript -->
    <script src="js/jquery/jquery.dataTables.min.js"></script>
    <script src="js/bootstrap/dataTables.bootstrap.min.js"></script>
    <!-- Custom Theme JavaScript -->
    <script src="js/sb-admin-2.js"></script>
    <!-- custom js-->
    <script>
        function show() {
            var x = document.getElementById("password");
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }
        }
        function editConfirmation(event) {
            var c = confirm('هل انت متأكد من التعديل؟');
            if(!c){event.preventDefault();}
        }
    </script>
</body>

</html>