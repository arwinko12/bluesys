<?php
session_start();
include 'db.conn.php';
if (isset($_SESSION['user'])) {
  header(header: "location: admin/index.php");
  exit();
}
function test_input($data): string {
  $data = trim(string: $data);
  $data = stripslashes(string: $data);
  $data = htmlspecialchars(string: $data);
  return $data;
}

$user_emailErr = $user_passwordErr = "";
$user_email = $user_password = "";
if ($_SERVER['REQUEST_METHOD'] == "POST") {
   $user_email = test_input(data: $_POST['user_email']);
  $user_password = test_input(data: $_POST['user_password']);


    if (!filter_var(value: $user_email, filter: FILTER_VALIDATE_EMAIL)) {
    $user_emailErr = "* Invalid Email Format!";
   }else{
    $user_email = test_input(data: $_POST['user_email']);
   }

 
    if (empty($user_email)) {
      $user_emailErr = "* Email is required!";
    }else{
      $user_email = test_input(data: $_POST['user_email']);
    }

    if (empty($user_password)) {
      $user_passwordErr = "* Password is required!";
    }else{
      $user_password = test_input(data: $_POST['user_password']);
    }

 
  

  if (empty($user_emailErr) && empty($user_passwordErr)) {
  $verify = $conn->prepare(query: "SELECT * FROM `tbl_users` WHERE ins_email = ? AND password = ?");
  $verify->bind_param(types: "ss", var: $user_email, vars: $user_password);
  $verify->execute();
  $verify_result = $verify->get_result();


  if ($verify_result->num_rows > 0 ) {
    $data = $verify_result->fetch_assoc();
    $_SESSION['user'] = $data['user_id'];
    $_SESSION['users_f_name'] = $data['f_name'];
    $_SESSION['users_m_name'] = $data['m_name'];
    $_SESSION['users_l_name'] = $data['l_name'];
    header(header: "Location: admin/index.php");
  }else{
    header(header: "Location: index.php");
    exit();
  }


  }

}
 ?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Big+Shoulders+Stencil:opsz,wght@10..72,100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
  </head>
  <style type="text/css">
    @import url("https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css");
    body{
    font-family: 'Arial', 'Helvetica', sans-serif;
    background-color: #2E5077;
    height: 100vh;
    width: 100%;
    }
    .roboto{
      font-family: "Roboto", sans-serif;
      font-optical-sizing: auto;
      font-weight: <weight>;
      font-style: normal;
      font-variation-settings:
        "wdth" 100;
    }
    .big-shoulders-stencil {
      font-family: "Big Shoulders Stencil", sans-serif;
      font-optical-sizing: auto;
      font-weight: <weight>;
      font-style: normal;
    }

    .col-lg-12{
      justify-content: center;
      align-items: center;
      display: flex;
      height: 100vh;
      width: 100%;
    }
    .card{

      box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
    }

   .card button{
      background:  #2E5077;
      color: #F6F8D5;
      transition: 0.5s;
      border: none;
      padding: 10px;
      border-radius: 10px;
      text-align: center;
      width: 100%;

    }
   .card button:hover{
    background: #4DA1A9;
    color: #F6F8D5;
    transform: translateY(-5px);
   }

   .card-header{
    background: #2E5077;
   }
   .card-header img{
    height: 70px;
    width: 70px;
    border-radius: 20px;
   }
   .card-header h3{
    color: #F6F8D5;
   }
  </style>
  <body>
    
    <div class="container">
      <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header">
                <div class="mb-3 text-center">
              <img src="img/pngwing.com.png" class="img-fluids">
              <h3 class="big-shoulders-stencil">BLUESYS</h3>
             </div>
              </div>
              <div class="card-body">
                <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                <div class="mb-3">
                  <label class="roboto" for="user_email">Email</label>
                  <input type="text" class="form-control" id="user_email" name="user_email">
                  <span class="text-danger">
                    <?= $user_emailErr; ?>
                  </span>
                </div>
                <div class="mb-3">
                  <label class="roboto" for="user_password">Password</label>
                  <input type="password" class="form-control" id="user_password" name="user_password">
                  <span class="text-danger">
                    <?= $user_passwordErr; ?>
                  </span>
                </div>
                <div class="mb-3">
                  <button type="submit" class=" btn-md roboto">Login <i class="bi bi-box-arrow-in-right"></i></button>
                </div>
                </form>
              </div>
            </div>
          </div>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  </body>
</html>