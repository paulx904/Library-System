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
              <a class="nav-link" href="home.php">首頁</a>
            </li>
			<li class="nav-item">
              <a class="nav-link" href="search.php">查詢</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="insert.php">新增書籍</a>
            </li>
            <li class="nav-item active">
              <a class="nav-link" href="#">刪除書籍</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="lend.php">借閱</a>
            </li>
			<li class="nav-item">
              <a class="nav-link" href="return.php">還書</a>
            </li> 
          </ul>

          <form class="form-inline mt-2 mt-md-0">
            <input class="form-control mr-sm-2" type="text" placeholder="Search" aria-label="Search">
            <button class="btn btn-outline-success mr-5 my-2 my-sm-0" type="submit">Search</button>
          </form>
		  <img src="img/account.svg" width="1.5%" height="1.5%">
		  <?php 		   		   			   			  
			  session_start(); 		 			 		   		     		   			   			  
			  echo "<strong><font color='white'>".$_SESSION['username']."</font></strong>"; 
		  ?>		  
		  <a class="nav-link" href="logout.php">登出</a> 
        </div>
      </nav>
    </header><br><br><br>
	
	<div style="width:1350px;height:70px;margin:auto">
		<h2>刪除藏書</h2><br>
	</div>

		<?php
			error_reporting(E_ALL & ~E_NOTICE);
			$db_host = "localhost";
			$db_user = "root";
			$db_pass = "";
			$db_name = "db";
		
			$dbconnect = "mysql:host=".$db_host.";dbname=".$db_name;
			$dbgo = new PDO($dbconnect, $db_user, $db_pass);
			
			//若輸入資訊完整，則執行delete動作；否則，alert使用者
			if (isset($_POST['checkbox'])) 
			{
				$sql = "DELETE FROM book WHERE bid=?";
				foreach($_POST['checkbox'] as $index){
					$statement= $dbgo->prepare($sql);
					$statement->execute([$index]);
				}
				echo "<script>alert('刪除成功!')</script>";
			}
			else
				echo "<script>alert('請勾選要刪除的項目!')</script>";
			
			//以表格顯示所有書的資訊
			$statement = $dbgo->query('SELECT * FROM book, user WHERE book.uid = user.uid ORDER BY bid ASC');
			
			echo "<form action='delete.php' method='POST'>";
			echo "<table style='width:90%'>";
			echo '<tr bgcolor="#FF9797"><th>編號</th><th>標題</th><th>作者</th><th>出版社</th><th>類別</th>
			<th>狀態</th><th>借書日期</th><th>還書期限</th><th>借閱人</th><th></th></tr>';
			foreach($statement as $row){
				$index = $row['bid'];
				echo '<tr><td>'.$row['bid'].'</td><td>'.$row['title'].'</td><td>'
				.$row['author'].'</td><td>'.$row['publisher'].'</td><td>'.$row['category'].'</td>';
				if($row['status'])
					echo '<td><small><p class="btn btn-inline-success m-0">&#10004</small></td>';
				else
					echo '<td><small><p class="btn btn-inline-danger m-0">&#10060</small></td>';
				echo '<td><strong>'.$row['startdate'].'</strong></td><td><strong>'.$row['duedate'].'</strong></td><td><strong>'.$row['uname'].'</strong></td>'; 				
				echo "<td>&nbsp<input type='checkbox' name='checkbox[]' value='$index'/></td></tr>";
			}
			echo "</table><br>";
			echo "<button class='btn btn-danger' type='submit'>刪除</button><br>";
			echo "</form>";
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
