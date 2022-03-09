<?php
session_start();
$userName = $_SESSION['user_id'];
$userPass = $_SESSION['user_pass'];
$date = date("Y-m-d");

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
    else{
        header('Location: decisions.php');
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
    <title>الرئيسية - بحث</title>
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
        #categoryChart,
        #changeChart {
            overflow-x: hidden;
            overflow-y: hidden;
            direction: ltr !important;
            width: 100%;
            height: 300px;
        }

        /*
        #changeChart{
            display: none; 
        }*/
    </style>
    <style>
        .window {
            display: none;
            position: fixed;
            z-index: 10;
            padding: 50px 0;
            left: 0;
            top: 0;
            width: 100vw;
            height: 100%;
            overflow: auto;
            /* Enable scroll if needed */
            background-color: rgba(0, 0, 0, 0.5);
        }

        .window .title {
            display: flex;
            flex-direction: row;
            align-items: center;
        }

        .window .profile {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            display: inline-block;
            box-shadow: 0 0 5px var(--primary), 0 0 10px var(--primary);
        }

        .window h1 {
            display: inline-block;
            margin-left: 10px;
        }

        /* The Close Button */

        .window-close {
            color: #279c96;
            float: right;
            font-size: 40px;
            font-weight: bold;
        }

        .window-close:hover,
        .window-close:focus {
            color: #ee7326;
            text-decoration: none;
            cursor: pointer;
        }

        /* window Content */
        .window-content {
            position: relative;
            background-color: #f8f8f8f0;
            margin: auto;
            padding: 10px;
            border: 1px solid #888;
            width: 50%;
            border-radius: 15px;
        }

        @media screen and (max-width: 768px) {
            .window .title {
                flex-direction: column;
                text-align: center;
                justify-content: center;
                align-items: center;
                width: 100%;
            }

            .window h1 {
                margin-left: 0;
            }

            .window p {
                text-align: justify;
            }
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
                <div class="col-lg-12">
                    <h1 class="page-header">الرئيسية</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            بحث
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <!-- Nav tabs -->
                            <form action="">
                                <div class="input-group mb-12">
                                    <div class="input-group-prepend" style="display: flex;">
                                        <button type="button" class="btn btn-primary">بحث</button>
                                        <button type="button" class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <span class="sr-only">Toggle Dropdown</span>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="#">Action</a>
                                            <a class="dropdown-item" href="#">Another action</a>
                                            <a class="dropdown-item" href="#">Something else here</a>
                                            <div role="separator" class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="#">Separated link</a>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" aria-label="Text input with segmented dropdown button">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            مؤشرات المحاور
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="col-lg-3">
                            </div>
                            <div id="categoryChart" style="height: 500px;"></div>
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
    <!-- Custom Theme JavaScript -->
    <script src="js/sb-admin-2.js"></script>
</body>

</html>