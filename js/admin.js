// JavaScript Document
jQuery(document).ready(function($) {
  
	  var tx = Raphael.colorwheel($(".textcolorwheel div")[0],150);
	  tx.input($(".textcolorwheel input")[0]);
	  
	  var bg = Raphael.colorwheel($(".bgcolorwheel div")[0],150);
	  bg.input($(".bgcolorwheel input")[0]);
	
});