headings:      docs
cache:         https://raw.githubusercontent.com/themanyone/firstpage/master/README.md
expires:       24
title:         README
description:   SEO, content management, and blogging using conventional files.

# FirstPage

SEO, content management, and blogging using conventional files. Drop files onto the server and FirstPage takes care of the rest!

Brought to you by [The Nerd Show](//thenerdshow.com/)

* Menus [update themselves](#menus-update-themselves)
* Reduce duplicate work
* [Cache management](#use-github-as-a-blogging-platform) for remote pages
* Supports [Markdown](#supports-markdown) and HTML.
* Use [GitHub as a blogging platform](#use-github-as-a-blogging-platform)
* [Unlimited styles and layouts](#adding-new-styles)
* Support [responsive web design](#supports-responsive-web-design)
* Click menus [without page reload](#no-page-reloads)
* Automatic [keyword generation](#automatic-keyword-generation)
* Works without a database
* Deploy and back up easily
* Update via [GIT](//git-scm.com/download/win), FTP, panel, etc.

This project is now **[available on GitHub](//github.com/themanyone/firstpage)**.

## Menus Update Themselves

**FirstPage** indexes existing content with menus that update themselves. Build a website by uploading files. FirstPage scans for new, removed, or renamed files periodically, updating the menus. A <head data-headings=` header tells FristPage what category headings to list them under. This is probably the only header you need to use.

FirstPage can also cache and update documents from a url, or always fetch their contents from an external source. Files without labels go into an *Other* menu category where they may not show up because this feature can be turned off in `navbar.phpx`.

header     | what it does
-----------|-------------
data-headings="foo, bar"       | List under foo and bar headings.
data-cache="http://foo.com"    | Show cached copy of foo.com
data-expires=4                 | Fetch new copy after 4 hours.
data-url="http://foo.com/file" | Get from foo.com (no cache).

## Managing Remote Content

The menu only looks at files on the server. If you want to add remote files to the menu there are several ways to do this.

1. Cache the remote content. This requires creating a *HTML shortcut*, basically an HTML file containing the necessary `<head data-cache` and optional `data-expires` headers. 

2. Link the remote content. Create a *HTML shortcut* with the appropriate `<head data-url="..."` header. FrontPage will use the shortcut's, title, description, and URL in the menu.

3. Hoard (mirror) the remote content. Copy the remote content to the server and keep it there. This may have to be manually updated from time to time.

## HTML Shortcuts

Shortcuts can be in either HTML or Markdown format. It may be easier to use Markdown for this (see the next section).

```
<html><head
data-url="//thenerdshow.com"
data-headings="remote files, nerd stuff">
<title>The Nerd Show</title>
<meta name="description" content="Nerd stuff...">
```

## Supports Markdown

FirstPage finds and displays markdown (*.md) documents with [marked.js](//github.com/chjj/marked) using the popular [GitHub Markdown format](//guides.github.com/features/mastering-markdown), allowing people to make web pages easily using an intuitive, wiki-like syntax. Markdown headers are a lot like HTML headers. Put these optional headers at the top of markdown files so FirstPage can better index them.

header     | what it does
-----------|-------------
headings : foo, bar       | List under foo and bar headings.
cache : http://foo.com    | Display cached copy of foo.com
expires : 4               | Fetch new copy after 4 hours.
url : http://foo.com/file | Download from foo.com (no cache).

## Markdown Shortcuts

To add a remote file link to the menu, create a placeholder (a web shortcut) for it and upload it to the server. Shortcuts can be in either HTML or Markdown format.

```
headings : remote files, nerd stuff
title : The Nerd Show
description : Nerd stuff...
url : https://thenerdshow.com
```
The above shortcut instruct FirstPage to put a link to The Nerd Show in the menu. Try it! There are no other restrictions on what can go in a shortcut. This README is actually a cache shortcut that FirstPage uses to display a more up-to-date copy of itself in the menu under "docs".

## Use GitHub as a Blogging Platform

Using the above headers we can upload markdown to GitHub and drop shortcuts on the web server. The server will track updates as they change on GitHub. **Cached files** with cache: header will persist even if the remote files are deleted! If you do not want to keep cached copies around, use the url : shortcut instead. **URL shortcuts** are never cached.

## Automatic Keyword Generation

After picking an item from the menu, FirstPage inserts a `<meta>` keywords tag. Developers can view this using the "inspect element" function in developer tools. Modern search engines index these dynamically-created pages, and some of them check keywords against the actual content to make sure no one is gaming the system and inserting irrelevant keywords such as Kanye West.

## Automatic Meta Tag Generation

If there are no meta tags in the document, FirstPage will attempt to create them dynamically as it displays different pages from the menus.

* `#` Heading at start of page becomes the &lt;title&gt; tag.
* The first sentence becomes the website description.
* Google [apparently indexes](http://searchengineland.com/tested-googlebot-crawls-javascript-heres-learned-220157) these automatically generated tags.

## Supports Responsive Web Design

The sky is the limit! Since FirstPage leaves existing content alone and merely builds menus and displays them, there is no reason not to [modify the stylesheets and files any way you see fit](//www.mezzoblue.com/zengarden/alldesigns/). Go nuts! [Responsive web resign is recommended](//www.w3schools.com/css/css_rwd_mediaqueries.asp) for mobile browsing.

## Installation

1. Get [FirstPage from GitHub](https://github.com/themanyone/firstpage)
1. Optionally update [marked.js](https://github.com/chjj/marked).
2. See that the latest marked.min.js is in the js folder of this project.
3. Edit this README.md or replace with custom markdown.
3. Copy this project to a web server.

## Testing

You may test this project

* by running `python3 -m http.server&`
* by running `php -S addr:port&`
* with `webfsd -F -p 8000&`
* using `ruby -run -ehttpd . -p8000&`
* or with [many other test servers](http://unix.stackexchange.com/questions/32182/simple-command-line-http-server).

## Smoother Browsing without Reloads

When Javascript is available the menu uses AJAX XMLHTTPRequest to create a RESTful user experience. This saves bandwidth and avoids tiresome page reloads that leave competitor's sites blank in the event of internet congestion. Choose an item from the menu and watch. The page does not reload!

## Adding New Styles

Add alternate stylesheet tags to FrontPage's `index.html` and these alternate styles will show up in the menu. This feature may be turned off in `navbar.phpx`.
