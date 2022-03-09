<?php
session_start();
$userName = $_SESSION['user_id'];
$userPass = $_SESSION['user_pass'];
$decision_id =  $_SESSION['id'];
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
        <div class='row'>
        <div class='col-md-12'>
            <div class='form-group'>
                <label class='text-right control-label col-form-label'>رقم القرار</label>
                <input type='text' class='form-control' disabled value='$row[0]'></input>                
            </div>
        </div>
        <div class='col-md-12'>
            <div class='form-group'>
                <label class='text-right control-label col-form-label'>سنة القرار</label>
                <input type='text' class='form-control' disabled value='$row[1]'></input>
             </div>
        </div>        
        </div>
        <div class='row'>
        <div class='col-md-12'>
            <div class='form-group'>
                <label class='text-right control-label col-form-label'>تاريخ القرار</label>
                <input type='text' class='form-control' disabled value='$row[2]'></input>
            </div>
        </div>
        <div class='col-md-12'>
            <div class='form-group'>
                <label class='text-right control-label col-form-label'>الموضوع</label>
                <input type='text' class='form-control' disabled value='$row[3]'></input>
            </div>
        </div>
        </div>
        <div class='row'>
        <div class='col-md-12'>
            <div class='form-group'>
                <label class='text-right control-label col-form-label'>جهة الاسناد</label>
                <input type='text' class='form-control' disabled value='$row[4]'></input>
            </div>
        </div>
        <div class='col-md-12'>
            <div class='form-group'>
                <label class='text-right control-label col-form-label'>جهة التنفيذ</label>
                <input type='text' class='form-control' disabled value='$row[5]'></input>
            </div>
        </div>
        </div>
        <div class='row'>
        <div class='col-md-12'>
            <div class='form-group'>
                <label for='name' class='text-right control-label col-form-label'>نوع القرار</label>
                <input type='text' class='form-control' disabled value='$row[6]'></input>
            </div>
        </div>
        <div class='col-md-12'>
            <div class='form-group'>
                <label class='text-right control-label col-form-label'>التصنيف</label>
                <input type='text' class='form-control' disabled value='$row[7]'></input>
            </div>
        </div>
        </div>
        <div class='row'>
        <div class='col-md-12'>
            <div class='form-group'>
                <label class='text-right control-label col-form-label'>الملف</label>
                <a class='form-control' href=\"$filepath\" target=\"_blank\">$row[8]</a>
            </div>
        </div>
        </div>
        ";
    $conn->close();
    return $options;
}

function decisionList()
{
    global $ID;
    $user_role = $_SESSION['user_role'];
    require 'connection.php';
    $sql = "SELECT * FROM decision WHERE id != $ID AND entry_type = '$user_role'";
    $list = mysqli_query($conn, $sql);
    $options = "";
    while ($row = mysqli_fetch_array($list)) {
        $options = $options . "<option value='$row[0]'>$row[0]</option>";
    }
    return $options;
}

function related_decision()
{
    global $ID;
    require 'connection.php';
    $sql = "SELECT * FROM `decision_relation` Where `main_decision_id`  = $ID or `sec_decision_id` = $ID";
    $list = mysqli_query($conn, $sql);
    $options = "";
    while ($row = mysqli_fetch_array($list)) { 
        $newSql = "SELECT * FROM `decision` where id = $row[2] or id = $row[1]";
        $newList = mysqli_query($conn, $newSql);
        while ($newRow = mysqli_fetch_array($newList)) {        
            $options = $options . "<tr>
            <td>$newRow[0]</td>
            <td>$newRow[3]</td>
            <td><a href=\"editor.php?file_id=$newRow[0]\">$newRow[8]</a></td>
            <td><form method='post' style='display: flex;' action='editor.php'>
            <input type='hidden' id='newid' name='newid' value='$row[0]'/>
            <input type='hidden' id='id' name='id' value='$newRow[0]'/>
            <input type='submit' style='margin: 0 1px;' name='action' class='btn btn-danger' value='الغاء الارتباط'/>
            <input type='submit' style='margin: 0 1px;' name='action' class='btn btn-primary' value='عرض'/></form></td>
        </tr>";
        }
    }    
    //        
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
    <title>تفاصيل قرار</title>
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
                    <h1 class='page-header'>تفاصيل القرار</h1>
                </div>
            </div>
            <!-- /.row -->
            <div class='column'>
                <div class='col-lg-6'>
                    <div class='panel panel-default'>
                        <div class='panel-heading'>
                            تفصيل القرار
                        </div>
                        <div class='panel-body'>
                            <?php echo decisionInfo(); ?>
                        </div>
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <div class="column">
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            قرارات ذات صلة
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="commentTable">
                                    <thead>
                                        <tr>
                                            <th>رقم القرار</th>
                                            <th>الموضوع</th>
                                            <th>الملف</th>
                                            <th>خيارات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php echo related_decision(); ?>
                                    </tbody>
                                </table>
                            </div>
                            <input type='button' id='addNewComment' class='btn btn-info' onclick='addComment()' value='اضافة صلة' />
                        </div>
                    </div>
                    <div class="panel panel-default" id="commentForm">
                        <div class="panel-heading">
                            اضافة صلة
                        </div>
                        <div class="panel-body">
                            <div class="col-lg-12">
                                <form method='post' action='editor.php'>
                                    <div class="form-group">
                                        <label for="d_relation" class="text-right control-label col-form-label">رقم القرار</label>
                                        <select class='form-control' name="d_relation" id="d_relation">
                                            <?php echo decisionList() ?>
                                        </select>
                                    </div>
                                    <input type='hidden' name='id' value='<?php echo $decision_id; ?>' />
                                    <input type='submit' name='action' onClick='editConfirmation(event)' class='btn btn-success' value='اضافة صلة' />
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
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

        function addComment() {
            const commentForm = document.getElementById("commentForm");
            commentForm.style.display = 'block';
            const addNewCommentbtn = document.getElementById("addNewComment");
            addNewCommentbtn.style.display = 'none';
        }
    </script>
</body>

</html>