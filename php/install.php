<!DOCTYPE html>
<html>
<head>
	<title>WikiNote</title>
	<meta charset="UTF-8">
	<link rel="stylesheet" type="text/css" href="../css/index-style.css">
	<script type="text/javascript" src="../js/jquery-2.2.1.min.js"></script>
	<script type="text/javascript" src="../js/index.js"></script>
</head>
<body>
	<?php
	require_once('manage.php');
	$my = new MANAGE('localhost', '3306', 'root', 'mysql');
	$my->m_database->connect();
	if ($my->m_database->getConnectState())
	{
	echo ' 连接成功';
		if ($my->m_database->createDatabase('WikiNote'));
		{
			echo '创建成功';
			$my->initDatabase('WikiNote');
			$my->addUser('user1', 'pwd1');
			$my->addUser('user2', 'pwd2');
			$my->changeUserPWD('pwd2', 'user2', 'newpwd');
			echo '2ok'.$my->addType(array(),'工业技术');
			echo '2ok'.$my->addType(array(),'数理化');
			echo '2ok'.$my->addType(array(),'人文');
			echo '4ok'.$my->addType(array('工业技术'),'计算机');
			echo '4ok'.$my->addType(array('工业技术'),'机械');
			echo '4ok'.$my->addType(array('工业技术'),'航天');
			echo '4ok'.$my->addType(array('工业技术', '计算机'),'编程技术');
			echo '4ok'.$my->addType(array('工业技术', '计算机','编程技术'),'C语言');
			$my->getSubTypes(array('工业技术'));
			$my->getSubTypes(array('工业技术', '计算机'));
			$my->getSubTypes(array('测试2'));
			$my->delSubTypes(array('测试'));
			$my->delSubTypes(array('测试2'));
			$sqlcmd = 'insert into type (t1ID, t2ID, t3ID, t4ID) values (1,1,1,1)';
			$my->m_database->executeQuery($sqlcmd);
			$sqlcmd = "insert into manager (nickname, pwd) values ('admin', 'admin')";
			$my->m_database->executeQuery($sqlcmd);
			$my->addNote(1, 1, '我有一事，生死予之');
			$my->addNote(1, 1, '我承认我不曾历经沧桑');
			$my->addNote(1, 1, '时间旅行者的妻子');
			$my->addNote(1, 1, '一个，很高兴认识你');
			$my->addNote(1, 1, '你好，旧时光');
			$my->addNote(1, 1, '城南旧事');
	
		}
		if ($my->m_database->dropDatabase('dd'))
		{
			echo '删除成功';
		}
	}
	else
	{
		echo '连接失败';
	}
	?>
</body>
</html>

	