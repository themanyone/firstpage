headings:       docs
permalink:      //raw.githubusercontent.com/themanyone/firstpage/master/README.md
title:          README
description:    SEO, content management, and blogging using conventional files.

# FirstPage

SEO, content management, and blogging using conventional files. Drop files onto the server and FirstPage takes care of the rest!

Brought to you by [The Nerd Show](//thenerdshow.com/)

* No more bad links
* Menus [update themselves](#menus-update-themselves)
* [Cache management](#use-github-as-a-blogging-platform) for remote pages
* Supports [Markdown](#supports-markdown) and HTML.
* Use [GitHub as a blogging platform](#use-github-as-a-blogging-platform)
* [Unlimited styles and layouts](#adding-new-styles)
* Support [responsive web design](#supports-responsive-web-design)
* Click menus [without page reload](#no-page-reloads)
* Automatic [keyword generation](#automatic-keyword-generation)
* No database required or used
* Deploy and back up easily
* Update via [GIT](//git-scm.com/download/win), FTP, panel, etc.

This project is now **[available on GitHub](//github.com/themanyone/firstpage)**.

## No More Bad Links

**FirstPage** puts a pretty face on your existing content with menus that update themselves. Build the website just by uploading files. FirstPage scans for new, removed, or renamed files periodically, updating the menus using the document's existing meta tags. Update the server with proven content versioning systems like GIT or plain old uploads via FTP or server panel. There is no need to update the menu!

## Menus Update Themselves

** FirstPage** finds and indexes HTML documents and lists them in the menu using a `<head data-headings=` header to organize pages under the appropriate menu headings. This is probably the only header you need to use. Look at the source of this page for an example.

FirstPage can also cache and update documents from a url, or always fetch their contents from an external source. Files without labels go into an *Other* menu category where they may not show up because this feature can be turned off in `navbar.phpx`.

header     | what it does
-----------|-------------
data-headings="foo, bar"   | List under foo and bar headings.
data-permalink="//foo.com" | Display cached copy of foo.com
data-expires=4             | Fetch new copy after 4 hours.
data-url="//foo.com/file"  | Download from foo.com (no cache).

## Example HTML Shortcut

The menu only looks at files on the server. If you want to add remote files to the menu there are at least *three ways* to do this.

1. Cache the remote content. This requires access to the remote file in order to add the necessary `data-permalink` header. Otherwise as soon as the cache updates the `data-permalink` header will be gone! Copy the file to the server *once* and it should update itself from time to time (default every 4 hours).

2. Create a permanent local copy just like we did in step 1 by trying to cache a remote file we do not own. This may have to be manually updated from time to time.

3. Create data-url placeholder (web shortcut) file with the appropriate `<head data-url="//somewhere..."` headers. FrontPage will list the supplied, title, description, and URL in the menu. It may be easier to use Markdown for this (see below).

```
<html><head
data-url="//thenerdshow.com"
data-headings="remote files, nerd stuff">
<title>The Nerd Show</title>
<meta name="description" content="Nerd stuff...">
```

## Supports Markdown

FirstPage finds and displays markdown (*.md) documents with [marked.js](//github.com/chjj/marked) using the popular [GitHub Markdown format](//guides.github.com/features/mastering-markdown), allowing people to make web pages easily using an intuitive, wiki-like syntax. Markdown data labels are a lot like HTML data labels. Put these optional headers at the top of markdown files so FirstPage can index them.

header     | what it does
-----------|-------------
headings : foo, bar   | List under foo and bar headings.
permalink : //foo.com | Display cached copy of foo.com
expires : 4           | Fetch new copy after 4 hours.
url : //foo.com/file  | Download from foo.com (no cache).

## Example Markdown Shortcut

To add a remote file link to the menu, create a placeholder (a web shortcut) for it and upload it to the server. Placeholder files can be in either HTML or Markdown format.

```
headings : remote files, nerd stuff
title : The Nerd Show
description : Nerd stuff...
url : //thenerdshow.com
```
These headers instruct FirstPage to create a menu link with title and description pointing to url.

## Use GitHub as a Blogging Platform

Using the above headers we can upload markdown to GitHub and add a permalink: header to it, or create a url: shortcut. Put these on the server *once* and the server will track updates as they change on GitHub. **Cached files** with permalink: header will persist even if the remote files are deleted! If you do not have access to change the remote file to add a permalink: header to it, use the url : shortcut instead. **URL shortcuts** are never cached.

## Automatic Keyword Generation

After choosing an item from the menu, FirstPage inserts a `<meta>` keywords tag. Developers can view this using the "inspect element" function in developer tools. Modern search engines index these dynamically-created pages, and some of them check keywords against the actual content to make sure no one is gaming the system and inserting irrelevant keywords such as Kanye West.

## Supports Responsive Web Design

The sky is the limit! Since FirstPage leaves existing content alone and merely builds menus and displays them, there is no reason not to [modify the stylesheets and files any way you see fit](//www.mezzoblue.com/zengarden/alldesigns/). Go nuts! [Responsive web resign is recommended](//www.w3schools.com/css/css_rwd_mediaqueries.asp) for mobile browsing.

## No page reloads

When Javascript is available the menu uses AJAX XMLHTTPRequest to create a RESTful user experience. This saves bandwidth and avoids potentially-troublesome page reloads that leave competitor's sites blank in the event of internet congestion. Choose an item from the menu and watch. The page does not reload!

## Adding New Styles

Add alternate stylesheet tags to FrontPage's `index.html` and these alternate styles will show up in the menu. This feature may be turned off in navbar.phpx.
