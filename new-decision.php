<?php
session_start();
$userName = $_SESSION['user_id'];
$userPass = $_SESSION['user_pass'];

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
    <title>قرار جديد</title>
    <!-- Bootstrap Core CSS -->
    <link href="css/rtl/bootstrap.min.css" rel="stylesheet">
    <!-- not use this in ltr -->
    <link href="css/rtl/bootstrap.rtl.css" rel="stylesheet">
    <!-- MetisMenu CSS -->
    <link href="css/plugins/metisMenu/metisMenu.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="css/rtl/sb-admin-2.css" rel="stylesheet">
    <!-- Custom Fonts -->
    <link href="css/font-awesome/font-awesome.min.css" rel="stylesheet" type="text/css">
    <style>
        #expenses {
            display: none;
        }
    </style>
</head>

<body>

    <div id="wrapper">
        <!-- Navigation -->
        <nav class='navbar navbar-default navbar-static-top' role='navigation' style='margin-bottom: 0'>
            <?php require('component/header.php'); ?>
            <!--=================================-->
        </nav>
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-6">
                    <h1 class="page-header">اضافة قرار</h1>
                </div>
                <div class="col-lg-6" style="direction: ltr;">
                    <a class='page-header btn btn-primary' href='decisions.php'><i class='fa fa-edit fa-fw'></i> قرارات </a>
                </div>
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            اضافة قرار جديد
                        </div>
                        <div class="panel-body">
                            <form id="newProjectForm" method="post" enctype="multipart/form-data" action="editor.php">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="text-right control-label col-form-label">رقم القرار</label>
                                            <input type="number" class="form-control" name="decision_id" id="decision_id" placeholder="رقم القرار" required></input>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="text-right control-label col-form-label">سنة القرار</label>
                                            <input type="number" class="form-control" name="decision_year" id="decision_year" placeholder="سنة القرار" required></input>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="text-right control-label col-form-label">تاريخ القرار</label>
                                            <input type="date" class="form-control" name="decision_date" id="decision_date" placeholder="تاريخ القرار" required></input>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="text-right control-label col-form-label">الموضوع</label>
                                            <input type="text" class="form-control" name="decision_subject" id="decision_subject" placeholder="الموضوع" required></input>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="text-right control-label col-form-label">جهة الاسناد</label>
                                            <input type="text" class="form-control" name="decision_from" id="decision_from" placeholder="جهة الاسناد" required></input>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="text-right control-label col-form-label">جهة التنفيذ</label>
                                            <input type="text" class="form-control" name="decision_to" id="decision_to" placeholder="جهة التنفيذ" required></input>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="name" class="text-right control-label col-form-label">نوع القرار</label>
                                            <select id="decision_type" name="decision_type" class='form-control'>
                                                <option selected disabled hidden required>اختر نوع القرار</option>
                                                <option value="مالي">مالي</option>
                                                <option value="اداري">اداري</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="text-right control-label col-form-label">التصنيف</label>
                                            <select id="decision_category" name="decision_category" class='form-control'>
                                                <option selected disabled hidden required>اختر تصنيف القرار</option>
                                                <option value="مالي">مالي</option>
                                                <option value="اداري">اداري</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="text-right control-label col-form-label">الملف</label>
                                            <input type="file" class="form-control" name="file" id="file" required></input>
                                        </div>
                                    </div>
                                </div>
                                <input type='hidden' name='id' value='null' />
                                <input type='submit' name='action' onClick='editConfirmation(event)' class="btn btn-success" value="اضافة" />
                                <input type="reset" class="btn btn-danger" value="مسح" />
                                <!-- <input type="hidden" disabled class="btn btn-info" onclick="addExpense()" value="اضافة مستخلص"/> -->
                            </form>
                            <!-- /.row (nested) -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->
    <?php require('component/footer.php'); ?>
    <!-- jQuery Version 1.11.0 -->
    <script src="js/jquery-1.11.0.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="js/metisMenu/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="js/sb-admin-2.js"></script>
    <script>
        function editConfirmation(event) {
            var c = confirm('هل انت متأكد من البيانات!؟');
            if (!c) {
                event.preventDefault();
            }
        }
    </script>
</body>

</html>