<!DOCTYPE html><html lang="en-US"><head data-headings="help">
<meta charset="utf-8"><!-- -*- coding: utf-8 -*- -->
<title>FirstPage</title>
<meta property="og:title" content="FirstPage" />
<meta name="copyright" content="© 2017 Henry Kroll www.thenerdshow.com" />
<meta name="description" content="A PHP Menu generator and content manager for files." />
<meta property="og:description" content="A PHP Menu generator and content manager for files." />
<meta property="og:description" content="65,535 little turkeys be enjoyin' this." />
<meta name="keywords" content="menu from permalink headings expires documents copy them labels label hours Fetch Data what using update under that permalinked management links head contents cache after">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta property="og:image" content="http://worldzoo.com/files/uploads/uxtri.png">
<meta property="og:image" content="https://encrypted-tbn2.gstatic.com/images?q=tbn:ANd9GcSLqN9dmM5G5A_R9NlPiWFKDJrI3VKRha6x1nqAKIkPbTzfEgDY">
<meta property="og:image" content="http://www.kordahitechnologies.com/sites/default/files/cms-development-services.png">
<meta property="og:image" content="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRH4LXJDpaIdUzEJxLl_1LzPHwlCUrbeR-46RDUmXluzYZdccxd">
<!-- Copyright (C) 2017 Henry Kroll http://thenerdshow.com
By all means redistribute, and credit the above link and author!

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

    http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
-->
<link rel="stylesheet" title="preferred" type="text/css" href="css/preferred.css?v=1.4">
<link rel="alternate stylesheet" title="plain" type="text/css" href="css/plain.css?v=1.2">
<link rel="alternate stylesheet" title="xmas" type="text/css" href="css/xmas.css?v=1.2">
<script src="js/include.js"></script>
<script src="js/marked.min.js"></script>
<script src="js/styleswitcher.js"></script>

</head>
<body>

<div style="margin:auto;width:99%">
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- Responsive -->
<ins class="adsbygoogle"
style="display:block"
data-ad-client="ca-pub-6719667455972380"
data-ad-slot="4356826116"
data-ad-format="auto"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
</div>

<form method="post" action="search.phpx" onsubmit="return submitForm(this);">
<div id="search">
<input name="_q" type="search" placeholder="search" required>
<input type="submit" value=" ">
</div></form>


<?php
function __autoload($class_name) {
    require_once $class_name . '.phpx';
}
$navbar = new navbar;
?>
   <div class="content shadow rounded">
      
      
      <h1>FirstPage</h1>
      
      <p>SEO, content management, and blogging using conventional files. Drop files onto the server and FirstPage takes care of the rest!
      </p>
     
      <ul>
         <li>Menus stay updated</li>
         <li>Supports <a href="https://guides.github.com/features/mastering-markdown/">Markdown</a> and HTML</li>
         <li>Caching of remote pages</li>
         <li>Unlimited styles and layouts</li>
         <li>Automatic keyword generation</li>
         <li>Supports responsive web design</li>
         <li>Publishes an <a href="https://www.mnot.net/rss/tutorial/">RSS feed</a> of menu contents</li>
         <li>Builds <a href="https://www.sitemaps.org/index.html">sitemaps</a> for crawlers to chew on</li>
         <li>Deploys and backs up easily</li>
         <li>No database required or used</li>
         <li>Updates via GIT, FTP, panel, etc.</li>
      </ul>

      <h2>Why This Exists</h2>
      
      <p>We can use our existing HTML pages. It doesn't require anything special except putting some optional data in the head tag to organize them. We can use a mix of HTML pages and Markdown.</p>

      <p><b>Less duplicate work.</b> We develop dozens of <a href="https://github.com/themanyone">GitHub projects</a> ourselves and syndicating them to a dozen websites and mirrors with hundreds of files each. Updating their menus, links, RSS feeds, and sitemaps became tiresome. We did not want the additional complexity of a database on top of all that. We like being able to back up our websites with a quick copy.</p>
      
      <p><b>Reduced server overhead.</b> Browsers cache the result of AJAX queries for many hours, unless <a href="http://www.itgeared.com/articles/1401-ajax-browser-cache-issues-fix/">instructed to do otherwise</a>, so there is nothing wrong with having websites fetch content dynamically. In fact, there are several advantages.</p>

      <h2>Menu Updates Itself</h2>
       
       <p>The FirstPage menu updates itself hourly to match server contents. It lets documents decide which menu headings they belong under by way of data-headings (see README or view source). When Javascript is available the menu uses AJAX to create a RESTful user experience. This saves bandwidth and avoids potentially-troublesome page reloads that leave competitor's sites <em>blank</em> in the event of internet congestion. Choose an item from the menu and watch. The page does not reload! FirstPage outputs standards-compliant css-styled horizontal or vertical <code>&lt;nav&gt;</code> elements with drop-down button menus that fall back to bullet lists for older browsers.</p>
      
      <h2>Caching of Remote Pages</h2>
      
      <p>The FirstPage content management system can cache remote content using <i>web shortcuts</i>, placeholder files that tell FirstPage where to get the content. See <code>README.md</code> for details. There are two types of web shortcuts, url shortcuts and cache shortcuts. There are no restrictions on what can go in a shortcut. <code>README.md</code> is itself a cache shortcut that maintains a cached version of itself under <code>README.md.cache</code>.</p>
      
      <h2>New Developments</h2>
      
      <ul>
         <li>Site Search</li>
         <li>More styles :)</li>
      </ul>
      
   </div>
</body>
</html>
