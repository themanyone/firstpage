headings:      docs
cache:         https://raw.githubusercontent.com/themanyone/firstpage/master/README.md
expires:       24
title:         README
description:   A PHP Menu generator and content manager for files.

# FirstPage

A PHP Menu generator and content manager for files. Drop files onto the server and FirstPage takes care of the rest!

Brought to you by [The Nerd Show](//thenerdshow.com/)

* Menus [update themselves](#menus-update-themselves)
* Reduce duplicate work
* [Cache management](#use-github-as-a-blogging-platform) for remote pages
* Supports [Markdown](#supports-markdown) and HTML.
* Site search with [regex](http://php.net/manual/en/function.preg-match.php)
* Updates [sitemaps](https://www.sitemaps.org/index.html) and [RSS feeds](https://www.mnot.net/rss/tutorial/) as content changes
* Use [GitHub as a blogging platform](#use-github-as-a-blogging-platform)
* [Unlimited styles and layouts](#adding-new-styles)
* Encourages [responsive web design](#supports-responsive-web-design)
* Rewrites links [so they don't reload the page](#fewer-page-reloads)
* Automatic [keyword generation](#automatic-keyword-generation)
* Works without a database
* [Deploy and back up easily](#installation)
* Update via [GIT](//git-scm.com/download/win), FTP, panel, etc.

This project is now **[available on GitHub](//github.com/themanyone/firstpage)**.

## Site Updates Itself

**FirstPage** updates menus, .rss feeds, and sitemaps hourly (adjustable) to match server contents, but only if the site is accessed. Build a website by uploading files. A `<head data-headings=` header tells FristPage what category headings to list documents under. **This is probably the only header you need to add.**

## Optional Headers

&lt;head data- header     | what it does
--------------------------|-------------
data-headings="foo, bar"       | List under foo and bar headings.*
data-cache="http://foo.com"    | Show cached copy of foo.com
data-expires=4                 | Fetch new copy after 4 hours.
data-url="http://foo.com/file" | Get from foo.com (no cache).

\* Files without `data-headings` go into an *Other* menu category where they may not show up because this feature can be turned off in `navbar.phpx`.

## Adding Custom Menu Items

The menu updates itself, but sometimes it is desirable to add a local file that isn't being noticed by the menu (recognized file types are configurable in navbar.phpx) or add a remote URL to the menu, sitemap, and RSS feeds. There are several ways to do this.

* Cache the remote content. This requires creating a *HTML shortcut*, an HTML file containing the necessary `<head data-cache` and optional `data-expires` headers. 

* Link the remote content. Create a *HTML shortcut* with the appropriate `<head data-url="..."` header like we did with license.md. The license.md shortcut is necessary to make the link to LICENSE appear in the menu because LICENSE is not one of the file types FirstPage examines. FirstPage adds the shortcut's, title, description, and URL to the navigation bar.

* Hoard (mirror) the remote content. Copy the remote content to the server and keep it there. Mirrors may have to be manually updated from time to time, but they are a nearly foolproof way of backing things up.

## HTML Shortcut Files

Shortcuts can be in either HTML or Markdown format. It may be easier to use Markdown for this (see the next section).

```
<html><head
data-url="//thenerdshow.com"
data-headings="remote files, nerd stuff">
<title>The Nerd Show</title>
<meta name="description" content="Nerd stuff...">
```

## Supports Markdown

FirstPage finds and displays markdown (*.md) documents with [marked.js](//github.com/chjj/marked) using the simple [GitHub Markdown format](//guides.github.com/features/mastering-markdown), allowing people to make web pages quickly using an intuitive, wiki-like syntax. Markdown headers are a lot like HTML headers. Put these optional headers at the top of markdown files, so FirstPage can better index them.

header     | what it does
-----------|-------------
headings : foo, bar       | List under foo and bar headings.*
cache : http://foo.com    | Display cached copy of foo.com
expires : 4               | Fetch new copy after 4 hours.
url : http://foo.com/file | Download from foo.com (no cache).

\* Files without `headings:` go into an *Other* menu category where they may not show up because this feature can be turned off in `navbar.phpx`.

## Markdown Shortcut Files

To add an off-site link to the menu, sitemap, and rss feeds, create a local placeholder (a shortcut file) for it and upload it to the server. Shortcuts can be in either HTML or Markdown format.

```
headings : docs, help
title : The Nerd Show
description : Nerd stuff
url : https://thenerdshow.com
```
The above shortcut file, when uploaded to the server, instruct FirstPage to put a link to The Nerd Show in the menu under "docs" and "help." The order of the elements is not critical. Try it! There are no real restrictions on what can go in a shortcut. This README is actually a cache shortcut that FirstPage uses to cache a more up-to-date copy of itself in the menu under "docs".

## Use GitHub as a Blogging Platform

We can upload markdown to GitHub and drop shortcuts on the web server. The server will track updates as they change on GitHub. **Cached files** with cache: header will persist even if the remote files disappear! If you do not want to keep cached copies around, use the url : shortcut instead. **URL shortcuts** are never cached.

## Automatic Keyword Generation

After picking an item from the menu, FirstPage inserts a `<meta>` keywords tag. Developers can view this using the "inspect element" function in developer tools. Modern search engines index these dynamically-created pages, and some of them check keywords against the actual content to make sure no one is gaming the system and inserting irrelevant keywords such as Kanye West.

## Automatic Meta Tag Generation

If there are no meta tags in the document, FirstPage will attempt to create them dynamically as it displays different pages from the menus.

* `#` Heading at start of page becomes the &lt;title&gt; tag.
* The first sentence becomes the website description.
* Google [apparently indexes](http://searchengineland.com/tested-googlebot-crawls-javascript-heres-learned-220157) these automatically generated tags.

## Generates RSS Feeds and Sitemaps

In addition to making the menu, FirstPage now generates sitemap.xml and an RSS Feed. 

## Supports Responsive Web Design

The sky is the limit! Since FirstPage leaves existing content alone and merely builds menus and displays them, there is no reason not to [modify the stylesheets and files any way you see fit](//www.mezzoblue.com/zengarden/alldesigns/). Go nuts! [Responsive web resign is recommended](//www.w3schools.com/css/css_rwd_mediaqueries.asp) for mobile browsing.

## Dynamic Menu Links

The double-underlined links in the document are menu links. If menu items like LICENSE appear in the text, FirstPage will turn them into links!

## Fewer Page Reloads

When Javascript is available FirstPage uses AJAX XMLHTTPRequest to create a RESTful user experience. These background requests save bandwidth and avoid tiresome page reloads that leave competitor's sites *blank* in the event of bad links or internet congestion. Click on any menu items, or double-underlined links and watch. The page does not reload!

An arrow ![Arrow](images/externallink.png) pops up next to external links that require a page reload.

## Installation

1. Get [FirstPage from GitHub](https://github.com/themanyone/firstpage)
1. Optionally update [marked.js](https://github.com/chjj/marked).
2. See that the latest marked.min.js is in the js folder of this project.
3. Use this README.md as a template or replace with custom markdown.
4. Edit the `navbar.phpx`, `.httaccess` and other files as desired.
3. Copy this project to a web server.

## Testing

You may test this project

* by running `php -S addr:port&`
* by copying it to a PHP web server.
* or with [many other test servers](http://unix.stackexchange.com/questions/32182/simple-command-line-http-server).

## Troubleshooting

**Javascript** TypeError: Cannot read property 'insertAdjacentHTML' of null

Edit the included .htaccess file and replace `test.k` with your domain name at the bottom of the file. Customize things as desired. In addition, `RewriteBase /firstpage/` should be changed to `/` if FirstPage is to manage the server root.

Permission denied errors: Check permissions. Make sure documents are owned by the process running PHP. See below.

### Rebuild sitemaps

If the menu or cached items are not updating, check folder permissions, review the error logs, and rebuild the menus. Folder permissions should be write and exec owned by the process running PHP e.g. apache 775 or 755. File permissions are 644 except for sitemap.* which are managed by PHP.

1. Delete all the sitemap files. `rm sitemap.*`
2. Refresh the page (Press `F5` in the browser) to rebuild them.
3. Delete sitemap.nav to reconstruct the menu *again*.
4. It should pick up all the latest files now.

### Rebuild cached files

It may be desirable to rebuild some cached files to make sure they are up to date before that ad campaign runs, etc.

1. Delete the .cache file(s) that need rebuilt. `rm myShortcut.md.cache`
2. Touch the shortcut with an earlier date. `touch -t 01010000 myShortcut.md`
3. Delete `rm sitemap.nav` to rebuild everything *again*.

## Adding New Styles

Add "alternate stylesheet" tags to FirstPage's `index.php` and these styles will show up in the menu. The listing of stylesheets in the menu may be turned off in `navbar.phpx`.

*Create a new .css file using one of the existing stylesheets as a template.
* Add a link to it like this:

```
<link rel="alternate stylesheet"
title="myStyle" type="text/css" href="css/myStyle.css?v=1.0">
```

If design changes are not showing up the `v=1.0` version number may be incremented to force browsers to fetch an updated CSS file. Cache expiration is set to one day in `.httaccess` but should probably be set longer for production sites. 
