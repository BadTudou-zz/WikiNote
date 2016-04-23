<?php
/*
	Copyright © BadTudou, 2016
	All rights reserved

	Name	:	SQL.php
	By		:	BadTudu
	Date	:	2016年4月23日13:31:05
	Note	:	SQL操作
*/
class MYSQL
{
	protected $m_host;
	protected $m_port;
	protected $m_user;
	protected $m_pwd;
	protected $m_resource;

	public function __construct(string $host, string $port,  string $user, string $pwd)
	{
		$this->m_host = $host;
		$this->m_port = $port;
		$this->m_user = $user;
		$this->m_pwd  = $pwd;
	}

	public function __destruct()
	{
		if ($this->GetConnectState())
		{
			mysqli_close($this->m_resource);
		}
	}

	/**
	 * [连接MYSQL服务器]
	 */
	public function connect()
	{
		$this->m_resource = mysqli_connect($this->m_host.":".$this->m_port, $this->m_user, $this->m_pwd);
	}

	/**
	 * [获取连接状态]
	 * @return [bool]         [状态：已连接:true;未连接:false]
	 */
	public function getConnectState()
	{
		return is_object($this->m_resource);
	}

	/**
	 * [选择数据库]
	 * @param  string $dbname [数据库名称]
	 * @return [bool]         [状态：成功:true;失败:false]
	 */
	public function selectDatabase(string $dbname)
	{
		return mysqli_select_db($this->m_resource, $dbname);
	}
	
	/**
	 * [创建数据库]
	 * @param  string $dbname [数据库名称]
	 * @return [bool]         [状态：成功:true;失败:false]
	 */
	public function createDatabase(string $dbname)
	{
		$sqlcmd = 'CREATE DATABASE IF NOT EXISTS '.$dbname;
		return mysqli_query($this->m_resource, $sqlcmd);
	}

	/**
	 * [删除数据库]
	 * @param  string $dbname [数据库名称]
	 * @return [bool]         [状态：成功:true;失败:false]
	 */
	public function dropDatabase(string $dbname)
	{
		$sqlcmd = 'DROP DATABASE IF EXISTS '.$dbname;
		return mysqli_query($this->m_resource, $sqlcmd);
	}
}

//TEST
$my = new MYSQL('localhost', '3306', 'root', '123qwe');
$my->connect();
if ($my->getConnectState())
{
	echo ' 连接成功';
	if ($my->selectDatabase('mysql'))
	{
		echo ' 选择数据库成功';
		if ($my->createDatabase('WikiNote'));
		{
			echo '创建成功';
		}
		if ($my->dropDatabase('dd'))
		{
			echo '删除成功';
		}		
	}
}
else
{
	echo '连接失败';
}
?>
