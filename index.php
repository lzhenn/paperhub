<?php

   require("./check_valid_record.php");

?>
<html>
   <head>
	   <title>Paperhub</title>
   </head>
   <body>   
	   <form action="solve_enw.php" method="post" enctype="multipart/form-data">
		   <label for="file"><b>请输入endnote格式enw导入文件的URL</b></label>
         <br /><br />
         <b>URL:</b><input type="text" name="url_enw"/>
         <br /><br />
         <p>或者请上传您的enw文件:</p>
         <br /><br />
         <input type="file" name="file" id="file" />
		   <input style="position:absolute; left:400px"type="submit" name="submit" value="提交" />
      </form>
      <hr>
      <form action="solve_refer.php" method="post" enctype="multipart/form-data">
         <p>生成文献格式</p>
         <input type="radio" name="journal" value="cd" checked="checked"/> Climate Dynamics<br />
         <input type="radio" name="journal" value="jc" /> Journal of Climate<br />
         <input type="radio" name="journal" value="jgr" /> Journal of Geophysical Research<br />
         <input type="radio" name="journal" value="grl" /> Geophysical Research Letters<br/>
         <input type="radio" name="journal" value="sr" /> Scientific Reports<br/>
         <br /><br /><b>需要生成格式的文献唯一标志列表（换行分割）：</b><br /><br />
         <textarea name="refer_lb" style="width:400px;height:300px;"></textarea> <br/>
		   <input style="margin-top:30px;margin-left: 400px;" type="submit" name="submit" value="提交" />
         <br />
      </form>
      <p>-------------------------------------------------</p>
      <ul>
         <li><a href="manage_page.php">集中管理</a></li>
      </ul>
   </body>
</html>

