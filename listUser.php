   
<!-- ค้นหา -->
  <form class="navbar-form navbar-left" role="search" action='session_search.php' method='post'  >
       <div class="form-group">
		<input type='text' name='Search_fname' placeholder='ชื่อ'  value='' class="form-control"  > 
		</div>
		<div class="form-group">
		<input type='text' name='Search_lname' placeholder='นามสกุล'  value='' class="form-control"  > 
		<input type='hidden' name='method'  value='search_name'> 
		 </div>
		<button  class="btn btn-default" ><i class="fa fa-search"></i></button >
  </form>
 		 
						<!--   <H1>หมายเหตุ รายการที่มีเครื่องหมายดอกจันทร์  (***) จำเป็นต้องระบุให้ครบ</H1> -->
 						

<!------------------------------------------------------------------>

<?php   
// สร้างฟังก์ชั่น สำหรับแสดงการแบ่งหน้า   
function page_navigator($before_p,$plus_p,$total,$total_p,$chk_page){   
	global $e_page;
	global $querystr;
	$urlfile=""; // ส่วนของไฟล์เรียกใช้งาน ด้วย ajax (ajax_dat.php)
	$per_page=10;
	$num_per_page=floor($chk_page/$per_page);
	$total_end_p=($num_per_page+1)*$per_page;
	$total_start_p=$total_end_p-$per_page;
	$pPrev=$chk_page-1;
	$pPrev=($pPrev>=0)?$pPrev:0;
	$pNext=$chk_page+1;
	$pNext=($pNext>=$total_p)?$total_p-1:$pNext;		
	$lt_page=$total_p-4;
	if($chk_page>0){  
		echo "<a  href='$urlfile?s_page=$pPrev".$querystr."' class='naviPN'>Prev</a>";
	}
	for($i=$total_start_p;$i<$total_end_p;$i++){  
		$nClass=($chk_page==$i)?"class='selectPage'":"";
		if($e_page*$i<=$total){
		echo "<a href='$urlfile?s_page=$i".$querystr."' $nClass  >".intval($i+1)."</a> ";   
		}
	}		
	if($chk_page<$total_p-1){
		echo "<a href='$urlfile?s_page=$pNext".$querystr."'  class='naviPN'>Next</a>";
	}
}   
 

 $Search_fname=trim($_SESSION[Search_fname]);
 $Search_lname=($_SESSION[Search_lname]);
 if($Search_fname!='' || $Search_lname){
 
  echo "แสดงคำที่ค้นหา : ".$Search_fname.' '.$Search_lname;
//คำสั่งค้นหา
     $q="select  u1.*,d1.name as de_name,d2.dep_name from user u1 
         LEFT OUTER JOIN department d1 ON u1.dep_id=d1.dep_id
         LEFT OUTER JOIN department_group d2 ON u1.main_dep=d2.main_dep
 	  Where u1.user_fname like '%$Search_fname%' and  u1.user_lname like '%$Search_lname%'"; 
 }else{
 $q="select u1.*,d1.name as de_name,d2.dep_name from user u1 
     LEFT OUTER JOIN department d1 ON u1.dep_id=d1.dep_id 
     LEFT OUTER JOIN department_group d2 ON u1.main_dep=d2.main_dep
     order by u1.user_fname"; 
 }
	
$qr=mysql_query($q);
if($qr==''){exit();}
$total=mysql_num_rows($qr);
 
$e_page=10; // กำหนด จำนวนรายการที่แสดงในแต่ละหน้า   
if(!isset($_GET['s_page'])){   
	$_GET['s_page']=0;   
}else{   
	$chk_page=$_GET['s_page'];     
	$_GET['s_page']=$_GET['s_page']*$e_page;   
}   
$q.=" LIMIT ".$_GET['s_page'].",$e_page";
$qr=mysql_query($q);
if(mysql_num_rows($qr)>=1){   
	$plus_p=($chk_page*$e_page)+mysql_num_rows($qr);   
}else{   
	$plus_p=($chk_page*$e_page);       
}   
$total_p=ceil($total/$e_page);   
$before_p=($chk_page*$e_page)+1;  
echo mysql_error();
?>
 </head>
<body>
  

 <table width="100%" class='table table-hover table-striped table-bordered'  >  
 <TR bgcolor='#C3C3C3'>
					<th width='5%'><CENTER><p>#</p></CENTER></th>
					<th width='30%'><CENTER>ชื่อ - นามสกุล</CENTER></th>
					<th width='15%'><CENTER>ฝ่าย</CENTER></th>
					<th width='14%'><CENTER>ศูนย์/กลุ่มงาน</CENTER></th>
					<th width='16%'><CENTER>ระดับการใช้งาน</CENTER></th>
					<th width='8%'><CENTER>ชื่อที่ใช้งาน</CENTER></th>
					<th width='12%'><CENTER>แก้ไข | ลบ</CENTER></th>
 </TR>
<?php 
 
$i=1;
while($result=mysql_fetch_assoc($qr)){
/*	if($bg == "#F9F9F9") { //ส่วนของการ สลับสี 
		$bg = "#FFFFFF";
		}else{
		$bg = "#F9F9F9";
		}
*/
	if($bg == "#F4F4F4") { //ส่วนของการ สลับสี 
		$bg = "#FFFFFF";
		}else{
		$bg = "#F4F4F4";
		}

?>  
 					<tr >	    
				    <TD height="20" align="center" ><?=($chk_page*$e_page)+$i?></TD>
					<TD><?=$result[user_fname]; ?> <?=$result[user_lname]; ?></TD>
                                        <TD><?=$result[dep_name]; ?></TD>
					<TD><?=$result[de_name]; ?></TD>
					<TD><? if($result[admin]=='Y'){echo 'คณะกรรมการ/ผู้ดูลระบบ'; }
                                        elseif($result[admin]=='N'){echo 'ผู้ใช้งานทั่วไป';}
                                        else{echo 'หัวหน้าฝ่าย';}?></TD>
					<TD><?=$result[user_name]; ?></TD>
 					<TD><CENTER>
				    <a href='frmUser.php?method=update&user_id=<?=$result[user_id]?>' ><i class="fa fa-edit"></i></a> 
				    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					
					<a href='./prcUser.php?method=delete&user_id=<?=$result[user_id]?>'  title="confirm" onclick="if(confirm('ยืนยันการลบ <?php  echo  $result[user_fname].' '.$result[user_lname]; ?>&nbsp;ออกจากรายการ ')) return true; else return false;">   
					<i class="fa fa-trash-o"></i></a>
					</tr> 
 
  			 
 		 <?php $i++; } ?>
 		 
</CENTER>
</table>

<?php if($total>0){
echo mysql_error();

?>
<div class="browse_page">
 
 <?php   
 // เรียกใช้งานฟังก์ชั่น สำหรับแสดงการแบ่งหน้า   
  page_navigator($before_p,$plus_p,$total,$total_p,$chk_page);    

  echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size='2'>มีจำนวนทั้งหมด  <B>$total รายการ</B> จำนวนหน้าทั้งหมด ";
  echo  $count=ceil($total/10)."&nbsp;<B>หน้า</B></font>" ;
}
  ?> 
 </div> 
