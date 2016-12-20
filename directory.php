<!DOCTYPE html><html lang="en-US"><head>
<meta charset="utf-8"><!-- -*- coding: utf-8 -*- -->
<title>Directory</title>
<meta name="description" content="Server directory">
<body>
<pre><?php
   $dir = scandir(".");
   forEach ($dir as $d) {
      echo $d."<br>";
   }
?></pre>
</body>
</html>
