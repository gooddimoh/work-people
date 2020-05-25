$(document).ready(function() {
	// $('.scrollbar-inner').scrollbar();
    $('.j-select').niceSelect();

    // $('.j-multi-select-docs').multiselect({
    // 	texts: {
	// 		placeholder: 'Ваши документы', 
	// 		search         : 'Search',        
	// 		selectedOptions: ' выбрано',     
	// 		selectAll      : 'Select all',   
	// 		unselectAll    : 'Unselect all',  
	// 		noneSelected   : 'None Selected' 
	// 	},
	// 	maxHeight: 500,
	// });
});

// Оповещения
$(function () {
	var btn = '.j-zvon',
		menu = '.j-zvon-menu',
		wrapper = '.j-zvon-wrapper';

	$(btn).on('click', function(e) {
		e.preventDefault();

		var _this = $(this);

		$(btn).removeClass('active');
		$(menu).removeClass('active');

		_this.toggleClass('active');
		_this.closest(wrapper).find(menu).toggleClass('active');
	});
	$(document).on('click', function(e) {
		var target = e.target;

		if(!$(target).is(menu) 
			&& !$(target).is(btn) 
			&& !$(target).parents().is(menu) 
			&& !$(target).parents().is(btn) 
			&& $(menu).is('.active') 
			&& $(btn).is('.active')) {
			$(btn).removeClass('active');
			$(menu).removeClass('active');
		}
	});
});

// Карусель
$(function() {

	function init_slick() {
		var slider = $('[slick-slider]');
		wW = $(window).width();

		if (slider.length) {
			slider.each(function() {
				var _this = $(this),
					countDesctop = _this.attr('data-desctop'),
					countTablet = _this.attr('data-tablet'),
					countTabletV = _this.attr('data-tablet-v'),
					countMobile = _this.attr('data-mobile'),
					countMobileV = _this.attr('data-mobile-v'),
					arrows = _this.siblings('.j-arrows-wrap').find('.j-arrows');

				if(countDesctop) {
					slickDesctop(_this, countDesctop, countTablet, countTabletV, countMobile, countMobileV);
				}
				if(!countDesctop && countTablet) {
					slickTablet(_this, countTablet, countTabletV, countMobile, countMobileV);
				}
				if(!countDesctop && !countTablet && countTabletV) {
					slickTabletV(_this, countTabletV, countMobile, countMobileV);
				}
				if(!countDesctop && !countTablet && !countTabletV && countMobile) {
					slickMobile(_this, countMobile, countMobileV);
				}
				if(!countDesctop && !countTablet && !countTabletV && countMobile && countMobileV) {
					slickMobileV(_this, countMobileV);
				}

				function slickDesctop (obj, countDesctop, countTablet, countTabletV, countMobile, countMobileV) {
					obj.not('.slick-initialized').slick({
						slidesToShow: countDesctop,
						slidesToScroll: 1,
						appendArrows: arrows,
						prevArrow: '<button type="button" class="arrow--prev arrow"></button>',
						nextArrow: '<button type="button" class="arrow--next arrow"></button>',
						responsive: [
							{
						      breakpoint: 1200,
						      settings: {
						        slidesToShow: countTablet
						      }
						    },
						    {
						      breakpoint: 992,
						      settings: {
						        slidesToShow: countTabletV
						      }
						    },
						    {
						      breakpoint: 767,
						      settings: {
						        slidesToShow: countMobile
						      }
						    },
						    {
						      breakpoint: 479,
						      settings: {
						        slidesToShow: countMobileV
						      }
						    },
						  ]
					});
				}
				function slickTablet (obj, countTablet, countTabletV, countMobile, countMobileV) {
					if(wW <= 1200) {
						obj.not('.slick-initialized').slick({
							slidesToShow: countTablet,
							slidesToScroll: 1,
							appendArrows: arrows,
							prevArrow: '<button type="button" class="arrow--prev arrow"></button>',
							nextArrow: '<button type="button" class="arrow--next arrow"></button>',
							responsive: [
							    {
							      breakpoint: 992,
							      settings: {
							        slidesToShow: countTabletV
							      }
							    },
							    {
							      breakpoint: 767,
							      settings: {
							        slidesToShow: countMobile
							      }
							    },
							    {
							      breakpoint: 479,
							      settings: {
							        slidesToShow: countMobileV
							      }
							    },
							  ]
						});
					} else {
						if(obj.is('.slick-initialized')) {
							obj.slick('unslick');
						}
					}
				}
				function slickTabletV (obj, countTabletV, countMobile, countMobileV) {
					if(wW <= 992) {
						obj.not('.slick-initialized').slick({
							slidesToShow: countTabletV,
							slidesToScroll: 1,
							appendArrows: arrows,
							prevArrow: '<button type="button" class="arrow--prev arrow"></button>',
							nextArrow: '<button type="button" class="arrow--next arrow"></button>',
							responsive: [
							    {
							      breakpoint: 767,
							      settings: {
							        slidesToShow: countMobile
							      }
							    },
							    {
							      breakpoint: 479,
							      settings: {
							        slidesToShow: countMobileV
							      }
							    },
							  ]
						});
					} else {
						if(obj.is('.slick-initialized')) {
							obj.slick('unslick');
						}
					}
				}
				function slickMobile (obj, countMobile, countMobileV) {
					if(wW <= 767) {
						obj.not('.slick-initialized').slick({
							slidesToShow: countMobile,
							slidesToScroll: 1,
							appendArrows: arrows,
							prevArrow: '<button type="button" class="arrow--prev arrow"></button>',
							nextArrow: '<button type="button" class="arrow--next arrow"></button>',
							responsive: [
							    {
							      breakpoint: 479,
							      settings: {
							        slidesToShow: countMobileV
							      }
							    },
							  ]
						});
					} else {
						if(obj.is('.slick-initialized')) {
							obj.slick('unslick');
						}
					}
				}
				function slickMobileV (obj, countMobileV) {
					if(wW <= 479) {
						obj.not('.slick-initialized').slick({
							slidesToShow: countMobileV,
							slidesToScroll: 1,
							appendArrows: arrows,
							prevArrow: '<button type="button" class="arrow--prev arrow"></button>',
							nextArrow: '<button type="button" class="arrow--next arrow"></button>',
						});
					} else {
						if(obj.is('.slick-initialized')) {
							obj.slick('unslick');
						}
					}
				}

			});
		};
	};
	$(document).ready(function() {
		init_slick();
	});
	$(window).on('resize', function() {
		init_slick();
	});
});

//menu 
$(function() {
	$('.j-toggle').on('click', function() {
		$('.j-toggle-menu').toggleClass('open');
	});
	$('.j-toggle-close').on('click', function() {
		$('.j-toggle').trigger('click');
	});
});

//Открыть фильтр
$('.j-filter-btn').on('click', function() {
	$(this).hide();
	$('.j-filter').slideToggle();
});

//Смена вида списка 
$('.j-view-list a').on('click', function(e) {
	e.preventDefault();

	var href = $(this).attr('href');

	$('.j-view-list a').removeClass('active');
	$(this).addClass('active');

	if(href == 'list') {
		$('.vacancy').addClass('vacancy--tile');
		document.cookie = "list_view_vacancy=list";
	} else {
		$('.vacancy').removeClass('vacancy--tile');
		document.cookie = "list_view_vacancy=tile";
	}
});

// change list to tile on low resolution;
window.addEventListener("resize", function() {
	if(document.documentElement.clientWidth <= 768) {
		$('.vacancy-fix-resize').removeClass('vacancy--tile');
	} else {
		var last_state = getCookie('list_view_vacancy');
		if(!last_state || last_state == 'list') {
			$('.vacancy-fix-resize').addClass('vacancy--tile');
		} else {
			$('.vacancy-fix-resize').removeClass('vacancy--tile');
		}
	}
});

function getCookie(name) {
	let matches = document.cookie.match(new RegExp(
	  "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
	));
	return matches ? decodeURIComponent(matches[1]) : undefined;
}

//Еще телефоны single.php
$('.j-more').on('click', function() {
	$(this).hide();
	$('.j-more-hide').slideToggle();
});

//header_scroll 
$(document).on('scroll', function() {
	var $scrollTop = $(window).scrollTop(),
		header = $('.j-header'),
		headerRelativeH = $('.header-relative').height();

	if($scrollTop > headerRelativeH + 10) {
		header.addClass('fixed-first');
	} else {
		header.removeClass('fixed-first');
	}
	if($scrollTop > 300) {
		header.addClass('fixed-second');
	} else {
		header.removeClass('fixed-second');
	}
});

//sticky scroll category
$(function () { 
	var controller = new ScrollMagic.Controller();
	var heightSticky = $('.j-sticky').height();
	var heightDuration = $('.j-height-sticky-column').height() - heightSticky - 30;
	var heightHeader = $('.j-header').height();

	if($(window).width() > 767) {
		var scene = new ScrollMagic.Scene({
				triggerElement: ".j-trigger", 
				duration: heightDuration,
				triggerHook: 0,
				offset: - heightHeader,
			})
			.setPin(".j-sticky")
			.addTo(controller);
	}

	function init_sticky() {
		if($(window).width() > 767) {
			heightSticky = $('.j-sticky').height();
			heightDuration = $('.j-height-sticky-column').height() - heightSticky - 30;
			scene.duration(heightDuration);
			scene.update(true);
		} else {
			scene.destroy(true);
		}
	}

	$(window).on('resize', function() {
		init_sticky();
	});
});

//phone mask
// $('.j-phone').inputmask('+38 ' + '(999) 999 - 99 - 99');
$('.j-phone').inputmask('+9{*}');
$('.j-mask-pass').inputmask('9-9-9-9-9-9');
//date mask
$(function() {
	$(".j-date")
		.datepicker()
		.inputmask("99.99.9999");
	$(".j-date-time")
		.datepicker({timepicker: true})
		.inputmask("99.99.9999 99:99");
});

//prop radio
$(function() {
	$('.j-radio-prop').each(function() {
		var _this = $(this);

		if(_this.find('input').prop('checked')) {
			_this.siblings('.j-block-prop').slideToggle('fast');
		}

		$(_this).on('change', function() {
			var _this = $(this);
			if(_this.closest('.j-prop-multiple')) {
				_this.closest('.j-prop-multiple').find('.j-block-prop').slideUp('fast');
				_this.siblings('.j-block-prop').slideToggle('fast');
			} else {
				_this.siblings('.j-block-prop').slideToggle('fast');
			}
			
		});
	});
});


//add input
// $('.j-add-input').on('click', function(e) {
// 	e.preventDefault();

// 	addInput($(this));
// });

// function addInput(element) {
// 	var _this = $(element);
// 	var count = _this.attr('data-key');
// 	var idTemplate = _this.attr('data-id');
// 	var html = $(idTemplate).html();
// 	var template = _.template(html);

// 	var forInput = String(_this.attr('for'));
// 	var oldFor = forInput.replace(/[0-9]/g, '');

// 	count++;

// 	var newElement = template({ key : count});

// 	_this.before(newElement);

// 	if(_this.is('.j-upload')) {
// 		_this.attr('for', oldFor + count);
// 	}

// 	_this.attr('data-key', count);
// 	$('.j-select:not(.nice-select)').niceSelect();
// };

//preview upload
// function uploadImgRegister() {
// 	$('.j-upload').prev('input').on('change', function () {
// 		var _this = $(this),
// 			val = _this.val().toLowerCase(),
// 			preview = $('.upload__preview'),
// 			forVar = _this.next('.j-upload').attr('for'),
// 			id = '#' + forVar,
// 			supportType = String(_this.attr('data-type')),
// 			errorBlock = _this.closest('.j-input').find('.error');

//         if (typeof (FileReader) != "undefined") {
//             var regex = new RegExp("(.*?)\.(" + supportType + ")$");

//            	if (!(regex.test(val))) {

// 	           errorBlock.fadeIn();

// 	        } else {

// 	        	$($(this)[0].files).each(function (event) {
// 	                var file = $(this),
// 	                	reader = new FileReader(),
// 		                name = file[event].name,
// 		                type = file[event].type,
// 		                imgType = ['image/png', 'image/jpeg'];

// 	                if(imgType.indexOf(type) != -1 ) {
// 		                reader.onload = function (e) {
// 		                    var img = $("<img />"),
// 		                    	wrap = $('<div class="upload__item">');
		                    
// 		                    wrap.attr('id', id);
// 		                    img.attr("src", e.target.result);
// 		                    errorBlock.hide();
// 		                    preview.append(wrap.html(img));
// 		                    addInput(_this.next('.j-upload'));
// 		                    uploadImgRegister();
// 		                }
// 		            } else {
// 		            	reader.onload = function (e) {
// 		                    var wrap = $('<div class="upload__item upload__item--text">');
		                    
// 		                    wrap.attr('id', id);
// 		                    errorBlock.hide();
// 		                    preview.append(wrap.html(name));
// 		                    addInput(_this.next('.j-upload'));
// 		                    uploadImgRegister();
// 		                }
// 		            }
// 	                reader.readAsDataURL(file[0]);
// 	            });
// 	        }
//         } 
//     });

//     $('.upload__item').on('click', function() {
//     	var _this = $(this),
//     		id = _this.attr('id');

//     	_this.remove();
//     	$(id).attr({
//     		type: 'hidden',
//     		value: 'delete'
//     	});
//     });
// }
// uploadImgRegister();

//preveiw edit img
$(function() {	
	$('.j-edit-img-btn').on('change', function() {
		var preview = $(this).closest('.j-edit').find('.j-edit-img');

		console.log('asd')
		if (typeof (FileReader) != "undefined") {
            var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.jpg|.jpeg|.gif|.png|.bmp)$/;

            $($(this)[0].files).each(function () {
                var file = $(this);
                var reader = new FileReader();

                reader.onload = function (e) {
                    var img = $("<img />");
                    img.attr("src", e.target.result);
                    preview.html(img);
                }
                reader.readAsDataURL(file[0]);
            });
        } 
	});

	$('.j-edit-img-remove').on('click', function(e) {
		e.preventDefault();

		$(this).closest('.j-edit').find('.j-edit-img').html('');
	});
});

//next step registration
// $(function() {
// 	$(document).ready(function() {
// 		var _marginTop = 0,
// 			header = $('.j-header');

// 		$('.j-next-step').each(function() {
// 			var _this = $(this),
// 				thisStep = _this.closest('.j-step'),
// 				nextStep = thisStep.next('.j-step');

// 			_this.on('click', function() {
// 				_marginTop += thisStep.outerHeight() + 30;
// 				console.log(thisStep.outerHeight() + 30);
// 				thisStep.removeClass('active');

// 				$('.j-step-wrapper').animate({
// 					marginTop: - _marginTop,
// 				}, 1000);

// 				if(nextStep) {
// 					nextStep.fadeIn().addClass('active');
// 				}

// 				$('body, html').animate({
// 					scrollTop: 0,
// 				}, 1000);

// 				header.addClass('fixed-all');
// 			});
// 		});
// 		$('.j-prev-step').each(function() {
// 			var _this = $(this),
// 				thisStep = _this.closest('.j-step'),
// 				prevStep = thisStep.prev('.j-step');

// 			_this.on('click', function() {
// 				_marginTop -= prevStep.outerHeight() + 30;
// 				console.log(thisStep.outerHeight() + 30);
// 				thisStep.fadeOut(1000).removeClass('active');

// 				$('.j-step-wrapper').animate({
// 					marginTop: - _marginTop,
// 				}, 1000);

// 				if(prevStep) {
// 					prevStep.addClass('active');
// 				}

// 				$('body, html').animate({
// 					scrollTop: 0,
// 				}, 1000)
// 			});
// 		});
// 	});
// });

//toggle-block global
// $(function() {
// 	var toggleButton = $('.j-toggle-button'),
// 		toggleBlock = $('.j-toggle-block'),
// 		toggleHide = $('.j-toggle-hide');

// 	toggleButton.on('click', function(e) {
// 		e.preventDefault();

// 		toggleBlock.fadeIn();
// 		toggleHide.hide();
// 	});
// });

//edit show input
// $(function() {
// 	$(document).on('click', '.j-edit-btn', function(e) {
// 		e.preventDefault();

// 		$(this).closest('.j-edit').find('.j-edit-input').removeAttr('readonly').addClass('edit');
// 		$(this).remove();
// 	});

// 	$('.j-edit-remove-value').on('click', function(e) {
// 		e.preventDefault();

// 		$(this).closest('.j-edit').find('.j-edit-input').val('');
// 	});
// });

//scroll anchor
// $(function() {
// 	var headerHeight = $('.j-header').height();

// 	$('.j-scroll').on('click', function(e) {
// 		e.preventDefault();

// 		var href = $(this).attr('href');

// 		$('body, html').animate({
// 			scrollTop: $(href).offset().top - headerHeight - 20,
// 		});
// 	});

// 	$(document).on('scroll', function() {
// 		$('.j-edit').each(function() {
// 			var _this = $(this),
// 				href = _this.attr('id'),
// 				editBlHeight = _this.height();

// 			if(($(window).scrollTop() >= _this.offset().top - headerHeight - 20) && 
// 				($(window).scrollTop() <= editBlHeight + _this.offset().top - headerHeight - 20)) {
// 				$('a[href="#'+href+'"]').addClass('active');
// 			} else {
// 				$('a[href="#'+href+'"]').removeClass('active');
// 			}
// 		});
// 	});
// });

// //accordion global
// $(function() {
// 	var toggleButton = '.j-accordion-btn',
// 		toggleWrapper = '.j-accordion-wrapper',
// 		toggleBlock = '.j-accordion-block';

// 	$(document).on('click', toggleButton, function(e) {
// 		e.preventDefault();

// 		var _this = $(this);

// 		if(!_this.is('.active')) {
// 			$(toggleBlock).slideUp('fast');
// 			$(toggleButton).removeClass('active');
// 			_this.closest(toggleWrapper).find(toggleBlock).slideToggle('fast');
// 			_this.closest(toggleWrapper).find(toggleButton).addClass('active');
// 		} else {
// 			_this.closest(toggleWrapper).find(toggleBlock).slideToggle('fast');
// 			_this.closest(toggleWrapper).find(toggleButton).removeClass('active');
// 		}
// 	});
// });

//sfera prop checked
// $(function() {
// 	var input = '.j-sfera-input',
// 		selectedWrapper = '.j-sfera-selected',
// 		close = '.reg-sfera__close';

// 	$(input).each(function(i) {
// 		$(this).attr('id', 'sfera' + i);
// 	});

// 	$(input).on('change', function() {
// 		var _this = $(this),
// 			value = _this.val(),
// 			id = _this.attr('id');

// 		if(_this.prop('checked')) {
// 			$(selectedWrapper).append('<li class="' + id + '">' + value + '<div class="reg-sfera__close"></div></li>');
// 		} else {
// 			$('.' + id).remove();
// 		}
// 	});

// 	$(document).on('click', '.reg-sfera__close', function() {
// 		var closest = $(this).closest('li'),
// 			id = closest.attr('class');

// 		$('#' + id).prop('checked', false);
// 		$(this).closest('li').remove();
// 	});
// });

//removeBlock
// $(function() {
// 	var removeBtn = '.j-remove',
// 		removeWrapper = '.j-remove-wrapper',
// 		removeBlock = '.j-remove-block';

// 	$(document).on('click', removeBtn, function(e) {
// 		e.preventDefault();

// 		var _this = $(this),
// 			wrapper = _this.closest(removeWrapper),
// 			block = wrapper.find(removeBlock);

// 		if(wrapper.is(removeBlock)) {
// 			wrapper.remove();
// 		} else {
// 			block.remove();
// 		}
// 	});
// });

//prop value language
// $(function() {
// 	var select = '.j-prop-lang',
// 		wrapper = '.j-prop-lang-wrapper';

// 	$(document).on('change', select, function() {
// 		var _this = $(this),
// 			target = _this.attr('data-target'),
// 			value = _this.val(),
// 			wrapperThis = _this.closest(wrapper),
// 			titleThis = wrapperThis.find(target);

// 		titleThis.text(value);
// 	});
// });

//delete value input 
// $(function() {
// 	var input = $('.j-delete-value'),
// 		wrapper = $('.j-delete-wrap'),
// 		close = '.close';

// 	input.on('input', function() {
// 		var _this = $(this),
// 			wrapperThis = _this.closest(wrapper);

// 		console.log(_this.val().length)
// 		if(_this.val().length >= 1) {
// 			if(!wrapperThis.is('.active')) {
// 				wrapperThis.append('<span class="close"></span>');
// 			}
// 			wrapperThis.addClass('active');
// 		} else {
// 			wrapperThis.find('.close').remove();
// 			wrapperThis.removeClass('active');
// 		}
// 	});

// 	$(document).on('click', close, function() {
// 		var _this = $(this),
// 			input = _this.siblings(input),
// 			wrapperThis = _this.closest(wrapper);

// 		input.val('');
// 		wrapperThis.find('.close').remove();
// 		wrapperThis.removeClass('active');
// 	});
// });

//check all checkbox messages
$('.j-check-all').on('change', function() {
	if($(this).prop('checked') == true) {
		$(document).find('.j-check-all-prop').prop('checked', true);
	} else {
		$(document).find('.j-check-all-prop').prop('checked', false);
	}
});


//wysiwyg
// $(function() {
// 	if($('*').is('.j-wysiwyg')) {
// 		ClassicEditor
// 			.create( document.querySelector( '.j-wysiwyg' ), {
// 				toolbar: [ 'heading', '|', 
// 							'bold', 'italic', 'alignment', '|', 
// 							'link', 'bulletedList', 'numberedList', 'blockQuote', '|',
// 							'undo', 'redo' ],
// 				language: 'ru'
// 			} )
// 			.then( editor => {
// 				window.editor = editor;
// 			} )
// 			.catch( err => {
// 				console.error( err.stack );
// 			} );
// 	}
// });

//tooltip
$(function() {
	var tooltip = ('.j-tooltip'),
		tooltipBtn = ('.j-tooltip-btn'),
		tooltipWrapper = ('.j-tooltip-wrapper');

	$(tooltipBtn).on('mouseover', function() {
		$(tooltip).stop().fadeOut('fast');
		$(this).closest(tooltipWrapper).find(tooltip).stop().fadeIn().addClass('show');
	});

	$(document).on('mouseover', function(e) {
		var target = e.target;

		if($(tooltip).is('.show') && !$(target).is(tooltipBtn)) {
			$(tooltip).stop().fadeOut('fast');
		}
	});
});

//rating reviews
// $(function() {
// 	var minus = '.j-minus',
// 		plus = '.j-plus',
// 		input = '.j-count-input',
// 		wrapper = '.j-rating-bl';

// 	//stars rating
// 	$('.j-rating').each(function() {
// 		var _this = $(this),
// 			_input = _this.closest(wrapper).find(input);

// 		_this.barrating({
// 			theme: 'css-stars',
// 			onSelect: function(value, text, event) {
// 				_input.text(value);
// 			}
// 		});
// 	});

// 	$(minus).on('click', function() {
// 		var _this = $(this),
// 			val = _this.siblings(input).text();

// 		if(val > 1) {
// 			_this.siblings(input).text(val-1);
// 			changeInput(_this);
// 		}
// 	});
// 	$(plus).on('click', function() {
// 		var _this = $(this),
// 			val = _this.siblings(input).text();

// 		if(val < 5) {
// 			_this.siblings(input).text(Number(val)+1);
// 			changeInput(_this);
// 		}
// 	});

// 	function changeInput(element) {
// 		var _this = $(element),
// 			rating = _this.closest(wrapper).find('.j-rating');
// 			value = _this.siblings(input).text();

// 		$(rating).barrating('set', value);
// 	}
// });