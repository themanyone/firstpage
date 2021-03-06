<?php
/*
 * navbar.php
 *
 * Builds drop-down CSS3 menus by scanning files in server directories.
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
function console_log( $data ){
  echo '<script>console.log('.json_encode( $data ).');</script>';
}

class link {
   var $headings;
   var $src;
   var $title;
   var $description;
}

// navbar not updating? remove sitemap.nav
class navbar {
   // sitemap & rss feed configuration
   private $title       = "RSS feed";
   private $url         = "http://www.mywebsite.com";
   private $description = "This is an example RSS feed";
   private $copyright   = "Copyright (C) 2016 mywebsite.com";
   private $language    = "en-us";
   // change names to "" to disable these features
   private $rss = "sitemap.rss";     // name of rss feed
   private $nav = "sitemap.nav";     // name of cached navbar file
   private $sitemap = "sitemap.xml"; // name of Google sitemap

   // whether to list documents with missing "data-headings" attribute
   // if set to true FirstPage will list them under "other" menu heading
   // if set to false it won't list uncategorized documents at all!
   private $other = true;
   public static $styles = true;       // show styles menu item
   public static $rewriteMenu = true;  // stop page reloads RESTfully
   public static $createLinks = true;  // link menu mentions in text
   // also check .httaccess rewrite rules to customize which
   // viewable root documents will display through FirstPage
   // otherwise those pages will see no menu, search bar, etc.
   private $filetypes = ['md', 'html', 'php', 'rss', 'xml']; // files to scan for
   private $exceptions = ['navbar.php', 'search.php'];
   private $dlen = 255; // page description length limit

   private $headings = array();
   private $links = array();

   private function tsearch($contents, $patterns, $top = 10){
      // Search top of contents string for array of patterns
      $lines = explode("\n", $contents, $top);
      if (count($lines) < $top) $top = count($lines);
      if (!$top) return "";
      $found = array();
      foreach ($patterns as $p){
         foreach ($lines as $l){
            preg_match($p, $l, $found);
            if (isSet($found[1])) return $found[1];
         }
      } return "";
   }

   private function cache($f, $c){
      // FirstPage can maintain a cached copy of documents.
      // Cached copies will have '.cache' appended to the end.

      // header for .md files:         cache: http://...
      // header for .html files: <head data-cache="http://...

      // cached files update by default every 4 hours
      // to change this use expires: header

      // expire cached copy after 24 hours
      // expires header for .md files:              expires: 24
      // expires header for .html files: <head data-expires= 24

      $hrs = 3600; $now = time();
      if (substr($f, -2) == "md") {
         $url = $this->tsearch($c, ['/^cache\s*:\s*(.*)/']);
         $few = $this->tsearch($c, ['/^expires\s*:\s*(.*)/']);
      } else {
         $url = $this->tsearch($c, ['/data-cache\s*=\s*"([^"]+)"/']);
         $few = $this->tsearch($c, ['/data-expires\s*=\s*"([^"]+)"/']);
      }
      if ($url){
         $few = isSet($few)? $few : 4;
         $update = $now - filemtime($f);
         // update after a few hours or if cache is missing
         if ($update > $few * $hrs || !file_exists($f.".cache")) {
            // ignore any errors
            if ($c = @file_get_contents($url)) {
               file_put_contents($f.".cache", $c);
            }
            touch($f); // touch myself
         }
         return $f.".cache";
      } else return "";
   }

   private function fetchRemote($f, $c){
      // Always fetch (do not cache) a remote file

      // Put data-url header in local file

      // header for .md files:         url: http://...
      // header for .html files: <head data-url="http://...
      if (substr($f, -2) == "md")
         $url_str = $this->tsearch($c, ['/^url\s*:\s*(.*)/']);
      else
         $url_str = $this->tsearch($c, ['/data-url\s*=\s*"([^"]+)"/']);
      return $url_str? $url_str : "";
   }

   private function getLinks($f){
      // get suggested menu headings, title, and description
      $c = file_get_contents($f);
      $ext = $f->getExtension();
      if ($ext == "md" || $ext == "html"){
         $remote = $this->cache($f, $c);
      }
      // get comma-separated list of menu headings
      $heads = array();
      if (substr($f, -2) == "md")
         $heads_str = $this->tsearch($c, ['/^headings\s*:\s*(.*)$/']);
      else
         $heads_str = $this->tsearch($c, ['/data-headings\s*=\s*"(.*)"/']);
      // represent headings as an array
      if ($heads_str) {
         $heads = preg_split("/,\s*/", $heads_str);
         foreach ($heads as $head) array_push($this->headings, $head);
      } else if ($this->other) {
         $heads = ["other"];
         array_push($this->headings, "other");
      }
      // get title and description
      $title = $this->tsearch($c, ['/^title:\W*(.{3,23})\W*/', '/^#+\s*(.{3,23})/',
                     '/\s*<title>\W*(.{3,23})\W*<\/title>/',
                     '/<h.>\s*(.{3,23})\s*<\/h.>/', '/^\W*([\w\s]{10,23})$/']);
      $description =  $this->tsearch($c, ['/^description:\s*(.{0,40})/',
               '/name\s*=\s*["\']description.*content\s*=\s*["\']([^"\']{0,40})/',
               '/<p>\s*(.{0,40})\s*<\/p>/', '/^([^#:><"\']{25}.*)/']);
      if (!$description) $description = "No Description";
      if (!$title) $title = $f->getFileName();
      // check if document describes a remote URL
      if (!isset($remote) || !$remote) $remote = $this->fetchRemote($f, $c);
      // build the menu link object
      $link = new link;
      $link->headings = $heads;
      $link->title = $title;
      $link->description = substr($description, 0, $this->dlen);
      $link->src = $remote? $remote : $f->getFileName();
      // push it onto links array
      array_push($this->links, $link);
   }

   private function scan(){
      // scan the folder for documents
      foreach (new DirectoryIterator(".") as $f) { // iterator
         if (in_array($f->getExtension(), $this->filetypes) ) { // in $this->filetypes
            // do something here
            if ($f->isFile() && !in_array($f, $this->exceptions)) {
               $this->getLinks($f);
   }  }  }  }

   private function sort(){
      $this->headings=array_unique($this->headings);
      sort($this->headings);
      if (false != ($op = array_search("other", $this->headings))){
         //move other to end
         array_splice($this->headings, $op, 1);
         array_push($this->headings, "other");
      }
   }

   public function update(){
      // (re)build the menu?
      if ($this->nav){
         $f = fopen($this->nav, 'w+');
         fwrite($f, "<!--Safe to delete. Re-generated by navbar.php-->\n"
            ."<input type=\"radio\" name=\"nav\" id=\"nav_close\">\n"
            ."<input type=\"radio\" name=\"nav\" id=\"nav_rot\" value=show>\n"
            ."<input type=\"radio\" name=\"nav\" id=\"nav_open\" value=show>\n"
            ."<label name=\"nav\" class=\"fa fa-times fa-2x\" for=\"nav_close\"></label>\n"
            ."<label name=\"nav\" class=\"fa fa-refresh fa-2x\" for=\"nav_rot\"></label>\n"
            ."<label name=\"nav\" class=\"fa fa-bars fa-2x\" for=\"nav_open\"></label>"
            );
         fwrite($f, "\n<nav><ul>\n");
         foreach ($this->headings as $heading){
            fwrite($f, "   <li><a href='javascript:void(0);' class='btn'>".
                        "$heading</a>\n");
            fwrite($f, "      <ul class='sub'>\n");
            foreach ($this->links as $link){
               // does it belong under this heading?
               if (in_array($heading, $link->headings))
               fwrite($f, "         <li><a class='btn' href=\"$link->src\"".
                    " title=\"$link->description\">$link->title</a></li>\n");
            }
            fwrite($f, "      </ul></li>\n");
         }
         fwrite($f, "</ul></nav>\n\n");
         fclose($f);
      }
      $url  = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
      $url .= $_SERVER['SERVER_NAME']."/";
      // $url .= htmlspecialchars($_SERVER['REQUEST_URI']);
      // create RSS feed?
      if ($this->rss){
         $rssfeed = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n"
         ."<rss version=\"2.0\">\n"
         ."<channel>\n"
         ."<title>$this->title</title>\n"
         ."<link>$this->url</link>\n"
         ."<description>$this->description</description>\n"
         ."<language>$this->language</language>\n"
         ."<copyright>$this->copyright</copyright>\n";
         foreach ($this->links as $link){
            $rssfeed .= "<item>\n";
            $rssfeed .= "<title>" . $link->title . "</title>\n";
            $rssfeed .= "<description>" . $link->description . "</description>\n";
            $rssfeed .= "<link>$url$link->src</link>\n";
            // set date.timezone in php.ini, if possible
            // but we'll go ahead and leave this here just in case
            // date_default_timezone_set("US/Alaska");
            $rssfeed .= "<pubDate>" . date("D, d M Y H:i:s O",
               filemtime($link->src)) . "</pubDate>\n";
            $rssfeed .= "</item>\n";
         }
         $rssfeed .= "</channel>\n";
         $rssfeed .= "</rss>";
         file_put_contents($this->rss, $rssfeed);
      }
      // create sitemap?
      if ($this->sitemap){
         $sitemap = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n"
         ."<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n"
         ."<title>sitemap</title>\n";
         foreach ($this->links as $link){
            $sitemap .=
             "<url>\n"
            ."<loc>$url$link->src</loc>\n"
            ."<lastmod>".date("Y-m-d", filemtime($link->src))
            ."</lastmod>\n"
            ."<changefreq>weekly</changefreq>\n"
            ."<priority>0.5</priority>\n"
            ."</url>\n";
         }
         $sitemap .= "</urlset>";
         file_put_contents($this->sitemap, $sitemap);
      }
   }

   private function needs_update($few = 1){
      // doesn't exist? needs update
      if (!file_exists($this->nav)) return 1;
      $hrs = 3600; $now = time();
      $update = $now - filemtime($this->nav);
      // update after a few hours
      return ($update > $few * $hrs);
   }

   public function search(){
      if(isSet($_GET['_q'])) {
         console_log($_GET['_q']);
      }
      if(isSet($_GET['_u'])) {
         echo "
<script>
   jEvent(window, \"load\", function(){
      fetch(\"$_GET[_u]\");
   });
</script>";
      }
   }

   public function display(){
      echo file_get_contents($this->nav);
   }

   function __construct(){
      if ($this->needs_update()){
         $this->scan();
         $this->sort();
         $this->update();
      }
      if($_GET){
         $this->search();
      }
      $this->display();
}  }
?>
<script>
function htmlEntities(str) {
    return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;')
    .replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&apos;');
}

(function(){
   "use strict";
   // fetch a page
   window.fetch = function(u){
      XHR(u + "?_firstpage", displayIt);
   };

// rewrite navbar http links to use XHR displayIt
<?php if (navbar::$createLinks) { ?>

   function replaceInNodes(nodes, regExp, flags, lnk, desc){
      var reg = new RegExp(regExp, flags);
      forAll(jqa(nodes), function (e) {
         forAll(e.childNodes, function (f){
            if (f.nodeType == 3) {
               var s = f.nodeValue;
               var m = reg.exec(s);
               if (m) {
                  var pn = f.parentNode;
                  var p1 = s.substr(0, m.index);
                  var p2 = m[0];
                  var p3 = s.substr(p2.length + m.index);
                  var t = document.createTextNode(p1);
                  pn.replaceChild(t, f);
                  var a = document.createElement("a");
                  a.className = "link";
                  a.href = lnk;
                  a.title= desc;
                  a.innerHTML = p2;
                  pn.insertBefore(a, t.nextSibling);
                  t = document.createTextNode(p3);
                  pn.insertBefore(t, a.nextSibling);
               }
            }
         });
      });
   }

   function createLinks(docContent){
      // link all words in .content text that match menu buttons
      forAll(jqa("nav .sub a"), function(e){
         var et = e.href.substr(0, 10) == "javascript"
            ? e.href : "javascript:fetch('"+e.href+"')";
         replaceInNodes("p, pre, code, h, b", "("+e.innerText+")",
          "gi", et, e.title);
      });
   }

<?php } ?>
   window.displayIt= function displayIt(e){
      function getKeywords(e){
         var obj = {}; var out=[], ret=[];
         /* count each keyword */
         forAll(e)(function(e){
             if (e.length > 3 &&!(e in obj.__proto__)) obj[e] = obj[e]?obj[e]+=1:1; });
         /* stuff into array and sort */
         obj.forAll(function(k,v){ if (k in Object.keys(obj)) out.push([k,v]); });
         out = out.sort().reverse().slice(0,25);
         out.forAll(function(e){ret.push(e[1]);});
         return ret.join(" ");
      }

      function tsearch(contents, patterns, top = 10){
         // Search top of contents string for array of patterns
         var lines = contents.split("\n", top);
         if (lines.length < top) top = lines.length;
         if (!top) return "";
         var found = [];
         forAll (patterns, function(p){
            forAll (lines, function(l){
               var got = l.match(p);
               if (got && got[1]) found.push(got[1]);
            });
         });
         return found;
      }

      // get title and description
      var c = e.responseText;
      var isTitle = tsearch(c, [/^title:\W*(.{3,23})\W*/, /#+\s*(.{3,23})/,
                     /\s*<title>\W*(.{3,23}).*<\/title>/,
                     /<h.>\s*(.{3,23})\s*<\/h.>/, /\W*([\w\s]{10,23})/]);
      var isDesc = tsearch(c, [/^description:\s*(.*)/,
               /name[ ="\']*description.*content[ ="\']+([^"\']*)/,
               /<p>\s*(.*)\s*<\/p>/, /^([^#:><]{25}.*)/]);
      if (isTitle && isSet(isTitle[0])) document.title
         = jq("meta[property=\"og:title\"]").content = isTitle[0];
      if (isDesc && isSet(isDesc[0]))
         jq("meta[name=\"description\"]").content =
         jq("meta[property=\"og:description\"]").content = isDesc[0];
      // change to window.tmpDiv for debugging this section
      var tmpDiv = document.createElement("div");
      if (tmpDiv) {
         // put markdown into temporary div
         // if coming from internal markdown request (link click)
         if (e.responseURL.match("\.md(.cache)?\\?_firstpage$")) {
            // strip off markdown headers and mark it up to HTML
            tmpDiv.innerHTML = marked(e.responseText
               .replace(/([\w\-]+:.+\n)*/, ""));
            // otherwise it could be HTML
         } else tmpDiv.innerHTML = e.responseText;
         // if it contains no HTML text type tags then code block it
         if (!tmpDiv.jq("p, pre, code, h, b")){
               tmpDiv.innerHTML = "<code><pre>"
               +htmlEntities(e.responseText)+"</pre></code>";
         }
         // copy tmpDiv to body content
         var docContent = jq(".content");
         var eleContent = tmpDiv.jq(".content");
         if(docContent){
            if (eleContent) docContent.innerHTML = eleContent.innerHTML;
            else if(eleContent = tmpDiv.jq("body"))
               docContent.innerHTML = eleContent.innerHTML;
            else docContent.innerHTML = tmpDiv.innerHTML;
            // create links to any menu titles mentioned in content
<?php if (navbar::$createLinks) { ?>
            createLinks(docContent);
<?php } ?>
            // count content keywords
            var words = docContent.innerText.replace(/\W+/g, " ")
               .trim().split(" ");
            jq("meta[name=\"keywords\"]").content = getKeywords(words);
         }
      }
   }

 <?php if (navbar::$rewriteMenu) { ?>
   // rewrite menu for RESTful click without page load
   jEvent(window, "load", function(){
      forAll(jqa("nav .sub a"), function(e){
         jEvent(e, "click", function(u){
            if (e.href.substr(0, 4) == "http") {
               u.preventDefault();
               fetch(e.href);
            }
         }, false);
      });
 <?php } if (navbar::$createLinks) { ?>
      createLinks(jq(".content"));
 <?php } if (navbar::$styles) { ?>

      // put alternate styles on menu
      var sl = jq("nav > ul > li:last-child");
      var styles = jqa("link[rel$=\"stylesheet\"]");
      var h = "";
      forAll(styles, function(e){
        if(e.title){
             h += '<li><a class="btn" title="'+ e.rel +'"'
             + ' href="javascript:setActiveStyleSheet(\''
             + e.title + '\')">' + e.title + "</a></li>\n";
         }
      });
      sl.insertAdjacentHTML('afterEnd', ' <li> <a href="javascript:void(0);"'
      + 'class="btn">styles</a><ul class="sub">' + h + '</ul></li>');
<?php } ?>
   }, false);
   
   window.onbeforeunload = function(){
      fetch("/");
      return false;
   };
   
   marked.setOptions({
     renderer: new marked.Renderer(),
     gfm: true,
     tables: true,
     breaks: false,
     pedantic: false,
     sanitize: false,
     smartLists: true,
     smartypants: true
   });

})();

</script>
