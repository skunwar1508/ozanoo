<?php
include('config.php');
$db_host = 'localhost';
$db_user = 'root';
$db_pass = 'alphonic123';
$db_name = 'alphoweb_ozanoo';
$con = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

if(isset($_POST["id"]) && !empty($_POST["id"])){
if($_REQUEST['datat'])
{
	 $sorts=addslashes($_REQUEST['datat']);
}
$vals=addslashes($_REQUEST['vals']);
//include database configuration file
$a= $_SESSION['pageviews'] = ($_SESSION['pageviews']) ? $_SESSION['pageviews'] + 1 : 1;
$star=30*$a;
$ends=$star+30;
//count all rows except already displayed
 $queryAll = mysqli_query($con,"SELECT COUNT(*) as num_rows FROM product $sorts ORDER BY id DESC limit $star,$ends");
$row = mysqli_fetch_assoc($queryAll);
$allRows = $row['num_rows'];

//$showLimit = 12;
//echo "SELECT * FROM product WHERE id < ".$_POST['id']." $sorts ORDER BY id DESC $vals LIMIT ".$showLimit;
//get rows query
echo $query = mysqli_query($con, "SELECT * FROM product $sorts ORDER BY id DESC limit $star,$ends");

//number of rows
$rowCount = mysqli_num_rows($query);

if($rowCount > 0){ 
    while($row = mysqli_fetch_assoc($query))
	{ 
        $tutorial_id = $row["id"]; 
		$prd_quty=$row['total_quantity'];
			if($prd_quty>0)
			{
				$pid=$row['id'];
			$base=$row['product_price'];
			$dis_per=$row['product_disc_per'];
			$pname1=$row['product_name'];
														$pname=trim($pname1);
													  	$pname=str_replace(',','-', $pname);
													  	$pname=str_replace('---','-', $pname);
														$pname=str_replace('(','-', $pname);
														$pname=str_replace(')','-', $pname);
														$pname=str_replace(' ','-', $pname);
														$pname=str_replace('--','-', $pname);
														$pname=chop($pname,"-");
														$generate1=strtolower($pname);
		?>
        <div class="product_box_wrp col-sm-4 col-xs-2">
              
            	<div class="product_wrp">
                <a href="<?=_APPLICATION_URL;?>product-view/<?=$row['id'];?>/<?=$generate1;?>">
                   <div class="prod_img">
				    <div class="prd_imin">
                	<img draggable="false" src="<?=_APPLICATION_URL;?>product_images/<?php echo $row['product_primary_image']; ?>" class="img-responsive first_view" alt="<?php echo $row['product_name']; ?>"/>
       <?php $secphoto = mysqli_query("SELECT * FROM product_images WHERE product_id='". $row['id'] ."' limit 0,1");
	   	$nussss=mysqli_num_rows($secphoto);
		if($nussss>0)
		{
			$secphotoqry = mysqli_fetch_assoc($secphoto);
	  
	   	 ?>
                    <img draggable="false" src="<?=_APPLICATION_URL;?>product_images/<?=$secphotoqry['image_name'];?>" class="img-responsive second_view" alt="prod"/>
                    <?php }
					else
					{ ?>
						 <img draggable="false" src="<?=_APPLICATION_URL;?>product_images/<?php echo $row['product_primary_image']; ?>" class="img-responsive second_view" alt="<?php echo $row['product_name']; ?>"/>
					<?php }?>
                   </div>
				    </div>
                   <h2><?php echo $row['product_name']; ?></h2>
                   <div class="box_price">
                  
                   <span> <i class="fa fa-inr" aria-hidden="true"></i> <?php echo $row['product_price'];  ?></span>
                   <h3> <i class="fa fa-inr" aria-hidden="true"></i> <?php echo $dis_per; ?></h3>
                   </div>
                   <div class="product_review_wrapper">
            <div class="stars">
                <ul>
                        <li><img src="<?=_APPLICATION_URL;?>images/rating.png" class="img-responsiv" alt="rating" /></li>
                        <li><img src="<?=_APPLICATION_URL;?>images/rating.png" class="img-responsiv" alt="rating" /></li>
                        <li><img src="<?=_APPLICATION_URL;?>images/rating.png" class="img-responsiv" alt="rating" /></li>
                        <li><img src="<?=_APPLICATION_URL;?>images/rating.png" class="img-responsiv" alt="rating" /></li>
                        <li><img src="<?=_APPLICATION_URL;?>images/unrating.png" class="img-responsiv" alt="rating" /></li>
                        <li class="reviw_count">(5)</li>
                      </ul>
              </div>
          
          </div>
                </a>
               <?php
			   $check=0;
			   if(isset($_SESSION['ozanouser']))
			   {
				   $user=$_SESSION["ozanouser"];
				   $check1=mysqli_query("select * from wishlist where pid='". $row['id'] ."' and user_id='$user'");
					$check=mysqli_num_rows($check1);
						
			   }
			   ?>
               <?php
			   if(!$check>0)
					{
					?>
                 <a title="Add to Wishlist" href="<?=_APPLICATION_URL;?>addtowishlist.php?pid=<?=$row['id'];?>&goto=<?=$_SERVER['REQUEST_URI'];?>">
                 <?php } ?>
                <div class="fav_heart" <?php if(isset($_SESSION['ozanouser']))
			   { if($check>0) { ?>style="color:#fc5970;" <?php } } ?>>
               
                    	<i class="fa fa-heart" aria-hidden="true"></i>
                        
                    </div>
                    <?php
			   if(!$check>0)
					{
					?>  </a><?php } ?>
                </div>
               
              
            </div>
<?php 
			} 
	}?>

<!-- <div class="clearfix"></div>-->
    <div class="show_more_main" id="show_more_main<?php echo $a; ?>">
	 <div class="clearfix"></div>
        <span id="<?php echo $a; ?>" class="show_more btn btn-primary" title="Load more posts">Show more</span>
        <span class="loding" style="display: none;"><span class="loding_txt"><img src="<?=_APPLICATION_URL;?>loading.gif"></span></span>
		 <div class="clearfix"></div>
    </div>

<?php 
    } 
}
?>