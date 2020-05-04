<?php		
		include("sendmail.php"); 	
		session_start();
		error_reporting(E_ALL & ~E_NOTICE);
		date_default_timezone_set('Asia/Taipei');
		$db_host = "localhost";
		$db_user = "root";
		$db_pass = "";
		$db_name = "db";

		$dbconnect = "mysql:host=".$db_host.";dbname=".$db_name;
		$dbgo = new PDO($dbconnect, $db_user, $db_pass);
		
		if (!is_null($_POST['borrow'])){			//判斷使用者是否有勾選要借閱/預約的書
			$sql = "SELECT status FROM book WHERE bid = ?";		
			$statement = $dbgo->prepare($sql);
			$statement->execute([$_POST['borrow']]);
			foreach ($statement as $row){
				if($row['status'])
					$status = true;
				else
					$status = false;
			}
			
			if($status){				//判斷此書是否在館
			$sql_start = "UPDATE book SET startdate = ? WHERE bid = ?";		//設置startdate
			$statement = $dbgo->prepare($sql_start);
			$statement->execute([date("Y-m-d H:i:s"), $_POST['borrow']]);
			$sql_due = "UPDATE book SET duedate = ? WHERE bid = ?";			//設置duedate
			$statement = $dbgo->prepare($sql_due);
			$statement->execute([date("Y-m-d H:i:s", strtotime("+7 days")), $_POST['borrow']]);
			$sql_due = "UPDATE book SET returndate = ? WHERE bid = ?";			//設置returndate 			
			$statement = $dbgo->prepare($sql_due); 			
			$statement->execute([NULL, $_POST['borrow']]);
			$sql_status = "UPDATE book SET status = 0 WHERE bid = ?";			//設置status
			$statement = $dbgo->prepare($sql_status);
			$statement->execute([$_POST['borrow']]);
			
			$sql_uid = "SELECT uid FROM user WHERE uname = ?";				//根據uname獲得uid
			$statement= $dbgo->prepare($sql_uid);
			$statement->execute([$_SESSION['username']]);
			$result = $statement->fetch(PDO::FETCH_BOTH);
			$sql_uid = "UPDATE book SET uid = ? WHERE bid = ?";				//設置uid
			$statement = $dbgo->prepare($sql_uid);
			$statement->execute([$result[0], $_POST['borrow']]);
			
			echo "<script>alert('借閱成功!');parent.location.href='lend.php';</script>";
			}
			else{
				$sql_uid = "SELECT uname,title FROM user, book WHERE user.uid = book.uid && book.bid = ?";	  //獲得借閱人的uname			
				$statement= $dbgo->prepare($sql_uid); 			
				$statement->execute([$_POST['borrow']]);
				foreach ($statement as $row){ 
					$borrowname = $row['uname'];
					$borrowbook = $row['title'];
				}
				sendmail($borrowname,$borrowbook); 	//sendmail(借閱人,標題)
			}
		}
		else
			echo "<script>alert('請勾選要借閱/預約的書籍!');parent.location.href='lend.php';</script>";
?>