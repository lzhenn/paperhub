<?php

    //Read in the warehouse list
   $fdata = fopen("./list.dat","r");
   $i=0;
   while(!feof($fdata)){
      $paper_pos = trim(fgets($fdata));
      $i=$i+1;
      $file    =  fopen("./warehouse/".$paper_pos.".enw","r");
      if (!$file){
         echo("WARNING: No exist ".$paper_pos."\n");
         continue;
      } 
      //---------Down to Solve the enw file---------------
      $attach_flag = false; //whether the attachment has been attached
      while (! feof($file)){
         $line = fgets($file);
         $mid_array=explode(" ",$line);
         switch($mid_array[0])
         {
         case "%T":   
            echo ($line);
            break;
         case "%>":
            echo (" had been attached...\n");
            $attach_flag = true;
            break;
         }
      }
      fclose($file);
      if(!$attach_flag){
         $file_new    =  fopen("./warehouse/".$paper_pos.".enw","a");
         fwrite($file_new,"%> internal-pdf://".$paper_pos.".pdf\n");
         fclose($file_new);
         echo (" done!\n");
      }
   }
   fclose($fdata);
?>
