<?php
   $fn=$_GET["fn"];
   if ($_POST["url_pdf"]!=""){
      $pdf_url=$_POST["url_pdf"];
      $pdf_content=file_get_contents($pdf_url);
      if (strlen($pdf_content)<10000){
         echo("Download Failed!");
         exit;
      }
      file_put_contents("./warehouse/".$fn.".pdf",$pdf_content);
      echo "Download successfully!<br />";
      echo "Rename:<a href='./warehouse/".$fn.".pdf'><b>".$fn.".pdf</b></a><br />";
      echo "Size:".round(filesize("./warehouse/".$fn.".pdf")/1024/1024,2)."MB<br /><br />";

   }else{
      if ($_FILES["file"]["size"] < 500000000)
      {
         if ($_FILES["file"]["error"] > 0)
         {
            echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
         }
         else
         {
            echo "<ul><li>Upload: " . $_FILES["file"]["name"] . "--><b><a href='./warehouse/".$fn.".pdf'>".$fn.".pdf</a></b></li>";
            echo "<li>Size: " . (round($_FILES["file"]["size"] / 1024/1024,2)) . " MB</li></ul>";
            move_uploaded_file($_FILES["file"]["tmp_name"],"./warehouse/".$fn.".pdf");
         }
      }else{
         echo("Invalid file!");
      }  
   } 
   echo "<a href='./manage_page.php'>Manage page</a></br>";
   echo "<a href='./warehouse'>Warehouse</a><br />";
   echo "<a href='./'>Upload another</a><br />";
?>
