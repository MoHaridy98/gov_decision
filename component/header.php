<?php
$userName = $_SESSION['user_id'];

if(!isset($userName)){
    header('Location: logout.php');
    exit;
}
if (isset($_POST['edit'])) {
    $user = $userName;
    $Password = $_POST['oldPassword'];
    $oldPassword = hash_hmac("sha256", $Password, $user);
    $Password = $_POST['newPassword'];
    $newPassword = hash_hmac("sha256", $Password, $user);
    $sql = "SELECT * from users where username = '$user' AND passhash = '$oldPassword'";
    require 'connection.php';
    if($passCheck = mysqli_query($conn, $sql)){
        $row=mysqli_num_rows($passCheck);
        if($row == 0){
            echo "<script>
                alert('كلمة سر خطأ!');
                window.location.href = 'index.php';
            </script>";
        }else{
            $sql = "UPDATE users
            SET 
                passhash = '$newPassword'
            WHERE 
                username = '$user'";
            if(mysqli_query($conn, $sql)){
            echo "<script>
                alert('تم تغير كلمة السر. من فضلك قم بتسجل الدخول مجدداً');
                window.location.assign('logout.php');
            </script>";
            }
        }
    }else{
        echo "ERROR: Hush! Sorry $sql. " 
        . mysqli_error($conn);
    }
}
echo "
    <style>
        @keyframes glow {
            from {
                box-shadow: rgba(39, 156, 150, 0.5) 0px 12px 28px 0px, rgba(39, 156, 150, 0.4) 0px 2px 4px 0px inset;
            }
            to {
                box-shadow: rgba(238, 115, 38, 0.5) 0px 12px 28px 0px, rgba(238, 115, 38, 0.4) 0px 2px 4px 0px inset;
            }
        }
        /* Chrome, Safari, Edge, Opera */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
          -webkit-appearance: none;
          margin: 0;
        }
        
        /* Firefox */
        input[type=number] {
          -moz-appearance: textfield;
        }
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

    <!-- Navigation -->
    <div class='navbar-header'>
        <button type='button' class='navbar-toggle' data-toggle='collapse' data-target='.navbar-collapse'>
            <span class='sr-only'>Toggle navigation</span>
            <span class='icon-bar'></span>
            <span class='icon-bar'></span>
            <span class='icon-bar'></span>
        </button>        
        <a class='navbar-brand' style='font-size: x-large; display:flex;' href='index.php'><img style='width: 40px; height: 40px; margin: -10px 0 0 10px; border-radius: 5px;' src='images/logo.png'>قرارات وتكليفات</a>
    </div>
    <!-- navbar-header -->
    <ul class='nav navbar-top-links navbar-left'>
        <!-- /.dropdown -->
        <img style='width: 35px; height: 45px; margin: 0 35px 0 0; border-radius: 5px;' src='images/swan.png'>
        <li class='dropdown'>
            <a class='dropdown-toggle' data-toggle='dropdown' href='#'>
                <i class='fa fa-user fa-fw'></i> <i class='fa fa-caret-down'></i>
            </a>
            <ul class='dropdown-menu dropdown-user'>
                <li>اهلا , $userName</li>
                <li><a href='#' onClick='setting()'><i class='fa fa-gear fa-fw'></i>الاعدادات</a>
                        </li>
                <li class='divider'></li>
                <li><a href='logout.php'><i class='fa fa-sign-out fa-fw'></i> تسجيل الخروج</a>
                </li>
            </ul>
            <!-- /.dropdown-user -->
        </li>
        <!-- /.dropdown -->
    </ul>
    <!-- /.navbar-static-side -->
    
    <!--POP_UP WINDOW CLICKED THE a-->
    <div id='popup' class='window'>
        <div class='window-content'>
            <span class='window-close'>&times;</span>
            <div id='popup-content'>
            </div>
        </div>
    </div>
    <div class='navbar-default sidebar' role='navigation'>
    <div class='sidebar-nav navbar-collapse'>
        <ul class='nav' id='side-menu'>
            <li>
                <a href='new-decision.php'><i class='fa fa-edit fa-fw'></i> اضافة قرار</a>
            </li>
            <li>
                <a href='decisions.php'><i class='fa fa-table fa-fw'></i> قرارات</a>
            </li>    
            <li>
                <a href='archive.php'><i class='fa fa-archive fa-fw'></i> الارشيف </a>
            </li>
        </ul>                    
    </div>
    <!-- /.sidebar-collapse -->
</div>
    <script>
        var modal = document.getElementById('popup');
        var change = document.getElementById('popup-content');
        var span = document.getElementsByClassName('window-close')[0];
        function setting(){
            modal.style.display = 'block';
            change.innerHTML = `<div class='row'>
                                    <div class='col-lg-12'>
                                        <div class='panel panel-default'>
                                            <div class='panel-heading'>
                                                تعديل المستخدم
                                            </div>
                                        <!-- /.panel-heading -->
                                        <div class='panel-body'>
                                            <form class='form text-right' action='' method='post'>
                                                <h3 class='text-center text-info'>تعديل البيانات</h3>                                        
                                                <div class='form-group'>
                                                    <label for='user' class='text-info'>اسم المستخدم:</label><br>
                                                    <input disabled type='text' name='user' value='$userName' minlength='4' maxlength='32' id='user' class='form-control'>
                                                </div>
                                                <div class='form-group' style = 'margin-bottom: 30px'>
                                                    <label for='password' class='text-info'>كلمة السر القديمة:</label><br>
                                                    <input type='password' name='oldPassword' minlength='4' maxlength='20' id='oldPassword' class='form-control' required>
                                                    <input type='checkbox' id='checkbox' onclick='show()'>
                                                    <label for='checkbox'>عرض الرقم السري</label>
                                                </div>
                                                <div class='form-group' style = 'margin-bottom: 30px'>
                                                    <label for='password' class='text-info'>كلمة السر الجديدة:</label><br>
                                                    <input type='password' name='newPassword' minlength='4' maxlength='20' id='newPassword' class='form-control' require>
                                                    <input type='checkbox' id='checkbox1' onclick='show2()'>
                                                    <label for='checkbox1'>عرض الرقم السري</label>
                                                </div>
                                                <div class='form-group text-right'>
                                                    <input type='submit' name='edit' onClick='editConfirmation(event)' class='btn btn-primary' value='تغير كلمة السر'>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>`;
        }
        // When the user clicks on <span> (x), close the modal
        span.onclick = function () {
            modal.style.display = 'none';
            window.onscroll = function () { };
        }
        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function (event) {
            if (event.target == modal) {
                modal.style.display = 'none';
                window.onscroll = function () { };
            }
        }
        function editConfirmation(event) {
            var c = confirm('هل انت متأكد من البيانات!؟');
            if(!c){event.preventDefault();}
        }
        function show() {
            var x = document.getElementById('oldPassword');
            if (x.type === 'password') {
                x.type = 'text';
            } else {
                x.type = 'password';
            }
        }
        function show2() {
            var x = document.getElementById('newPassword');
            if (x.type === 'password') {
                x.type = 'text';
            } else {
                x.type = 'password';
            }
        }
    </script>
";
