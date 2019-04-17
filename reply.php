<!DOCTYPE html>

<html>
	<head>
	<meta charset = "utf-8">
	<title>reply Form</title>
	<link rel = "stylesheet"  type = "text/css" href = "reply.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
	</head>
	<body>
		<div class = "header">
			<div class = "header-logo">掲示板</div>
			
			<div class = "header-list">
				<ul>
					<li>更新</li>
				</ul>
			</div>
		</div>
		
		<div class = "main">
			<div class = "copy-container">
				<h1><span>TSUJITA</span>  掲示板</h1>
				<h2>返信コメントを入力してください</h2>
			</div>
			
			<div class = "board">
			<?php
				try{
					$pdo = new PDO('mysql:host=mysql1.php.xdomain.ne.jp;dbname=ppftech_db1;charset=utf8', 'ppftech_user1','user1234',[PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,]);
				}catch(PDOException $e){
					header('Content-Type: text/plain; charset=UTF-8', true, 500);
					exit($e->getMessage());
				}

				$page = 1;
				if('id' != ""){
					$postid = $_GET['id'];
				}else{
					$postid;
				}
				$page = empty($_GET["page"])? 1:$_GET["page"];

				
				if($page == 1){
						$sql = " select * from reply WHERE postid = $postid ORDER BY replyday DESC LIMIT ".($page*10);
					}else if(9 >= $page && $page >= 2 ){
						$sql = " select * from reply WHERE postid = $postid ORDER BY replyday DESC LIMIT ".($page*10)." OFFSET ".($page*10-9);
					}else if($page == 10){
						$sql = " select * from reply WHERE postid = $postid ORDER BY replyday DESC LIMIT 100 OFFSET ".($page*10-9);
					}else{
						header("Location:index.php");
					}
//					echo $sql . '<br />';
					
				$arr = $pdo->query($sql);
				
//				var_dump($arr);
				
				if($arr !== false){
			?>
				<table class = "table" border = "1">
						<?php foreach ($arr as $row){ ?>
							<tr>
								<td class = "tablepostid"><?php 
//								$row['postid'] = $postid;
								echo "<p>$row[postid]</p>"; ?></td>
								<td><?php echo "<p>$row[replyid]</p>"; ?></td>
								<td class = "tablename"><?php echo "<p>$row[replyname]</p>"; ?></td>
								<td class = "tableday"><?php echo "<p>$row[replyday]</p>"; ?></td>
								<td class = "tabletext"><?php echo "<p>$row[replytext]</p>"; ?></td>
							</tr>
						<?php } ?>
  					</table>
  					
  					
  				<?php }else{ echo 'データの取得に失敗しました'; } ?>
  				<br><br><br>
			</div>
			
			<div class = "enter-form">
			<?php
				$sql=$pdo->prepare('insert into reply(replyname, replyday, replytext, postid) values(?, NOW() , ?, ?)');
				
				if(empty($_POST['replyname'])){
					echo '投稿者名を入力してください';
				}
				else if(empty($_POST['replytext'])){
					echo '本文を入力してください';
				}
				else if($sql->execute([$_POST['replyname'], $_REQUEST['replytext'], $_REQUEST['id']])){
					echo '追加に成功しました。';
					$url = "http://localhost/tsujita/reply.php?id=".$postid;
					header("Location:".$url);
				}
				else{
					echo '追加に失敗しました';
				}
			 ?>
				<form action="" method="post">
  					<table class = "table" border = "1">
    					<tr>
      						<td>投稿者</td>
      						<td><input maxlength = '10' type="text" name="name" class="focusName" value=""></td>
    					</tr>
    						
    					<tr>
    						<td>本文</td>
      						<td><textarea type="text" name="text" class="focusComment" value=""></textarea wrap="hard"></td>
    					</tr>
  					</table>
  					
        			<input type="submit" value="投稿">
        			
				</form>
				
				<?php
					
					function paging($limit, $page, $disp = 5){
					
						$page = empty($_GET["page"])? 1:$_GET["page"];
						$next = $page+1;
						$prev = $page-1;
						
						$start =  ($page-floor($disp/2) > 0) ? ($page-floor($disp/2)) : 1;
						$end =  ($start > 1) ? ($page+floor($disp/2)) : $disp;
						$start = ($limit < $end)? $start-($end-$limit):$start;
						if('id' != ""){
							$postid = $_GET['id'];
						}else{
							$postid;
						}
						$urll ="id=".$postid;

						
						if($page !=1){
							print '<a href="?'.$urll.'&page='.$prev.'">&laquo; 前へ</a>';
						}
						
						if($start >= floor($disp/2)){
							print '<a href="?'.$urll.'&page=1">1</a>';
							if($start > floor($disp/2)) print "...";
						}
						
						for($i=$start; $i <= $end ; $i++){
							$class = ($page == $i) ? ' class="current"':"";
							if($i <= $limit && $i > 0 ){
								print '<a href="?'.$urll.'&page='.$i.'"'.$class.'>'.$i.'</a>';
							}
						}
						
						if($limit > $end){
							if($limit-1 > $end ) print "...";
							print '<a href="?'.$urll.'&page='.$limit.'">'.$limit.'</a>';
						}
						
						if($page < $limit){
							print '<a href="?'.$urll.'&page='.$next.'">次へ &raquo;</a>';
						}
					}
					
					$limit = 10;
					paging($limit, $page);
				?>
				
				<form action="index.php" method="post">
        			<input type="submit" value="戻る">
				</form>
				
			</div>
			<p class="replyToTop">ページトップへ</p>
		</div>
		
		<div class = "footer">
			<div class = "footer-logo">
				掲示板
			</div>
			
			<div class = "footer-list">
				<ul>
					<li>お問い合わせ</li>
				</ul>
			</div>
		</div>
		<script src="reply.js" charset="UTF-8"></script>
	</body>


</html>