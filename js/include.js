// <!-- -*- coding: utf-8 -*- -->
// Copyright (C) 2014-2015 by Henry Kroll, www.thenerdshow.com

/* Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

    http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License. */

"use strict";
var isSet=function(e){return typeof e!=="undefined"};
Object.prototype.forAll = function(cb) {
    var self=this, key, keys = Object.keys(self), len = keys.length;
    if(keys.forEach) keys.forEach(function(key){cb(self[key], key);});
    else for (var i=len;i--;) cb(self[keys[i]], keys[i]);
};
/** do an operation on entire object or array
 * @param {Object} - callback function
 * @param {forAllCallback} - callback function
 */
var forAll = function(obj, cb) {
    return (typeof cb === "function")
    ? obj.forAll(cb) : typeof obj === "object"
        ? function(e) { obj.forAll(e); } : undefined;
};
Array.prototype.uniq = function(){
	var obj = {};var out=[];
    this.forAll(function(e){ obj[e] = 0; });
	return Object.keys(obj);
};
if(!isSet(Object.jq)){
    Object.prototype.jq=function(query){
        return (typeof query=="string")
        ? this.querySelector(query)
        : undefined;
    };
    /** document.querySelector shortcut
     * usage:  jq("div") //1st DIV Element in document
     *         jq(myDoc, "div.x") //DIV.x Element in myDoc
     * @param {Object} obj   - object to look in
     * @param {string} query - css selector to look for
     */
    var jq = function(obj, query) {
        var res;
        return (isSet(query) && (res=obj.jq(query)))
        ? res
        : (typeof obj=="string")
            ? document.querySelector(obj)
            : undefined;
    };
}
if(!isSet(Object.jqa)){
    /** Object.querySelectorAll shortcut
     * usage:  document.jqa("div") //1st DIV Element in document
     *          myDoc.jqa("div.x") //DIV.x Element in myDoc
     * @param {string} query - css selector to look for
     */
    Object.prototype.jqa=function(query){
        return (typeof query=="string")
        ? this.querySelectorAll(query)
        : undefined;
    };
    /** document.querySelector shortcut
     * usage:  jqa("div") //1st DIV Element in document
     *         jqa(myDoc, "div.x") //DIV.x Element in myDoc
     * @param {Object} obj   - object to look in
     * @param {string} query - css selector to look for
     */
    var jqa = function(obj, query) {
        var res;
        return (isSet(query) && (res=obj.jqa(query)))
        ? res
        : (typeof obj=="string")
            ? document.querySelectorAll(obj)
            : undefined;
    };
}
/** addEventListener | attachEvent shortcut
 * @param {element}  - event element
 * @param {string}   - event string
 * @param {function} - event function
 * @param {bool}     - useCapture
 */
var jEvent = function(ele, es, func, useCapture, remove) {
    if (arguments.length == 5){
        if (ele.removeEventListener) ele.removeEventListener(es, func, useCapture);
        else if (ele.detachEvent) ele.detachEvent("on"+es, func, useCapture);
        else ele["on"+es] = "";
    } else { if (ele.addEventListener) ele.addEventListener(es, func, useCapture);
        else if (ele.attachEvent) ele.document.attachEvent("on"+es, func, useCapture);
        else ele["on"+es] = ele["on"+es+func];
    }
};

/** insert HTML before element
 *  @param {element} - element to insert HTML before
 *  @param {string}  - HTML
 */
var insertHTML = function(ele, html) {
    ele.insertAdjacentHTML("beforebegin", html);
};
/** XHRCallback prototype
 * @callback XHRCallback
 * @param {Object}  - XMLHttpRequest Object
 */

/** new XMLHttpRequest Object
 * @param {url}         url       - url of request
 * @param {XHRCallback} callback  - function
 * example: XHR("docs/foo", doMarkup) //add docs/foo to markup
 */
var XHR = function(url, callback) {
    var request = new XMLHttpRequest();
    request.open("GET", url, true);
    request.onreadystatechange = function() {
        return (this.readyState == 4
            &&  (this.status == 0 || 
        (this.status >= 200 && this.status < 300) || 
        this.status == 304 || this.status == 1223))
            ?  callback(request)
            :  null;
    };
    request.send(null);
};


/** include: dynanamic HTML include
 * @param {string} url         - url
 * @param {string} cssElements - css selector of elements to fetch
 * @param {string} cssDest     - css selector of destination container
 * @disableScripts {bool}      - don't execute included scripts
 */
var include=function(url, cssElements, cssDest, disableScripts) {
    // make scripts executable when placed on page
    var appendScripts=function(cssDest, source){
        if (!isSet(disableScripts) || !disableScripts){
            forAll(source)(function(tag){
                var el = document.createElement('script');
                el.innerHTML = tag.innerHTML;
                forAll(tag.attributes)(function(att){
                    el.setAttribute(att.name, att.value);
                });
                cssDest.appendChild(el);
                tag.parentNode&&tag.parentNode.removeChild(tag);
            });
        }
    };
    var doInclude=function(req) {
        var _doc = document.implementation.createHTMLDocument("_doc");
        _doc.documentElement.innerHTML = req.responseText;
        //~ if no specific content cssElements get body content
        if (!isSet(cssElements) || cssElements == "")
            var content = _doc.jqa("body > *");
        else content = _doc.jqa(cssElements);
        //~ copy scripts to head and body
        var scripts = jqa("script");
        var myScript = scripts[scripts.length - 1];
        var headScripts=_doc.jqa("head > script");
        appendScripts(jq("head"), headScripts);
        var bodyScripts=_doc.jqa("body > script");

        //~ if we have a destination
        if (isSet(cssDest)) {
            //~ try to locate destination element(s)
            var destElements=jqa(cssDest);
            //~ insert content(s) into *every* destination
            if (isSet(destElements)) {
                forAll(destElements)(function(destNode){
                    forAll(content)(function(node){
                        var clonedNode = node.cloneNode();
                        clonedNode.innerHTML = node.innerHTML;
                        if (clonedNode.tagName=="SCRIPT"){
                            appendScripts(destNode,[clonedNode]);
                        }
                        else destNode.appendChild(clonedNode);
                        clonedNode=null;
                    });
                });
            }
            else console.error(
                "include(url,cssElements,cssDest): dest "
                + dest + " not found!");
        //~ else insert content(s) before myScript tag
        } else {
            forAll(content)(function(node) {
                myScript.parentNode.insertBefore(
                    node, myScript);
            });
            appendScripts(myScript.parentNode, bodyScripts);
        }
        ret=null;
        _doc=null;
    };
    XHR(url, doInclude);
};
