<?php
require 'connection.php';
session_start();
/*if (isset($_GET['file_id'])) {
    $id = $_GET['file_id'];
    $user_role = $_SESSION['user_role'];
    $result = mysqli_query($conn, "SELECT * FROM decision WHERE id=$id");
    $file = mysqli_fetch_assoc($result);
    $filepath = 'uploads/' . $user_role . " رقم " . $id . ' ' . $file['attach_file'];

    if (file_exists($filepath)) {
        header('Content-Type: application/octet-stream');
        header('Content-Description: File Transfer');
        header('Content-Disposition: attachment; filename=' . basename($filepath));
        header('Expires: 0');
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header('Pragma:public');
        header('Contnet-Length:' . filesize($filepath));
        readfile($filepath);
        exit;
    }
    mysqli_close($conn);
}*/ if ($_POST['action'] && $_POST['id']) {
    if ($_POST['action'] == 'اضافة') {
        $username = $_SESSION['user_id'];
        $user_role = $_SESSION['user_role'];

        $decision_id = $_REQUEST['decision_id'];
        $decision_year = $_REQUEST['decision_year'];
        $decision_date = $_REQUEST['decision_date'];
        $decision_subject = $_REQUEST['decision_subject'];
        $decision_from = $_REQUEST['decision_from'];
        $decision_to = $_REQUEST['decision_to'];
        $decision_type = $_REQUEST['decision_type'];
        $decision_category = $_REQUEST['decision_category'];
        ///////// 
        if (!file_exists('uploads/')) {
            mkdir('uploads/', 0777, true);
        }
        $targetDir = "uploads/";
        $fileName = $_FILES["file"]["name"];
        $targetFilePath = $targetDir . $user_role . " رقم " . $decision_id . ' ' . $fileName;
        $fileType = pathinfo($fileName, PATHINFO_EXTENSION);
        $file = $_FILES['file']['tmp_name'];
        $size = $_FILES['file']['size'];

        if ($_FILES["file"]["size"] > 20000000) {
            echo "عذراً، حجم الملف اكبر من 2MB";
        } else if (move_uploaded_file($file, $targetFilePath)) {
            $sql = "INSERT INTO decision (id,year,date,subject,decision_from,decision_to,type,category,attach_file,entry_name,entry_type)
         VALUES ($decision_id , $decision_year , '$decision_date' , '$decision_subject','$decision_from','$decision_to','$decision_type','$decision_category','$fileName','$username','$user_role');";
            if (mysqli_query($conn, $sql)) {
                echo "<script>
                alert('تمت الاضافة');
                window.location.href='decisions.php';
                </script>";
            } else {
                echo "ERROR: Hush! Sorry $sql. "
                    . mysqli_error($conn);
            }
        } else {
            echo "<script>
                alert('خطأ في رفع الملف!');
                window.location.href='forms.php';
            </script>";
        }
    }
    if ($_POST['action'] == 'حذف') {
        $project_id = $_POST['id'];
        $sql = "DELETE FROM projects_expense WHERE project_id = $project_id;
        DELETE FROM projects_comment WHERE project_id = $project_id;
        DELETE FROM projects WHERE id = $project_id;";
        if (mysqli_multi_query($conn, $sql)) {
            echo "<script>confirm('متأكد من الحذف!');</script>";
            header("Location: decisions.php");
            $conn->close();
            die();
        } else {
            echo "ERROR: Hush! Sorry $sql. "
                . mysqli_error($conn);
        }
    }
    if ($_POST['action'] == 'تعديل') {
        $id = $_POST['id'];
        session_start();
        $_SESSION['id'] = $id;
        header("Location: projectEdit.php");
    }
    if ($_POST['action'] == 'عرض') {
        $id = $_POST['id'];
        session_start();
        $_SESSION['id'] = $id;
        header("Location: details.php");
    }
    if ($_POST['action'] == 'اضافة صلة') {
        $main_id = $_POST['id'];
        $sec_id = $_REQUEST['d_relation'];
        $sql = "SELECT * FROM `decision_relation` where `main_decision_id` = $main_id AND `sec_decision_id` = $sec_id OR (`main_decision_id` = $sec_id AND `sec_decision_id` = $main_id)";
        $users = mysqli_query($conn, $sql);
        $row = mysqli_num_rows($users);
        $data = mysqli_fetch_array($users);
        if ($row == 0) {
            //
            $sql = "INSERT INTO `decision_relation` (`main_decision_id`, `sec_decision_id`) values ($main_id , $sec_id);";
            if (mysqli_query($conn, $sql)) {
                session_start();
                $_SESSION['id'] = $main_id;
                header("Location: details.php");
            } else {
                echo "ERROR: Hush! Sorry $sql. "
                    . mysqli_error($conn);
            }
            //            
        } else {
            echo "<script>
            alert('موجود مسبقاً');
            window.location.href='decisions.php';
            </script>";
        }
    }
    if ($_POST['action'] == 'ارشيف') {
        $id = $_POST['id'];
        $sql = "UPDATE `decision` SET `archive`='1' WHERE id=$id";
        if (mysqli_multi_query($conn, $sql)) {
            header("Location: decisions.php");
            $conn->close();
            die();
        } else {
            echo "ERROR: Hush! Sorry $sql. "
                . mysqli_error($conn);
        }
    }
    if ($_POST['action'] == 'استرجاع') {
        $id = $_POST['id'];
        $sql = "UPDATE `decision` SET `archive`= 0 WHERE id=$id";
        if (mysqli_multi_query($conn, $sql)) {
            header("Location: archive.php");
            $conn->close();
            die();
        } else {
            echo "ERROR: Hush! Sorry $sql. "
                . mysqli_error($conn);
        }
    }
    if ($_POST['action'] == 'تعديل القرار') {
        $id = $_POST['id'];
        $decision_id = $_REQUEST['decision_id'];
        $decision_year = $_REQUEST['decision_year'];
        $decision_date = $_REQUEST['decision_date'];
        $decision_subject = $_REQUEST['decision_subject'];
        $decision_from = $_REQUEST['decision_from'];
        $decision_to = $_REQUEST['decision_to'];
        $decision_type = $_REQUEST['decision_type'];
        $decision_category = $_REQUEST['decision_category'];
        //////
        $targetDir = "uploads/";
        $fileName = $_FILES["file"]["name"];
        $targetFilePath = $targetDir . $decision_id . $fileName;
        $fileType = pathinfo($fileName, PATHINFO_EXTENSION);
        $file = $_FILES['file']['tmp_name'];
        $size = $_FILES['file']['size'];
        //////
        $result = mysqli_query($conn, "SELECT attach_file FROM decision WHERE id=$id");
        $row = mysqli_fetch_row($result);
        $name = $row[0];
        $file_pointer = 'uploads/' . $id . $name;
        if (file_exists($file_pointer)) {
            unlink($file_pointer);
            if (move_uploaded_file($file, $targetFilePath)) {
                $sql = "UPDATE `decision` SET `id`='$decision_id',`year`='$decision_year',`date`='$decision_date',`subject`='$decision_subject',`decision_from`='$decision_from',`decision_to`='$decision_to',`type`='$decision_type',`category`='$decision_category',`attach_file`='$fileName' WHERE id = $id";
                if (mysqli_query($conn, $sql)) {
                    echo "<script>
                    alert('تم التعديل');
                    window.location.href='decisions.php';
                    </script>";
                } else {
                    echo "ERROR: Hush! Sorry $sql. "
                        . mysqli_error($conn);
                }
            } else {
                echo "<script>
                    alert('خطأ في رفع الملف!');
                    window.location.href='decisions.php';
                </script>";
            }
        } else {
            if (move_uploaded_file($file, $targetFilePath)) {
                $sql = "UPDATE `decision` SET `id`='$decision_id',`year`='$decision_year',`date`='$decision_date',`subject`='$decision_subject',`decision_from`='$decision_from',`decision_to`='$decision_to',`type`='$decision_type',`category`='$decision_category',`attach_file`='$fileName' WHERE id = $id";
                if (mysqli_query($conn, $sql)) {
                    echo "<script>
                    alert('تم التعديل');
                    window.location.href='decisions.php';
                    </script>";
                } else {
                    echo "ERROR: Hush! Sorry $sql. "
                        . mysqli_error($conn);
                }
            } else {
                echo "<script>
                    alert('خطأ في رفع الملف!');
                    window.location.href='decisions.php';
                </script>";
            }
        }
    }
    if ($_POST['action'] == 'الغاء الارتباط') {
        $id = $_POST['newid'];
        $newid = $_REQUEST['id'];
        $sql = "DELETE FROM `decision_relation` WHERE id = $id";
        if (mysqli_multi_query($conn, $sql)) {
            session_start();
            $_SESSION['id'] = $newid;
            header("Location: details.php");
        }
    }
    //========= admin options================//
    if ($_POST['action'] == 'حذف المستخدم') {
        $currentUser = $_SESSION['user_id'];
        $user = $_POST['username'];
        $id = $_POST['id'];
        if ($user == $currentUser) {
            echo "<script>
            alert('لا يمكنك حذف نفسك!');
            window.location.href='admin.php';
            </script>";
        } else {
            $sql = "DELETE FROM users WHERE id = $id;";
            if (mysqli_query($conn, $sql)) {
                echo "<script>
                    alert('تم الحذف!');
                    window.location.href='admin.php';
                   </script>";
            } else {
                echo "<script>
                    alert('خطأ في السيرفر! تعذر الاتصال');
                    window.location.href='admin.php';
                </script>";
            }
        }
    }
    if ($_POST['action'] == 'تعديل المستخدم') {
        $id = $_POST['id'];
        session_start();
        $_SESSION['id'] = $id;
        header("Location: user-edit.php");
    }
    if ($_POST['action'] == 'تعديل الموظف') {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $user = $_POST['user'];
        $role = $_POST['role'];
        $password = $_POST['password'];
        if ($password == '') {
            $sql = "UPDATE users
            SET 
                name = '$name',
                username = '$user',
                role = '$role'
            WHERE 
                id = $id;";
            if (mysqli_query($conn, $sql)) {
                echo "<script>
                alert('تم التعديل!');
                window.location.href='admin.php';
            </script>";
            } else {
                echo "<script>
                alert('خطأ في السيرفر! تعذر الاتصال');
                window.location.href='admin.php';
            </script>";
            }
        } else {
            $password = hash_hmac("sha256", $password, $user);
            $sql = "UPDATE users
            SET 
                name = '$name',
                username = '$user',
                passhash = '$password',
                role = '$role'
            WHERE 
                id = $id;";
            if (mysqli_query($conn, $sql)) {
                echo "<script>alert('تم التعديل!');</script>";
                header("Location: admin.php");
                $conn->close();
                die();
            } else {
                echo "ERROR: Hush! Sorry $sql. "
                    . mysqli_error($conn);
            }
        }
    }
}
