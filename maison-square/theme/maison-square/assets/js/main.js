/**
 * Maison Square: the menu drawer and the home banners.
 * The banners are the only thing on the page that moves by itself.
 */
( function () {
	'use strict';

	var doc = document;
	var drawer = doc.getElementById( 'ms-drawer' );
	var openers = doc.querySelectorAll( '[data-ms-drawer-open]' );
	var lastFocus = null;

	function each( list, fn ) {
		Array.prototype.forEach.call( list, fn );
	}

	/* Menu drawer ---------------------------------------------------------- */

	function setDrawer( open ) {
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
			var close = drawer.querySelector( '[data-ms-drawer-close]' );
			if ( close ) {
				close.focus();
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
				setDrawer( true );
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
			var onWide = function () {
				if ( wide.matches ) {
					setDrawer( false );
				}
			};
			if ( wide.addEventListener ) {
				wide.addEventListener( 'change', onWide );
			} else if ( wide.addListener ) {
				wide.addListener( onWide );
			}
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
	   one. It waits while the pointer or the keyboard is on it, and it stays still
	   for visitors who ask their device for less motion. */

	each( doc.querySelectorAll( '[data-ms-banners]' ), function ( hero ) {
		var slides = hero.querySelectorAll( '[data-ms-slide]' );
		var media = hero.querySelectorAll( '[data-ms-media]' );
		var dots = hero.querySelectorAll( '[data-ms-goto]' );
		var visual = hero.querySelector( '.ms-hero__visual' );
		var count = slides.length;
		var current = 0;
		var timer = null;
		var still = !! ( window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches );
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
		}

		function start() {
			if ( still || timer || count < 2 ) {
				return;
			}
			timer = window.setInterval( function () {
				var next = ( current + 1 ) % count;
				if ( ! doc.hidden && ready( next ) ) {
					go( next );
				}
			}, 6000 );
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
}() );
