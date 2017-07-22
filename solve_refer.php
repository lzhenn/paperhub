<?php
/***************************************************/
// To generate the style of referrence 
// according to the label given by the 
// users
//                L_Zealot
//
//                
// Updated to batch processing     2015-07-14
// Updated to table style          2015-09-17
// Give a remind of the capitals   2015-12-20
// Update style of Sci. Rep.       2016-01-07
/**************************************************/

// Solve the input from index.php
   $lbs=$_POST["refer_lb"];
   $lb_array=explode("\n",$lbs);    
   $j_submit = $_POST["journal"];
   array_filter($lb_array);

// Check the validataion of input list
   if (strlen(trim($lbs))<5){
         echo "No valid input label!<br />";
         echo "<a href='./index.php'>index.php</a>";
         exit;
      } 
//Read in the shortname database
   $fdata = fopen("./jr_short/short_ref.dat","r");
   $i=0;
   while(!feof($fdata)){
      $short[$i] = fgets($fdata);
      $i=$i+1;
   }
   fclose($fdata);

?>




<html>
   <head>
      <title>Solve Referrence</title>
      <style>
        table, th, td{border: 1px solid grey;}
      </style>
   </head>
   <body>
        <p style="color:red;">WARNING: the CAPITAL is converted in the title in refline, you have to convert it after copy!</p>
        <table >
            <tr>
                <th>Rank</th><th>Label</th><th>Inner style</th><th>Outer style</th><th>Ref line</th><th>Ref line (complete)</th>
            </tr>
<?php
   $n_ref = 0;
   foreach ($lb_array as $fn){
      $fn=trim($fn);
      if (strlen($fn)<5)
         continue;
      if (file_exists("./warehouse/".$fn)){
         $n_ref++;
         $lb = substr(trim($fn),0,-3)."enw";  
         if(!file_exists("./warehouse/".$lb))
            $lb = substr(trim($fn),0,-3)."ris";
         $lb = "./warehouse/".$lb;
         
         $ref_line="<tr><td>".$n_ref."</td><td><a href='./warehouse/".substr(trim($fn),0,-4).".pdf'>".substr(trim($fn),0,-4)."</td>";
         $ref_line.=get_refer_from_enw($lb,$j_submit,$short)."</tr>";  // solve the enw file

         echo ($ref_line);
      }
   }
?>

            </tr>
        </table>      
   </body>
</html>




<?php
    
  
   // In fact, not only .enw but also .ris will be processed
   function get_refer_from_enw($lb,$j_submit,$short){
      $file    =  fopen($lb,"r");

      //initialize all flags

      $a_n = 0;
      $author  =  "";
      $journal =  "";
      $vol     =  "";
      $number  =  "";
      $page    =  "";
      $at      =  "";
      $year0   =  "";
      $title   =  "";
      //---------Down to Solve the enw file---------------
      while (! feof($file)){
         $line = fgets($file);
         $mid_array=explode(" ",$line);
         $mid_array2=explode("-",$line);
         if(trim($mid_array[0])=="JF"||trim($mid_array[0])=="JO"||trim($mid_array[0])=="T2")
            $mid_array[0]="JA";
         switch(trim($mid_array[0]))
         {

         //------------RIS case in first--------------
         case "A1": //auther
           $enw_flag = false;
           if ($author[$a_n]==""){
               $author[$a_n]=trim(substr($line,5));
            }
            $a_n = $a_n+1;
            break;
         case "AU": //auther
           $enw_flag = false;
           if ($author[$a_n]==""){
               $author[$a_n]=trim(substr($line,5));
            }
            $a_n = $a_n+1;
            break;
         case "T1": //title
            if ($title=="")
                $title = trim(substr($line,5));
            break;

         case "TI": //title
            if ($title=="")
                $title = trim(substr($line,5));
            break;

         case "Y1": //year
            $year0  =  substr(trim($mid_array2[1]),0,4);
            break;
         case "PY": //year
            $year0  =  substr(trim($mid_array2[1]),0,4);
            break;
         case "JO": //journal
            foreach ($short as $item){
               $case_name=explode("@",$item);  //$case_name[0]=Journel of Climate; [1]= JC;
               $j_longname = trim($mid_array2[1]);
               $case_longname = trim($case_name[0]);

               if(strlen($j_longname)==strlen($case_longname)){
                  if(strcasecmp($case_longname,$j_longname)==0){
                     $journal = trim($case_name[1]);
                     break;
                  }
               }elseif(strlen($j_longname)<strlen($case_longname)){
                  if(stristr($case_longname,$j_longname)){
                     $journal = trim($case_name[1]);
                  }
               }else{
                  if(stristr($j_longname,$case_longname)){
                     $journal = trim($case_name[1]);
                  }
               }
            }
            break;
        case "JA": //journal
           if ($j_longname!="")
              break;
           foreach ($short as $item){
               $case_name=explode("@",$item);  //$case_name[0]=Journel of Climate; [1]= JC;
               $j_longname = trim($mid_array2[1]);
               $case_longname = trim($case_name[0]);

               if(strlen($j_longname)==strlen($case_longname)){
                  if(strcasecmp($case_longname,$j_longname)==0){
                     $journal = trim($case_name[1]);
                     break;
                  }
               }elseif(strlen($j_longname)<strlen($case_longname)){
                  if(stristr($case_longname,$j_longname)){
                     $journal = trim($case_name[1]);
                  }
               }else{
                  if(stristr($j_longname,$case_longname)){
                     $journal = trim($case_name[1]);
                  }
               }
            }
            break;
         case "VL":
            $vol  =  trim($mid_array2[1]);
            break;
         case "IS":
            $number  =  trim($mid_array2[1]);
            break;
         case "SP":
            $page0  =  trim($mid_array2[1]);
            if (is_numeric($page0))
               $page = $page0;
            break;
         case "EP":
            $page0  = trim($mid_array2[1]);
            if (is_numeric($page0))
            {
               $page .= "-".$page0;
               $page_flag = true;
            }
            break;


         //------------ENW case after--------------
         case "%T":
            $title = trim(substr($line,3));
            break;
         case "%A":
            if ($author[$a_n]==""){
               $author[$a_n]=trim(substr($line,3));
            }
            $a_n = $a_n+1;
            break;
         case "%J":

            $j_longname = trim(substr($line,3));
            foreach ($short as $item){
               $case_name=explode("@",$item);  //$case_name[0]=Journel of Climate; [1]= JC;
               $case_longname = trim($case_name[0]);

               if(strlen($j_longname)==strlen($case_longname)){
                  if(strcasecmp($case_longname,$j_longname)==0){
                     $journal = trim($case_name[1]);
                     break;
                  }
               }elseif(strlen($j_longname)<strlen($case_longname)){
                  if(stristr($case_longname,$j_longname)){
                     $journal = trim($case_name[1]);
                  }
               }else{
                  if(stristr($j_longname,$case_longname)){
                     $journal = trim($case_name[1]);
                  }else{
                     $journal = $j_longname;
                  }
               }
            }
         case "%V":
            $vol  =  trim($mid_array[1]);
            break;
         case "%N":
            $number  =  trim($mid_array[1]);
            break;
         case "%P":
            $page  =  trim($mid_array[1]);
            break;
         case "%@":
            $at  =  trim($mid_array[1]);
            break;
         case "%D":
            $year0  =  trim($mid_array[1]);
            break;
         }
      }
      $ref_line="";

      $name_line0="";
      $name_line1="";
      $inner_ref="";
      $outer_ref="";
      switch($j_submit)
      {
          
// CASE NUMBER 1.          
//-------------------for Climate Dynamics------------------------         
      case "cd":
         
         //Author
         
         foreach($author as $item){
            $name_line0   =  $name_line0.$item.", ";  

            $name_all = explode(" ",$item);
            $name_pos = 0;
            foreach($name_all as $name0){
               if ($name_pos == 0){
                  $name_line1 .= $name0." ";
               }else{
                  $name_line1 .= substr($name0,0,1);
               }
               $name_pos ++;
            }
            $name_line1 .=", ";
         }
         $name_line0 = substr($name_line0,0,-2)." ";
         $name_line1 = substr($name_line1,0,-2)." ";

         //Year
         $ref_line .= "(".$year0.") ";

         //Title
         $ref_line .= $title.". ";

         //Journal
         $ref_line .= $journal." ";

         //vol
         $ref_line .= $vol.": ";

         //page
         $ref_line .= $page.".";
         break;

// CASE NUMBER 2.          
//-------------------for Journal of Climate------------------------         
      case "jc":
         
         //Author
         $n_a = count($author);
         $n_pos = 0;       //which author
         if ($n_a>1)
            $p_and = $n_a - 1;   //for where to put 'and'
         else
            $p_and = 0;

         foreach($author as $item){
             
            $n_pos ++; //which author is being processed
            $name_all = explode(" ",$item);
            $name_pos = 0;    //name position in one specific name
            if ($n_pos==1){
               foreach($name_all as $name0){
                  if ($name_pos == 0){
                     //Xie,
                     $name0 = ucfirst(strtolower($name0));
                     $name_line0 .= $name0." ";  //for long
                     $name_line1 .= $name0." ";  //for short
                     $inner_ref .= substr($name0,0,-1)." ";   //for "Xie et al. 1999" style
                  }else{
                     //Xie, S.
                     $name_line0 .= $name0.". ";  
                     $name_line1 .= substr($name0,0,1).". ";
                  }
                  $name_pos ++;
               }
            }else{
                if ($n_a>=9){
                   $name_line0 .="and Coauthors, ";
                   $name_line1 .="and Coauthors, ";
                   $inner_ref  .="and Coauthors ";
                   break;
                }
                foreach($name_all as $name0){
                  if ($name_pos == 0){
                     $name_last = $name0; //Store the last name
                  }else{
                     //S.
                     $name_line0 .= $name0.". ";  
                     $name_line1 .= substr($name0,0,1).". ";
                  }
                  $name_pos ++;
                }
                //S. Xie,
                $name_line0 .=$name_last;
                $name_line1 .=$name_last;
                if ($n_a==2){
                    $inner_ref .= "and ".substr($name_last,0,-1)." ";
                 }elseif($n_pos==2){
                    $inner_ref .= " et al. ";
                 }
            }
                $name_line0 =substr($name_line0,0,-1).", "; 
                $name_line1 =substr($name_line1,0,-1).", ";
     

                if($n_pos==$p_and){
                   $name_line0 .="and ";
                   $name_line1 .="and ";
                }
         }

         //Year
         $ref_line .= $year0.": ";

         $outer_ref = $inner_ref . $year0.";";
         $inner_ref .= "(". $year0.")";
         

         //Title
         //$ref_line .= ucfirst(strtolower($title)).". ";
         $ref_line .= $title.". ";
         
         //Journal
         $ref_line .= "<i>".$journal."</i>, ";

         //vol
         $ref_line .= "<b>".$vol."</b>, ";

         //page
         $ref_line .= $page.".";
         break;

         
// CASE NUMBER 3.          
//-------------------for Scientific Reprots------------------------         
      case "sr":
         //Author
         $n_a = count($author);
         $n_pos = 0;       //which author
         if ($n_a>1)
            $p_and = $n_a - 1;   //for where to put '&'
         else
            $p_and = 0;
         foreach($author as $item){
             
            $n_pos ++; //which author is being processed
            $name_all = explode(" ",$item);
            $name_pos = 0;    //name position in one specific name


            //----- 1 author -----
            if ($n_pos==1){
               foreach($name_all as $name0){
                  if ($name_pos == 0){
                     //Xie,
                     $name0 = ucfirst(strtolower($name0));
                     $name_line0 .= $name0." ";  //for long style
                     $name_line1 .= $name0." ";  //for short style
                     $inner_ref .= substr($name0,0,-1)." ";   //for "Xie et al. 1999" style
                  }else{
                     //Xie, S.
                     $name_line0 .= $name0.". "; // for long style 
                     $name_line1 .= substr($name0,0,1).". "; // for short style
                  }
                  $name_pos ++;
               }
            }else{
                
                //----- 6 or more than 6 authors -----
                if ($n_a>=6){
                   $name_line0 .="et al. ";
                   $name_line1 .="et al. ";
                   $inner_ref  .="et al. ";
                   break;
                }

                //----- 2 to 5 authors -----
                foreach($name_all as $name0){
                    if ($name_pos == 0){
                     //Xie,
                     $name0 = ucfirst(strtolower($name0));
                     $inner_name0 = substr($name0, 0, -1);
                     $name_line0 .= $name0." ";  //for long
                     $name_line1 .= $name0." ";  //for short
                  }else{
                      //Xie, S.
                     if ($n_pos>=$p_and){
                        $name_line0 .= $name0.". ";  
                        $name_line1 .= substr($name0,0,1).". ";
                     }else{
                        $name_line0 .= $name0."., ";  
                        $name_line1 .= substr($name0,0,1)."., ";

                     }
                  }
                  $name_pos ++;

                }
                //----- For inner ref -----
                if ($n_a==2){
                    $inner_ref .= "& ".$inner_name0." ";
                 }elseif($n_pos==2){
                    $inner_ref .= " et al. ";
                 }
            }
    

            if($n_pos==$p_and){
               $name_line0 .="& ";
               $name_line1 .="& ";
            }
         }

         
         //Title
         //$ref_line .= ucfirst(strtolower($title)).". ";
         $ref_line .= $title.". ";
         
         //Journal
         $ref_line .= "<i>".ucwords(strtolower($journal))."</i> ";

         //vol
         $ref_line .= "<b>".$vol."</b>, ";

         //page
         $ref_line .= $page." ";
         
         //Year
         $ref_line .= "(".$year0.").";

         $outer_ref = $inner_ref . $year0.";";
         $inner_ref .= "(". $year0.")";
         break;
      } //switch off
        
      return "<td>".$inner_ref."</td><td>".$outer_ref."</td><td>".$name_line1.$ref_line."</td><td>".$name_line0.$ref_line."</td>";

   } //function off
?>

