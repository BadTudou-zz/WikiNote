<!doctype html>
<html>
<head>
<meta charset="utf-8">
<link  href="../css/manage-style.css" rel="stylesheet">
<title></title>
</head>
<body>
<table width="700" height="134"  border="1" cellpadding="0" cellspacing="0" bgcolor="#D0E8B4" align="center">
  <!--表1-->
  <form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF']?>">
    <tr>
      <td width="700" height="51" bgcolor="#D0E8B4"><div align="center">请输入需查询用户昵称
          <input name="txt_book" type="text" id="txt_book" size="25" >
          &nbsp;
          <input type="submit" name="Submit" value="查询">
        </div></td>
    </tr>
    <!--表1的第一行为表单-->
  </form>
  <tr valign="top" bgcolor="#FFFFFF"> 
    <!--表1的第二行开始-->
    <td height="120"><br>
      <table width="700"  border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#D0E8B4">
        <!--表2-->
        <tr align="center" bgcolor="#D0E8B4">
          <td width="200" height="40" bgcolor="#D0E8B4">用户ID</td>
          <td width="200" bgcolor="#D0E8B4">昵称</td>
          <td width="200" bgcolor="#D0E8B4">密码</td>
        </tr>
        <?php
$link=mysql_connect("localhost:3306","a0827184445","34565126") or die("数据库连接失败".mysql_error());    //建立与数据库的连接
 mysql_select_db("a0827184445", $link);   //选择数据库
mysql_query("set names utf8");//php文档结构为html5，字符集只能使用utf-8
if (isset($_POST["Submit"]))
{
	$user_name=$_POST["user_name"];
	$sql=mysql_query("select * from user where nickename like '%".trim($user_name)."%'"); 	//如果选择的条件为"like",则进行模糊查询
    $info=mysql_fetch_object($sql);
   	if($info==true){ 
	do{
 ?>
          <tr align="left" bgcolor="#FFFFFF">
          <td height="20" align="center"><?php echo $info->uID; ?></td>
          <td >&nbsp;<?php echo $info->nickname; ?></td>
          <td align="center"><?php echo $info->pwd ; ?></td>
          </tr>
        <?php
}while($info=mysql_fetch_object($sql));
mysql_free_result($sql);
mysql_close($link);
	}else {
	echo "<div align='center' style='color:#FF0000; font-size:12px'>对不起，您所查询的用户不存在!</div>"; }
   	}
?>
      </table></td>
    <!--表2结束--> 
    
  </tr>
  <!--表1第二行结束-->
</table>
<!--表1结束-->
</body>
</html>
