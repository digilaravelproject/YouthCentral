(function () {

   'use strict';

   // MAIN VARIABLES ===========================================================

   var loc = window.location.href;
   var dir = loc.substring(0, loc.lastIndexOf('/')),
   domain = window.location.hostname,
   readingFile = 0,
   loadedAllCSS = 0,
   loadedAllJS = 0,
   embeddingJS = 0,
   waitingDelay = 0,
   pageHead = document.head || document.getElementsByTagName('head')[0],
   pageBody =  document.body || document.getElementsByTagName('body')[0];

   // FUNCTIONS ================================================================

   String.prototype.replaceAll = String.prototype.replaceAll || function(needle, replacement) {
      return this.split(needle).join(replacement);
   };

   function readTextFile(file,isCSS){

      if(isCSS&&file.indexOf("!")>=0){
         file = file.split("!");
         file = file[0];
      }
      else{
         file = checkDelay(file);
      }

      var rawFile = new XMLHttpRequest();
      rawFile.open("GET", file, true);
      readingFile = 1;
      embeddingJS = 0;
      rawFile.onreadystatechange = function (){
         if(rawFile.readyState === 4)
         {
            if(rawFile.status === 200 || rawFile.status === 0){
               var a = rawFile.responseText; // All Code As Text
               if(isCSS===true){
                  var replaceDir = file.substring(0, file.lastIndexOf('/')) + "/";
                  a = a.replaceAll('url (../', 'url(../');
                  a = a.replaceAll('url( ../', 'url(../');
                  a = a.replaceAll('url ( ../', 'url(../');
                  a = a.replaceAll('url ("../', 'url("../');
                  a = a.replaceAll('url( "../', 'url("../');
                  a = a.replaceAll('url(" ../', 'url("../');
                  a = a.replaceAll("url ('../", "url('../");
                  a = a.replaceAll('url( "../', 'url("../');
                  a = a.replaceAll('url(" ../', 'url("../');
                  /*a = a.replaceAll('url (/', 'url(');
                  a = a.replaceAll('url ("/', 'url("');
                  a = a.replaceAll("url ('/", "url('"); BETA */
                  a = a.replaceAll('url(', 'url(' + replaceDir);
                  a = a.replaceAll('url(' + replaceDir + "'", "url('" + replaceDir);
                  a = a.replaceAll('url(' + replaceDir + '"', 'url("' + replaceDir);
                  //
                  a = a.replaceAll(replaceDir + ' http://www', 'http://www');
                  a = a.replaceAll(replaceDir + 'http://www', 'http://www');
                  a = a.replaceAll(replaceDir + ' https://www', 'https://www');
                  a = a.replaceAll(replaceDir + 'https://www', 'https://www');
                  //
                  a = a.replaceAll(replaceDir + ' http://', 'http://');
                  a = a.replaceAll(replaceDir + 'http://', 'http://');
                  a = a.replaceAll(replaceDir + ' https://', 'https://');
                  a = a.replaceAll(replaceDir + 'https://', 'https://');
                  //
                  a = a.replaceAll(replaceDir + 'url(', 'url(' + replaceDir);
                  //
                  includeCSSAsCode(a);
               }
               else{ // Is JS
                  // First ensure that current script won't embed other scripts
                  var skip = 0,
                  c = 0,
                  d = 0,
                  shouldntMatch = [
                     a.indexOf("<script"),
                     a.indexOf("/script"),
                     a.indexOf("getScript"),
                     a.indexOf("script src"),
                     a.indexOf("text/javascript"),
                     a.indexOf("includeJS"),
                     a.indexOf("HttpRequest"),
                     a.indexOf(".open("),
                     a.indexOf("createElement('script"),
                     a.indexOf('createElement("script'),
                     a.indexOf("createElement( 'script"),
                     a.indexOf('createElement(" script'),
                     a.indexOf('createElement( " script')
                  ],
                  allowMatch = [
                     a.indexOf("#password-form"),
                     a.indexOf("JavaScript requires jQuery"),
                     a.indexOf("jQuery requires a window"),
                     a.indexOf("cannot call methods on")
                  ];
                  for(c=0;c<shouldntMatch.length;c++){
                     if(shouldntMatch[c]>=0)
                     skip = 1;
                  }
                  for(d=0;d<allowMatch.length;d++){
                     if(allowMatch[d]>=0)
                     skip = 0;
                  }
                  if(skip===0){
                     // Minify (coming soon) and include
                     includeJSAsCode(a);
                  }
                  else{
                     // Include without minification
                     includeJS(file);
                  }
               }
               readingFile = 0;
            }
         }
      };
      rawFile.send(null);
   }

   // PARSING CSS ==============================================================

   function checkIfCSSAlreadyExists(url){
       if (!url) url = "http://xxx.co.uk/xxx/script.js";
       var links = getElements("link");
       for (var i = links.length; i--;) {
           if (links[i].href === url) {
             return true;
          }
       }
       return false;
   }

   function includeCSS(url){
      var link  = document.createElement('link');
      link.rel  = 'stylesheet';
      link.type = 'text/css';
      link.href = url;
      pageBody.appendChild(link);
      readingFile = 0;
   }

   function includeCSSAsCode(code){
      var css = code,
      style = document.createElement('style');
      style.type = 'text/css';
      if (style.styleSheet){
         style.styleSheet.cssText = css;
      }
      else {
         style.appendChild(document.createTextNode(css));
      }
      pageHead.appendChild(style);
      readingFile = 0;
   }

   function checkDelay(url){
      if(url.indexOf("!")>=0){
         waitingDelay = url.split("!");
         url = waitingDelay[0];
         waitingDelay = waitingDelay[1];
      }
      return url;
   }

   function executeDelay(){
      if(waitingDelay>0){
         var dec = setInterval(function(){
            waitingDelay = waitingDelay - 100;
            if(waitingDelay<=0){
               waitingDelay = 0;
               clearInterval(dec);
            }
         },100);
      }
   }

   function loadCSS(){
      var cssCount = 0;
      var loadCSS = setInterval(function(){
         if(!readingFile&&waitingDelay===0){
            if(cssCount<cssFiles.length){
               if(!checkIfCSSAlreadyExists(dir + "/" + cssFiles[cssCount])){
                  if(dir.indexOf("http://")>=0||dir.indexOf("https://")>=0){ // if on online server
                     if(cssFiles[cssCount].indexOf(domain)>=0 || cssFiles[cssCount].indexOf("http")===-1){ // contains current domain
                        // minify with php/js (coming soon) and include
                        var code = readTextFile(dir + "/" + cssFiles[cssCount],true);
                     }
                     else{
                        // include without minification
                        includeCSS(cssFiles[cssCount]);
                     }
                  }
                  else{ // if runing locally (offline)
                     // include without minification
                     includeCSS(cssFiles[cssCount]);
                  }
               }
               cssCount++;
            }
            else{
               loadedAllCSS = 1;
               clearInterval(loadCSS);
            }
         }
      },50);
   }

   loadCSS();

   // PARSING JS ===============================================================

   function checkIfJSAlreadyExists(url){
       if (!url) url = "http://xxx.co.uk/xxx/script.js";
       var scripts = getElements("script");
       for (var i = scripts.length; i--;) {
           if (scripts[i].src === url) return true;
       }
       return false;
   }

   function includeJS(url){
      embeddingJS = 1;
      readingFile = 0;
      function onLoad() {
         embeddingJS = 0;
         executeDelay();
      }
      var cleanURL = checkDelay(url);
      var script = document.createElement('script');
      script.src = cleanURL;
      script.onload = onLoad;
      pageBody.appendChild(script);
   }

   function includeJSAsCode(code){
      var js = code,
      script = document.createElement('script');
      script.text = js;
      pageBody.appendChild(script);
      executeDelay();
   }

   function loadJavascript(){
      var jsCount = 0,
      jsTime,
      jsMaxTime = 3000,
      loadJS = setInterval(function(){
         if(embeddingJS===0&&!readingFile&&waitingDelay===0){
            jsTime = 0;
            if(jsCount<jsFiles.length){
               if(!checkIfJSAlreadyExists(dir + "/" + jsFiles[jsCount])){
                  if(dir.indexOf("http://")>=0||dir.indexOf("https://")>=0){ // if is online server
                     if(jsFiles[jsCount].indexOf(domain)>=0 || jsFiles[jsCount].indexOf("http")===-1){ // contains current domain
                        // minify with php/js (coming soon) and include
                        var code = readTextFile(dir + "/" + jsFiles[jsCount],false);
                     }
                     else{
                        // include without minification
                        includeJS(jsFiles[jsCount]);
                     }
                  }
                  else{ // if running locally (offline)
                     // include without minify
                     includeJS(jsFiles[jsCount]);
                  }
               }
               jsCount++;
            }
            else{
               loadedAllJS = 1;
               clearInterval(loadJS);
            }
         }
         else{
            jsTime += 100;
            if(jsTime>=jsMaxTime){
               jsTime = embeddingJS = readingFile = waitingDelay = 0;
            }
         }
      },100);
   }

   // GENERAL ==================================================================

   function getElements(elem){
      elem = document.getElementsByTagName(elem);
      return elem;
   }

   function setPlaceholderClass(){
      var imgs = getElements("img");
      var i;
      for(i=0;i<imgs.length;i++){
         imgs[i].className += " img-placeholder";
      }
   }
   setPlaceholderClass();

   function eliminatePlaceholderStyles(imgs){
      setTimeout(function(){
         var allStyles,
         k=-1,
         l,
         eliminated=0;
         var eliminate = setInterval(function(){
            var allStyles = document.getElementsByTagName('style');
            if(eliminated){
               eliminated = 0;
            }
            else{
               k++;
            }
            if(k<allStyles.length){
               if(allStyles[k].hasAttribute("class")){
                  if(allStyles[k].getAttribute("class").indexOf("placeholder-style")>=0){
                     allStyles[k].outerHTML = "";
                     eliminated = 1;
                  }
               }
            }
            else{
               clearInterval(eliminate);
            }
         },1);
         for(l=0;l<imgs.length;l++){
            if(imgs[l].hasAttribute("class")){
               if(imgs[l].getAttribute("class").indexOf("style-width")>=0){
                  imgs[l].className = imgs[l].className.replace(" placeholder-style-width-"+l,"");
                  imgs[l].className = imgs[l].className.replace("placeholder-style-width-"+l,"");
               }
               if(imgs[l].getAttribute("class").indexOf("style-height")>=0){
                  imgs[l].className = imgs[l].className.replace(" placeholder-style-height-"+l,"");
                  imgs[l].className = imgs[l].className.replace("placeholder-style-height-"+l,"");
               }
            }
         }
      },3000);
   }

   function setTemporaryImageDimensions(){

      var
      datasrc,
      imgsrc,
      css,
      style,
      hasPlaceholder,
      head = document.head || document.getElementsByTagName('head')[0],
      j = 0,
      imgs = getElements("img"),
      imageWidth = 0,
      imageHeight = 0;

      if(imgs.length){
         var s = setInterval(function(){

            hasPlaceholder = 0;

            if(j<imgs.length){
               // The "visible" placeholder images must be reset before this
               if(imgs[j].hasAttribute("class")){
                  if(imgs[j].getAttribute("class").indexOf("img-placeholder")>=0){
                     hasPlaceholder = 1;
                  }
               }

               if(!hasPlaceholder&&!imgs[j].hasAttribute("width") && imgs[j].style.width==="" && imgs[j].naturalWidth!==0){

                  css = 'img.placeholder-style-width-' + j + '{width:' + window.getComputedStyle(imgs[j]).width + ' !important;}';
                  style = document.createElement('style');

                  style.setAttribute("class", "placeholder-style placeholder-style-width-" + j);
                  style.type = 'text/css';
                  if (style.styleSheet){
                     style.styleSheet.cssText = css;
                  } else {
                     style.appendChild(document.createTextNode(css));
                  }
                  head.appendChild(style);
                  if(imgs[j].className==="")
                  imgs[j].className += "placeholder-style-width-" + j;
                  else
                  imgs[j].className += " placeholder-style-width-" + j;
               }

               if(!hasPlaceholder&&!imgs[j].hasAttribute("height") && imgs[j].style.height==="" && imgs[j].naturalHeight!==0){

                  css = 'img.placeholder-style-height-' + j + ' {height:' + window.getComputedStyle(imgs[j]).height + ' !important;}';
                  style = document.createElement('style');

                  style.setAttribute("class", "placeholder-style placeholder-style-height-" + j);
                  style.type = 'text/css';
                  if (style.styleSheet){
                     style.styleSheet.cssText = css;
                  } else {
                     style.appendChild(document.createTextNode(css));
                  }
                  head.appendChild(style);
                  if(imgs[j].className==="")
                  imgs[j].className += "placeholder-style-height-" + j;
                  else
                  imgs[j].className += " placeholder-style-height-" + j;
               }

               j++;
            }
            else{
               clearInterval(s);
               eliminatePlaceholderStyles(imgs);
            }
         },25);
      }

   }

   var checkIfAllCSSIsLoaded = setInterval(function(){
      if(loadedAllCSS===1){
         clearInterval(checkIfAllCSSIsLoaded);
         pageBody.style.opacity = "1";
         loadJavascript();
      }
   },50);

   var checkJquery = setInterval(function(){
      if(loadedAllJS){
         // Detect when all "primary" Javascript files has been embeded
         // Understand "primary" as the Javascript files included on the parser queue, not those who will be futurely embeded by these files
         // To detect jQuery you can use if(window.jQuery)
         setTemporaryImageDimensions();
         clearInterval(checkJquery);
      }
   },50);

}());
