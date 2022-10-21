/* image mangement
	js form content page
	by song
	11/07/2022
*/
let arrImageTags = [];
let arrImageLists = [];
let arrSelectedImages = [];
let inputId;

(function( $ ){
    $.fn.imagesBoxSelector = function(param1, data){ 
    
        $(this).each(function () {
            if (typeof data === 'undefined') {
                data = {};
            }

            let inputId = $(this).attr('id')
            let inputVal = $(this).val()
            let imageSelect = '';
            arrSelectedImages[inputId] = [];

            const arrInputVal = inputVal.split(",");

            $.each(arrInputVal, function (i, val) {

                if (val){
                    arrSelectedImages[inputId].push(parseInt(val))

                    let dImage = data.imageLists.find(e => e.id === parseInt(val));

                    if (dImage != undefined) {
                        imageSelect += '<img src ="' + dImage.small_path + '">';
                    }
                }
            });

            if (data !== undefined) {

                arrImageTags[inputId] = data.imageTags
                arrImageLists[inputId] = data.imageLists
            }

          //  let divShowImageSelector = '<div class="box-show-images-select" id="show_images-' + inputId + '" data-bs-toggle="modal" data-bs-target="#modal-images-' + inputId + '">' + imageSelect + '</div>'
            let divShowImageSelector = '<div class="box-show-images-select" id="show_images-' + inputId +'" data-readonly="'+data.readonly+'"'
            if (!data.readonly) {
                divShowImageSelector += 'data-bs-toggle="modal" data-bs-target="#modal-images-' + inputId + '"'
            }
            divShowImageSelector += '>'+imageSelect
            divShowImageSelector += '</div>';

            $(this).before(divShowImageSelector);
        });
        // console.log(arrImageLists)
        // console.log(arrImageTags)
    }; 
})( jQuery );


$(function () { 

    $('.box-show-images-select').on('click', function () {
        let readonly = $(this).data('readonly')

        if (readonly == 'undefined') {
            inputId = $(this).next("input").attr('id')
            let modalId = '#modal-images-' + inputId;
            modalImagesBox.show('', { 'inputId': inputId });
        }
    });

    $(document).on('click', '.close-box-show-images', function () { 
        modalImagesBox.hide();
    });

    $(document).on('click', '.filter-link', function () {

        // let newHeight = $(".box-list").height()+$('#block-filter').height();
        // $(".box-list").height(newHeight);

        $('#block-filter').slideToggle();
        
	});

    $(document).on('click','#btn-all', function () {
        // $(this).toggleClass("btn-default btn-primary");
        $(this).addClass("btn-primary");
        $('.btn-cat').removeClass('btn-primary').addClass('btn-default');
        $('#btn-no-tag').removeClass('btn-primary').addClass('btn-default');
        $('#btn-on-select').removeClass('btn-primary').addClass('btn-default');
        list_images('');
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
        $('#btn-on-select').removeClass('btn-primary').addClass('btn-default');
	
		list_images(arrCatId);
    });
    
    $(document).on('click','#btn-no-tag', function () {
        // $(this).toggleClass("btn-default btn-primary");
        $(this).addClass("btn-primary");
        $('.btn-cat').removeClass('btn-primary').addClass('btn-default');
        $('#btn-all').removeClass('btn-primary').addClass('btn-default');
        
        list_images('no');
    });

    $(document).on('click','#btn-on-select', function () {
        // $(this).toggleClass("btn-default btn-primary");
        $(this).addClass("btn-primary");
        $('.btn-cat').removeClass('btn-primary').addClass('btn-default');
        $('#btn-all').removeClass('btn-primary').addClass('btn-default');
        $('#btn-no-tag').removeClass('btn-primary').addClass('btn-default');
        list_images('select');
    });


    $(document).on('click', '#btn-select-all', function () { 

        $(".box-list .square-content").each(function () { 
            $(this).addClass("select-img");
            var n_imageId = $(this).attr('id');

            $('.input-checkbox').attr('checked', true);

            if ($.inArray(parseInt(n_imageId), arrSelectedImages[inputId]) == -1) {

                arrSelectedImages[inputId].push(parseInt(n_imageId))
                let keyOrder = $.inArray(parseInt(n_imageId), arrSelectedImages[inputId]);
                let showBoxOrder = '<span class="box-order">' + (keyOrder+1) + '</span>';
                $(this).append(showBoxOrder);
            }
        })

        $('#'+inputId).val(arrSelectedImages[inputId]);
        _RenderViewImage(inputId);
    
    });

    $(document).on('click', '#btn-unselect-all', function () { 
        $("span").remove(".box-order");
        $(".box-list .square-content").each(function () { 
            $(this).removeClass("select-img");
            $('.input-checkbox').attr('checked', false);
        })
        arrSelectedImages[inputId] = []
        $('#show_images-' + inputId).html('');
        $('#show_images-' + inputId).height(110)
        $('#'+inputId).height($('#show_images-'+inputId).height());
    });

    $(document).on('click', '.icon-size', function () {
        var sizeNew = $(this).data('val');
        $(".box-list").each(function () {
            var classOld = $(".box-list .square-box").attr('class');
            var classNew = 'col-xs-' + sizeNew + ' square-box';
            $(".box-list .square-box").removeClass(classOld).addClass(classNew).show();
        })
    });

    $(document).on('click', '.icon-zoom-image', function () { 
        var imagePath = $(this).data('image-path');
        modalZoomImage.show(imagePath);
    });
    $(document).on('click', '.close-zoom-image', function () { 
        modalZoomImage.hide();
    });

    $(document).on('change', '#order-images', function () { 

        var valueSelected = this.value;
        const arrVal = valueSelected.split("-");
        if (arrVal[1] == 'desc') { 
            arrImageLists[inputId].keySort(arrVal[0], true);
        } else {
            arrImageLists[inputId].keySort(arrVal[0]);
        }
        let newList = _RenderBlockImageLists(inputId)
        $('#block-list-images').html(newList);
    });


    $(document).on('click', '.square-content', function () { 

        $(this).toggleClass("select-img ''");
		var length_select = $(".box-list .select-img").length;
		var limit_select = $('#'+inputId).attr('max');

		if (length_select > limit_select) {
			$(this).toggleClass("select-img ''");
			var msse = 'Please select '+limit_select+' images only.';
				alertDialog.show(msse,'alert...', {dialogSize: 'sm'});
			return false;
		}
		
		//----new
        
        var n_imageId = $(this).attr('id');

        if ($.inArray(parseInt(n_imageId), arrSelectedImages[inputId]) == -1) {
            arrSelectedImages[inputId].push(parseInt(n_imageId))
        } else {
            arrSelectedImages[inputId].splice($.inArray(parseInt(n_imageId), arrSelectedImages[inputId]), 1);
        }

		// const arrImage = [];
		
        $("span").remove(".box-order");
        $('.input-checkbox').attr('checked', false);

        $('#'+inputId).val(arrSelectedImages[inputId]);

		$(".box-list .select-img").each(function () {

			let nn_imageId = $(this).attr('id');
			let keyOrder = $.inArray(parseInt(nn_imageId), arrSelectedImages[inputId]);
			let showBoxOrder = '<span class="box-order">' + (keyOrder+1) + '</span>';
			$(this).append(showBoxOrder);
			
            // var imgPath = $(this).data('img');
            // $('#input-'+nn_imageId).attr('checked', true);
			// arrImage[keyOrder] = imgPath;
        });
        
        _RenderViewImage(inputId);

		
		//$('#select-images').html(arrSelectedImages.length);

		// var dialogBox ='';
		// $.each(arrImage , function(i, val) { 
		// 	dialogBox = dialogBox+' <img src="'+val+'" >';
        // });
        

		// $('#show_images-'+inputId).html(dialogBox);
		// $('#'+inputId).height($('#show_images-'+inputId).height());
    });

});

function _RenderViewImage(inputId) { 

    const arrImage = [];
    $(".box-list .select-img").each(function () {

		let nn_imageId = $(this).attr('id');
		let keyOrder = $.inArray(parseInt(nn_imageId), arrSelectedImages[inputId]);
        var imgPath = $(this).data('img');
		arrImage[keyOrder] = imgPath;
	});

	var dialogBox ='';
	$.each(arrImage , function(i, val) { 
		dialogBox = dialogBox+' <img src="'+val+'" >';
    });

	$('#show_images-'+inputId).html(dialogBox);
	$('#'+inputId).height($('#show_images-'+inputId).height());
}

function _RenderBlockFilter(id) { 

    let listOrder = _SelectOrder();
    let html = '<div id="block-filter" class="clearfix">'
        html += _RenderBlockTags(id)
        html += '<div class="col-icon-view" id="box-show-order">' +
            '<div class="row">' +
            '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">' +
            '<div class="box-icon"><i class="icon-size fa fa-square" aria-hidden="true" data-val="6"></i></div>' +
            '<div class="box-icon"><i class="icon-size fa fa-th-large" aria-hidden="true" data-val="3"></i></div>' +
            '<div class="box-icon"><i class="icon-size fa fa-th" aria-hidden="true" data-val="2"></i>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '<div class="row">' +
            '<div class="box-text-select-item">' +
            '<span id="count-image">' + arrImageLists[id].length + '</span>' +
            ' Items Found<br>' +
            listOrder+
            '</div></div></div>' +
            '<div class="col-icon-view" style="width:130px">' +
                    '<button type="button" id="btn-select-all" class="btn btn-success mb-1 btn-all">Select All</button>' +
                    '<button type="button" id="btn-unselect-all" class="btn btn-danger mb-1 btn-all">Unselect All</button>' +
                    
                '</div>' +
            '</div > ';
    
    return html
}

function _RenderBlockTags(id) { 

    let data = arrImageTags[id]
    let html =  '<div class="col-list-tags"><i class="fa fa-tags" aria-hidden="true"></i> Tags : ' +
                '<button type="button" id="btn-all" class="btn btn-primary btn-all">#All Images</button>';
    $.each(data, function (key,tag) {
        html += '<button type="button" data-catid="'+tag.id+'" class="btn btn-default btn-cat" title="'+tag.tag_description+'">'+tag.tag_name+'</button>'
    });
    html += '<button type="button" id="btn-no-tag" class="btn btn-default btn-all">#No Tag</button>';
    html += '<button type="button" id="btn-on-select" class="btn btn-default btn-on-select">#On Select</button>';
    html += '</div>';
    return html
}

function _SelectOrder() { 
    let html = '<select class="form-control" id="order-images">' +
        '<option value="id-desc">id Desc</option>' +
        '<option value="id-asc">id Asc</option>' +
        '<option value="name-asc">Name Asc</option>' +
        '<option value="name-desc">Name Desc</option>' +
        '</select>';
    return html;
}

function _RenderBlockImageLists(id) { 
    let data = arrImageLists[id]
    //let imageSelect = arrSelectedImages[id]
    let html = '<div class="box-list">'

    $.each(data, function (key, image) {
        
        let showBoxOrder = '';
        let zoomIcon = '<span class="icon-zoom-image" id="zoom-image-'+image.id+'" data-image-path="'+image.path+'"><i class="fa fa-search" aria-hidden="true"></i></span>';
        let selectImg = '';
        let checkBox = '';
        let keyOrder = $.inArray(parseInt(image.id), arrSelectedImages[id]);

        if (keyOrder != -1) {
            // console.log(image.id, keyOrder)
            selectImg = 'select-img'
            showBoxOrder = '<span class="box-order">' + (keyOrder + 1) + '</span>';
            checkBox = 'checked'
        }

        html += '<div class="col-xs-2 square-box">' +
                    '<div style="background-image:url('+image.small_path+');" ' +
                    'class="square-content '+selectImg+'" id="'+image.id+'" data-name="'+image.name+'"  data-img="'+image.small_path+'" ' +
                    'data-count="2" data-imageid="'+image.id+'" data-tags="'+image.image_tag_id+'">' +
                        '<div class="box-galley-content">' +
                            image.name+
                        '</div>' +
                        '<div class="box-input-checkbox">'+
                            '<input class="form-control input-lg input-checkbox" id="input-'+image.id +'" type = "checkbox" value = "' + image.id + '" name="checkbox-image_id[]" '+checkBox+'>' +
                        '</div > ' +
                        showBoxOrder  +
                    '</div>' +
                    zoomIcon+
                '</div>'
    });

    html += '</div>';
    return html
}

Array.prototype.keySort = function(key, desc){
    this.sort(function(a, b) {
        var result = desc ? (a[key] < b[key]) : (a[key] > b[key]);
        return result ? 1 : -1;
    });
    return this;
}

var list_images = function (arrCatId) {

    let countImages = 0;
    var arrImgSelect = [];
    
    $(".box-list .select-img").each(function() {
        var imageId = $(this).attr('id');
        arrImgSelect.push(imageId);
    });

	$(".box-list .square-box").each(function () { 
		
		let _this = $(this);
		let tagIds = _this.find(".square-content").data('tags');
		let imageId = _this.find(".square-content").data('imageid').toString();
		let isChkImgShow = false;
        let isCount = false;
        let tagArray = [];
        if (tagIds != null) {
            tagArray = tagIds.toString().split(",");
        } else { 
            tagArray = [0]
        }

		if ($.inArray(imageId, arrImgSelect) == -1) {

		    $.each(tagArray, function (imgK, imageTagId) {
		        if (arrCatId != '') {
                    if (arrCatId != 'no' && arrCatId != 'select') {

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

                    } else if (arrCatId == 'no'){ //<-arrCatId == 'no'

                        if (imageTagId == '') {
                            _this.show();
                            isCount = true
                        } else {
                            _this.hide();
                        }
                    } else {  //<-arrCatId == 'select'
                        _this.hide();
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
		
    });
    
    $('#count-image').html(countImages);
}

var modalImagesBox = modalImagesBox || (function ($) {
    'use strict';
    var $dialog = $(
		'<div class="modal" id="modal-images-block" tabindex="-1" aria-labelledby="modal-extra-large" aria-hidden="true">' +
            '<div class="modal-dialog modal-xl" role="document">' +
                '<div class="modal-content">' +
                    '<div class="block block-rounded shadow-none mb-0">' +
                        '<div class="block-header block-header-default">' +
                            '<h3 class="block-title">Images Box</h3>' +
                            '<div class="block-options">' +
                                '<button type="button" class="btn-block-option filter-link" >' +
                                    '<i class="fa fa-filter"></i></i>' +
                                '</button>' +
                                '<button type="button" class="btn-block-option close-box-show-images" aria-label="Close">'+
                                    '<i class="fa fa-times"></i>'+
                                '</button>' +
                            '</div>'+
                        '</div>'+
                        '<div class="block-content fs-sm clearfix">' +
                        
                        '</div> '+
                        '<div class="block-content block-content-full block-content-sm text-end border-top">'+
                            '<button type="button" class="btn btn-alt-primary close-box-show-images" data-bs-dismiss="modal">Done</button>'+
                        '</div>'+
                    '</div>'+
                '</div>'+
            '</div>'+
        '</div>');

    return {
        show: function (message, options) { 
            if (typeof options === 'undefined') {
				options = {};
			}
			if (typeof message === 'undefined') {
				message = 'Loading';
            }

            let htmlBlockFilter = _RenderBlockFilter(options.inputId)
            let htmlBlockImageLists = _RenderBlockImageLists(options.inputId)

           // _RenderSelectImage(options.inputId)

            $dialog.find('.block-content.fs-sm').html(htmlBlockFilter)
            $dialog.find('.block-content.fs-sm').append('<div id="block-list-images"></div>')
            $dialog.find('.block-content.fs-sm #block-list-images').append(htmlBlockImageLists)
            $dialog.find('.modal').attr('id', options.modalId);
            $dialog.modal();

            //--function

        },
        hide: function () {
			$dialog.modal('hide');
		}
    }

})(jQuery);

var modalZoomImage = modalZoomImage || (function ($) {
    'use strict';
    var $dialog = $(
        '<div class="modal" id="modal-zoom-image" tabindex="-1" aria-labelledby="modal-extra-large" aria-hidden="true">' +
        '<div class="modal-dialog modal-xl" role="document">' +
        '<div class="modal-content">' +
        '<span class="close close-zoom-image">&times;</span>' +
        '<div id="img-show"></div>'+
        '</div>' +
        '</div>');

    return {
        show: function (imagePath) { 
            let img = '<img class=" close-zoom-image" src="'+imagePath+'">'
            $dialog.find('#img-show').html(img)
            $dialog.modal();
        },
        hide: function () {
			$dialog.modal('hide');
		}
    }
})(jQuery);

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

