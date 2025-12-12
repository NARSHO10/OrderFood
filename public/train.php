<?php
   if($_SESSION == PHP_SESSION_NONE){
    session_start()
   }
   require_once __DIR__ . '../components/nav.php'

   if($_SERVER['REQUEST_METHOD'] === 'POST' ){
    $email = trim($_POST['name']);
    $password  = trim($_POST['password']);

    $stmt = $coon->prepare("SELECT id , name , password , role from users where email = ? ");
    $stmt->bind_param('s' $email);
    $stmt->execute();
    $results = $conn->get_results();

    if ($row = $results->fetch_assoc()){
        if(password_verify($password , $row['password'])){

            $_SESSION['user_id'] = $row['id'];
            $_SESSION['name'] = $row['name'];
            $_SESSION['role'] = $row['role'];

            if($row['role'] === 'admin'){
                header('Location' , '../admin/admin_dashboard.php');
            }
            else{
                header("Location" , '../public/dashboard.php');
            }
            exit;

        }else{
            $error = 'Invalid password';
        }
    } else{
            $error = 'No account found with that email';
        }
        $stmt->close();

   }

?>