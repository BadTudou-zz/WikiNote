<?php
	require_once('SQL.php');
	class WIKINOTE_MANAGER_NOTETYPE
	{
		public $m_resource;

		public function __construct(string $host, string $port,  string $user, string $pwd)
		{
			$this->m_resource = new MYSQL($host, $port, $user, $pwd);
			$this->m_resource->connect();
			$this->m_resource->selectDatabase('WikiNote');
		}

		/**
		 * [获取类型的ID，最上层]
		 * @param  array  $aType [层次类型]
		 * @return int        [存在：类型ID, 不存在：false]
		 */
		public function getTypeID(array $aType)
		{
			$tables = '';
			$sqlcmdWhere ='';
			$depth = count($aType);
			for ($i=1; $i < $depth; $i++) 
			{ 
				$tables .= ", t$i";
				$sqlcmdWhere .= " AND t".($i).".title = '". $aType[$i-1] ."'";
			}

			$sqlcmd = 'SELECT t' .$depth. ".tID FROM t$depth$tables WHERE t$depth".".title = '" .$aType[$depth-1] ."'";
			$sqlcmd .= $sqlcmdWhere;
			$queryResult = $this->m_database->executeQuery($sqlcmd);
			if($queryResult)
			{
				$field = mysqli_fetch_array($queryResult, MYSQLI_ASSOC);
				return $field['tID'];
			}
			return false;
		}

		/**
		 * [添加类别]
		 * @param array  $aType [层次类别]
		 * @param string $title [添加的类别]
		 * @return bool     	 [成功：true, 失败：false]
		 */
		public function addType(array $aType, string $title)
		{
			$this->m_database->selectDatabase('WikiNote');
			$depth = count($aType);
			if ($depth == 0)
			{
				$sqlcmd = "INSERT INTO t1 (tPID, title) values (0, '$title')";
				$this->m_database->executeQuery($sqlcmd);
				return true;
			}
			if ($field = $this->getTypeID($aType))
			{
				$sqlcmd = 'INSERT INTO t'.($depth+1). '(tPID, title) values ('. $field. ", '$title')";
				$this->m_database->executeQuery($sqlcmd);
				return true;
			}
			return false;
			
		}

		/**
		 * [获取指定类型的所有子类别]
		 * @param  array  $aType [层次类型]
		 * @return array  $fields[成功：类别数组, 失败：false]
		 */
		public function getSubTypes(array $aType)
		{
			error_log(implode('/', $aType));
			$this->m_database->selectDatabase('WikiNote');
			$fields = array();
			$depth = count($aType);
			if ($depth == 0)
			{
				$sqlcmd = "SELECT title FROM t1 ORDER BY tID";
			}
			else if ($field = $this->getTypeID($aType))
			{
				$sqlcmd = 'SELECT t' .($depth+1). '.title FROM t' .($depth+1). "  WHERE t".($depth+1). ".tPID = " .$field. ' ORDER BY tID';
			}
			else
			{
				return false;
			}
			$queryResult = $this->m_database->executeQuery($sqlcmd);
			while ($field = mysqli_fetch_array($queryResult, MYSQLI_ASSOC))
			{
				array_push($fields, $field);
			}
			return $fields;
		}

		/**
		 * [级联删除指定ID的类别]
		 * @param  array  $aType [层次类型]
		 * @return bool     	 [成功：true, 失败：false]
		 */
		public function delSubTypes(array $aType)
		{
			$depth = count($aType);
			if ($field = $this->getTypeID($aType))
			{
				$sqlcmd = "DELETE FROM t$depth WHERE t$depth.tID = $field";
				$queryResult = $this->m_database->executeQuery($sqlcmd);
				return true;
			}
			return false;
		}

		/**
		 * [重命名类型名称]
		 * @param  array  $aType [层次类型]
		 * @param  string $title [新名词]
		 * @return bool     	 [成功：true, 失败：false]
		 */
		public function renameTypeTitle(array $aType, string $title)
		{
			$depth = count($aType);
			if ($field = $this->getTypeID($aType))
			{
				$sqlcmd = "UPDATE  t$depth SET title = '$title' WHERE tID = $field";
				$this->m_database->executeQuery($sqlcmd);
				return true;
			}
			return false;
		}

	}
?>