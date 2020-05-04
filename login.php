<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico">

    <title>Carousel Template for Bootstrap</title>

    <!-- Bootstrap core CSS -->
    <link href="dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
      
    <!-- Custom styles for this template -->
    <link href="carousel.css" rel="stylesheet">
  </head>
  <body>
  
    <header>
      <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
        <a class="navbar-brand" href="#">Library System</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
          <ul class="navbar-nav mr-auto">
            <li class="nav-item">
              <a class="nav-link" href="main.php">首頁</a>
            </li>
		  </ul>
		  
		  <form class="form-inline mt-2 mt-md-0">
            <input class="form-control mr-sm-2" type="text" placeholder="Search" aria-label="Search">
            <button class="btn btn-outline-success mr-1 my-2 my-sm-0" type="submit">Search</button>
          </form>		   			  		  
		  <a class="nav-link" href="#">登入</a>
        </div>
      </nav>
    </header><br><br><br>
	
	<div style="width:400px;height:580px;margin:auto">
		<h2>登入系統</h2><br>
		  <form class="form-inline" action="login.php" method="POST">
	　		帳號:<input class="form-control ml-1" type="text" name="username" placeholder="請輸入使用者名稱">
	　		<button class="btn btn-outline-primary" type="submit">Login</button>
		  </form>
	</div>
	
	 <!-- 與SQL資料庫連接 -->
	<?php
		session_start();
		error_reporting(E_ALL & ~E_NOTICE);
		$db_host = "localhost";
		$db_user = "root";
		$db_pass = "";
		$db_name = "db";

		$dbconnect = "mysql:host=".$db_host.";dbname=".$db_name;
		$dbgo = new PDO($dbconnect, $db_user, $db_pass);
		$statement = $dbgo->query('SELECT uname FROM user');
		$permission = false;
		
		if($_POST['username'] != NULL){
			foreach($statement as $row){							//判斷使用者是否有權限
				if($row['uname'] == $_POST['username']){			
					echo "<script>alert(\"歡迎登入! ".$row['uname']."\");parent.location.href='home.php';</script>";
					$permission = true;
					$_SESSION['username'] = $_POST['username'];
					break;
				}
			}
			if ($permission == false)
				echo "<script>alert('你無權限造訪本系統!');parent.location.href='login.php';</script>";
		}
	?>
	
		  <!-- FOOTER -->
	  <footer class="container">
	    <p class="float-right"><a href="#">Back to top</a></p>
		<p>&copy; 2017-2018 Company, Inc. &middot; <a href="#">Privacy</a> &middot; <a href="#">Terms</a></p>
	  </footer>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="assets/js/vendor/jquery-slim.min.js"><\/script>')</script>
    <script src="assets/js/vendor/popper.min.js"></script>
    <script src="dist/js/bootstrap.min.js"></script>
    <!-- Just to make our placeholder images work. Don't actually copy the next line! -->
    <script src="assets/js/vendor/holder.min.js"></script>
  </body>
</html>
