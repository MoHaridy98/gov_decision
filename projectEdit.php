<?php
session_start();
$userName = $_SESSION['user_id'];
$userPass = $_SESSION['user_pass'];
$GLOBALS['ID'] =  $_SESSION['id'];

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

if ($_SESSION['id'] == '') {
    //send them back
    header('Location: decisions.php');
    $_SESSION['id'] = '';
} else {
    //reset the variable
    $_SESSION['id'] = '';
}

function decisionInfo()
{
    global $ID;
    $user_role = $_SESSION['user_role'];
    require 'connection.php';
    $sql = "SELECT * FROM decision WHERE id = $ID";
    $list = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($list);
    //
    $result = mysqli_query($conn, "SELECT * FROM decision WHERE id=$row[0]");
    $file = mysqli_fetch_assoc($result);
    $filepath = 'uploads/' . $user_role . " رقم " . $row[0] . ' ' . $file['attach_file']; 
    //
    $options = "
    <form id='newProjectForm' method='post' enctype='multipart/form-data' action='editor.php'>
    <div class='row'>
        <div class='col-md-4'>
            <div class='form-group'>
                <label class='text-right control-label col-form-label'>رقم القرار</label>
                <!--<input type='text' class='form-control' disabled value='$row[0]'></input>-->
                <input type='number' class='form-control' name='decision_id' id='decision_id' value='$row[0]' required></input>
            </div>
        </div>
        <div class='col-md-4'>
            <div class='form-group'>
                <label class='text-right control-label col-form-label'>سنة القرار</label>
                <input type='number' class='form-control' name='decision_year' id='decision_year' value='$row[1]' required></input>
             </div>
        </div>
        <div class='col-md-4'>
            <div class='form-group'>
                <label class='text-right control-label col-form-label'>تاريخ القرار</label>
                <input type='date' class='form-control' name='decision_date' id='decision_date' value='$row[2]' required></input>
            </div>
        </div>
    </div>
    <div class='row'>
        <div class='col-md-4'>
            <div class='form-group'>
                <label class='text-right control-label col-form-label'>الموضوع</label>
                <input type='text' class='form-control' name='decision_subject' id='decision_subject' value='$row[3]' required></input>
            </div>
        </div>
        <div class='col-md-4'>
            <div class='form-group'>
                <label class='text-right control-label col-form-label'>جهة الاسناد</label>
                <input type='text' class='form-control' name='decision_from' id='decision_from' value='$row[4]' required></input>
            </div>
        </div>
        <div class='col-md-4'>
            <div class='form-group'>
                <label class='text-right control-label col-form-label'>جهة التنفيذ</label>
                <input type='text' class='form-control' name='decision_to' id='decision_to' value='$row[5]' required></input>
            </div>
        </div>
    </div>
    <div class='row'>
        <div class='col-md-4'>
            <div class='form-group'>
                <label for='name' class='text-right control-label col-form-label'>نوع القرار</label>
                <select id='decision_type' name='decision_type' class='form-control'>
                    <option selected hidden value='$row[6]' required>$row[6]</option>
                    <option value='مالي'>مالي</option>
                    <option value='اداري'>اداري</option>
                </select>
            </div>
        </div>
        <div class='col-md-4'>
            <div class='form-group'>
                <label class='text-right control-label col-form-label'>التصنيف</label>
                <select id='decision_category' name='decision_category' class='form-control'>
                    <option selected hidden value='$row[7]' required>$row[7]</option>
                    <option value='مالي'>مالي</option>
                    <option value='اداري'>اداري</option>
                </select>
            </div>
        </div>
        <div class='col-md-4'>
            <div class='form-group'>
                <label class='text-right control-label col-form-label'>الملف</label>
                <a class='form-control' href=\"$filepath\" target=\"_blank\">$row[8]</a>
                <input type='file' class='form-control' name='file' id='file' required></input>
            </div>
        </div>
    </div>
    <input type='hidden' id='id' name='id' value='$row[0]'/>
    <input type='submit' name='action' onClick='editConfirmation(event)' class='btn btn-success' value='تعديل القرار' />
</form>
    ";
    $conn->close();
    return $options;
}
?>

<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <meta name='description' content=''>
    <meta name='author' content=''>
    <link rel='icon' href='images/aswan.png'>
    <title>تعديل قرار</title>
    <!-- Bootstrap Core CSS -->
    <link href='css/rtl/bootstrap.min.css' rel='stylesheet'>
    <!-- not use this in ltr -->
    <link href='css/rtl/bootstrap.rtl.css' rel='stylesheet'>
    <!-- MetisMenu CSS -->
    <link href='css/plugins/metisMenu/metisMenu.min.css' rel='stylesheet'>
    <!-- Custom CSS -->
    <link href='css/rtl/sb-admin-2.css' rel='stylesheet'>
    <!-- Custom Fonts -->
    <link href='css/font-awesome/font-awesome.min.css' rel='stylesheet' type='text/css'>
    <style>
        #expensesForm,
        #commentForm {
            display: none;
        }
    </style>

</head>

<body>
    <div id='wrapper'>
        <!-- Navigation -->
        <nav class='navbar navbar-default navbar-static-top' role='navigation' style='margin-bottom: 0'>
            <?php require('component/header.php'); ?>
        </nav>

        <div id='page-wrapper'>
            <div class='row'>
                <div class='col-lg-12'>
                    <h1 class='page-header'>عرض القرار</h1>
                </div>
            </div>
            <!-- /.row -->
            <div class='column'>
                <div class='col-lg-12'>
                    <div class='panel panel-default'>
                        <div class='panel-heading'>
                            تعديل القرار
                        </div>
                        <div class='panel-body'>
                            <?php echo decisionInfo(); ?>
                        </div>
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>            
        </div>
    </div>
    <?php require('component/footer.php'); ?>
    <!-- /#wrapper -->

    <!-- jQuery Version 1.11.0 -->
    <script src='js/jquery-1.11.0.js'></script>

    <!-- Bootstrap Core JavaScript -->
    <script src='js/bootstrap.min.js'></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src='js/metisMenu/metisMenu.min.js'></script>
    <!-- DataTables JavaScript -->
    <script src='js/jquery/jquery.dataTables.min.js'></script>
    <script src='js/bootstrap/dataTables.bootstrap.min.js'></script>
    <!-- Custom Theme JavaScript -->
    <script src='js/sb-admin-2.js'></script>
    <script>
        function deleteHandler(event) {
            var sure = confirm('هل انت متاكد من الحذف؟');
            if (sure == false) {
                event.preventDefault();
            } else {}
        }

        function editConfirmation(event) {
            var c = confirm('هل انت متأكد من التعديل؟');
            if (!c) {
                event.preventDefault();
            }
        }
    </script>
</body>

</html>