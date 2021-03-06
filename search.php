<?php
/*
 * search.php
 *
 * Searches files in server directories.
 *
 * Copyright (C) 2017 by Henry Kroll http://thenerdshow.com
 * By all means redistribute, and credit the above link and author!
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 */
class search {
   private $s;
   private $filetypes = ['md', 'html', '']; // files to search
   private $limit = 99; // limit to 99 results
   
   private function sanitize($s){
      $s = substr($s, 0, 35);
      $s = preg_replace("/[^a-zA-Z0-9\s]/", "", $s);
      return $s;
   }
   private function search($f){
      $c = file_get_contents($f);
      forEach(explode("\n", $c) as $l){
         preg_match("/.{0,99}(".$this->s.").{0,99}/i", $l, $found, PREG_OFFSET_CAPTURE);
         if (isSet($found[0])){ // $found[1];
            echo "<p class=search>".$f."<br>";
            // print_r($found);
            $ofs = $found[1][1]-$found[0][1];
            $str = $found[0][0];
            $s =substr($found[0][0], 0, $ofs)."<b>"
            .substr($str, $ofs, strlen($this->s))."</b>"
            .substr($str, $ofs+ strlen($this->s));
            echo $s."</p>\n\n";
            $this->limit--;
            break;
         }
      }
   }
   
   private function scan(){
   // scan the folder for documents
      foreach (new DirectoryIterator(".") as $f) {
         if (in_array($f->getExtension(), $this->filetypes)) {
            // limit results
            if (!$this->limit) {
               echo "<p class=search>Search returned > 99 results! Try refining search terms.</p>";
               break;
            }
            if ($f->isFile()) {
               $this->search($f);
   }  }  }  }
  
   function __construct($s){
      $this->s = $this->sanitize($s);
      $this->scan();
   }
}
if (isset($_POST["_q"]) && $_POST["_q"]) {
   $ret = new search($_POST["_q"]);
}
?>
