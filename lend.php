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
            <li class="nav-item">
              <a class="nav-link" href="delete.php">刪除書籍</a>
            </li>
            <li class="nav-item active">
              <a class="nav-link" href="#">借閱</a>
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
	
	<div style="width:450px;height:150px;margin:auto">
		<h2>借閱書籍</h2><br>
		<form class="form-inline" action="lend.php" method="POST">
			查詢:<select class="form-control ml-1 mr-2" name="selected_method">
					<option>標題</option><option>作者</option><option>出版社</option><option>類別</option><option>所有結果</option>
				</select>
			<input type="text" class="form-control ml-1 mr-2" name="keyword" placeholder="請輸入關鍵字"/>
			<button class="btn btn-primary" type="submit">送出</button>
		</form>
	</div>
    
	<?php
		error_reporting(E_ALL & ~E_NOTICE);
		$db_host = "localhost";
		$db_user = "root";
		$db_pass = "";
		$db_name = "db";
	
		$dbconnect = "mysql:host=".$db_host.";dbname=".$db_name;
		$dbgo = new PDO($dbconnect, $db_user, $db_pass);
		
		//判斷是哪種查詢方式
		$keyword = $_POST['keyword'];
		switch ($_POST['selected_method']) {
		    case "標題":
				$selected_method = 'title';
				break;
		    case "作者":
				$selected_method = 'author';
				break;
		    case "出版社":
				$selected_method = 'publisher';
				break;
			case "類別":
				$selected_method = 'category';
				break;
			case "所有結果": 				
				$selected_method = 'all'; 				
				break;
		}
		if($selected_method != 'all')
			$sql = "SELECT * FROM book, user WHERE book.uid = user.uid && $selected_method LIKE '%$keyword%' ORDER BY bid ASC";
		else
			$sql = "SELECT * FROM book, user WHERE book.uid = user.uid ORDER BY bid ASC";
		$statement = $dbgo->query($sql);

		if($selected_method == 'all' || $keyword){
			$result = $statement->fetch(PDO::FETCH_BOTH); //抓取$statement內容,判斷是否有select到資料
			if(!empty($result[0])){						//若有資料則列出供使用者借閱
				echo "<form action='lend_form.php' method='POST'>";
				echo "<table style='width:90%'>";
					echo '<tr bgcolor="#FF9797"><th>編號</th><th>標題</th><th>作者</th><th>出版社</th><th>類別</th>
					<th>狀態</th><th>借書日期</th><th>還書期限</th><th>借閱人</th><th></th></tr>';
					$statement = $dbgo->query($sql);
					foreach($statement as $row){
						$bid = $row['bid'];
						echo '<tr><td>'.$row['bid'].'</td><td>'.$row['title'].'</td><td>'
						.$row['author'].'</td><td>'.$row['publisher'].'</td><td>'.$row['category'].'</td>';
						if($row['status'])
							echo '<td><small><p class="btn btn-inline-success m-0">&#10004</small></td>';
						else
							echo '<td><small><p class="btn btn-inline-danger m-0">&#10060</small></td>';
						echo '<td><strong>'.$row['startdate'].'</strong></td><td><strong>'.$row['duedate'].'</strong></td><td><strong>'.$row['uname'].'</strong></td>'; 					
						echo "<td>&nbsp<input type='radio' name='borrow' value='$bid'></td></tr>";
					}
				echo "</table>";
				echo "<br><button class='btn btn-danger' type='submit'>借閱/預約</button><br><br>";
				echo "</form>";
			}
			else{
				echo "<div style='width:300px;height:400px;margin:auto'><p align='left'>找不到符合搜尋字詞「".$keyword."」的書籍。<br>";
				echo "建議：<br>．請檢查有無錯別字。<br>．試試以其他關鍵字搜尋。<br>．試試以較籠統的關鍵字搜尋。<br></p></div>";
			}
		}
		else
			echo "<script>alert('請輸入關鍵字!')</script>";
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