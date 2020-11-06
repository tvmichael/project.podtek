BX.ready(function () {
	$(".product-item-detail-tab[data-entity='tab']").on('click', function () {
		$(".product-item-detail-tab[data-entity='tab']").each(function () {
			$(this).removeClass('active');
		});
		$(".product-item-detail-tab-content[data-entity='tab-container']").each(function () {
			$(this).css('display', 'none');
		});
		$(this).addClass('active');
		$(".product-item-detail-tab-content[data-value='" + $(this).attr('data-value') + "']").css('display', 'block');
	});

	$('.product-item-detail-tab-content').on('click', 'button[data-id]', function () {
		var id = $(this).attr('data-id');
		if(id && acUrlAjax)
			$.post( acUrlAjax, {productId:id, action:"Add2Basket"})
				.done(function( data ) {
					var data = JSON.parse(data);
					if(parseInt(data.result) > 0) BX.onCustomEvent('OnBasketChange');
				});
	});
});