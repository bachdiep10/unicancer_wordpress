/**
 * FAQ Accordion
 *
 * @package UNI_ASIA
 */

(function () {
	'use strict';

	document.addEventListener('DOMContentLoaded', function () {
		var faqButtons = document.querySelectorAll('.faq-question');

		faqButtons.forEach(function (btn) {
			btn.addEventListener('click', function () {
				var item = btn.closest('.faq-item');
				var isOpen = item.classList.contains('is-open');
				var answer = item.querySelector('.faq-answer');

				// Toggle this item
				if (isOpen) {
					item.classList.remove('is-open');
					btn.setAttribute('aria-expanded', 'false');
					answer.style.maxHeight = null;
				} else {
					item.classList.add('is-open');
					btn.setAttribute('aria-expanded', 'true');
					answer.style.maxHeight = answer.scrollHeight + 'px';
				}

				// Close other items in same accordion
				var accordion = item.closest('.faq-accordion');
				if (accordion) {
					accordion.querySelectorAll('.faq-item').forEach(function (otherItem) {
						if (otherItem !== item && otherItem.classList.contains('is-open')) {
							otherItem.classList.remove('is-open');
							otherItem.querySelector('.faq-question').setAttribute('aria-expanded', 'false');
							otherItem.querySelector('.faq-answer').style.maxHeight = null;
						}
					});
				}
			});
		});

		// Open first FAQ by default
		var firstFaq = document.querySelector('.faq-item');
		if (firstFaq) {
			var firstAnswer = firstFaq.querySelector('.faq-answer');
			if (firstAnswer && firstFaq.classList.contains('is-open')) {
				firstAnswer.style.maxHeight = firstAnswer.scrollHeight + 'px';
			}
		}

		// Recalculate on window resize
		var resizeTimer;
		window.addEventListener('resize', function () {
			clearTimeout(resizeTimer);
			resizeTimer = setTimeout(function () {
				document.querySelectorAll('.faq-item.is-open').forEach(function (item) {
					var answer = item.querySelector('.faq-answer');
					answer.style.maxHeight = answer.scrollHeight + 'px';
				});
			}, 250);
		});

	});

})();