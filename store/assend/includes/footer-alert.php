
<style>
    #boxes{display:block;}
    
    
#boxes:before {
  content:"";
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.95);
  position: fixed;
  z-index: 9;
}
#mask {
  position:fixed;
  left:0;
  top:0;
  right:0;
  bottom:0;
  z-index:9000;
  background-color:#000;
  opacity:0.8;
  display:none;
}  
#boxes .window {
  position: fixed;
    left: 25%;
    top: 10%;
    width: 50%;
  z-index:9999;
  text-align: center;
}
#boxes #dialog {
  height:auto;     
}
#boxes .popup {
  width: 625px;
  position: fixed;
  left:28% !important;
  z-index: 1000;
 
}
    
#boxes{display:block;
    animation: fadein 2s;
    -moz-animation: fadein 2s; /* Firefox */
    -webkit-animation: fadein 2s; /* Safari and Chrome */
    -o-animation: fadein 2s; /* Opera */
    }
    
    #continue{
        animation: fadein 9s;
    -moz-animation: fadein 9s; /* Firefox */
    -webkit-animation: fadein 9s; /* Safari and Chrome */
    -o-animation: fadein 9s; /* Opera */
    }   
@keyframes fadein {
    from {
        opacity:0;
    }
    to {
        opacity:1;
    }
}
@-moz-keyframes fadein { /* Firefox */
    from {
        opacity:0;
    }
    to {
        opacity:1;
    }
}
@-webkit-keyframes fadein { /* Safari and Chrome */
    from {
        opacity:0;
    }
    to {
        opacity:1;
    }
}
@-o-keyframes fadein { /* Opera */
    from {
        opacity:0;
    }
    to {
        opacity: 1;
    }
}        
    
#boxes:target .popup {
    top: -100%;
    left: -100%;
}
	.video-container {
    text-align: center;
}
    #boxes .text_part{
        font-size: 3rem;
        color: fff;
    }    
.popup .close {
    top: 15px;
    right: 8px;
    transition: all 200ms;
    font-size: 30px;
    font-weight: bold;
    text-decoration: none;
    color: #333;
	cursor:pointer;
}
.maintext{
	text-align: center;
  font-family: "Segoe UI", sans-serif;
  text-decoration: none;
}

#lorem{
	font-family: "Segoe UI", sans-serif;
	font-size: 12pt;
	color:#fff;
}
#popupfoot{
	font-family: "Segoe UI", sans-serif;
	font-size: 16pt;
  padding: 10px 20px;
}
#popupfoot a{
	text-decoration: none;
}
.agree:hover{
  text-decoration: underline; 
}

.popupoption2:hover{ text-decoration: underline; 
}
.backspace {
    
}

@media only screen and (max-width:769px) {
    #boxes .text_part {font-size: 2rem;}
	.video-container{position:relative;padding-bottom:56.25%;padding-top:30px;height:0;overflow:hidden}
.video-container embed,.video-container iframe,.video-container object{position:absolute;top:0;left:0;width:100%;height:100%}
#boxes .window {left:0px; width:100%; padding-bottom: 56.25%;}
	#boxes .popup {width:100% !important; left:0px !important;}
}
@media only screen and (min-width :768px) and (max-width :1024px) {
.video-container{position:relative;padding-bottom:56.25%;padding-top:30px;height:0;overflow:hidden}
.video-container embed,.video-container iframe,.video-container object{position:absolute;top:0;left:0;width:100%;height:100%}
#boxes .window {left:0px; width:100%; padding-bottom: 56.25%;
    padding-top: 30px;}
	#boxes .popup {width:100% !important; left:0px !important;}
}
</style>


<script>
$(document).ready(function() {
$('#boxes').delay(4000000).show(0);
$('#dialog').delay(4000000).fadeIn(400);		
});
</script>
<script src="js/main.js"></script>
<div id="boxes" >
<div id="dialog" class="window backspace feedback_content popup">
<div class="text_part" style="margin-top:0px;">
<h1>Something's On The Horizon</h1></div>
 
<div id="continue" style="text-align: center; width:100%;"><a class="close agree" style="font-size:16px;color:#fff;" onclick="closePopup();">Continue</a>
    </div>
    </div>
  
</div>
<script>
function closePopup() {
var popup1 = document.getElementById('boxes');
popup1.style.display = "none";
};
</script>

<script type="text/javascript" src="js/jquery-ui.js"></script>
<script src="js/bootstrap.min.js"></script>


