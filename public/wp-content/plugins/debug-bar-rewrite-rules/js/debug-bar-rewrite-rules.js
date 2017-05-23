/* Debug Rewrite Rules Panel / WordPress Admin Page functionality. */
var DebugBarRewriteRules_App = (function($) {
	$(function() {

		/**
		 * Debug Bar Rewrite Urls namespace.
		 */
		var $this = {

			/**
			 * Search for matches in rewrites
			 *
			 * This function do  check for a input into "Filter Rewrite Rules
			 * List" field, and if any it filter table to only then rouns that
			 * actually fits to our search pattern, and hilight that pattern.
			 */
			search: function() {

				$('.mono.matches')
					.bind('focus', function() {
						$('div.filterui .url.active').removeClass('active');
						$('.dbrr.rules table tr .col-match').remove();
						$('.dbrr.rules table thead tr th.col-data').css({
							width: '50%'
						});
					})
					.bind('keyup', $this._search_for_match);

			},


			/**
			 * Search for match.
			 *
			 * Searching for a pattern ( a value from .mono.matches input )
			 * in table of rewrite rules, and shows only found rows with
			 * heighligted value.
			 */
			_search_for_match: function(e) {

				// Getting Pattern
				$this.pattern = $.trim($(this).val());

				if (!$this._active(e)) {
					return;
				}

				// table reset
				$this._table_reset($('.dbrr.rules table'));

				// reset if field empty.
				if ('' == $this.pattern) {
					return;
				}

				$('.dbrr.rules table tbody tr').each(function() {

					if ($(this).text().indexOf($this.pattern) == -1) {
						$(this).addClass('hidden');
					} else {
						// actual replace.
						$('td', $(this)).each(function() {
							var value = $(this).text()
								.replace($this.pattern, '<span>' + $this.pattern + '</span>');

							$(this).html(value);
						});
					}
				});

				$this._table_zebra($('.dbrr.rules table'));

			},


			/**
			 * Check whatever we need to care about this keyup event or not.
			 *
			 * return true if we need to care, and false otherwise.
			 *
			 * @param  event e  KeyUp event.
			 * @return bool
			 */
			_active: function(e) {
				// get keycode of current keypress event
				var code = (e.keyCode || e.which);

				// do nothing if it's an arrow key
				// Ignoting all arrow keys.
				if ([32, 37, 38, 39, 40, 91, 17].indexOf(code) > -1) {
					return false;
				}

				if (13 == code) {
					e.preventDefault();
				}

				return true;
			},


			/**
			 *  Search for url match in patterns.
			 *
			 * Test Url we type in url box against our rewrite rules.
			 * use internaly _filter_match  and _filter_searched
			 */
			test: function() {

				// Clickcing wp url and making webpath active.
				$('.mono.domain')
					.bind('click', function() {
						$('.mono.search').focus();
					}).trigger('click');

				// Click to mono.search input will activate search UI.
				$('.mono.search')
					.bind('focus', function() {

						$(this).parent().addClass('active');

						if (!$('.dbrr.rules table thead tr th.col-match').length) {

							$('<th/>')
								.addClass('col-match')
								.css('width', '30%')
								.text($this.settings.matches)
								.appendTo($('.dbrr.rules table thead tr'))
								.parent()
								.find('th.col-data')
								.css('width', '35%');

							$('<td/>')
								.addClass('col-match')
								.html(' ')
								.appendTo($('.dbrr.rules table tbody tr'));
						}
					})
					.bind('blur', function() {

						if ($.trim($(this).val()) == '' && $('.col-match').length > 0) {
							$(this).parent().removeClass('active');
							$('.col-match').remove();
						}

					})
					.bind('keyup', _.debounce($this._filter_match, 300))
					.bind('keyup', _.debounce($this._filter_sarched, 300))
					.bind('focus', _.debounce($this._filter_match, 300))
					.bind('focus', _.debounce($this._filter_sarched, 300));
			},


			/**
			 * Filter actual input and repalce it by one that actually
			 * doesn't contain site domain and path.
			 *
			 * @param  event  e KeyUp Event
			 */
			_filter_match: function(e) {

				if (!$this._active(e)) {
					return;
				}

				$el = $(this);

				if (typeof $this.base == 'undefined') {
					$this.base = $this.parse_url($this.settings.home);
				}

				var current = $this.parse_url($(this).val());

				jQuery.each(['scheme', '://', 'host', 'port', 'path'], function(index, value) {

					val = o = $el.val().split('?')[0].split('#')[0];

					if (typeof current[value] == 'string' &&
						current[value] === $this.base[value] &&
						val.indexOf(current[value]) != -1) {

						val = val.substring(
							val.indexOf(current[value]) + current[value].length
						);

					} else if (value == '://' && val.indexOf(value) != -1) {

						val = val.substring(
							val.indexOf(value) + value.length
						);

					} else if (value == 'path' && val.indexOf($this.base[value]) == 0) {
						val = val.substring(
							val.indexOf($this.base[value]) + $this.base[value].length
						);
					}

					// Additional Starting Trail
					if (value == 'path' && val.substring(0, 1) == '/') {
						val = val.substring(1, val.length);
					}

					$el.val(o != jQuery.trim(val) ? jQuery.trim(val) : o);
				});

			},


			/**
			 * Filtering table by urls that match to our pattern.
			 *
			 * @param  {[type]} e [description]
			 * @return {[type]}   [description]
			 */
			_filter_sarched: function(e) {

				if (!$this._active(e)) {
					return;
				}

				// Current Element (input)
				$el = $(this);
				$this._table_reset($('.dbrr.rules table'));

				// Reset table - removing `span` and cleaning matches table.
				$('.dbrr.rules table tbody tr').each(function() {
					$('td:eq(0), td:eq(1)', $(this)).each(function() {
						$(this).html($(this).text());
					});
					$('td:eq(2)', $(this)).html('');
				});

				if ($.trim($el.val()) === "") {
					return;
				}

				// Creating Rules.
				// @todo -> provide extra source of rewrite rules later.
				if (typeof $this.rules == 'undefined') {
					$this.rules = {};
					$('.dbrr.rules table tbody tr').each(function() {
						$this.rules[$(this).attr('id')] = {
							rule: $('td:eq(0)', $(this)).text(),
							match: $('td:eq(1)', $(this)).text()
						};
					});
				}

				// Checking do we really have matched rules?
				// and checking it with server side support.
				//
				jQuery.ajax({
					url: $this.settings.validator,
					crossDomain: true,
					dataType: 'json',
					type: 'POST',
					data: {
						rules: $this.rules,
						search: $el.val()
					}
				}).done(function(data) {

					$this._table_reset($('.dbrr.rules table'));

					// Special Case if TWO interfaces open.
					//  Check this JUMP css rendering if nothing changed.
					//  @todo - fix "repeated parsers."

					$('.dbrr.rules').each(function() {
						$('table tbody tr', this).each(function(i) {
							if (data.rules[(i + 1)].result == false) {
								$(this).addClass('hidden');
							} else {
								$this.currentRow = this;
								if (typeof data.rules[(i + 1)].vars != "undefined") {
									$.each(data.rules[(i + 1)].vars, function(index, value) {
										$('td:eq(2)', $this.currentRow)
											.append('<div><strong>' + index + '</strong> : ' + value + '</div>');
									});
								}
							}
						});
					});

					$this._table_zebra($('.dbrr.rules table'));
				});

			},


			/**
			 * Helper Function that reset table structure and view.
			 *
			 * Removes .hidden from table ,  colorize it as zebra and
			 * removed html grom cells.
			 *
			 * @param  jQuery   jQueryTableElement Table jQuery element.
			 */
			_table_reset: function(jQueryTableElement) {

				$('tbody tr', jQueryTableElement)
					.removeClass('alt hidden')
					.each(function() {
						$('td', $(this))
							.each(function() {
								$(this).html($(this).text());
							});
					});

				$this._table_zebra(jQueryTableElement);
			},


			/**
			 * Zebra Stripes colors for tables.
			 *
			 * @param  jQuery   jQueryTableElement Table jQuery element.
			 */
			_table_zebra: function(jQueryTableElement) {

				$('tbody tr', jQueryTableElement)
					.filter(':visible')
					.each(
						function(i) {
							$(this).addClass(((i + 2) % 2 == 0) ? 'alt' : '_');
						}
					);

				return $this;
			},


			/**
			 * parse_url php function clone.
			 *
			 * @param  string url [description]
			 * @return {[type]}     [description]
			 */
			parse_url: function(url) {

				/*	Dom parsing of A	*/
				var parser = document.createElement('a');

				if (url.substring(0, 1) != '/' && url.substring(0, 4) != 'http') {
					url = '/' + url;
				}

				parser.href = url;

				/*	Exception for user:password	*/
				var re = /(\/\/)(.+?)((:(.+?))?)@/i;
				if (re.exec(url)) {
					var result = re.exec(url)
					if (typeof result[2] == 'string' && result[2].length > 0) {
						parser.user = result[2];
					}

					if (typeof result[5] == 'string' && result[5].length > 0) {
						parser.pass = result[5];
					}
				}

				var urls = new Object();
				var urls_php = [
					'scheme', 'host', 'port', 'user', 'pass', 'path', 'query', 'fragment'
				];
				var urls_js = [
					'protocol', 'hostname', 'port', 'user', 'pass', 'pathname', 'search', 'hash'
				];

				urls_js.forEach(function(value, index) {
					if (typeof parser[value] == "string" && parser[value].length > 0) {
						switch (value) {
							case "protocol":
								urls[urls_php[index]] = parser[value].replace(":", "");
								break;
							case "hash":
								urls[urls_php[index]] = parser[value].replace("#", "");
								break;
							case "pathname":
								if (parser[value] != "/")
									urls[urls_php[index]] = parser[value];
								break;
							default:
								urls[urls_php[index]] = parser[value];
								break;
						}
					}
				});
				return urls;
			},


			/**
			 *  Reset Rewrite Rules within DebugBar.
			 */
			reset_rewrite_rules: function() {

				// We have only one link in headings seaction.
				$('.debug-bar-rewrites-urls a')
					.bind('click', function(e) {

						e.preventDefault();

						$(this).find('.spinner').css({
							visibility: 'visible'
						});

						var $_el = this;

						$.post($this.settings.ajaxurl, {
							action: 'debug_bar_rewrite_rules',
							nonce: $this.settings.nonce
						}, function(data) {

							$($_el).find('.spinner').css({
								visibility: 'hidden'
							});

							// Deelting
							delete $this.rules;

							// Replacing Tables
							$.each(['rules', 'filters'], function(index, value) {
								if (typeof data[value] != "underfined" &&
									$('.dbrr.' + value).length > 0) {

									$('.dbrr.' + value).html(
										$('table',
											$(data[value])
										).parent().html()
									);
								}
							});

							// Replacing Counts.
							$.each(['rules', 'filters_hooked', 'filters'], function(index, value) {
								if (typeof data['count_' + value] != "underfined" && $('h2.dbrr_count_' + value + ' i').length > 0) {
									$('h2.dbrr_count_' + value + ' i').text(data['count_' + value]);
								}
							});

						}, 'json');
					});
			},
			/**
			 * Setup Settings.
			 */
			setup: function(settings) {
				$this.settings = $.extend($this.settings, settings);
			},


			/**
			 * Initialzier.
			 * @param  object settings object of array
			 */
			intialize: function(settings) {

				return $this.setup(settings),
					$this.search(),
					$this.test(),
					$this.reset_rewrite_rules();
			}

		};

		return $this.intialize(debugBarRewriteRules);
	});
})(jQuery);