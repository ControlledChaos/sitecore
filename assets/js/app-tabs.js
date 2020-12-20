/**
 * Simple jQuery tabs
 */

;( function( $, window, undefined ) {
	'use strict';

	$.fn.app_tabs = function( options ) {

		// Default options.
		var defaults = {
			tabevent        : 'click',
			tabactiveclass  : 'active',
			tabattribute    : 'href',
			tabanimation    : false,
			tabanispeed     : 400,
			tabrotate       : false,
			tabdeeplinking  : false,
			tabpausehover   : true,
			tabdelay        : 2000,
			tabactive       : 1,
			tabcontainer    : false,
			tabcontrols     : {
				tabprev : '.prev-tab',
				tabnext : '.next-tab'
			}
		};

		var options = $.extend( defaults, options );

		return this.each( function() {

			var $this = $(this), _cache_li = [], _cache_div = [];

			if ( options.tabcontainer ) {
				var _tabcontainer = $( options.tabcontainer );
			} else {
				var _tabcontainer = $this;
			}

			var _app_tabs = _tabcontainer.find( '> div' );

			// Caching.
			_app_tabs.each( function() {
				_cache_div.push( $(this).css( 'display' ) );
			});

			// Autorotate.
			var elements = $this.find( '> ul > li' ), i = options.tabactive - 1; // ungly

			if ( ! $this.data( 'app_tabs-init' ) ) {

				$this.data( 'app_tabs-init', true );
				$this.opts = [];

				$.map(
					[
						'tabevent',
						'tabactiveclass',
						'tabattribute',
						'tabanimation',
						'tabanispeed',
						'tabrotate',
						'tabdeeplinking',
						'tabpausehover',
						'tabdelay',
						'tabcontainer'
					],
					function( val, i ) {
						$this.opts[val] = $this.data(val) || options[val];
					}
				);

				$this.opts['tabactive'] = $this.opts.tabdeeplinking ? deep_link() : ( $this.data( 'tabactive' ) || options.tabactive )

				_app_tabs.hide();

				if ( $this.opts.tabactive ) {
					_app_tabs.eq( $this.opts.tabactive - 1 ).show();
					elements.eq( $this.opts.tabactive - 1 ).addClass( options.tabactiveclass );
				}

				var fn = eval(

					function( e, tab ) {

						if ( tab ) {
							var _this = elements.find( 'a[' + $this.opts.tabattribute + '="' + tab +'"]' ).parent();
						} else {
							var _this = $(this);
						}

						_this.trigger( '_before' );
						elements.removeClass( options.tabactiveclass );
						_this.addClass( options.tabactiveclass );
						_app_tabs.hide();

						i = elements.index( _this );

						var currentTab = tab || _this.find( 'a' ).attr( $this.opts.tabattribute );

						if ( $this.opts.tabdeeplinking ) {
							location.hash = currentTab;
						}

						if ( $this.opts.tabanimation ) {

							_tabcontainer.find( currentTab ).animate(
								{ opacity : 'show' },
								$this.opts.tabanispeed
							);
							_this.trigger( '_after' );

						} else {
							_tabcontainer.find( currentTab ).show();
							_this.trigger( '_after' );
						}

						return false;
					}
				);

				var init = eval( "elements." + $this.opts.tabevent + "(fn)" );

				init;

				var t;
				var forward = function() {

					// Wrap around.
					i = ++i % elements.length;

					$this.opts.tabevent == 'hover' ? elements.eq(i).trigger( 'mouseover' ) : elements.eq(i).click();

					if ( $this.opts.tabrotate ) {

						clearTimeout(t);
						t = setTimeout( forward, $this.opts.delay );

						$this.mouseover( function () {
							if ( $this.opts.tabpausehover ) {
								clearTimeout(t);
							}
						});
					}
				}

				if ( $this.opts.tabrotate ) {

					t = setTimeout( forward, $this.opts.tabdelay );

					$this.hover( function() {
						if ( $this.opts.tabpausehover ) {
							clearTimeout(t);
						}
					}, function() {
						t = setTimeout( forward, $this.opts.tabdelay );
					});

					if ( $this.opts.tabpausehover ) {
						$this.on( "mouseleave", function() {
							clearTimeout(t); t = setTimeout( forward, $this.opts.delay );
						});
					}
				}

				function deep_link() {

					var ids = [];
					elements.find( 'a' ).each( function() {
						ids.push( $(this).attr( $this.opts.tabattribute ) );
					});

					var index = $.inArray( location.hash, ids )

					if ( index > -1 ) {
						return index + 1
					} else {
						return ( $this.data( 'active' ) || options.tabactive )
					}

				}

				var move = function( direction) {

					// Wrap around.
					if ( direction == 'forward' ) {
						i = ++i % elements.length;
					}

					// Wrap around.
					if ( direction == 'backward' ) {
						i = --i % elements.length;
					}

					elements.eq( i ).click();
				}

				$this.find( options.tabcontrols.tabnext ).click( function() {
					move( 'forward' );
				});

				$this.find( options.tabcontrols.tabprev ).click( function() {
					move( 'backward' );
				});

				$this.on ( 'show', function( e, tab ) {
					fn( e, tab );
				});

				$this.on ( 'next', function() {
					move( 'forward' );
				});

				$this.on ( 'prev', function() {
					move( 'backward' );
				});

				$this.on ( 'destroy', function() {

					$(this).removeData().find( '> ul li' ).each( function(i) {
						$(this).removeClass( options.tabactiveclass );
					});

					_app_tabs.each( function(i) {
						$(this).removeAttr( 'style' ).css( 'display', _cache_div[i] );
					});
				});
			}
		});
	};

	$(document).ready( function () {
		$( '[data-tabbed="tabbed"]' ).app_tabs();
	} );

})( jQuery );