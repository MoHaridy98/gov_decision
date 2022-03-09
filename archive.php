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
        header('Location: ../logout.php');
        exit;
    }
}
function projectList()
{
    $user_role = $_SESSION['user_role'];
    require 'connection.php';
    $sql = "SELECT * FROM `decision` where archive = 1 AND entry_type = '$user_role'";
    $list = mysqli_query($conn, $sql);
    $options = "";
    while ($row = mysqli_fetch_array($list)) {        
        //to display row
        $options = $options . "<tr>
        <td>$row[0]</td>
        <td>$row[1]</td>
        <td>$row[2]</td>
        <td>$row[3]</td>
        <td>$row[4]</td>
        <td>$row[5]</td>
        <td>$row[6]</td>
        <td>$row[7]</td>
        <td><a href=\"editor.php?file_id=$row[0]\">$row[8]</a></td>
        <td><form method='post' style='display: flex;' action='editor.php'>
        <input type='hidden' id='id' name='id' value='$row[0]'/>
        <input type='submit' style='margin: 0 1px;' name='action' class='btn btn-info' value='تعديل'/>
        <input type='submit' style='margin: 0 1px;' name='action' onClick='deleteHandler(event)' class='btn btn-danger'value='استرجاع'/></form></td>
    </tr>";
    }
    $conn->close();
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
    <link rel='icon' href='images/aswan.png'>
    <title>الارشيف</title>
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
                <div class="col-lg-12">
                    <h1 class="page-header">الارشيف</h1>
                </div>
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            جدول الارشيف
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="column-group">اختر العنوان : -
                                <a class="toggle-vis" data-column="0">رقم القرار</a> -
                                <a class="toggle-vis" data-column="1">سنة القرار</a> -
                                <a class="toggle-vis" data-column="2">تاريخ القرار</a> -
                                <a class="toggle-vis" data-column="3">الموضوع</a> -
                                <a class="toggle-vis" data-column="4">جهة الاسناد</a> -
                                <a class="toggle-vis" data-column="5">جهة التنفيذ</a> -
                                <a class="toggle-vis" data-column="6">نوع القرار</a> -
                                <a class="toggle-vis" data-column="7">التصنيف</a>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="reportTable">
                                    <thead>
                                        <tr>
                                            <th>رقم القرار</th>
                                            <th>سنة القرار</th>
                                            <th>تاريخ القرار</th>
                                            <th>الموضوع</th>
                                            <th>جهة الاسناد</th>
                                            <th>جهة التنفيذ</th>
                                            <th>نوع القرار</th>
                                            <th>التصنيف</th>
                                            <th>الملف</th>
                                            <th>خيارات</th>
                                        </tr>
                                    </thead>
                                    <?php $projects = projectList(); ?>
                                    <tbody>
                                        <?php echo $projects; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php require('component/footer.php'); ?>
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
            var table = $('#reportTable').DataTable({
                "footerCallback": function(row, data, start, end, display) {
                    var api = this.api(),
                        data;
                    // converting to interger to find total
                    var intVal = function(i) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '') * 1 :
                            typeof i === 'number' ?
                            i : 0;
                    };
                    var tableRows = document.getElementById("reportTable");
                    tableRows = tableRows.tBodies[0].rows.length
                    // computing column Total of the complete result                     
                },
            });
            $('a.toggle-vis').on('click', function(e) {
                e.preventDefault();
                // Get the column API object
                var column = table.column($(this).attr('data-column'));
                // Toggle the visibility
                column.visible(!column.visible());
            });
        });

        function deleteHandler(event) {
            var sure = confirm('هل انت متاكد؟');
            if (sure == false) {
                event.preventDefault();
            } else {}
        }
    </script>
</body>

</html>