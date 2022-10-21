/* image mangement
	js form content page
	by song
	11/07/2022
*/
$( document ).ready(function() {

	let imageSelect = $('#imageIds').val();
	const arrSelectedImages = imageSelect.split(",");
	const arrImage = [];

	$(".box-list .square-box").each(function () {

		let _this = $(this).find(".square-content")

			let nn_imageId = _this.attr('id');
			let keyOrder = $.inArray(nn_imageId, arrSelectedImages);

			if (keyOrder != -1) {
				_this.toggleClass("select-img ''");
				let showBoxOrder = '<span class="box-order">' + (keyOrder+1) + '</span>';
				_this.append(showBoxOrder);

				var imgPath = _this.data('img');
				arrImage[keyOrder] = imgPath;
			}

	});

	var dialogBox ='';
		$.each(arrImage , function(i, val) { 
			dialogBox = dialogBox+' <img src="'+val+'" >';
		});
		$('.box-show-images-select').html(dialogBox);
		$('.input-img').height($('#showimg').height());
});

$(function () {
	
	let imageSelect = $('#imageIds').val();
	let arrSelectedImages = [];
	if (imageSelect != '') {
		arrSelectedImages = imageSelect.split(",");
	}
	
	$(document).on('click', '.square-content', function () {
		
		$(this).toggleClass("select-img ''");
		var length_select = $(".box-list .select-img").length;
		var limit_select = $('#imageIds').attr('max');

		if (length_select > limit_select) {  
			$(this).toggleClass("select-img ''");
			var msse = 'Please select '+limit_select+' images only.';
				alertDialog.show(msse,'alert...', {dialogSize: 'sm'});
			return false;
		}
		

		//----new
		//colsole.log(arrSelectedImages, 'old');
		
		var n_imageId = $(this).attr('id');
		
		if ($.inArray(n_imageId, arrSelectedImages) == -1) {
			arrSelectedImages.push(n_imageId)
		} else { 
			arrSelectedImages.splice($.inArray(n_imageId, arrSelectedImages), 1);
		}
		//colsole.log(arrSelectedImages,'new');
		const arrImage = [];
		
		$("span").remove(".box-order");

		$(".box-list .select-img").each(function () {

			let nn_imageId = $(this).attr('id');
			let keyOrder = $.inArray(nn_imageId, arrSelectedImages);
			let showBoxOrder = '<span class="box-order">' + (keyOrder+1) + '</span>';
			$(this).append(showBoxOrder);
			
			var imgPath = $(this).data('img');
			arrImage[keyOrder] = imgPath;
		});

		//-----
		
		$('#imageIds').val(arrSelectedImages);
		
		$('#select-images').html(arrSelectedImages.length);

		var dialogBox ='';
		$.each(arrImage , function(i, val) { 
			dialogBox = dialogBox+' <img src="'+val+'" >';
		});
		$('.box-show-images-select').html(dialogBox);
		$('.input-img').height($('#showimg').height());
	});
	
	$(document).on('click','.btn-cat', function () {
		$(this).toggleClass("btn-default btn-primary");
		var arrCatId = [];
		$(".col-list-tags .btn-cat.btn-primary").each(function() {
			var catId = $(this).data('catid');
			arrCatId.push(catId);
		});
		if(arrCatId.length>0){
			$('.btn-all').removeClass('btn-primary').addClass('btn-default');
		} else {
			$('#btn-all').addClass('btn-primary').removeClass('btn-default');
		}
		var arrImageId = [];
		$(".box-list .select-img").each(function() {
			var imageId = $(this).attr('id');
			arrImageId.push(imageId);
		});
		list_images(arrCatId,arrImageId);
	});
	
	$('#btn-all').on('click', function () {
		$(this).toggleClass("btn-default btn-primary");
		$('.btn-cat').removeClass('btn-primary').addClass('btn-default');
		$('#btn-notag').removeClass('btn-primary').addClass('btn-default');
		var arrImageId = [];
		$(".box-list .select-img").each(function() {
			var imageId = $(this).attr('id');
			arrImageId.push(imageId);
		});
		list_images('',arrImageId);
	});
	
	$('#btn-notag').on('click', function () {
		$(this).toggleClass("btn-default btn-primary");
		$('.btn-cat').removeClass('btn-primary').addClass('btn-default');
		$('#btn-all').removeClass('btn-primary').addClass('btn-default');
		var arrImageId = [];
		$(".box-list .select-img").each(function() {
			var imageId = $(this).attr('id');
			arrImageId.push(imageId);
		});
		list_images('no',arrImageId);
	});

	$('.iconsize').on('click', function () {
		var sizeNew = $(this).data('val');
		$(".box-list").each(function() {
			var classold = $(".box-list .square-box").attr('class');
			var classnew = 'col-xs-'+sizeNew+' square-box';
			$(".box-list .square-box").removeClass(classold).addClass(classnew).show();
		})
	});

	
	$(document).on('click','.box-image-count', function () {
		var e=$(this);
		var imageId = e.data('id')		
		if(!e.data('content')){
			var url = $('#url_image_user').val()+imageId;	
			//var url = '/admin/index.php/image/listContentImage/'+imageId;
			$.post(url).done(function(d) {
				e.data('content',d);
				e.popover({
				 	container: 'body',
      			 	html: true
				});
				e.popover('show');
			});
		}
	});
	
	$(document).on('mouseout','.box-image-count', function () {
		 var e=$(this);
		 e.popover('hide');
	});
	
	$(document).on('click', '.filter-link', function () {
		
		$('#block-filter').slideToggle();
	});
});


var alertDialog = alertDialog || (function ($) {
    'use strict';
	  var $dialog = $(
		'<div class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true" style="padding-top:15%; overflow-y:visible;">' +
		'<div class="modal-dialog modal-m">' +
		'<div class="modal-content">' +
			'<div class="modal-header">'+
			' <h4 class="modal-title" id="myModalLabel" id="confirmtitle">Alert</h4>'+
			'</div>' +
			'<div class="modal-body" id="confirmMessage">' +
				' Please select images only.' +
			'</div>' +
			'<div class="modal-footer">'+
            '<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>'+
            '</div>'+
		'</div></div></div>');

	return {
		show: function (message,title, options) {
			// Assigning defaults
			if (typeof options === 'undefined') {
				options = {};
			}
			
			if (typeof message === 'undefined') {
				message = ' Please select  images only.';
			}
			if (typeof title === 'undefined') {
				title = 'Alert';
			}
			
			var settings = $.extend({
				dialogSize: 'm',
				progressType: '',
				onHide: null // This callback runs after the dialog was hidden
			}, options);

			// Configuring dialog
			
			$dialog.find('.modal-dialog').attr('class', 'modal-dialog').addClass('modal-' + settings.dialogSize);
			
			$dialog.find('#confirmtitle').text(message);
			$dialog.find('#confirmMessage').text(message);
			// Adding callbacks
			if (typeof settings.onHide === 'function') {
				$dialog.off('hidden.bs.modal').on('hidden.bs.modal', function (e) {
					settings.onHide.call($dialog);
				});
			}
			// Opening dialog
			$dialog.modal();
		},
		/**
		 * Closes dialog
		 */
		hide: function () {
			$dialog.modal('hide');
		}
	};

})(jQuery);

var list_images = function (arrCatId,arrImgSelect) {

	let countImages = 0;
	$(".box-list .square-box").each(function () { 
		
		let _this = $(this);
		let tagIds = _this.find(".square-content").data('tags').toString();
		let imageId = _this.find(".square-content").data('imageid').toString();
		let isChkImgShow = false;
		let isCount = false;
		const tagArray = tagIds.split(",");

		if ($.inArray(imageId, arrImgSelect) == -1) {

			$.each(tagArray, function (imgK, imageTagId) {
				if (arrCatId != '') {
					if (arrCatId != 'no') {					
						$.each(arrCatId, function (tagK, tagId) {
							if (imageTagId == tagId) {
								_this.show();
								isCount = true
								isChkImgShow = true;
							} else {
								if (!isChkImgShow) {
									_this.hide();
								}
							}
						});

					} else {

						if (imageTagId == '') {
							_this.show();
							isCount = true
						} else {
							_this.hide();						
						}
					}

				} else {
					_this.show();
					isCount = true
				}
			});
			if (isCount == true) {
				countImages = countImages + 1
			}
		} else { 
			_this.show();
		}

		$('#count-image').html(countImages);
	});
}

var ProcessingDialog = ProcessingDialog || (function ($) {
    'use strict';

	var $dialog = $(
		'<div class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true" style="padding-top:15%; overflow-y:visible;">' +
		'<div class="modal-dialog modal-m">' +
		'<div class="modal-content">' +
			'<div class="modal-header"><h3 style="margin:0;"></h3></div>' +
			'<div class="modal-body">' +
				'<div class="progress progress-striped active" style="margin-bottom:0;"><div class="progress-bar" style="width: 100%"></div></div>' +
			'</div>' +
		'</div></div></div>');

	return {
		show: function (message, options) {
			if (typeof options === 'undefined') {
				options = {};
			}
			if (typeof message === 'undefined') {
				message = 'Loading';
			}
			var settings = $.extend({
				dialogSize: 'm',
				progressType: '',
				onHide: null // This callback runs after the dialog was hidden
			}, options);

			// Configuring dialog
			
			$dialog.find('.modal-dialog').attr('class', 'modal-dialog').addClass('modal-' + settings.dialogSize);
			$dialog.find('.progress-bar').attr('class', 'progress-bar');
			if (settings.progressType) {
				$dialog.find('.progress-bar').addClass('progress-bar-' + settings.progressType);
			}
			$dialog.find('h3').text(message);
			// Adding callbacks
			if (typeof settings.onHide === 'function') {
				$dialog.off('hidden.bs.modal').on('hidden.bs.modal', function (e) {
					settings.onHide.call($dialog);
				});
			}
			// Opening dialog
			$dialog.modal();
		},
		/**
		 * Closes dialog
		 */
		hide: function () {
			$dialog.modal('hide');
		}
	};

})(jQuery);
