/**
 * Functions for manipulating the os commerce cart via javascript.
 * Allows for adding items to the cart including items with attributes.
 * Currently allows for the display of the summary cart.  
 * 
 * Last updated:
 *
 * Author: Sunny Chow
 */

function osc_cart(path)
{
	var me = this;
	me.path = path;
	var pid;
	var options;

	/** 
	 * Functions specific to filtering information from input boxes
	 */
	this.getID = function(idName)
	{
		var pat = /id\[(\d*)\]/;
		var matches = pat.exec(idName);
		return matches[1];
	}

	this.setProductFromInput = function()
	{
		// Link that was passed back.  Extract product id.
		me.pid = $(this).siblings("input[name=products_id]").val();

		var selected = $('select[name^=id]');
		me.options = new Object();
		for (var i = 0; i < selected.length; i++)
		{
			var selOptions = $('option:selected', selected[i]);
			// extract the number from id.
			var idNo = me.getID(selected[i].name)
			me.options[idNo] = selOptions.val();
		}

		me.addItemToCart();
		return false;
	}


	/**
	 * Ids the links that will add the product to cart.
	 */
	this.id_inputs = function(index)
	{
		var patAddToCart = / Add to Cart /;
		return this.title.match(patAddToCart);
	}

	/**
	 * Functions specific to adding AJAX shopping cart functionality to links
	 */
	this.id_links = function(index)
	{
		var pat1 = /action=buy_now/;
		return this.href.match(pat1);
	}

	/**
	 * Basic functionality common to add items to cart as well as allow users
	 * to select attributes
	 */
	this.getItemAttributes = function()
	{
		// Link that was passed back.  Extract product id.
		var pat = /products_id=(\d*)/;
		var matches = pat.exec(this.href);
		me.pid = matches[1];
		me.options = new Object();

		$.get(me.path + 'actions.php?method=product_options&pid=' + me.pid,  me.getItemAttributesCB);
		return false;
	}

	this.getItemAttributesCB = function(data)
	{
		var attributes;
		try {
			attributes = $.parseJSON(data)	
		}
		catch (err)
		{
			me.onError(err);
			return false;
		}

		// if no attributes go straight to add to cart.
		if ( attributes.result != null ) 
		{
			me.addItemToCart();
			return;
		}

		// Reset cart.
		$('.cart_status').html('');

		// Set up the combo boxes for users to select the product options
		for (var i = 0; i < attributes.length; i++) 
		{
			var idTxt = 'id[' + attributes[i].id + ']';
			$('.cart_status').append(attributes[i].text + ': <select name="' + attributes[i].id +'" + id="' + idTxt + '"></select>');

			for (var j = 0; j < attributes[i].values.length; j++)
			{
				$('select', $('.cart_status')).last().append('<option value="' + attributes[i].values[j].id + '">' + attributes[i].values[j].text + '</option>');
			}
		}
		// Add button.
		$('.cart_status').dialog({ buttons: { 'Ok' : me.setProductFromDialog }});
		$('.cart_status').dialog( "option", "dialogClass", 'alert' )
		$('.cart_status').dialog('option', 'title', 'Product Options');
		$('.cart_status').dialog('open');
	}

	this.setProductFromDialog = function()
	{
		var selected = $('select', $('.cart_status'));
		for (var i = 0; i < selected.length; i++)
		{
			var selOptions = $('option:selected', selected[i]);
			me.options[selected[i].name] = selOptions.val();
		}
		me.addItemToCart();
		return false;
	}

	this.addItemToCart = function()
	{
		$.post(me.path + 'actions.php',
			{ method: "add_to_cart", 
				pid: me.pid,
			  id : me.options},
		me.addItemToCartCB);
		$('.cart_status').dialog({ buttons: null});
		$('.cart_status').html('<img style="vertical-align:middle" src="' + me.path + 'includes/osc_cart/json-images/loading.gif" />&nbsp; Adding item to cart');
		$('.cart_status').dialog('open');
		$('.cart_status').dialog('option', 'title', '');
		$('.cart_status').dialog("option", "position", "center");
		return false;
	}

	this.addItemToCartCB = function(data)
	{
		var retObj;
		try {
			retObj = $.parseJSON(data);
		}
		catch (err)
		{
			me.onError(err); 
			return false;
		}

		$('.cart_status').html('Added to cart :');
		$('.cart_status').append('<p class="json-productname">' + retObj.product_name );
		if (retObj.options != '')
		{
			for ( var opt in retObj.options)
			{
				$('.cart_status').append('<div style="font-size:.8em;padding-left:1em"><em> - ' + opt + ':' + retObj.options[opt] + '</em></div>');
			}
		}
		$('.cart_status').append('</p>');

		$('.cart_status').dialog('option', 'title', 'Shopping Cart Updated');
		$('.cart_status').dialog(
			{ 
				buttons: { 
					'Continue Shopping' : function() { $('.cart_status').dialog('close'); },
					'View/Modify Cart' : function() { window.location = me.path + 'shopping_cart.php'; }, 
					'Checkout' : function() { window.location = me.path + 'checkout_shipping.php';}
				}
			});
		$('.cart_status').dialog("option", "position", "center");
		me.requestCartContents();
	}

	/**
	 * Updates and loads the cart contents
	 */
	this.requestCartContents = function()
	{
		$.get(me.path + 'actions.php?method=get_cart_summary', me.requestCartContentsCB); 
	}

	this.findShoppingCart = function()
	{
		var shoppingCart = $('.infoBox').filter(me.id_shoppingcart); 
		var shoppingCartTable = shoppingCart.find('.infoBoxContents').first();

		return shoppingCartTable;
	}
	this.requestCartContentsCB = function(data)
	{
		var obj;
		try {
			obj = $.parseJSON(data);
		}
		catch (err) {
			me.onError(err);
			return false;
		}

		var count = 0;
		for ( var productName in obj.contents )  
			count += obj.contents[productName].qty;

		var itemStr = 'item';
		if (count > 0)
		{
			if (count > 1) itemStr += 's';
			$(".shopping_cart").css('display',  'block');
			$(".summary").html( count + ' ' + itemStr + ' $' + obj.total );
		}
		
		// Add items to shopping cart dialog box.
		// Clear HTML
		// Look for an infobox where the infoboxHeading is equal to Shopping cart
		var shoppingCartTable = me.findShoppingCart();
		var text = '';

		// Add Spacing
		text += '<tr><td><img src="images/pixel_trans.gif" border="0" alt="" width="100%" height="1"></td></tr>';

		// Add Items
		text += '<tr><td><table border="0" width="100%" cellspacing="0" cellpadding="0">';
		for (var productName in obj.contents)
		{
			// Add Product Qty
			text += '<tr><td align="right" valign="top" class="infoBoxContents">';
			text += '<span class="';
			text += (obj.contents[productName].updated) ? "newItemInCart" : "infoBoxContents";
			text += '">';
			text += obj.contents[productName].qty;
			text += '&nbsp;x&nbsp;</span></td><td valign="top" class="infoBoxContents">';

			// Add Product No
			text += '<a href=' + me.path + '/product_info.php?products_id=' + productName + '>';
			text += '<span class="';
			text += (obj.contents[productName].updated) ? "newItemInCart" : "infoBoxContents";
			text += '">';
			text += obj.contents[productName].name;
			text += '</span></a></td></tr>';
		}
		text += '</table></td></tr>';

		// Add Line
		text += '<tr><td class="boxText"><img src="images/pixel_black.gif" border="0" alt="" width="100%" height="1"></td> </tr>';

		// Add Total
		text += '<tr><td align="right" class="boxText">';
		text += '<span class="newItemInCart">';
		text += obj.total + '</span></td></tr>'
;
		// Add Spacing
		text += '<tr><td><img src="images/pixel_trans.gif" border="0" alt="" width="100%" height="1"></td></tr>';

		shoppingCartTable.html(text);
	}

	this.id_shoppingcart = function(index)
	{
		// !! Change "Shopping Cart" to your shopping cart title.
		var patShoppingCart = /AddToCart/;
		if ( $(this).prev() == null || $(this).prev().html() == null)
			return false;

		return $(this).prev().html().match(patShoppingCart);
	}

	this.onError = function(err)
	{
		$('.cart_status').html('<p>' + err + '</p>');
		$('.cart_status').dialog('option', 'title', 'Error');
		$('.cart_status').dialog(
			{ 
				buttons: { 
					'OK' : function() { $('.cart_status').dialog('close'); }
				}
			});
		$('.cart_status').dialog("option", "position", "center");

		$('.cart_status').dialog('open');
	}

	// Initializes the class.
	this.osc_init = function()
	{
		// add to cart inputs
		$('input').filter(me.id_inputs).click(me.setProductFromInput);

		// add to cart link
		$('a').filter(me.id_links).click(me.getItemAttributes);

		// add to cart dialog boxes.
		$('body').append('<div class="cart_status" style="display:none"></div>');

		$('.cart_status').dialog(
			{
				"autoOpen":false,
				"draggable":false,
				"resizable":false,
				"modal":true
			}
		);


		// Add mouse over handler to what we added.
		//$('body').append('<div class="cart_contents" style="display:none"></div>');
		//$('.cart_contents').dialog(
		//	{
		//		autoOpen:false,
		//		draggable:false,
		//		resizable:false,
		//		title:'Shopping Cart Contents'
		//	}
		//);
		//var link = $('a', $('#menuNavigation')).filter(function() { return this.href.indexOf('shopping_cart.php') != -1;} );
		//link.mouseover(function() { $('.cart_contents').dialog('open') } );
	}
}

