/**
 * DentoMart front-end behaviour — DentalKart 1:1 match.
 * - Hero multi-banner auto-slider with touch swipe
 * - Horizontal category & product carousels
 * - Flash deals live countdown timer
 * - Mobile slide-in menu
 * - Sticky header state
 * - Single product: gallery, tabs, variant chips, pincode check
 */
(function () {
	'use strict';

	/**
	 * Mobile menu.
	 */
	function initMobileMenu() {
		var toggle = document.querySelector('.dm-mobile-toggle');
		var menu = document.getElementById('dmMobileMenu');
		var closers = menu ? menu.querySelectorAll('[data-dm-close]') : [];

		if (!toggle || !menu) {
			return;
		}

		function openMenu() {
			menu.setAttribute('aria-hidden', 'false');
			toggle.setAttribute('aria-expanded', 'true');
			document.body.classList.add('dm-menu-open');
		}

		function closeMenu() {
			menu.setAttribute('aria-hidden', 'true');
			toggle.setAttribute('aria-expanded', 'false');
			document.body.classList.remove('dm-menu-open');
		}

		toggle.addEventListener('click', function () {
			if ('false' === menu.getAttribute('aria-hidden')) {
				closeMenu();
			} else {
				openMenu();
			}
		});

		closers.forEach(function (closer) {
			closer.addEventListener('click', closeMenu);
		});

		document.addEventListener('keydown', function (event) {
			if ('Escape' === event.key && 'false' === menu.getAttribute('aria-hidden')) {
				closeMenu();
			}
		});
	}

	/**
	 * Hero Multi-Banner Slider with auto-play, touch swipe, dots and arrows.
	 */
	function initHeroSlider() {
		var slider = document.querySelector('[data-dm-hero-slider]');
		if (!slider) {
			return;
		}

		var slides = slider.querySelectorAll('.dm-hero-slide');
		var dots = slider.querySelectorAll('.dm-hero-dot');
		var prevBtn = slider.querySelector('[data-dm-hero-prev]');
		var nextBtn = slider.querySelector('[data-dm-hero-next]');

		if (slides.length <= 1) {
			return;
		}

		var currentIndex = 0;
		var autoPlayTimer = null;
		var intervalMs = 5000;

		function goToSlide(index) {
			if (index < 0) {
				index = slides.length - 1;
			} else if (index >= slides.length) {
				index = 0;
			}

			slides.forEach(function (slide, i) {
				slide.classList.toggle('is-active', i === index);
			});

			dots.forEach(function (dot, i) {
				dot.classList.toggle('is-active', i === index);
			});

			currentIndex = index;
		}

		function nextSlide() {
			goToSlide(currentIndex + 1);
		}

		function prevSlide() {
			goToSlide(currentIndex - 1);
		}

		function startAutoPlay() {
			stopAutoPlay();
			autoPlayTimer = setInterval(nextSlide, intervalMs);
		}

		function stopAutoPlay() {
			if (autoPlayTimer) {
				clearInterval(autoPlayTimer);
				autoPlayTimer = null;
			}
		}

		if (prevBtn) {
			prevBtn.addEventListener('click', function () {
				prevSlide();
				startAutoPlay();
			});
		}

		if (nextBtn) {
			nextBtn.addEventListener('click', function () {
				nextSlide();
				startAutoPlay();
			});
		}

		dots.forEach(function (dot, i) {
			dot.addEventListener('click', function () {
				goToSlide(i);
				startAutoPlay();
			});
		});

		slider.addEventListener('mouseenter', stopAutoPlay);
		slider.addEventListener('mouseleave', startAutoPlay);

		// Touch swipe support
		var touchStartX = 0;
		var touchEndX = 0;

		slider.addEventListener('touchstart', function (e) {
			touchStartX = e.changedTouches[0].screenX;
			stopAutoPlay();
		}, { passive: true });

		slider.addEventListener('touchend', function (e) {
			touchEndX = e.changedTouches[0].screenX;
			var diff = touchStartX - touchEndX;
			if (Math.abs(diff) > 40) {
				if (diff > 0) {
					nextSlide();
				} else {
					prevSlide();
				}
			}
			startAutoPlay();
		}, { passive: true });

		startAutoPlay();
	}

	/**
	 * Flash Deals Countdown Timer.
	 */
	function initCountdownTimer() {
		var timerEl = document.querySelector('[data-dm-countdown]');
		if (!timerEl) {
			return;
		}

		var hoursEl = timerEl.querySelector('[data-dm-hours]');
		var minutesEl = timerEl.querySelector('[data-dm-minutes]');
		var secondsEl = timerEl.querySelector('[data-dm-seconds]');

		if (!hoursEl || !minutesEl || !secondsEl) {
			return;
		}

		// Count down to midnight or rolling 12h window
		var totalSeconds = (8 * 3600) + (42 * 60) + 19;

		function updateTimer() {
			if (totalSeconds > 0) {
				totalSeconds--;
			} else {
				totalSeconds = 12 * 3600; // Reset
			}

			var h = Math.floor(totalSeconds / 3600);
			var m = Math.floor((totalSeconds % 3600) / 60);
			var s = totalSeconds % 60;

			hoursEl.textContent = (h < 10 ? '0' : '') + h;
			minutesEl.textContent = (m < 10 ? '0' : '') + m;
			secondsEl.textContent = (s < 10 ? '0' : '') + s;
		}

		setInterval(updateTimer, 1000);
		updateTimer();
	}

	/**
	 * Universal Horizontal scroll carousels with prev/next buttons.
	 */
	function initCarousels() {
		var controls = document.querySelectorAll('[data-dm-scroll-controls]');
		if (!controls.length) {
			return;
		}

		controls.forEach(function (group) {
			var trackId = group.getAttribute('data-dm-scroll-controls');
			var track = document.getElementById(trackId);
			var prev = group.querySelector('[data-dm-scroll="prev"]');
			var next = group.querySelector('[data-dm-scroll="next"]');

			if (!track || !prev || !next) {
				return;
			}

			function updateButtons() {
				var maxScroll = track.scrollWidth - track.clientWidth - 8;
				prev.disabled = track.scrollLeft <= 6;
				next.disabled = track.scrollLeft >= maxScroll;
			}

			function getStep() {
				var card = track.querySelector('.dm-slider-item, .dm-category-circle-card, .dm-brand-card, .dm-product-card');
				var gap = 16;
				if (card) {
					var w = card.getBoundingClientRect().width;
					return (w + gap) * 2;
				}
				return track.clientWidth * 0.75;
			}

			prev.addEventListener('click', function () {
				track.scrollBy({ left: -getStep(), behavior: 'smooth' });
			});

			next.addEventListener('click', function () {
				track.scrollBy({ left: getStep(), behavior: 'smooth' });
			});

			track.addEventListener('scroll', updateButtons, { passive: true });
			window.addEventListener('resize', updateButtons);
			setTimeout(updateButtons, 100);
		});
	}

	/**
	 * Sticky header scrolled state.
	 */
	function initStickyState() {
		var sticky = document.getElementById('dmHeaderSticky');
		if (!sticky) {
			return;
		}

		function onScroll() {
			sticky.classList.toggle('is-scrolled', window.scrollY > 12);
		}

		window.addEventListener('scroll', onScroll, { passive: true });
		onScroll();
	}

	/**
	 * Refresh cart count badge across AJAX add to cart.
	 */
	function initCartCount() {
		document.body.addEventListener('added_to_cart', function () {
			var badge = document.querySelector('.dm-cart-count');
			if (badge) {
				badge.removeAttribute('data-empty');
			}
		});
	}

	/**
	 * Product gallery: click thumbnail to swap the main image.
	 */
	function initProductGallery() {
		var thumbs = document.querySelectorAll('.dm-product__gallery-thumb');
		var slides = document.querySelectorAll('.dm-product__gallery-slide');
		if (!thumbs.length || !slides.length) {
			return;
		}

		thumbs.forEach(function (thumb) {
			thumb.addEventListener('click', function () {
				var idx = thumb.getAttribute('data-thumb');
				slides.forEach(function (s) { s.classList.toggle('is-active', s.getAttribute('data-slide') === idx); });
				thumbs.forEach(function (t) { t.classList.toggle('is-active', t === thumb); });
			});
		});
	}

	/**
	 * Product tabs.
	 */
	function initProductTabs() {
		var buttons = document.querySelectorAll('.dm-product__tab-btn');
		var panes   = document.querySelectorAll('.dm-product__tab-pane');
		if (!buttons.length) {
			return;
		}

		function activate(tab) {
			buttons.forEach(function (b) {
				var match = b.getAttribute('data-tab') === tab;
				b.classList.toggle('is-active', match);
				b.setAttribute('aria-selected', match ? 'true' : 'false');
			});
			panes.forEach(function (p) {
				p.classList.toggle('is-active', p.getAttribute('data-pane') === tab);
			});
		}

		buttons.forEach(function (btn) {
			btn.addEventListener('click', function () {
				activate(btn.getAttribute('data-tab'));
			});
		});
	}

	/**
	 * Variant chips.
	 */
	function initVariantChips() {
		var variants = document.querySelectorAll('.dm-product__variant');
		if (!variants.length) {
			return;
		}

		variants.forEach(function (variant) {
			var chips = variant.querySelectorAll('.dm-product__chip');
			var valueEl = variant.querySelector('.dm-product__variant-value');
			var attrKey = variant.getAttribute('data-attribute');
			chips.forEach(function (chip) {
				chip.addEventListener('click', function () {
					chips.forEach(function (c) { c.classList.toggle('is-active', c === chip); });
					if (valueEl) {
						valueEl.textContent = chip.textContent.trim();
					}
					var select = document.querySelector('select[name="' + attrKey + '"]');
					if (select) {
						select.value = chip.getAttribute('data-term-slug');
						select.dispatchEvent(new Event('change', { bubbles: true }));
					}
				});
			});
		});
	}

	/**
	 * Pincode check & Header Location Modal (Admin-managed & persistent).
	 */
	function initPincodeCheck() {
		var settings = (window.dentomartData && window.dentomartData.pincodes) ? window.dentomartData.pincodes : {
			mode: 'all',
			express_pincodes: '110001, 110002, 400001, 400002, 560001, 600001, 700001, 500001',
			standard_pincodes: '',
			express_msg: '✓ Express Clinic Delivery available (Dispatch in 24–48 hrs).',
			standard_msg: '✓ Standard Clinic Delivery available (Dispatch in 3–5 business days).',
			unserviceable_msg: '✕ Delivery currently unavailable for this PIN code.'
		};

		// Single Product Elements
		var prodInput   = document.getElementById('dm-pincode');
		var prodBtn     = document.querySelector('.dm-product__pincode-submit-btn, .dm-product__pincode-btn');
		var prodOut     = document.querySelector('.dm-product__pincode-result');
		var prodChange  = document.querySelector('.dm-product__pincode-change-btn, .dm-product__pincode-change');
		var prodCurrent = document.querySelector('.dm-product__pincode-current');

		// Header & Modal Elements
		var headerPincodeBtn = document.querySelector('.dm-masthead__pincode-btn, [data-dm-pincode-header]');
		var headerModal      = document.getElementById('dmPincodeHeaderModal');
		var headerForm       = headerModal ? headerModal.querySelector('[data-pincode-modal-form]') : null;
		var headerInput      = headerModal ? headerModal.querySelector('#dm_header_pincode_input') : null;
		var headerResult     = headerModal ? headerModal.querySelector('[data-pincode-modal-result]') : null;
		var headerClosers    = headerModal ? headerModal.querySelectorAll('[data-dm-close-pincode]') : [];
		var headerResetBtn   = headerModal ? headerModal.querySelector('[data-pincode-reset]') : null;

		function parseList(str) {
			if (!str) return [];
			return str.split(/[\s,]+/).map(function (s) { return s.trim(); }).filter(Boolean);
		}

		var expressList  = parseList(settings.express_pincodes);
		var standardList = parseList(settings.standard_pincodes);

		function verifyCode(code) {
			code = (code || '').replace(/\D/g, '');
			if (code.length !== 6) {
				return { valid: false, message: 'Please enter a valid 6-digit PIN code.', status: 'invalid' };
			}

			if (expressList.indexOf(code) !== -1) {
				return { valid: true, message: settings.express_msg || '✓ Express Clinic Delivery available (Dispatch in 24–48 hrs).', status: 'express' };
			}

			if (settings.mode === 'specific') {
				if (standardList.indexOf(code) !== -1) {
					return { valid: true, message: settings.standard_msg || '✓ Standard Clinic Delivery available (Dispatch in 3–5 business days).', status: 'standard' };
				}
				return { valid: false, message: settings.unserviceable_msg || '✕ Delivery currently unavailable for this PIN code.', status: 'unserviceable' };
			}

			return { valid: true, message: settings.standard_msg || '✓ Standard Clinic Delivery available (Dispatch in 3–5 business days).', status: 'standard' };
		}

		function applyPincodeUI(code, res) {
			// Save in localStorage & Cookie
			if (code && res.valid) {
				try {
					localStorage.setItem('dm_user_pincode', code);
				} catch (e) {}
				document.cookie = 'dm_user_pincode=' + code + '; path=/; max-age=31536000';
			}

			// Update Header Display
			var btnLabel = document.querySelector('.dm-masthead__pincode-btn');
			if (btnLabel) {
				btnLabel.textContent = code ? ('Delivering to ' + code) : 'Enter Pincode';
			}

			// Update Product Page Card
			if (prodCurrent) {
				prodCurrent.textContent = code || '-';
			}
			if (prodOut && res) {
				prodOut.textContent = res.message;
				prodOut.style.color = res.valid ? (res.status === 'express' ? '#0d9488' : '#1D70CA') : '#b45309';
			}

			// Update Header Modal State
			if (headerInput && code) {
				headerInput.value = code;
			}
			if (headerResult && res) {
				headerResult.style.display = 'block';
				headerResult.textContent = res.message;
				headerResult.style.color = res.valid ? '#10b981' : '#ef4444';
			}
			if (headerResetBtn) {
				headerResetBtn.style.display = code ? 'inline-block' : 'none';
			}
		}

		function openHeaderModal() {
			if (!headerModal) return;
			var saved = '';
			try { saved = localStorage.getItem('dm_user_pincode') || ''; } catch (e) {}
			if (saved) {
				var res = verifyCode(saved);
				applyPincodeUI(saved, res);
			}
			headerModal.classList.add('is-open');
			headerModal.setAttribute('aria-hidden', 'false');
			document.body.classList.add('dm-modal-open');
			if (headerInput) {
				setTimeout(function () { headerInput.focus(); }, 150);
			}
		}

		function closeHeaderModal() {
			if (!headerModal) return;
			headerModal.classList.remove('is-open');
			headerModal.setAttribute('aria-hidden', 'true');
			document.body.classList.remove('dm-modal-open');
		}

		// Attach Header Trigger
		if (headerPincodeBtn) {
			headerPincodeBtn.addEventListener('click', function (e) {
				e.preventDefault();
				openHeaderModal();
			});
		}

		headerClosers.forEach(function (c) {
			c.addEventListener('click', function (e) {
				e.preventDefault();
				closeHeaderModal();
			});
		});

		// Header Modal Form Submit
		if (headerForm) {
			headerForm.addEventListener('submit', function (e) {
				e.preventDefault();
				var code = headerInput.value.replace(/\D/g, '');
				var res  = verifyCode(code);
				applyPincodeUI(res.valid ? code : '', res);

				if (res.valid) {
					setTimeout(function () {
						closeHeaderModal();
					}, 800);
				}
			});
		}

		// Reset Pincode
		if (headerResetBtn) {
			headerResetBtn.addEventListener('click', function (e) {
				e.preventDefault();
				try { localStorage.removeItem('dm_user_pincode'); } catch (e) {}
				document.cookie = 'dm_user_pincode=; path=/; max-age=0';
				applyPincodeUI('', { valid: false, message: 'Enter pincode to verify fast clinic delivery & dispatch speed.', status: 'none' });
				if (headerInput) headerInput.value = '';
				if (headerResult) headerResult.style.display = 'none';
				closeHeaderModal();
			});
		}

		// Single Product Pincode Card Handlers
		if (prodBtn && prodInput) {
			prodBtn.addEventListener('click', function (e) {
				e.preventDefault();
				var code = prodInput.value.replace(/\D/g, '');
				var res  = verifyCode(code);
				applyPincodeUI(res.valid ? code : '', res);
				if (res.valid) {
					prodInput.value = '';
				}
			});

			prodInput.addEventListener('keydown', function (e) {
				if (e.key === 'Enter') {
					e.preventDefault();
					prodBtn.click();
				}
			});
		}

		if (prodChange) {
			prodChange.addEventListener('click', function (e) {
				e.preventDefault();
				openHeaderModal();
			});
		}

		// Load Saved Pincode on Init
		var savedCode = '';
		try { savedCode = localStorage.getItem('dm_user_pincode') || ''; } catch (e) {}
		if (savedCode) {
			var savedRes = verifyCode(savedCode);
			applyPincodeUI(savedCode, savedRes);
		}
	}


	/**
	 * Wholesale / Bulk pricing tiers selection & live cart price update.
	 */
	function initWholesaleTiers() {
		var wholesaleBox = document.querySelector('[data-wholesale-box]');
		if (!wholesaleBox) {
			return;
		}

		var tierItems = wholesaleBox.querySelectorAll('.dm-product__tier-item');
		var qtyInput = document.querySelector('input[name="quantity"], input.qty');
		var priceCard = document.querySelector('[data-price-card]');
		var sellingPriceEl = document.querySelector('[data-selling-price]');
		var tierNotice = document.querySelector('[data-tier-notice]');
		var tierNoticeText = document.querySelector('[data-tier-notice-text]');

		if (!tierItems.length) {
			return;
		}

		// Store base price from price card or initial text
		var basePrice = priceCard ? parseFloat(priceCard.getAttribute('data-base-price')) : 0;
		if (isNaN(basePrice) || basePrice <= 0) {
			basePrice = 0;
		}

		function formatCurrency(val) {
			try {
				return new Intl.NumberFormat('en-IN', {
					style: 'currency',
					currency: 'INR',
					maximumFractionDigits: 2
				}).format(val);
			} catch (e) {
				return '₹' + val.toFixed(2);
			}
		}

		function updateTierState(currentQty) {
			currentQty = parseInt(currentQty, 10) || 1;
			var activeTier = null;

			// Sort tiers descending to match highest applicable tier first (e.g. 10+, then 5+)
			var tierList = Array.from(tierItems).map(function (item) {
				return {
					el: item,
					qty: parseInt(item.getAttribute('data-tier-qty'), 10) || 0,
					price: parseFloat(item.getAttribute('data-tier-price')) || 0,
					save: item.getAttribute('data-tier-save') || '0',
					btn: item.querySelector('.dm-product__tier-btn')
				};
			}).sort(function (a, b) { return b.qty - a.qty; });

			// Find matching tier
			for (var i = 0; i < tierList.length; i++) {
				if (currentQty >= tierList[i].qty) {
					activeTier = tierList[i];
					break;
				}
			}

			// Update UI active state for each tier card
			tierList.forEach(function (t) {
				var isMatch = activeTier && (activeTier.qty === t.qty);
				t.el.classList.toggle('is-selected', isMatch);
				if (t.btn) {
					if (isMatch) {
						t.btn.textContent = '✓ Selected';
						t.btn.classList.add('is-selected');
					} else {
						t.btn.textContent = 'Select ' + t.qty;
						t.btn.classList.remove('is-selected');
					}
				}
			});

			// Update dynamic notice & selling price display if applicable
			if (activeTier && activeTier.price > 0) {
				if (tierNotice && tierNoticeText) {
					tierNotice.style.display = 'flex';
					tierNoticeText.textContent = 'Wholesale Bulk Tier: Extra ' + activeTier.save + '% OFF applied (' + formatCurrency(activeTier.price) + ' / unit)';
				}
			} else {
				if (tierNotice) {
					tierNotice.style.display = 'none';
				}
			}
		}

		// Click handlers for tier cards and buttons
		tierItems.forEach(function (item) {
			var targetQty = parseInt(item.getAttribute('data-tier-qty'), 10);
			if (!targetQty) {
				return;
			}

			function selectTier(e) {
				e.preventDefault();
				if (qtyInput) {
					qtyInput.value = targetQty;
					qtyInput.dispatchEvent(new Event('change', { bubbles: true }));
					qtyInput.dispatchEvent(new Event('input', { bubbles: true }));
				}
				updateTierState(targetQty);
			}

			item.addEventListener('click', selectTier);
		});

		// Sync with quantity input changes
		if (qtyInput) {
			['change', 'input'].forEach(function (evtName) {
				qtyInput.addEventListener(evtName, function () {
					updateTierState(qtyInput.value);
				});
			});
			// Initial check
			updateTierState(qtyInput.value);
		}
	}

	/**
	 * Bulk Quote Modal form handler.
	 */
	function initBulkQuoteModal() {
		var modal = document.getElementById('dmBulkQuoteModal');
		if (!modal) {
			return;
		}

		var triggers = document.querySelectorAll('[data-action="bulk-quote"], [data-action="suggest"]');
		var closers  = modal.querySelectorAll('[data-dm-close-quote]');
		var form     = modal.querySelector('[data-quote-form]');
		var success  = modal.querySelector('[data-quote-success]');
		var successMsg = modal.querySelector('[data-quote-success-msg]');

		// Attached product card elements in modal
		var imgEl   = modal.querySelector('[data-quote-product-img]');
		var brandEl = modal.querySelector('[data-quote-product-brand]');
		var titleEl = modal.querySelector('[data-quote-product-title]');
		var skuEl   = modal.querySelector('[data-quote-product-sku]');
		var priceEl = modal.querySelector('[data-quote-product-price]');
		var hiddenId = modal.querySelector('[data-quote-input-id]');
		var hiddenTitle = modal.querySelector('[data-quote-input-title]');
		var qtyInput = modal.querySelector('#dm_quote_qty');

		function openModal(trigger) {
			var pId    = trigger.getAttribute('data-product-id') || '';
			var pTitle = trigger.getAttribute('data-product-title') || document.title;
			var pSku   = trigger.getAttribute('data-product-sku') || 'N/A';
			var pBrand = trigger.getAttribute('data-product-brand') || '';
			var pImg   = trigger.getAttribute('data-product-img') || '';
			var pPrice = trigger.getAttribute('data-product-price') || '';

			// Read selected quantity on single product page if present
			var pageQtyEl = document.querySelector('input[name="quantity"], input.qty');
			var pageQty = pageQtyEl ? parseInt(pageQtyEl.value, 10) : 50;
			var initialQty = (pageQty && pageQty >= 50) ? pageQty : 50;

			if (imgEl && pImg) {
				imgEl.src = pImg;
				imgEl.alt = pTitle;
				imgEl.parentElement.style.display = 'block';
			} else if (imgEl) {
				imgEl.parentElement.style.display = 'none';
			}

			if (brandEl) { brandEl.textContent = pBrand; }
			if (titleEl) { titleEl.textContent = pTitle; }
			if (skuEl)   { skuEl.textContent   = pSku; }
			if (priceEl) { priceEl.textContent = pPrice; }
			if (hiddenId) { hiddenId.value = pId; }
			if (hiddenTitle) { hiddenTitle.value = pTitle; }
			if (qtyInput) { qtyInput.value = initialQty; }

			// Reset form state
			if (form) { form.style.display = 'block'; }
			if (success) { success.style.display = 'none'; }

			modal.classList.add('is-open');
			modal.setAttribute('aria-hidden', 'false');
			document.body.classList.add('dm-modal-open');
		}

		function closeModal() {
			modal.classList.remove('is-open');
			modal.setAttribute('aria-hidden', 'true');
			document.body.classList.remove('dm-modal-open');
		}

		triggers.forEach(function (btn) {
			btn.addEventListener('click', function (e) {
				e.preventDefault();
				openModal(btn);
			});
		});

		closers.forEach(function (c) {
			c.addEventListener('click', function (e) {
				e.preventDefault();
				closeModal();
			});
		});

		document.addEventListener('keydown', function (e) {
			if (e.key === 'Escape' && modal.classList.contains('is-open')) {
				closeModal();
			}
		});

		if (form) {
			form.addEventListener('submit', function (e) {
				e.preventDefault();
				var nameVal = modal.querySelector('#dm_quote_name').value.trim();
				var qtyVal  = qtyInput ? qtyInput.value : 50;
				var pTitle  = hiddenTitle ? hiddenTitle.value : 'selected item';

				form.style.display = 'none';
				if (success) {
					success.style.display = 'block';
				}
				if (successMsg) {
					successMsg.textContent = 'Thank you, ' + (nameVal || 'Doctor') + '! Your institutional quote request for "' + pTitle + '" (' + qtyVal + ' units) has been submitted successfully. Our wholesale desk will contact you within 24 hours.';
				}
			});
		}
	}

	function init() {
		initMobileMenu();
		initHeroSlider();
		initCountdownTimer();
		initCarousels();
		initStickyState();
		initCartCount();
		initProductGallery();
		initProductTabs();
		initVariantChips();
		initPincodeCheck();
		initWholesaleTiers();
		initBulkQuoteModal();
	}

	document.addEventListener('DOMContentLoaded', init);
})();


