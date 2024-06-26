var RSGoPro_MenuTO = 0,
	RSGoPro_MenuElemHover = false;

var RSDevFunc_PHONETABLET = false;
if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|Windows Phone/i.test(navigator.userAgent) ) { RSDevFunc_PHONETABLET = true };
	

function RSGoPro_ElementInMenuPos($liObj) {
	var l = 250,
		$searchObj;
	
	if (!$liObj.hasClass('first')) {
		$searchObj = $liObj.parents('li.first');
	} else {
		$searchObj = $liObj;
	}

	if ($searchObj.find('.elementinmenu').length > 0) {
		var c = $('.js-menu .catalogmenu').find('li.hover').length - 2;

		if ($('.js-menu .catalogmenu').find('li.hover:last').parent().find('ul').length < 1) {
			c = c - 1;
		}

		if ($('.centering:first').width() < 1260) {
			c = c + 5;
		}

		if (c > 2) {
			$('.js-menu .catalogmenu').find('li.first.hover').parent().find('.elementinmenu').css({'display':'none'});
		} else {
			$('.js-menu .catalogmenu').find('li.first.hover').parent().find('.elementinmenu').css({'display':'block','left':(c*l-2)+'px'});
		}
	}
}

$(document).ready(function(){
	
	$('.js-menu .catalogmenu').on('mouseenter',function(){
		$(this).addClass('hover');
	}).on('mouseleave',function(){
		$(this).removeClass('hover');
	});
	
	var timeoutHover = {};
	$('.js-menu .catalogmenu a').on('click',function(e){
		var $link = $(this);
		if(!$link.hasClass('hover')){
			e.preventDefault();
			$link.addClass('hover');
		}
	}).on('mouseenter',function(){
		var $link = $(this);
		$link.parent().parent().find('a.hover').removeClass('hover');
		timeoutHover[$link.index()] = setTimeout(function(){
			$link.addClass('hover');
		},150);
	}).on('mouseleave',function(){
		var $link = $(this);
		clearTimeout(timeoutHover[$link.index()]);
		timeoutHover[$link.index()] = setTimeout(function(){
			$link.removeClass('hover');
		},2);
	});
	
	$('.js-menu .catalogmenu li').on('mouseenter',function(){
		var $liObj = $(this);
		$liObj.parent().find('li.hover').removeClass('hover');
		setTimeout(function(){
			$liObj.addClass('hover');
			RSGoPro_ElementInMenuPos( $liObj );
		}, 2);
	}).on('mouseleave',function(){
		var $liObj = $(this);
		setTimeout(function(){
			if (!RSGoPro_MenuElemHover) {
				$liObj.removeClass('hover')
			}
			RSGoPro_ElementInMenuPos( $liObj );
		},2);
	});
	
	$('.js-menu .catalogmenu .elementinmenu').on('mouseenter',function(){
		RSGoPro_MenuElemHover = true;
	}).on('mouseleave',function(){
		RSGoPro_MenuElemHover = false;
	});
	
	if (RSDevFunc_PHONETABLET) {
		$('.js-menu .catalogmenusmall a.parent').on('click',function(){
			if ($(this).parent().find('ul').hasClass('noned')) {
				$(this).parent().find('ul').removeClass('noned');
				return false;
			}
		});

		$(document).on('click',function(){
			var $obj = $(this);
			if (!$('.js-menu .catalogmenusmall ul.first').hasClass('noned')) {
				$('.js-menu .catalogmenusmall ul.first').addClass('noned');
			}
		});
	} else {
		$('.js-menu .catalogmenusmall li.parent').on('mouseenter',function(){
			$(this).find('ul').removeClass('noned');
		}).on('mouseleave',function(){
			$(this).find('ul').addClass('noned');
		});
	}
	function setMenuWidth()
	{
		var i, container, menuLevel2, width;
		if(window.innerWidth > 768)
		{
			container = document.querySelector('.workarea > .bx-content-seection');
			width = container.offsetWidth;
			menuLevel2 = document.querySelectorAll('.catalogmenu .list-unstyled.lvl2');
			for (i = 0; i < menuLevel2.length; i++ ){
				menuLevel2[i].style.width = (width - 310) + 'px';
			}
		}
	}
	setMenuWidth();
	window.addEventListener('resize', setMenuWidth);
});