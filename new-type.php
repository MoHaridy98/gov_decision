<?php
session_start();
$userName = $_SESSION['user_id'];
$userPass = $_SESSION['user_pass'];
$userRole = $_SESSION['user_role'];

if (!isset($userName)) {
    header('Location: logout.php');
    exit;
} else {
    require 'connection.php';
    $loginState = mysqli_query($conn, "SELECT state FROM users WHERE username = '$userName' AND passhash = '$userPass';");
    $data = mysqli_fetch_array($loginState);
    if ($data['state'] == 0) {
        header('Location: logout.php');
        exit;
    }
}
if ($userRole !== 'admin') {
    echo "<script>
            alert('غير مسموح لغير المشرفين!');
            window.location.href='index.php';
        </script>";
    exit;
}
//==========display db data handler========//
function userList(){
    require 'connection.php';
    $sql = "SELECT * FROM `user_type`";
    $list = mysqli_query($conn, $sql);
    $options = "";
    while ($row = mysqli_fetch_array($list)) {
        $newSql = "SELECT COUNT(role)
            FROM users
            WHERE role='$row[1]';";
        $newList = mysqli_query($conn, $newSql);
        $newrow = mysqli_fetch_array($newList);
        $options = $options . "<tr>
        <td>$row[1]</td>
        <td>$newrow[0]</td>
    </tr>";
    }
    $conn->close();
    return $options;
}

function user_type(){
    require 'connection.php';
    $sql = "SELECT * FROM `user_type`";
    $list = mysqli_query($conn, $sql);
    $options = "<option hidden disabled selected>اختر نوع القرارات</option>";
    while ($row = mysqli_fetch_array($list)) {
        $options = $options."<option value='$row[1]'>$row[1]</option>";
    }
    return $options;
}
//==========add new data handler===========//
if (isset($_POST['signup'])) {
    require 'connection.php';
    $ename = $_POST['ename'];
    $userCheck = mysqli_query($conn, "SELECT * FROM user_type WHERE name = '$ename';");
    $row = mysqli_num_rows($userCheck);
    if ($row > 0) {
        echo "<script>
            alert('هذا الاسم موجود مسبقاً');
            window.location.href = 'admin.php';
        </script>";
        exit;
    } else {
        $sql = "INSERT INTO `user_type` (`name`) VALUES ('$ename');";
        if (mysqli_query($conn, $sql)) {
            echo "<script>
                alert('تمت الاضافة');
                window.location.href = 'admin.php';
            </script>";
        } else {
            echo "<script>
                alert('حدث خطأ');
                window.location.href = 'admin.php';
            </script>";
        }
    }
}

unset($_POST['signup']);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel='icon' href='images/aswan.png'>
    <title>ادمن - قرارات </title>
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
        .container {
            background-color: #fff;
            opacity: 0.95;
            border-radius: 15px;
            box-shadow: rgb(0 0 0 / 19%) 0px 10px 20px, rgb(0 0 0 / 23%) 0px 6px 6px;
            margin-top: 10%;
        }

        .container #login-row #login-box #login-form {
            padding: 20px;
        }

        .container #login-row #login-box #login-form #register-link {
            margin-top: -85px;
        }

        .cityDB {
            display: none;
        }

        /* #insert, #user-form,
        #category-form, #section-form,
        #city-form, #local-form, #Village-form{
            display:none;
        } */

        @media (min-width: 512px) {
            .container {
                width: 60% !important;
            }
        }
    </style>
</head>

<body>
    <div id="wrapper">
        <!-- Navigation -->
        <nav class='navbar navbar-default navbar-static-top' role='navigation' style='margin-bottom: 0'>
            <?php require('component/admin-header.php'); ?>
            <!--=================================-->
        </nav>
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">اعدادات المشرف</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            المستخدمين
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <!-- Nav tabs -->
                            <div>
                                <table class="table table-striped table-bordered table-hover" id="userTable">
                                    <thead>
                                        <tr>
                                            <th>الاسم</th>
                                            <th>عدد المستخدمين</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php echo userList(); ?>
                                    </tbody>
                                </table>
                            </div>
                            <input type='button' class='btn btn-primary' onclick='formChange("user")' value='اضافة جديد' />
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <?php require('component/footer.php'); ?>
    <!--POP_UP WINDOW CLICKED THE a-->
    <div id="popup" class="window">
        <div class="window-content">
            <span class="window-close">&times;</span>
            <div id="popup-content">
            </div>
        </div>
    </div>

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
    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
        $(document).ready(function() {
            $('#userTable').dataTable();            
        });

        function show() {
            var x = document.getElementById("password");
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }
        }
    </script>
    <script>
        var modal = document.getElementById("popup");
        var change = document.getElementById("popup-content");
        var span = document.getElementsByClassName("window-close")[0];
        // When the user clicks the button, open the modal
        function formChange(name) {
            modal.style.display = "block";
            switch (name) {
                case "user": {
                    change.innerHTML = `<div class="row">
                                <div class="col-lg-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            اضافة جديد
                                        </div>
                                    <!-- /.panel-heading -->
                                    <div class="panel-body">
                                        <form class="form text-right" action="" method="post">
                                            <h3 class="text-center text-info">اضافة تصنيف جديد</h3>
                                            <div class="form-group">
                                                <label for="ename" class="text-info">اسم التصنيف:</label><br>
                                                <input type="text" name="ename" minlength='4' maxlength='32' id="ename" class="form-control" required>
                                            </div>                                            
                                            <div class="form-group text-right">
                                                <input type="submit" name="signup" onClick='editConfirmation(event)' class="btn btn-primary" value="اضافة تصنيف">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>`;
                    break;
                }
            }
        }
        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = "none";
            window.onscroll = function() {};
        }
        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
                window.onscroll = function() {};
            }
        }

        function deleteHandler(event) {
            var sure = confirm('هل انت متاكد من الحذف؟');
            if (sure == false) {
                event.preventDefault();
            } else {}
        }

        function editConfirmation(event) {
            var c = confirm('هل انت متأكد من البيانات!؟ لن تتمكن من الحذف لاحقاً!');
            if (!c) {
                event.preventDefault();
            }
        }
    </script>
</body>

</html>