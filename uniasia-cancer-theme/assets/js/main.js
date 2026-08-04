/**
 * UNI-ASIA Cancer Theme - Main JavaScript
 *
 * @package UNI_ASIA
 */

(function ($) {
	'use strict';

	$(document).ready(function () {

		// ======================================
		// Mobile Menu Toggle
		// ======================================
		$('.menu-toggle').on('click', function () {
			var $toggle = $(this);
			var $nav = $('.main-navigation');
			var isOpen = $toggle.attr('aria-expanded') === 'true';

			$toggle.attr('aria-expanded', !isOpen);
			$nav.toggleClass('is-open');
		});

		// Close mobile menu when clicking outside
		$(document).on('click', function (e) {
			if (!$(e.target).closest('.main-navigation, .menu-toggle').length) {
				$('.main-navigation').removeClass('is-open');
				$('.menu-toggle').attr('aria-expanded', 'false');
			}
		});

		// ======================================
		// Back to Top Button
		// ======================================
		var $backToTop = $('.back-to-top');
		$(window).on('scroll', function () {
			if ($(this).scrollTop() > 300) {
				$backToTop.addClass('is-visible');
			} else {
				$backToTop.removeClass('is-visible');
			}
		});

		$backToTop.on('click', function () {
			$('html, body').animate({ scrollTop: 0 }, 600);
			return false;
		});

		// ======================================
		// Smooth Scroll for Anchor Links
		// ======================================
		$('a[href*="#"]').on('click', function (e) {
			var target = $(this.getAttribute('href'));
			if (target.length) {
				e.preventDefault();
				$('html, body').animate({
					scrollTop: target.offset().top - 80
				}, 600);
			}
		});

		// ======================================
		// Treatment Tech Tabs
		// ======================================
		$('.tech-tab-btn').on('click', function () {
			var tabId = $(this).data('tab');
			var $tabs = $(this).closest('.tech-tabs');

			$tabs.find('.tech-tab-btn').removeClass('active');
			$tabs.find('.tech-tab-pane').removeClass('active');
			$(this).addClass('active');
			$tabs.find('#' + tabId).addClass('active');
		});

		// ======================================
		// Consultation Form Submission
		// ======================================
		$('#uniasia-consultation-form').on('submit', function (e) {
			e.preventDefault();

			var $form = $(this);
			var $submit = $form.find('.btn-submit');
			var $btnText = $submit.find('.btn-text');
			var $btnLoading = $submit.find('.btn-loading');
			var $message = $form.find('.form-message');

			// Disable submit
			$submit.prop('disabled', true);
			$btnText.hide();
			$btnLoading.show();

			// Collect data
			var formData = {
				action: 'uniasia_submit_consultation',
				nonce: $form.find('[name="nonce"]').val(),
				name: $form.find('[name="name"]').val(),
				age: $form.find('[name="age"]').val(),
				phone: $form.find('[name="phone"]').val(),
				email: $form.find('[name="email"]').val(),
				message: $form.find('[name="message"]').val(),
			};

			$.ajax({
				url: uniasiaData.ajaxUrl,
				type: 'POST',
				data: formData,
				success: function (response) {
					if (response.success) {
						$message
							.removeClass('error')
							.addClass('success')
							.text(response.data.message)
							.fadeIn();
						$form[0].reset();
					} else {
						$message
							.removeClass('success')
							.addClass('error')
							.text(response.data.message || uniasiaData.i18n.submitError)
							.fadeIn();
					}
				},
				error: function () {
					$message
						.removeClass('success')
						.addClass('error')
						.text(uniasiaData.i18n.submitError)
						.fadeIn();
				},
				complete: function () {
					$submit.prop('disabled', false);
					$btnText.show();
					$btnLoading.hide();
				},
			});
		});

		// ======================================
		// Simple AOS (Animate on Scroll)
		// ======================================
		if ('IntersectionObserver' in window) {
			var aosObserver = new IntersectionObserver(function (entries) {
				entries.forEach(function (entry) {
					if (entry.isIntersecting) {
						entry.target.classList.add('aos-animate');
						aosObserver.unobserve(entry.target);
					}
				});
			}, { threshold: 0.1 });

			document.querySelectorAll('[data-aos]').forEach(function (el) {
				aosObserver.observe(el);
			});
		} else {
			$('[data-aos]').addClass('aos-animate');
		}

		// ======================================
		// Lazy Load Images Fallback
		// ======================================
		if ('loading' in HTMLImageElement.prototype) {
			document.querySelectorAll('img[loading="lazy"]').forEach(function (img) {
				if (img.dataset.src) {
					img.src = img.dataset.src;
				}
			});
		}

		// ======================================
		// Sticky Header on Scroll
		// ======================================
		var header = document.getElementById('masthead');
		var lastScrollTop = 0;

		$(window).on('scroll', function () {
			var st = $(this).scrollTop();
			if (st > 100) {
				$(header).addClass('is-scrolled');
			} else {
				$(header).removeClass('is-scrolled');
			}
			lastScrollTop = st;
		});

	});

})(jQuery);