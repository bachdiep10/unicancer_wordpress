/**
 * Swiper Initialization
 *
 * @package UNI_ASIA
 */

(function () {
	'use strict';

	document.addEventListener('DOMContentLoaded', function () {

		// MDT Team Slider
		if (typeof Swiper !== 'undefined' && document.getElementById('mdtSwiper')) {
			new Swiper('#mdtSwiper', {
				slidesPerView: 1,
				spaceBetween: 24,
				loop: true,
				autoplay: {
					delay: 5000,
					disableOnInteraction: false,
					pauseOnMouseEnter: true,
				},
				pagination: {
					el: '#mdtSwiper .swiper-pagination',
					clickable: true,
				},
				navigation: {
					prevEl: '#mdtSwiper .swiper-button-prev',
					nextEl: '#mdtSwiper .swiper-button-next',
				},
				breakpoints: {
					640: {
						slidesPerView: 2,
					},
					992: {
						slidesPerView: 3,
					},
					1200: {
						slidesPerView: 4,
					},
				},
			});
		}

		// Patient Stories Slider
		if (typeof Swiper !== 'undefined' && document.getElementById('storiesSwiper')) {
			new Swiper('#storiesSwiper', {
				slidesPerView: 1,
				spaceBetween: 24,
				loop: true,
				autoplay: {
					delay: 6000,
					disableOnInteraction: false,
					pauseOnMouseEnter: true,
				},
				pagination: {
					el: '#storiesSwiper .swiper-pagination',
					clickable: true,
				},
				breakpoints: {
					640: {
						slidesPerView: 2,
					},
					992: {
						slidesPerView: 3,
					},
					1200: {
						slidesPerView: 3,
					},
				},
			});
		}

		// Doctor Archive Slider (if used)
		if (typeof Swiper !== 'undefined' && document.getElementById('doctorsSwiper')) {
			new Swiper('#doctorsSwiper', {
				slidesPerView: 1,
				spaceBetween: 24,
				loop: true,
				pagination: {
					el: '#doctorsSwiper .swiper-pagination',
					clickable: true,
				},
				breakpoints: {
					640: { slidesPerView: 2 },
					992: { slidesPerView: 3 },
					1200: { slidesPerView: 4 },
				},
			});
		}

	});

})();