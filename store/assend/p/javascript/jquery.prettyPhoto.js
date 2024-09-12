(function($j){$j.prettyPhoto={version:'2.5.6'};
$j.fn.prettyPhoto=function(settings){settings=jQuery.extend({animationSpeed:'normal',opacity:0.80,showTitle:true,allowresize:true,default_width:500,default_height:344,counter_separator_label:'/',theme:'light_rounded',hideflash:false,wmode:'opaque',autoplay:true,modal:false,changepicturecallback:function(){},callback:function(){},markup:'<div class="pp_pic_holder">\
<div class="pp_top">\
<div class="pp_left"></div>\
<div class="pp_middle"></div>\
<div class="pp_right"></div>\
</div>\
<div class="pp_content_container">\
<div class="pp_left">\
<div class="pp_right">\
<div class="pp_content">\
<div class="pp_loaderIcon"></div>\
<div class="pp_fade">\
<a href="#"class="pp_expand"title="Expand the image">Expand</a>\
<div class="pp_hoverContainer">\
<a class="pp_next"href="#">next</a>\
<a class="pp_previous"href="#">previous</a>\
</div>\
<div id="pp_full_res"></div>\
<div class="pp_details clearfix">\
<a class="pp_close"href="#">Close</a>\
<p class="pp_description"></p>\
<div class="pp_nav">\
<a href="#"class="pp_arrow_previous">Previous</a>\
<p class="currentTextHolder">0/0</p>\
<a href="#"class="pp_arrow_next">Next</a>\
</div>\
</div>\
</div>\
</div>\
</div>\
</div>\
</div>\
<div class="pp_bottom">\
<div class="pp_left"></div>\
<div class="pp_middle"></div>\
<div class="pp_right"></div>\
</div>\
</div>\
<div class="pp_overlay"></div>\
<div class="ppt"></div>',image_markup:'<img id="fullResImage" src="" />',flash_markup:'<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="{width}" height="{height}"><param name="wmode" value="{wmode}" /><param name="allowfullscreen" value="true" /><param name="allowscriptaccess" value="always" /><param name="movie" value="{path}" /><embed src="{path}" type="application/x-shockwave-flash" allowfullscreen="true" allowscriptaccess="always" width="{width}" height="{height}" wmode="{wmode}"></embed></object>',quicktime_markup:'<object classid="clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B" codebase="http://www.apple.com/qtactivex/qtplugin.cab" height="{height}" width="{width}"><param name="src" value="{path}"><param name="autoplay" value="{autoplay}"><param name="type" value="video/quicktime"><embed src="{path}" height="{height}" width="{width}" autoplay="{autoplay}" type="video/quicktime" pluginspage="http://www.apple.com/quicktime/download/"></embed></object>',iframe_markup:'<iframe src ="{path}" width="{width}" height="{height}" frameborder="no"></iframe>',inline_markup:'<div class="pp_inline clearfix">{content}</div>'},settings);
if($j.browser.msie&&parseInt($j.browser.version)==6){settings.theme="light_square"}if($j('.pp_overlay').size()==0)_buildOverlay();
var doresize=true,percentBased=false,correctSizes,$jpp_pic_holder,$jppt,$jpp_overlay,pp_contentHeight,pp_contentWidth,pp_containerHeight,pp_containerWidth,windowHeight=$j(window).height(),windowWidth=$j(window).width(),setPosition=0,scrollPos=_getScroll();
$j(window).scroll(function(){scrollPos=_getScroll();
_centerOverlay();
_resizeOverlay()});
$j(window).resize(function(){_centerOverlay();
_resizeOverlay()});
$j(document).keydown(function(e){if($jpp_pic_holder.is(':visible'))switch(e.keyCode){case 37:$j.prettyPhoto.changePage('previous');
break;
case 39:$j.prettyPhoto.changePage('next');
break;
case 27:if(!settings.modal)$j.prettyPhoto.close();
break}});
$j(this).each(function(){$j(this).bind('click',function(){_self=this;
theRel=$j(this).attr('rel');
galleryRegExp=/\[(?:.*)\]/;
theGallery=galleryRegExp.exec(theRel);
var images=new Array(),titles=new Array(),descriptions=new Array();
if(theGallery){$j('a[rel*='+theGallery+']').each(function(i){if($j(this)[0]===$j(_self)[0])setPosition=i;
images.push($j(this).attr('href'));
titles.push($j(this).find('img').attr('alt'));
descriptions.push($j(this).attr('title'))})}else{images=$j(this).attr('href');
titles=($j(this).find('img').attr('alt'))?$j(this).find('img').attr('alt'):'';
descriptions=($j(this).attr('title'))?$j(this).attr('title'):''}$j.prettyPhoto.open(images,titles,descriptions);
return false})});
$j.prettyPhoto.open=function(gallery_images,gallery_titles,gallery_descriptions){if($j.browser.msie&&$j.browser.version==6){$j('select').css('visibility','hidden')};
if(settings.hideflash)$j('object,embed').css('visibility','hidden');
images=$j.makeArray(gallery_images);
titles=$j.makeArray(gallery_titles);
descriptions=$j.makeArray(gallery_descriptions);
image_set=($j(images).size()>0)?true:false;
_checkPosition($j(images).size());
$j('.pp_loaderIcon').show();
$jpp_overlay.show().fadeTo(settings.animationSpeed,settings.opacity);
$jpp_pic_holder.find('.currentTextHolder').text((setPosition+1)+settings.counter_separator_label+$j(images).size());
if(descriptions[setPosition]){$jpp_pic_holder.find('.pp_description').show().html(unescape(descriptions[setPosition]))}else{$jpp_pic_holder.find('.pp_description').hide().text('')};
if(titles[setPosition]&&settings.showTitle){hasTitle=true;
$jppt.html(unescape(titles[setPosition]))}else{hasTitle=false};
movie_width=(parseFloat(grab_param('width',images[setPosition])))?grab_param('width',images[setPosition]):settings.default_width.toString();
movie_height=(parseFloat(grab_param('height',images[setPosition])))?grab_param('height',images[setPosition]):settings.default_height.toString();
if(movie_width.indexOf('%')!=-1||movie_height.indexOf('%')!=-1){movie_height=parseFloat(($j(window).height()*parseFloat(movie_height)/100)-100);
movie_width=parseFloat(($j(window).width()*parseFloat(movie_width)/100)-100);
percentBased=true}$jpp_pic_holder.fadeIn(function(){imgPreloader="";
switch(_getFileType(images[setPosition])){case'image':imgPreloader=new Image();
nextImage=new Image();
if(image_set&&setPosition>$j(images).size())nextImage.src=images[setPosition+1];
prevImage=new Image();
if(image_set&&images[setPosition-1])prevImage.src=images[setPosition-1];
$jpp_pic_holder.find('#pp_full_res')[0].innerHTML=settings.image_markup;
$jpp_pic_holder.find('#fullResImage').attr('src',images[setPosition]);
imgPreloader.onload=function(){correctSizes=_fitToViewport(imgPreloader.width,imgPreloader.height);
_showContent()};
imgPreloader.onerror=function(){alert('Image cannot be loaded. Make sure the path is correct and image exist.');
$j.prettyPhoto.close()};
imgPreloader.src=images[setPosition];
break;
case'youtube':correctSizes=_fitToViewport(movie_width,movie_height);
movie='http://www.youtube.com/v/'+grab_param('v',images[setPosition]);
if(settings.autoplay)movie+="&autoplay=1";
toInject=settings.flash_markup.replace(/{width}/g,correctSizes['width']).replace(/{height}/g,correctSizes['height']).replace(/{wmode}/g,settings.wmode).replace(/{path}/g,movie);
break;
case'vimeo':correctSizes=_fitToViewport(movie_width,movie_height);
movie_id=images[setPosition];
movie='http://vimeo.com/moogaloop.swf?clip_id='+movie_id.replace('http://vimeo.com/','');
if(settings.autoplay)movie+="&autoplay=1";
toInject=settings.flash_markup.replace(/{width}/g,correctSizes['width']).replace(/{height}/g,correctSizes['height']).replace(/{wmode}/g,settings.wmode).replace(/{path}/g,movie);
break;
case'quicktime':correctSizes=_fitToViewport(movie_width,movie_height);
correctSizes['height']+=15;
correctSizes['contentHeight']+=15;
correctSizes['containerHeight']+=15;
toInject=settings.quicktime_markup.replace(/{width}/g,correctSizes['width']).replace(/{height}/g,correctSizes['height']).replace(/{wmode}/g,settings.wmode).replace(/{path}/g,images[setPosition]).replace(/{autoplay}/g,settings.autoplay);
break;
case'flash':correctSizes=_fitToViewport(movie_width,movie_height);
flash_vars=images[setPosition];
flash_vars=flash_vars.substring(images[setPosition].indexOf('flashvars')+10,images[setPosition].length);
filename=images[setPosition];
filename=filename.substring(0,filename.indexOf('?'));
toInject=settings.flash_markup.replace(/{width}/g,correctSizes['width']).replace(/{height}/g,correctSizes['height']).replace(/{wmode}/g,settings.wmode).replace(/{path}/g,filename+'?'+flash_vars);
break;
case'iframe':correctSizes=_fitToViewport(movie_width,movie_height);
frame_url=images[setPosition];
frame_url=frame_url.substr(0,frame_url.indexOf('iframe')-1);
toInject=settings.iframe_markup.replace(/{width}/g,correctSizes['width']).replace(/{height}/g,correctSizes['height']).replace(/{path}/g,frame_url);
break;
case'inline':myClone=$j(images[setPosition]).clone().css({'width':settings.default_width}).wrapInner('<div id="pp_full_res"><div class="pp_inline clearfix"></div></div>').appendTo($j('body'));
correctSizes=_fitToViewport($j(myClone).width(),$j(myClone).height());
$j(myClone).remove();
toInject=settings.inline_markup.replace(/{content}/g,$j(images[setPosition]).html());
break};
if(!imgPreloader){$jpp_pic_holder.find('#pp_full_res')[0].innerHTML=toInject;
_showContent()}})};
$j.prettyPhoto.changePage=function(direction){if(direction=='previous'){setPosition--;
if(setPosition<0){setPosition=0;
return}}else{if($j('.pp_arrow_next').is('.disabled'))return;
setPosition++};
if(!doresize)doresize=true;
_hideContent(function(){$j.prettyPhoto.open(images,titles,descriptions)});
$j('a.pp_expand,a.pp_contract').fadeOut(settings.animationSpeed)};
$j.prettyPhoto.close=function(){$jpp_pic_holder.find('object,embed').css('visibility','hidden');
$j('div.pp_pic_holder,div.ppt,.pp_fade').fadeOut(settings.animationSpeed);
$jpp_overlay.fadeOut(settings.animationSpeed,function(){$j('#pp_full_res').html('');
$jpp_pic_holder.attr('style','').find('div:not(.pp_hoverContainer)').attr('style','');
_centerOverlay();
if($j.browser.msie&&$j.browser.version==6){$j('select').css('visibility','visible')};
if(settings.hideflash)$j('object,embed').css('visibility','visible');
setPosition=0;
settings.callback()});
doresize=true};
_showContent=function(){$j('.pp_loaderIcon').hide();
projectedTop=scrollPos['scrollTop']+((windowHeight/2)-(correctSizes['containerHeight']/2));
if(projectedTop<0)projectedTop=0+$jppt.height();
$jpp_pic_holder.find('.pp_content').animate({'height':correctSizes['contentHeight']},settings.animationSpeed);
$jpp_pic_holder.animate({'top':projectedTop,'left':(windowWidth/2)-(correctSizes['containerWidth']/2),'width':correctSizes['containerWidth']},settings.animationSpeed,function(){$jpp_pic_holder.find('.pp_hoverContainer,#fullResImage').height(correctSizes['height']).width(correctSizes['width']);
$jpp_pic_holder.find('.pp_fade').fadeIn(settings.animationSpeed);
if(image_set&&_getFileType(images[setPosition])=="image"){$jpp_pic_holder.find('.pp_hoverContainer').show()}else{$jpp_pic_holder.find('.pp_hoverContainer').hide()}if(settings.showTitle&&hasTitle){$jppt.css({'top':$jpp_pic_holder.offset().top-25,'left':$jpp_pic_holder.offset().left+20,'display':'none'});
$jppt.fadeIn(settings.animationSpeed)};
if(correctSizes['resized'])$j('a.pp_expand,a.pp_contract').fadeIn(settings.animationSpeed);
settings.changepicturecallback()})};
function _hideContent(callback){$jpp_pic_holder.find('#pp_full_res object,#pp_full_res embed').css('visibility','hidden');
$jpp_pic_holder.find('.pp_fade').fadeOut(settings.animationSpeed,function(){$j('.pp_loaderIcon').show();
if(callback)callback()});
$jppt.fadeOut(settings.animationSpeed)}function _checkPosition(setCount){if(setPosition==setCount-1){$jpp_pic_holder.find('a.pp_next').css('visibility','hidden');
$jpp_pic_holder.find('a.pp_arrow_next').addClass('disabled').unbind('click')}else{$jpp_pic_holder.find('a.pp_next').css('visibility','visible');
$jpp_pic_holder.find('a.pp_arrow_next.disabled').removeClass('disabled').bind('click',function(){$j.prettyPhoto.changePage('next');
return false})};
if(setPosition==0){$jpp_pic_holder.find('a.pp_previous').css('visibility','hidden');
$jpp_pic_holder.find('a.pp_arrow_previous').addClass('disabled').unbind('click')}else{$jpp_pic_holder.find('a.pp_previous').css('visibility','visible');
$jpp_pic_holder.find('a.pp_arrow_previous.disabled').removeClass('disabled').bind('click',function(){$j.prettyPhoto.changePage('previous');
return false})};
if(setCount>1){$j('.pp_nav').show()}else{$j('.pp_nav').hide()}};
function _fitToViewport(width,height){hasBeenResized=false;
_getDimensions(width,height);
imageWidth=width;
imageHeight=height;
if(((pp_containerWidth>windowWidth)||(pp_containerHeight>windowHeight))&&doresize&&settings.allowresize&&!percentBased){hasBeenResized=true;
notFitting=true;
while(notFitting){if((pp_containerWidth>windowWidth)){imageWidth=(windowWidth-200);
imageHeight=(height/width)*imageWidth}else if((pp_containerHeight>windowHeight)){imageHeight=(windowHeight-200);
imageWidth=(width/height)*imageHeight}else{notFitting=false};
pp_containerHeight=imageHeight;
pp_containerWidth=imageWidth};
_getDimensions(imageWidth,imageHeight)};
return{width:Math.floor(imageWidth),height:Math.floor(imageHeight),containerHeight:Math.floor(pp_containerHeight),containerWidth:Math.floor(pp_containerWidth)+40,contentHeight:Math.floor(pp_contentHeight),contentWidth:Math.floor(pp_contentWidth),resized:hasBeenResized}};
function _getDimensions(width,height){width=parseFloat(width);
height=parseFloat(height);
$jpp_details=$jpp_pic_holder.find('.pp_details');
$jpp_details.width(width);
detailsHeight=parseFloat($jpp_details.css('marginTop'))+parseFloat($jpp_details.css('marginBottom'));
$jpp_details=$jpp_details.clone().appendTo($j('body')).css({'position':'absolute','top':-10000});
detailsHeight+=$jpp_details.height();
detailsHeight=(detailsHeight<=34)?36:detailsHeight;
if($j.browser.msie&&$j.browser.version==7)detailsHeight+=8;
$jpp_details.remove();
pp_contentHeight=height+detailsHeight;
pp_contentWidth=width;
pp_containerHeight=pp_contentHeight+$jppt.height()+$jpp_pic_holder.find('.pp_top').height()+$jpp_pic_holder.find('.pp_bottom').height();
pp_containerWidth=width}function _getFileType(itemSrc){if(itemSrc.match(/youtube\.com\/watch/i)){return'youtube'}else if(itemSrc.match(/vimeo\.com/i)){return'vimeo'}else if(itemSrc.indexOf('.mov')!=-1){return'quicktime'}else if(itemSrc.indexOf('.swf')!=-1){return'flash'}else if(itemSrc.indexOf('iframe')!=-1){return'iframe'}else if(itemSrc.substr(0,1)=='#'){return'inline'}else{return'image'}};
function _centerOverlay(){if(doresize){titleHeight=$jppt.height();
contentHeight=$jpp_pic_holder.height();
contentwidth=$jpp_pic_holder.width();
projectedTop=(windowHeight/2)+scrollPos['scrollTop']-((contentHeight+titleHeight)/2);
$jpp_pic_holder.css({'top':projectedTop,'left':(windowWidth/2)+scrollPos['scrollLeft']-(contentwidth/2)});
$jppt.css({'top':projectedTop-titleHeight,'left':(windowWidth/2)+scrollPos['scrollLeft']-(contentwidth/2)+20})}};
function _getScroll(){if(self.pageYOffset){return{scrollTop:self.pageYOffset,scrollLeft:self.pageXOffset}}else if(document.documentElement&&document.documentElement.scrollTop){return{scrollTop:document.documentElement.scrollTop,scrollLeft:document.documentElement.scrollLeft}}else if(document.body){return{scrollTop:document.body.scrollTop,scrollLeft:document.body.scrollLeft}}};
function _resizeOverlay(){windowHeight=$j(window).height();
windowWidth=$j(window).width();
$jpp_overlay.css({'height':$j(document).height()})};
function _buildOverlay(){$j('body').append(settings.markup);
$jpp_pic_holder=$j('.pp_pic_holder');
$jppt=$j('.ppt');
$jpp_overlay=$j('div.pp_overlay');
$jpp_pic_holder.attr('class','pp_pic_holder '+settings.theme);
$jpp_overlay.css({'opacity':0,'height':$j(document).height()}).bind('click',function(){if(!settings.modal)$j.prettyPhoto.close()});
$j('a.pp_close').bind('click',function(){$j.prettyPhoto.close();
return false});
$j('a.pp_expand').bind('click',function(){$jthis=$j(this);
if($jthis.hasClass('pp_expand')){$jthis.removeClass('pp_expand').addClass('pp_contract');
doresize=false}else{$jthis.removeClass('pp_contract').addClass('pp_expand');
doresize=true};
_hideContent(function(){$j.prettyPhoto.open(images,titles,descriptions)});
$jpp_pic_holder.find('.pp_fade').fadeOut(settings.animationSpeed);
return false});
$jpp_pic_holder.find('.pp_previous, .pp_arrow_previous').bind('click',function(){$j.prettyPhoto.changePage('previous');
return false});
$jpp_pic_holder.find('.pp_next, .pp_arrow_next').bind('click',function(){$j.prettyPhoto.changePage('next');
return false})};
_centerOverlay()};
function grab_param(name,url){name=name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
var regexS="[\\?&]"+name+"=([^&#]*)";
var regex=new RegExp(regexS);
var results=regex.exec(url);
if(results==null)return"";
else return results[1]}})(jQuery);


