
OSI.namespace('OSI.app');
OSI.app.categories = function(config){
	this.config = {
		rootTarget : "categories",		
		subTarget : "categories-subs",	
		afterLoadClass : "after-load-subs",	
		itemCurrentClass : "current",	
		afterShowSubClass : "after-show-sub",	
		afterShowNextItemClass : "active-border-color",	
		showDelayTime : 50,	
		hiddenDelayTime : 40,	
		excursion : [137,0],	
		searchBar : window.searchBar,	
		
		forceRun	: false,	
		getCategoriesAPI : "get_categories.htm",	
		iframeMask : OSI.bom.isIE ? true : false,	
		
		getThrough  : true,	
		throughMaskExcursion : [137,1],	
		maskWidth : "1px",
		maskBackground : "#fff5e2",
		
		maskHeightDiff	:	-2,		
		
		
		isCollapse : YUD.getElementsByClassName("categories-collapse").length > 0 ? true : false,
		handleItem : get("categories-title"),	
		handleItemLink : get("categories-title").getElementsByTagName("a")[0],

		viewMoreClass : "view-more",
		
		viewMoreText : "View More",
		viewMoreTextClass : "view-more",
		viewLessText : "View Less",
		viewLessTextClass : "view-less",
		
		isShadow : true,	
		shadowClass : "shadow-line",
		
		shadow4RootExcursion : [0,0], 
		shadow4SubExcursion : [0,0] 
	};
	
	this.temp = {
		rootItems : [],		
		subItems : {},		
		collapseTimeoutId : "", 
		viewMoreBox : "",	
		viewMoreHandle : "",
		whiteMask : "",		
		shadow4Root : "",	
		shadow4Sub : "",	
		iframeMask : null	
	};
	
	return this;
}




OSI.app.categories.prototype = {
	
	
	afterShow : function(ev,s,args){	
		var _self = this;var config = this.config;var temp = this.temp;
		
		
		
		
		var xz = (YUD.getY(args.subItem) + args.subItem.offsetHeight) - (document.documentElement.clientHeight + YUD.getDocumentScrollTop());
		if(xz>0){
			YUD.setY( args.subItem,( YUD.getY(args.subItem) - xz ) )
		}else{
			YUD.setY( args.subItem,( YUD.getY(args.rootItem) ) )
		}
				
		
		if(config.iframeMask){
			YUD.setStyle( temp.iframeMask , "display" ,"block" );
			YUD.setStyle( temp.iframeMask , "width" , args.subItem.offsetWidth + "px" );
			YUD.setStyle( temp.iframeMask , "height" , args.subItem.offsetHeight + "px" );
			YUD.setX( temp.iframeMask,YUD.getX(args.subItem) );
			YUD.setY( temp.iframeMask,YUD.getY(args.subItem) );
		}

		//alert(args.rootItem.offsetHeight)
		if(config.afterShowSubClass){
			YUD.addClass(config.rootTarget,config.afterShowSubClass);
		}

		
		if(config.searchBar){
			searchBar.intelligentObj.UIController.hideList();
		}
		
		YUD.addClass(args.nextItem,config.afterShowNextItemClass);
		YUD.addClass(args.rootItem.parentNode,config.itemCurrentClass);
		
		
		if(config.getThrough){
			
			if(!_self.temp.whiteMask){
				var whiteMask = _self.temp.whiteMask = document.createElement("div");
				whiteMask.style.width = config.maskWidth;
				whiteMask.style.background = config.maskBackground;
				config.subTarget.appendChild(whiteMask);
			}else{
				var whiteMask = _self.temp.whiteMask;
			}
			whiteMask.style.height = (args.rootItem.offsetHeight+config.maskHeightDiff)+"px";
			YUD.setXY(whiteMask,[YUD.getX(args.rootItem)+config.throughMaskExcursion[0],YUD.getY(args.rootItem)+config.throughMaskExcursion[1]]);
		}
		
		
		if(config.isShadow){
			if(!_self.temp.shadow4Root){
				var shadow4Root = _self.temp.shadow4Root = document.createElement("div");
				YUD.addClass(shadow4Root,config.shadowClass); 
				config.rootTarget.appendChild(shadow4Root);
			}else{
				var shadow4Root = _self.temp.shadow4Root;
			}
			
			if(args.nextItem){
				shadow4Root.style.display = "";
				shadow4Root.style.width = args.rootItem.offsetWidth+"px";
				YUD.setXY(shadow4Root,[YUD.getX(args.rootItem)+config.shadow4RootExcursion[0],YUD.getY(args.rootItem)+args.rootItem.offsetHeight+config.shadow4RootExcursion[1]]);
			}
			
			if(!_self.temp.shadow4Sub){
				var shadow4Sub = _self.temp.shadow4Sub = document.createElement("div");
				YUD.addClass(shadow4Sub,config.shadowClass); 
				config.rootTarget.appendChild(shadow4Sub);
			}else{
				var shadow4Sub = _self.temp.shadow4Sub;
			}
			shadow4Sub.style.display = "";
			shadow4Sub.style.width = args.subItem.offsetWidth+"px";
			YUD.setXY(shadow4Sub,[YUD.getX(args.subItem)+config.shadow4SubExcursion[0],YUD.getY(args.subItem)+args.subItem.offsetHeight+config.shadow4SubExcursion[1]]);
		}
		
	},
	
	
	afterHidden : function(ev,s,args){	
		var _self = this;var config = this.config;var temp = this.temp;
		
		
		if(config.iframeMask){
			YUD.setStyle( temp.iframeMask , "display" ,"none" );
		}
		
		
		YUD.setXY(temp.whiteMask,[-10000,YUD.getY(args.rootItem)+config.throughMaskExcursion[1]]);
		
		if(config.afterShowSubClass){
			YUD.removeClass(config.rootTarget,config.afterShowSubClass);
		}
		
		YUD.removeClass(args.nextItem,config.afterShowNextItemClass);
		YUD.removeClass(args.rootItem.parentNode,config.itemCurrentClass);
		
		if(config.isShadow && _self.temp.shadow4Root && args.subItem.style.display == ""){
			_self.temp.shadow4Root.style.display = "none";
			_self.temp.shadow4Sub.style.display = "none";
		}
		
	},
	
	
	rootExpand : function(){
		var _self = this;var config = this.config;
		config.rootTarget.style.visibility = "visible";YUD.addClass("cats_link","expanded");
			YUD.addClass("search-bar","handle-expanded");
		if(config.isCollapse){
			YUD.addClass(config.handleItemLink,"expanded");
			YUD.addClass(config.handleItemLink.parentNode.parentNode,"handle-expanded");
		}
	},

	rootCollapse : function(){
		var _self = this;var config = this.config;
		YUD.addClass("page","categories-collapse");
		config.rootTarget.style.visibility = "hidden";YUD.removeClass("cats_link","expanded");
			YUD.removeClass("search-bar","handle-expanded");
		if(config.isCollapse){
			YUD.removeClass(config.handleItemLink,"expanded");
			YUD.removeClass(config.handleItemLink.parentNode.parentNode,"handle-expanded");
			YUD.removeClass("page","categories-collapse");
		}
	},

	
	toggle : function(ev,args){
		var _self = this;var config = this.config;
		var handle = args.handle;
		var collapseClass = args.collapseClass;
		var target = args.target;
		var toggleHeight = args.toggleHeight;

		
		if(handle.locked == true)return;
		
		
		if(YUD.hasClass(handle,collapseClass)){
			if(args.collapseForce == true)return;
			
			if(args.beforeExpandCall){
				args.beforeExpandCall.call(_self, args);
			}
			
			target.style.height = "auto";
			var toHeight = target.offsetHeight;
			target.style.height = toggleHeight + "px";
			var attributes = {
				height: { to: toHeight }
			};
			var anim = new YAHOO.util.Anim(target, attributes, args.expandTime, YAHOO.util.Easing.easeOut); 
			anim.onComplete.subscribe(function(s, o) {
				handle.locked = false;
				target.style.height = "auto";
				YUD.removeClass(args.handle,args.collapseClass);
				if(args.afterExpandCall){
					args.afterExpandCall.call(_self, args);
				}
			});
			
			handle.locked = true;
			anim.animate();
			
		}else{
			if(args.expandForce == true)return;
			
			if(args.beforeCollapseCall){
				args.beforeCollapseCall.call(_self, args);
			}
			
			var attributes = {   
				height: { to: toggleHeight }
			};
			var anim = new YAHOO.util.Anim(target, attributes, args.collapseTime, YAHOO.util.Easing.easeOut); 
			anim.onComplete.subscribe(function(s, o) {
				handle.locked = false;
				YUD.addClass(args.handle,args.collapseClass);
				if(args.afterCollapseCall){
					args.afterCollapseCall.call(_self, args);
				}
			});
			
			handle.locked = true;
			anim.animate();
		}
	},
	
	
	viewMoreAfterCollapse : function(args){
		var _self = this;var config = this.config;
		args.handleText.innerHTML = config.viewMoreText;
		YUD.replaceClass(args.handleText,config.viewLessTextClass,config.viewMoreTextClass);
		if(OSI.bom.isIE){ CollectGarbage(); }
	},
	
	
	viewMoreAfterExpand : function(args){
		var _self = this;var config = this.config;
		args.handleText.innerHTML = config.viewLessText;
		YUD.replaceClass(args.handleText,config.viewMoreTextClass,config.viewLessTextClass);
		if(OSI.bom.isIE){ CollectGarbage(); }
	},
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////
	run : function(){
		
			var _self = this;var config = this.config;var temp = this.temp;
			
			config.rootTarget = get(config.rootTarget);
			config.subTarget = get(config.subTarget);
			
			
			var subItems = config.subTarget.getElementsByTagName("div");
			
			for(var i=0; i<subItems.length; i++){
				var subItem = subItems[i];
				
				_self.temp.subItems[trim(subItem.getAttribute("rel")).toUpperCase()] = subItem;
			}

			
			var rootItems = config.rootTarget.getElementsByTagName("a");
			for(var i=0; i<rootItems.length; i++){
				var rootItem = rootItems[i];
				
				var key = trim((rootItem.innerText?rootItem.innerText:rootItem.textContent)).toUpperCase();
				
				if(_self.temp.subItems[key]){
					var overShow = rootItem.overShow = new OSI.widget.overShow();
					var subItem = rootItem.subItem = this.temp.subItems[key];subItem.rootItem = rootItem;
					var nextItem = rootItems[i+1]?rootItems[i+1]:null;
					
					
					overShow.afterShow.subscribe(_self.afterShow,{rootItem:rootItem, subItem:subItem, nextItem:nextItem },_self);
					overShow.afterHidden.subscribe(_self.afterHidden,{rootItem:rootItem, subItem:subItem, nextItem:nextItem },_self);
					
					var excursion = config.excursion;
										
					overShow.init({
						targetId:rootItem,
						contentId:subItem,
						showDelayTime:config.showDelayTime,
						hiddenDelayTime:config.hiddenDelayTime,
						excursion:excursion
					});
				}

				_self.temp.rootItems.push(rootItem);
			}


			
			if(config.iframeMask){
				temp.iframeMask = document.createElement("iframe");
				YUD.setStyle(temp.iframeMask,"position","absolute");
				YUD.setStyle(temp.iframeMask,"display","none");
				YUD.setStyle(temp.iframeMask,"opacity","0");
				YUD.setStyle(temp.iframeMask,"z-index",YUD.getStyle(config.subTarget,"z-index") - 1);
				config.rootTarget.appendChild(temp.iframeMask);
			}
			
			
			var viewMoreHandle = this.temp.viewMoreHandle = YUD.getElementsByClassName(config.viewMoreClass,"a",config.rootTarget)[0];
			if(viewMoreHandle){
				var viewMoreBox = this.temp.viewMoreBox = YUD.getNextSibling(viewMoreHandle);
				var toggleConfig = {
							handle:viewMoreHandle,
							handleText:viewMoreHandle,
							collapseClass:config.viewMoreClass,
							target:viewMoreBox,
							toggleHeight:0,
							afterCollapseCall : _self.viewMoreAfterCollapse,
							afterExpandCall : _self.viewMoreAfterExpand,
							expandTime : 0.25,
							collapseTime : 0.25
				};
				
				YUE.on(viewMoreHandle,"click",_self.toggle,toggleConfig,_self);
			}
			
			
			if(config.isCollapse){

				YUE.on([config.handleItem,config.rootTarget,config.subTarget], "mouseover", function(){
					clearTimeout(_self.temp.collapseTimeoutId);
					_self.rootExpand();
				})
				
				YUE.on([config.handleItem,config.rootTarget,config.subTarget], "mouseout", function(){
					_self.temp.collapseTimeoutId = setTimeout(function(){_self.rootCollapse();},500)
				})
				
				YUE.on(config.handleItem, "mouseover", function(){
					
					if(_self.temp.viewMoreBox){
						_self.temp.viewMoreBox.style.height = "0";
						_self.temp.viewMoreHandle.innerHTML = "View More";
						YUD.replaceClass(_self.temp.viewMoreHandle,"view-less","view-more");
					}
				})
				
			}

			
			YUD.addClass(config.rootTarget,config.afterLoadClass);
			
			
			if(OSI.tool){
				OSI.tool.defineEmptyLinks(config.rootTarget);
				OSI.tool.defineEmptyLinks(config.subTarget);
			}
	},
	
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////
	init : function(customConfig){
			 
			this.config = YL.merge(this.config,customConfig||{});
			
			var _self = this;var config = this.config;var temp = this.temp;

			if(!config.forceRun && config.isCollapse){
					
					var callback =
					{
					  success:function(o){
					  	var categoriesBody = document.createElement("div");
					  	categoriesBody.id = "categories-body";
							categoriesBody.innerHTML = o.responseText;
							config.handleItem.parentNode.parentNode.appendChild(categoriesBody);
							
							_self.run();
							
					  },
					  failure:function(){}
					};
					try{
						var request = YAHOO.util.Connect.asyncRequest('GET', config.getCategoriesAPI , callback);
					}catch(e){
						
						//_self.run();
					}
			}else{
				
				_self.run();
			}
			
			
			
			if(OSI.bom.isIE){ CollectGarbage(); }
			
			
			return _self;
	}
}