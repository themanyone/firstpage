<!DOCTYPE html><html lang="en-US"><head>
<meta charset="utf-8"><!-- -*- coding: utf-8 -*- -->
<title>Directory</title>
</head>
<body>
   <pre>
<?php
class dir {
   private $s;
   private $filetypes = ['md', 'html', 'xml', 'rss', 'php', '']; // files to list
   private $exceptions = ['search.php'];
   private function scan(){
      foreach (new DirectoryIterator(".") as $f) {
         if (in_array($f->getExtension(), $this->filetypes)
            && $f->isfile() && !in_array($f, $this->exceptions)) {
            echo $f."\n";
         }
      }
   }
     
   function __construct(){
      $this->scan();
   }
   
}
$ret = new dir();

?></pre></body></html>
