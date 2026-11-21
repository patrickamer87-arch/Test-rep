/**
 * Maison Square: the menu drawer, the home banners and the product page bar.
 * The banners are the only thing on the page that moves by itself.
 */
( function () {
	'use strict';

	var doc = document;
	var drawer = doc.getElementById( 'ms-drawer' );
	var openers = doc.querySelectorAll( '[data-ms-drawer-open]' );
	var lastFocus = null;
	var reduced = !! ( window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches );

	function each( list, fn ) {
		Array.prototype.forEach.call( list, fn );
	}

	function onMedia( query, fn ) {
		if ( query.addEventListener ) {
			query.addEventListener( 'change', fn );
		} else if ( query.addListener ) {
			query.addListener( fn );
		}
	}

	/* Menu drawer ---------------------------------------------------------- */

	function setDrawer( open, toSearch ) {
		if ( ! drawer || drawer.hidden === ! open ) {
			return;
		}

		drawer.hidden = ! open;
		doc.body.classList.toggle( 'ms-drawer-open', open );
		each( openers, function ( button ) {
			button.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
		} );

		if ( open ) {
			lastFocus = doc.activeElement;
			// The search button in the header opens the drawer on its search field.
			var target = toSearch ? drawer.querySelector( 'input[type="search"]' ) : null;
			if ( ! target ) {
				target = drawer.querySelector( '[data-ms-drawer-close]' );
			}
			if ( target ) {
				target.focus();
			}
		} else if ( lastFocus && lastFocus.focus ) {
			lastFocus.focus();
		}
	}

	function visibleFocusables() {
		var all = drawer.querySelectorAll( 'a[href], button:not([disabled]), input:not([type="hidden"]), select, textarea' );
		return Array.prototype.filter.call( all, function ( el ) {
			return el.offsetParent !== null;
		} );
	}

	if ( drawer ) {
		each( openers, function ( button ) {
			button.addEventListener( 'click', function () {
				setDrawer( true, button.hasAttribute( 'data-ms-drawer-search' ) );
			} );
		} );

		drawer.addEventListener( 'click', function ( event ) {
			if ( event.target.closest( '[data-ms-drawer-close]' ) ) {
				setDrawer( false );
			}
		} );

		drawer.addEventListener( 'keydown', function ( event ) {
			if ( event.key === 'Escape' ) {
				setDrawer( false );
				return;
			}
			if ( event.key !== 'Tab' ) {
				return;
			}

			// Keep the keyboard inside the open menu.
			var items = visibleFocusables();
			if ( ! items.length ) {
				return;
			}
			var first = items[ 0 ];
			var last = items[ items.length - 1 ];
			if ( event.shiftKey && doc.activeElement === first ) {
				event.preventDefault();
				last.focus();
			} else if ( ! event.shiftKey && doc.activeElement === last ) {
				event.preventDefault();
				first.focus();
			}
		} );

		// The drawer is only for narrow screens: close it when the window grows.
		if ( window.matchMedia ) {
			var wide = window.matchMedia( '(min-width: 1024px)' );
			onMedia( wide, function () {
				if ( wide.matches ) {
					setDrawer( false );
				}
			} );
		}

		// Categories with sub-categories open and close inside the drawer.
		each( drawer.querySelectorAll( '.menu-item-has-children' ), function ( item, index ) {
			var sub = item.querySelector( '.sub-menu' );
			var link = item.querySelector( 'a' );
			if ( ! sub || ! link ) {
				return;
			}

			sub.id = sub.id || 'ms-sub-' + index;

			var button = doc.createElement( 'button' );
			button.type = 'button';
			button.className = 'ms-subtoggle';
			button.setAttribute( 'aria-expanded', 'false' );
			button.setAttribute( 'aria-controls', sub.id );
			button.innerHTML = '<svg class="ms-icon" width="20" height="20" aria-hidden="true" focusable="false"><use href="#ms-chevron-down"></use></svg>';

			var label = doc.createElement( 'span' );
			label.className = 'screen-reader-text';
			label.textContent = link.textContent.trim() + ': more';
			button.appendChild( label );

			button.addEventListener( 'click', function () {
				var open = item.classList.toggle( 'is-open' );
				button.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
			} );

			item.insertBefore( button, sub );
		} );
	}

	/* Banners ---------------------------------------------------------------
	   With several banners, the banner moves on by itself until the visitor picks
	   one or presses pause. It waits while the pointer or the keyboard is on it,
	   and it stays still for visitors who ask their device for less motion. */

	each( doc.querySelectorAll( '[data-ms-banners]' ), function ( hero ) {
		var slides = hero.querySelectorAll( '[data-ms-slide]' );
		var media = hero.querySelectorAll( '[data-ms-media]' );
		var dots = hero.querySelectorAll( '[data-ms-goto]' );
		var pause = hero.querySelector( '[data-ms-pause]' );
		var visual = hero.querySelector( '.ms-hero__visual' );
		var count = slides.length;
		var current = 0;
		var timer = null;
		var still = reduced;
		var startX = 0;
		var startY = 0;

		function show( list, active ) {
			each( list, function ( el, index ) {
				el.hidden = index !== active;
				el.classList.toggle( 'is-active', index === active );
			} );
		}

		function go( active ) {
			current = ( active + count ) % count;
			show( slides, current );
			show( media, current );
			each( dots, function ( dot, index ) {
				dot.classList.toggle( 'is-active', index === current );
				dot.setAttribute( 'aria-pressed', index === current ? 'true' : 'false' );
			} );
		}

		// The pause button shows what the banners are doing right now.
		function showState() {
			if ( ! pause ) {
				return;
			}
			var moving = !! timer;
			pause.setAttribute( 'aria-pressed', moving ? 'false' : 'true' );
			each( pause.querySelectorAll( '[data-ms-when]' ), function ( el ) {
				el.hidden = el.getAttribute( 'data-ms-when' ) !== ( moving ? 'moving' : 'still' );
			} );
		}

		// A banner only shows once its photo has arrived.
		function ready( index ) {
			var img = media[ index ] ? media[ index ].querySelector( 'img' ) : null;
			return ! img || img.complete;
		}

		// The photos of the other banners are fetched as soon as the page itself is done.
		function fetchPhotos() {
			each( media, function ( el ) {
				var img = el.querySelector( 'img' );
				if ( img ) {
					img.loading = 'eager';
				}
			} );
		}

		function stop() {
			if ( timer ) {
				window.clearInterval( timer );
				timer = null;
			}
			showState();
		}

		function start() {
			if ( still || timer || count < 2 ) {
				showState();
				return;
			}
			timer = window.setInterval( function () {
				var next = ( current + 1 ) % count;
				if ( ! doc.hidden && ready( next ) ) {
					go( next );
				}
			}, 6000 );
			showState();
		}

		// The visitor chose a banner, so it stays there.
		function pick( active ) {
			still = true;
			stop();
			go( active );
		}

		each( dots, function ( dot ) {
			dot.addEventListener( 'click', function () {
				pick( parseInt( dot.getAttribute( 'data-ms-goto' ), 10 ) || 0 );
			} );
		} );

		if ( pause ) {
			pause.addEventListener( 'click', function () {
				if ( timer ) {
					still = true;
					stop();
				} else {
					still = false;
					start();
				}
			} );
		}

		hero.addEventListener( 'mouseenter', stop );
		hero.addEventListener( 'mouseleave', start );
		hero.addEventListener( 'focusin', stop );
		hero.addEventListener( 'focusout', start );

		// A sideways swipe on the photo shows the next or the previous banner.
		if ( visual ) {
			visual.addEventListener( 'touchstart', function ( event ) {
				startX = event.changedTouches[ 0 ].clientX;
				startY = event.changedTouches[ 0 ].clientY;
			}, { passive: true } );

			visual.addEventListener( 'touchend', function ( event ) {
				var moveX = event.changedTouches[ 0 ].clientX - startX;
				var moveY = event.changedTouches[ 0 ].clientY - startY;
				if ( Math.abs( moveX ) > 40 && Math.abs( moveX ) > Math.abs( moveY ) * 1.5 ) {
					pick( current + ( moveX < 0 ? 1 : -1 ) );
				}
			}, { passive: true } );
		}

		if ( 'complete' === doc.readyState ) {
			fetchPhotos();
		} else {
			window.addEventListener( 'load', fetchPhotos );
		}

		start();
	} );

	/* Product page bar --------------------------------------------------------
	   On a phone, a bar with the price and the add-to-cart button shows whenever
	   the real form is out of sight. Its button presses the real one, or brings
	   the visitor to the form when a size still has to be chosen. */

	var bar = doc.querySelector( '[data-ms-stickybar]' );
	var cartForm = doc.querySelector( 'form.cart' );

	if ( bar && cartForm && 'IntersectionObserver' in window && window.matchMedia ) {
		var narrow = window.matchMedia( '(max-width: 899px)' );
		var formSeen = true;
		var barButton = bar.querySelector( 'button' );

		function updateBar() {
			var on = narrow.matches && ! formSeen;
			bar.hidden = ! on;
			doc.body.classList.toggle( 'ms-stickybar-on', on );
		}

		new IntersectionObserver( function ( entries ) {
			formSeen = entries[ 0 ].isIntersecting;
			updateBar();
		}, { threshold: 0 } ).observe( cartForm );

		onMedia( narrow, updateBar );

		if ( barButton ) {
			barButton.addEventListener( 'click', function () {
				var real = cartForm.querySelector( '.single_add_to_cart_button' );
				var needsChoice = ! real || real.disabled || real.classList.contains( 'disabled' );

				if ( needsChoice ) {
					cartForm.scrollIntoView( { block: 'center', behavior: reduced ? 'auto' : 'smooth' } );
					var first = cartForm.querySelector( 'select, input:not([type="hidden"])' );
					if ( first ) {
						first.focus( { preventScroll: true } );
					}
					return;
				}

				real.click();
			} );
		}
	}
}() );
