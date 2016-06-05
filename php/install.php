<!DOCTYPE html>
<html>
<head>
	<title>WikiNote</title>
	<meta charset="UTF-8">
</head>
<body>
	<?php
	require_once('WIKINOTE_MANAGER.php');
	echo ' 连接数据库服务器......  ';
	$my = new WIKINOTE_MANAGER('localhost', '3306', 'root', 'mysql');
	$my->m_resource->connect();
	if ($my->m_resource->getConnectState())
	{
		echo '成功<br/>';
		echo ' 创建数据库......';
		if ($my->m_resource->createDatabase('WikiNote'));
		{
			echo '成功<br/>';
			echo ' 初始化数据库......';
			$my->initDatabase('WikiNote');
			/*
			$sqlcmd = 'insert into type (t1ID, t2ID, t3ID, t4ID) values (1,1,1,1)';
			$my->m_resource->executeQuery($sqlcmd);
			*/
			
			echo '成功<br/>';
		}
	}
	else
	{
		echo '失败';
	}
	?>
</body>
</html>

	