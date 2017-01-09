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
      <form action="package_down.php" method="post" enctype="multipart/form-data">
         <b>请选择需要集中打包的文件：</b>
		   <input style="position:absolute; left:400px" type="submit" name="submit" value="提交" />
         <br /><br />
         <?php 
            foreach ($all_record as $key_element => $element){ 
               $fn_block = explode(".",$key_element); 
         ?>
                  <input type="checkbox" name="<?php echo(trim($fn_block[0]));?>"><?php echo(date("Y-m-d H:i:s",$element)." <b>".$key_element."</b>");?>
                  <br/>
         <?php 
            } 
         ?>
      </form>

   </body>
</html>
