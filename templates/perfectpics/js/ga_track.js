var _bb={},_GA={getDom:function(a,c){try{if("undefined"===typeof a||!a.match(/^https?\:\/\//i)&&"undefined"===typeof c)c=a,a=document.location.href;c="undefined"===typeof c?"dom":c;var b={};b.np=a.replace(/^https?\:\/\//i,"");b.host=b.np.split("/")[0];b.dom=b.host.split(".").reverse()[1]+"."+b.host.split(".").reverse()[0];b.sub=b.host.replace("."+b.dom,"");b.paths=b.np.replace(b.host,"");b.search=b.paths.split("?")[1];b.hash=b.paths.split("#")[1];b.path=b.paths.replace("?"+b.search,"").replace("#"+
b.hash,"");return b[c]}catch(d){return null}},arrayPos:function(a,c){for(i=0;i<a.length;i++)if(null!==a[i].match(new RegExp(c,"i")))return i;return-1},mkCookieDom:function(a,c){for(var b="",d=_GA.arrayPos(a,c);-1!==d;d--)b+="."+a[d];return b}};_GA.thisDom=document.domain.toLowerCase();_GA.domRev=_GA.thisDom.split(".").reverse();_GA.isSupport=_GA.getDom("sub").match(/support/i)?!0:!1;_GA.cookieDom=_GA.isSupport?"support.blurb.com":_GA.mkCookieDom(_GA.domRev,"blurb");
_GA.domRegEx=new RegExp(_GA.cookieDom,"i");_GA.track=function(){var a=[],c,b=_GA.isSupport?1:2;for(c=0;c<b;c++){a[c]={};for(var d=0;d<arguments.length;d++){a[c][d]=[];for(var e=0;e<arguments[d].length;e++)a[c][d][e]=[],trackerName=0==e&&0==d&&0!=c?"r.":"",a[c][d][e]=""!=trackerName?trackerName+arguments[d][e]:arguments[d][e];_gaq.push(a[c][d])}}};
var _gaq={push:function(){for(var a=arguments,c=0;c<a.length;c++){var b=arguments[c],d=arguments[c][0],e="";/\./.test(d)&&(e=d.split(".")[0]+".",d=d.split(".")[1]);var f={_trackEvent:function(){var a=[e+"send","event",b[1],b[2]];b[3]&&a.push(b[3]);b[4]&&a.push(b[4]);b[5]&&a.push({nonInteraction:b[5]});return a},_trackPageview:function(){var a=[e+"send","pageview"];b[1]&&a.push(b[1]);return a},_trackSocial:function(){var a=[e+"send","social",b[1],b[2]];b[3]&&a.push(b[3]);return a},_setCustomVar:function(){var a=
[e+"set","dimension"+b[1],b[3]];3==b[1]&&0<b[3].length&&_GA.send("set","&uid",b[3]);return a},_addTrans:function(){return[e+"ecommerce:addTransaction",{id:b[1],affiliation:b[2],revenue:b[3],tax:b[4],shipping:b[5]}]},_addItem:function(){var a=[e+"ecommerce:addItem",{id:b[1],sku:b[2],name:b[3],category:b[4],price:b[5],quantity:b[6]}];b[3]&&a.push(b[3]);return a},_trackTrans:function(){return[e+"ecommerce:send"]}};"function"==typeof f[d]?ga.apply(this,f[d](b)):ga("send","event","_GAQ Alert","function not recognized",
d,{nonInteraction:1})}}};_GA.send=function(){for(var a=_bb.trackers.length,c,b=0;b<a;b++)c=arguments,c[0]=(0==b?"":_bb.trackers[b]+".")+c[0],ga.apply(this,c)};
_bb.setUADom=function(a){null!==_GA.thisDom.match(/.*\.blurb\.com/i)&&null===_GA.thisDom.match(/^(www\.blurb|secure\.blurb)\.com/i)&&(null!==_GA.thisDom.match(/.*au(-.*|\.)blurb\.com/i)?a="au.blurb.com":null!==_GA.thisDom.match(/.*la(-.*|\.)blurb\.com/i)?a="la.blurb.com":null!==_GA.thisDom.match(/.*it(-.*|\.)blurb\.com/i)?a="it.blurb.com":null!==_GA.thisDom.match(/.*br(-.*|\.)blurb\.com/i)?a="br.blurb.com":null!==_GA.thisDom.match(/.*nl(-.*|\.)blurb\.com/i)&&(a="nl.blurb.com"));return a};
_bb.gaUADomain=_bb.setUADom(_GA.cookieDom);
_bb.getGAWPI=function(){var a={blurbcom:"UA-283660-1",blurbde:"UA-283660-20",blurbfr:"UA-283660-21",blurbes:"UA-283660-22",brblurbcom:"UA-283660-23",itblurbcom:"UA-283660-24",nlblurbcom:"UA-283660-25",blurbcouk:"UA-283660-26",aublurbcom:"UA-283660-27",blurbca:"UA-283660-28",lablurbcom:"UA-283660-35"},c={blurbcom:"UA-30214263-1",blurbde:"UA-30214263-2",blurbfr:"UA-30214263-3",blurbes:"UA-30214263-4",brblurbcom:"UA-30214263-5",itblurbcom:"UA-30214263-6",nlblurbcom:"UA-30214263-7",blurbcouk:"UA-30214263-8",
aublurbcom:"UA-30214263-9",blurbca:"UA-30214263-10",lablurbcom:"UA-30214263-11"},b=_bb.gaUADomain.replace(/\./g,"");return null===_GA.thisDom.match(/.*\.eng|ct|webteam|platform|esa|wao|epub|ecom|test|wt|ws|et\.blurb|dev\.blurb/i)?a[b]?a[b]:"NONE":c[b]?c[b]:"NONE"};_bb.getGARollUpWPI=function(){return null===_GA.thisDom.match(/.*\.eng|ct|webteam|platform|esa|wao|epub|ecom|test|wt|ws|et\.blurb|dev\.blurb/i)?"UA-30210951-1":"UA-30210951-2"};
var blurb_uacct=void 0===window.blurb_uacct?_bb.getGAWPI():blurb_uacct,blurbRollUp_uacct=void 0===window.blurbRollUp_uacct?_bb.getGARollUpWPI():blurbRollUp_uacct;_bb.getGALang=function(){var a={blurbcom:"en",blurbde:"de",blurbfr:"fr",blurbes:"es",brblurbcom:"pt",itblurbcom:"it",nlblurbcom:"nl",blurbcouk:"en",aublurbcom:"en",blurbca:"en",lablurbcom:"es"};var c=_bb.gaUADomain.replace(/\./g,""),a="blurbca"!=c||"fr.blurb.ca"!=_GA.thisDom&&"store-fr.blurb.ca"!=_GA.thisDom?a[c]?a[c]:"NONE":"fr";return a};
var selected_language=void 0===window.selected_language?_bb.getGALang():selected_language,ga_on=void 0===window.ga_on?!0:ga_on;_GA._uGC=function(a,c,b){if(!a||""==a||!c||""==c||!b||""==b)return"";var d,e="-";d=a.indexOf(c);c=c.indexOf("=")+1;-1<d&&(b=a.indexOf(b,d),0>b&&(b=a.length),e=a.substring(d+c,b));return"-"==e?"":e};_GA.checkIfNoUDV=function(){try{var a=_GA._uGC(document.cookie,"__utmv=",";");return""==a?!0:null!==a.split("|")[0].match("!")?!1:!0}catch(c){return!1}};
_GA.getGAReferrerData=function(){var a=_GA._uGC(document.cookie,"__utmz=",";"),c=_GA._uGC(a,"utmcsr=","|"),b=_GA._uGC(a,"utmcmd=","|"),d=_GA._uGC(a,"utmctr=","|"),e=_GA._uGC(a,"utmcct=","|"),f=_GA._uGC(a,"utmccn=","|");""!==_GA._uGC(a,"utmgclid=","|")&&(c="google",b="cpc");a=(c+"!"+b+"!"+f+"!"+d+"!"+e).replace(/[^a-zA-Z0-9-~!*%:_.\s]/g,"");return a=a.replace(/%20/g," ")};_GA.matchInArray=function(a,c){for(i=0;i<a.length;i++)if(null!==c.match(new RegExp(a[i],"i")))return!0;return!1};
_bb.trackers=["main","r"];
ga_on&&(function(a,c,b,d,e,f,g){a.GoogleAnalyticsObject=e;a[e]=a[e]||function(){(a[e].q=a[e].q||[]).push(arguments)};a[e].l=1*new Date;f=c.createElement(b);g=c.getElementsByTagName(b)[0];f.async=1;f.src=d;g.parentNode.insertBefore(f,g)}(window,document,"script","//www.google-analytics.com/analytics.js","ga"),_GA.isSupport?ga("create",blurbRollUp_uacct,"auto",{legacyCookieDomain:_GA.cookieDom,allowLinker:!0,allowAnchor:!0,siteSpeedSampleRate:3}):ga("create",blurb_uacct,"auto",{legacyCookieDomain:_GA.cookieDom,
allowLinker:!0,allowAnchor:!0,siteSpeedSampleRate:3}),_GA.isSupport||ga("create",blurbRollUp_uacct,"auto",{legacyCookieDomain:_GA.cookieDom,name:"r",allowLinker:!0,allowAnchor:!0,siteSpeedSampleRate:3}));window.optimizely=window.optimizely||[];window.optimizely.push("activateUniversalAnalytics");_GA.send("require","displayfeatures");_GA.send("require","linkid","linkid.js");_GA.send("require","ecommerce","ecommerce.js");_GA.send("set","dimension4",selected_language);
var a_appPageSource=void 0===window.a_appPageSource?"unknown":a_appPageSource;document.location.pathname.match(/^.*(\/my\/checkout\/order_complete).*/i)||_GA.send("set","dimension2",a_appPageSource);var linker;ga(function(a){linker=new window.gaplugins.Linker(a)});void 0!==window.a_pageSection&&window.a_pageSection.length&&(_GA.send("set","contentGroup1",a_pageSection),void 0!==window.a_pageName&&window.a_pageName.length&&_GA.send("set","contentGroup2",a_pageSection+"/"+a_pageName.replace(":","/")));
void 0!==window.a_postAuthor&&window.a_postAuthor.length&&_GA.send("set","contentGroup3",a_postAuthor);_GA.send("send","pageview");_bb.postComment=function(a,c){_GA.send("send","event","Post Comments",a,c,0)};_bb.intraLinkCheck=function(a){try{return"undefined"!==typeof a&&a.match(/^https?\:\/\//i)&&_GA.getDom(a)==_GA.getDom()&&_GA.getDom(a,"sub")!==_GA.getDom("sub")?!0:!1}catch(c){return!1}};
_GA.kwRanking=function(){if(document.referrer.match(/google\.com/gi)&&document.referrer.match(/cd/gi)){var a=document.referrer,c=a.match(/cd=(.*?)&/),c=parseInt(c[1]),a=a.match(/q=(.*?)&/),a=0<a[1].length?decodeURI(a[1]):"(not provided)";_GA.send("send","event","RankTracker",a,document.location.pathname,c,{nonInteraction:1})}};
if("undefined"!==typeof jQuery)try{var filetypes=/\.(zip|exe|pdf|doc.*|xls.*|ppt.*|mp3|dmg|blurb)$/i,baseHref="";void 0!==jQuery("base").attr("href")&&(baseHref=jQuery("base").attr("href"));jQuery(document).on("click","a",function(){var a=jQuery(this),c="undefined"!==typeof a.attr("href")?a.attr("href"):"",b=c.split("?")[0].split("#")[0],d=_bb.intraLinkCheck(b),e=a.parent().attr("class");if(c&&!c.match(/^javascript:/i)&&"#"!==c&&0<c.length&&"share-buttons"!==e)if(null!==c.match(filetypes)){if(b=/[.]/.exec(c)?
/[^.]+$/.exec(c):[""],_GA.send("send","event","Download","Click-"+b[0].toLowerCase(),c.toLowerCase()),!a.attr("target")||"_blank"!==a.attr("target").toLowerCase())return setTimeout(function(){location.href=baseHref+c},300),!1}else if(c.match(/^mailto\:/i))a=c.replace(/^mailto\:/i,"").toLowerCase(),_GA.send("send","event","Email","Click",a);else if(c.match(/^https?\:/i)&&(_GA.getDom(c,"host").match(_GA.getDom("host"))||!_GA.getDom(c,"sub").match("support")&&!_GA.getDom("sub").match("support")||(c=
linker.decorate(a.attr("href")),a.attr("href",c)),"undefined"!==typeof d&&d?_GA.send("send","event","Intra-Domain",document.location.toString().split("?")[0].toLowerCase(),b.toLowerCase()):c.match(_GA.domRegEx)||_GA.send("send","event","External","Click",c.replace(/^https?\:\/\//i,"").split("?")[0].toLowerCase()),!a.attr("target")||"_blank"!==a.attr("target").toLowerCase()))return setTimeout(function(){location.href=c},300),!1})}catch(e$$15){}var _gaST=_gaST||{};
_gaST.twitterHandler_=function(a,c){var b;if(a&&"tweet"==a.type||"click"==a.type)"IFRAME"==a.target.nodeName&&(b=_gaST.getFromUri_(a.target.src,"url")),_GA.send("send","social",{socialNetwork:"twitter",socialAction:a.type+("click"==a.type?"-"+a.region:""),socialTarget:b})};_gaST.getFromUri_=function(a,c){if(a){var b=(new RegExp("[\\?&#]"+c+"=([^&#]*)")).exec(a);if(null!==b)return unescape(b[1])}};
_gaST.twitter=function(a){try{intent_handler=function(b){_gaST.twitterHandler_(b,a)},twttr.events.bind("click",intent_handler),twttr.events.bind("tweet",intent_handler)}catch(c){}};
_gaST.facebook=function(a){try{FB&&FB.Event&&FB.Event.subscribe&&(FB.Event.subscribe("edge.create",function(a){_GA.send("send","social",{socialNetwork:"facebook",socialAction:"like",socialTarget:a})}),FB.Event.subscribe("edge.remove",function(a){_GA.send("send","social",{socialNetwork:"facebook",socialAction:"unlike",socialTarget:a})}),FB.Event.subscribe("message.send",function(a){_GA.send("send","social",{socialNetwork:"facebook",socialAction:"send",socialTarget:a})}))}catch(c){}};
var getCookieValue=function(a){a=(new RegExp(a+"=([^;]+)")).exec(document.cookie);return null!=a?unescape(a[1]):""},visitationTableInfo=function(){try{var a=window.location.href.split("?")[1],a=a?a:"",c={context:"Website Visitation Tracking",session_id:getCookieValue("_session_id"),events:[{data:{user_info:JSON.parse(getCookieValue("dm")||"{}"),referrer:encodeURI(document.referrer),environment:window.location.hostname,user_agent:navigator.appVersion,path_name:encodeURI(window.location.pathname),search_parameters:encodeURI(a),
operating_system:navigator.platform}}]};window.Prototype&&delete Array.prototype.toJSON;jQuery.post(window.location.protocol+"//clientevents.blurb.com/notice","data="+JSON.stringify(c)).fail(function(a,b,c){console.log("ERROR occurred on post to visitation tracking: textStatus="+b+", errorThrown="+c)})}catch(b){console.log("ERROR: unable to run VisitationTableInfo function"+b)}};
"undefined"!==typeof jQuery&&jQuery(document).ready(function(){try{_GA.kwRanking(),setTimeout(function(){_gaST.twitter();_gaST.facebook()},2E3),visitationTableInfo()}catch(a){}});