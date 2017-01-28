<?php 
include('config.php');
//include("check_user.php");
$db = new Database();
$url= $_REQUEST['goto'];
$pid=(int)$_REQUEST['pid'];
if(isset($_SESSION['ozanouser']))
{
	$user=$_SESSION["ozanouser"];
	$check=$db->db_num("select * from wishlist where pid='$pid' and user_id='$user'");
	if($check>0)
	{
		header("Location:".$url);
	}
	else
	{
		$querys="INSERT INTO wishlist(id,pid,user_id)values(NULL,'$pid','$user')";
		$res=$db->insertQuery($querys);
		$_SESSION['alert']="Product has been added in wishlist !";
		header("Location:".$url);
	}
}
else
{
	header("Location:login.php?redirect=".$url);
}