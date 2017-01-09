<?php
   $all_file=scandir('./warehouse');
   $all_record=array();
   foreach($all_file as $k=>$v){
          if($v=='.'||$v=='..') continue;
              if(pathinfo($v, PATHINFO_EXTENSION)!='pdf') continue;
              $all_record[$v]=filemtime('./warehouse/'.$v);
   }
arsort($all_record);

?>
<html>
   <head>
      <title>Package</title>
   </head>
   <body>
      <form action="manage_action.php" method="post" enctype="multipart/form-data">
         <b>请选择需要集中管理的文件：</b>
		   <input style="position:absolute; left:300px" type="submit" name="package" value="打包下载" />
         <br /><br />
         <ol style="line-height:200%">
         <?php 
            foreach ($all_record as $key_element => $element){ 
               $fn_block = explode(".",$key_element); 
?>
               <li>
                  <input type="checkbox" name="<?php echo(trim($fn_block[0]));?>"><?php echo(date("Y-m-d H:i:s",$element)." <b><a href='./warehouse/".$key_element."'>".$key_element."</a></b>");?>
               </li>
<?php 
            } 
?>
         </ol>
		   <input style="position:absolute; left:600px" type="submit" name="delete" value="删除" />
         <br />   
   </form>

   </body>
</html>
