<?php
// Scan the warehouse and renew the list.dat
   $files=scandir("./warehouse");
   $i=0;
   $n_valid = 0;
   $n_all = 0;
   $valid_list ="";
   foreach($files as $fn){
      $i++;
      if ($fn=="." || $fn=="..") // Debug: . and ..
          continue;
      $fn_block = explode(".",$fn);
      if ($fn_block[1]=="pdf")   //skip pdf
         continue;
      $n_all++;
      $check_fn = $fn_block[0].".pdf";
      if (!file_exists("./warehouse/".$check_fn)){
         unlink("./warehouse/".$fn);
         echo($fn." is an invalid record, removed...<br />");
         continue;
      }
      $valid_list .=$fn_block[0]."\n";
      $n_valid++;
   }
   $file=fopen("./list.dat","w");
   fwrite($file,$valid_list);
   fclose($file);

   echo("System  >>>>  VALIDITION CHECK DONE! ".$n_all." checked, ".$n_valid." valid.<br/><br/>");
?>
