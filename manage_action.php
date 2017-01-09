<?php
   $all_dir=scandir("./warehouse");
   $pos = 0;
   mkdir("./package");
   $flag = false;
   $package_flag = !empty($_POST['package']);
   foreach ($all_dir as $element){
      if($element=='.'||$element=='..') continue;
      if(pathinfo($element, PATHINFO_EXTENSION)!='pdf') continue;
      $fn_block = explode(".",$element);
      if ($_POST[$fn_block[0]]){
         $flag = true;
         if($package_flag) {
            exec("cp ./warehouse/".$fn_block[0].".* ./package");
            echo $fn_block[0]." 已完成复制...<br />";
         } else {
            exec("rm -f ./warehouse/".$fn_block[0].".*");
            echo $fn_block[0]." 已完成删除...<br />";
         }
      }
   }
   if ($flag && $package_flag){
      echo "<br/>正在打包，请稍候……<br/>";
      exec("rm -f package.zip");
      exec("zip package.zip ./package/*");
      exec("rm -rf ./package");
      echo "恭喜，打包完成！<br/><br/>";
      echo "<a href='./package.zip'>package.zip</a>";
   }elseif (!$package_flag){
      require("./check_valid_record.php");
   }
?>
