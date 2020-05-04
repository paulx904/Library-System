<?php
	function sendmail($whoborrow,$book){
		
		$db_host = "localhost"; 		
		$db_user = "root"; 		
		$db_pass = ""; 		
		$db_name = "db";  		
		$dbconnect = "mysql:host=".$db_host.";dbname=".$db_name; 		
		$dbgo = new PDO($dbconnect, $db_user, $db_pass);
		
		$sql_mail = "SELECT uemail, startdate FROM user, book WHERE user.uid = book.uid && uname = ?";
		$statement= $dbgo->prepare($sql_mail); 			 				
		$statement->execute([$whoborrow]);
		foreach ($statement as $row){  					
			$to_email = $row['uemail']; 					
			$borrowdate = $row['startdate']; 				
		}
		
		$subject = "提醒還書通知";  
		$body = $whoborrow."您好:\n您於".$borrowdate."借閱的書籍:\n《".$book."》，有人要借閱請盡快歸還。";  
		$headers = "From: Library System_線上圖書館管理系統";   
		if (mail($to_email, $subject, $body, $headers)){
			echo "<script>alert('已幫您通知借閱人盡速歸還!');parent.location.href='lend.php';</script>";			
		}  
		else{      	
			echo "Email sending failed...";
		}
    }
	
	function sendmail_Group($book){
			
 		$db_host = "localhost"; 		 		
		$db_user = "root"; 		 		
		$db_pass = ""; 		 		
		$db_name = "db";  		 		
		$dbconnect = "mysql:host=".$db_host.";dbname=".$db_name; 		 		
		$dbgo = new PDO($dbconnect, $db_user, $db_pass); 	
		
		$sql_mail = "SELECT uemail FROM user"; 		
		$statement= $dbgo->prepare($sql_mail); 			 				 		
		$statement->execute(); 
		
		$recipients = array();				//將所有user的email存放到recipients陣列裡
		foreach ($statement as $row){
			array_push($recipients, $row['uemail']);
		}
		
		$to_email = implode(',', $recipients); 
		$subject = "新書上架通知";   		
		$body = "您好:\n有增加新的書籍:《".$book."》，有興趣的用戶歡迎借閱。"; 
  		$headers = "From: Library System_線上圖書館管理系統";    		
		if (mail($to_email, $subject, $body, $headers)){ 			
			echo "<script>alert('Email寄送成功!')</script>";
		}   		
		else{      	 			
			echo "<script>alert('Email寄送失敗!')</script>"; 		
		}
    }
?>