;(function(root, factory) {
  if (typeof define === 'function' && define.amd) {
    define(['jquery'], factory);
  } else if (typeof exports === 'object') {
    module.exports = factory(require('jquery'));
  } else {
    root.jquery_mmenu_all_js = factory(root.jQuery);
  }
}(this, function(jQuery) {
/*
 * jQuery mmenu v6.1.8
 * @requires jQuery 1.7.0 or later
 *
 * mmenu.frebsite.nl
 *
 * Copyright (c) Fred Heusschen
 * www.frebsite.nl
 *
 * License: CC-BY-NC-4.0
 * http://creativecommons.org/licenses/by-nc/4.0/
 */
(function ($) {
    var _PLUGIN_ = 'mmenu';
    var _VERSION_ = '6.1.8';
    //	Newer version of the plugin already excists
    if ($[_PLUGIN_] && $[_PLUGIN_].version > _VERSION_) {
        return;
    }
    /*
        Class
    */
    $[_PLUGIN_] = function ($menu, opts, conf) {
        this.$menu = $menu;
        this._api = ['bind', 'getInstance', 'initPanels', 'openPanel', 'closePanel', 'closeAllPanels', 'setSelected'];
        this.opts = opts;
        this.conf = conf;
        this.vars = {};
        this.cbck = {};
        this.mtch = {};
        if (typeof this.___deprecated == 'function') {
            this.___deprecated();
        }
        this._initAddons();
        this._initExtensions();
        this._initMenu();
        this._initPanels();
        this._initOpened();
        this._initAnchors();
        this._initMatchMedia();
        if (typeof this.___debug == 'function') {
            this.___debug();
        }
        return this;
    };
    $[_PLUGIN_].version = _VERSION_;
    $[_PLUGIN_].addons = {};
    $[_PLUGIN_].uniqueId = 0;
    $[_PLUGIN_].defaults = {
        extensions: [],
        initMenu: function () { },
        initPanels: function () { },
        navbar: {
            add: true,
            title: 'Menu',
            titleLink: 'parent'
        },
        onClick: {
            //			close			: true,
            //			preventDefault	: null,
            setSelected: true
        },
        slidingSubmenus: true
    };
    $[_PLUGIN_].configuration = {
        classNames: {
            divider: 'Divider',
            inset: 'Inset',
            nolistview: 'NoListview',
            nopanel: 'NoPanel',
            panel: 'Panel',
            selected: 'Selected',
            spacer: 'Spacer',
            vertical: 'Vertical'
        },
        clone: false,
        openingInterval: 25,
        panelNodetype: 'ul, ol, div',
        transitionDuration: 400
    };
    $[_PLUGIN_].prototype = {
        getInstance: function () {
            return this;
        },
        initPanels: function ($panels) {
            this._initPanels($panels);
        },
        openPanel: function ($panel, animation) {
            this.trigger('openPanel:before', $panel);
            if (!$panel || !$panel.length) {
                return;
            }
            if (!$panel.is('.' + _c.panel)) {
                $panel = $panel.closest('.' + _c.panel);
            }
            if (!$panel.is('.' + _c.panel)) {
                return;
            }
            var that = this;
            if (typeof animation != 'boolean') {
                animation = true;
            }
            //	vertical
            if ($panel.hasClass(_c.vertical)) {
                //	Open current and all vertical parent panels
                $panel
                    .add($panel.parents('.' + _c.vertical))
                    .removeClass(_c.hidden)
                    .parent('li')
                    .addClass(_c.opened);
                //	Open first non-vertical parent panel
                this.openPanel($panel
                    .parents('.' + _c.panel)
                    .not('.' + _c.vertical)
                    .first());
                this.trigger('openPanel:start', $panel);
                this.trigger('openPanel:finish', $panel);
            }
            else {
                if ($panel.hasClass(_c.opened)) {
                    return;
                }
                var $panels = this.$pnls.children('.' + _c.panel), $current = $panels.filter('.' + _c.opened);
                //	old browser support
                if (!$[_PLUGIN_].support.csstransitions) {
                    $current
                        .addClass(_c.hidden)
                        .removeClass(_c.opened);
                    $panel
                        .removeClass(_c.hidden)
                        .addClass(_c.opened);
                    this.trigger('openPanel:start', $panel);
                    this.trigger('openPanel:finish', $panel);
                    return;
                }
                //	/old browser support
                //	'Close' all children
                $panels
                    .not($panel)
                    .removeClass(_c.subopened);
                //	'Open' all parents
                var $parent = $panel.data(_d.parent);
                while ($parent) {
                    $parent = $parent.closest('.' + _c.panel);
                    if (!$parent.is('.' + _c.vertical)) {
                        $parent.addClass(_c.subopened);
                    }
                    $parent = $parent.data(_d.parent);
                }
                //	Add classes for animation
                $panels
                    .removeClass(_c.highest)
                    .not($current)
                    .not($panel)
                    .addClass(_c.hidden);
                $panel
                    .removeClass(_c.hidden);
                this.openPanelStart = function () {
                    $current.removeClass(_c.opened);
                    $panel.addClass(_c.opened);
                    if ($panel.hasClass(_c.subopened)) {
                        $current.addClass(_c.highest);
                        $panel.removeClass(_c.subopened);
                    }
                    else {
                        $current.addClass(_c.subopened);
                        $panel.addClass(_c.highest);
                    }
                    this.trigger('openPanel:start', $panel);
                };
                this.openPanelFinish = function () {
                    $current.removeClass(_c.highest).addClass(_c.hidden);
                    $panel.removeClass(_c.highest);
                    this.trigger('openPanel:finish', $panel);
                };
                if (animation && !$panel.hasClass(_c.noanimation)) {
                    //	Without the timeout the animation will not work because the element had display: none;
                    setTimeout(function () {
                        //	Callback
                        that.__transitionend($panel, function () {
                            that.openPanelFinish.call(that);
                        }, that.conf.transitionDuration);
                        that.openPanelStart.call(that);
                    }, that.conf.openingInterval);
                }
                else {
                    this.openPanelStart.call(this);
                    this.openPanelFinish.call(this);
                }
            }
            this.trigger('openPanel:after', $panel);
        },
        closePanel: function ($panel) {
            this.trigger('closePanel:before', $panel);
            var $li = $panel.parent();
            //	Vertical only
            if ($li.hasClass(_c.vertical)) {
                $li.removeClass(_c.opened);
                this.trigger('closePanel', $panel);
            }
            this.trigger('closePanel:after', $panel);
        },
        closeAllPanels: function ($panel) {
            this.trigger('closeAllPanels:before');
            //	Vertical
            this.$pnls
                .find('.' + _c.listview)
                .children()
                .removeClass(_c.selected)
                .filter('.' + _c.vertical)
                .removeClass(_c.opened);
            //	Horizontal
            var $pnls = this.$pnls.children('.' + _c.panel), $frst = ($panel && $panel.length) ? $panel : $pnls.first();
            this.$pnls
                .children('.' + _c.panel)
                .not($frst)
                .removeClass(_c.subopened)
                .removeClass(_c.opened)
                .removeClass(_c.highest)
                .addClass(_c.hidden);
            this.openPanel($frst, false);
            this.trigger('closeAllPanels:after');
        },
        togglePanel: function ($panel) {
            var $l = $panel.parent();
            //	Vertical only
            if ($l.hasClass(_c.vertical)) {
                this[$l.hasClass(_c.opened) ? 'closePanel' : 'openPanel']($panel);
            }
        },
        setSelected: function ($li) {
            this.trigger('setSelected:before', $li);
            this.$menu.find('.' + _c.listview).children('.' + _c.selected).removeClass(_c.selected);
            $li.addClass(_c.selected);
            this.trigger('setSelected:after', $li);
        },
        bind: function (evnt, fn) {
            this.cbck[evnt] = this.cbck[evnt] || [];
            this.cbck[evnt].push(fn);
        },
        trigger: function () {
            var that = this, args = Array.prototype.slice.call(arguments), evnt = args.shift();
            if (this.cbck[evnt]) {
                for (var e = 0, l = this.cbck[evnt].length; e < l; e++) {
                    this.cbck[evnt][e].apply(that, args);
                }
            }
        },
        matchMedia: function (mdia, yes, no) {
            var that = this, func = {
                'yes': yes,
                'no': no
            };
            //	Bind to windowResize
            this.mtch[mdia] = this.mtch[mdia] || [];
            this.mtch[mdia].push(func);
        },
        _initAddons: function () {
            this.trigger('initAddons:before');
            //	Add add-ons to plugin
            var adns;
            for (adns in $[_PLUGIN_].addons) {
                $[_PLUGIN_].addons[adns].add.call(this);
                $[_PLUGIN_].addons[adns].add = function () { };
            }
            //	Setup add-ons for menu
            for (adns in $[_PLUGIN_].addons) {
                $[_PLUGIN_].addons[adns].setup.call(this);
            }
            this.trigger('initAddons:after');
        },
        _initExtensions: function () {
            this.trigger('initExtensions:before');
            var that = this;
            //	Convert array to object with array
            if (this.opts.extensions.constructor === Array) {
                this.opts.extensions = {
                    'all': this.opts.extensions
                };
            }
            //	Loop over object
            for (var mdia in this.opts.extensions) {
                this.opts.extensions[mdia] = this.opts.extensions[mdia].length ? 'mm-' + this.opts.extensions[mdia].join(' mm-') : '';
                if (this.opts.extensions[mdia]) {
                    (function (mdia) {
                        that.matchMedia(mdia, function () {
                            this.$menu.addClass(this.opts.extensions[mdia]);
                        }, function () {
                            this.$menu.removeClass(this.opts.extensions[mdia]);
                        });
                    })(mdia);
                }
            }
            this.trigger('initExtensions:after');
        },
        _initMenu: function () {
            this.trigger('initMenu:before');
            var that = this;
            //	Clone if needed
            if (this.conf.clone) {
                this.$orig = this.$menu;
                this.$menu = this.$orig.clone();
                this.$menu.add(this.$menu.find('[id]'))
                    .filter('[id]')
                    .each(function () {
                    $(this).attr('id', _c.mm($(this).attr('id')));
                });
            }
            //	Via options
            this.opts.initMenu.call(this, this.$menu, this.$orig);
            //	Add ID
            this.$menu.attr('id', this.$menu.attr('id') || this.__getUniqueId());
            //	Add markup
            this.$pnls = $('<div class="' + _c.panels + '" />')
                .append(this.$menu.children(this.conf.panelNodetype))
                .prependTo(this.$menu);
            //	Add classes
            var clsn = [_c.menu];
            if (!this.opts.slidingSubmenus) {
                clsn.push(_c.vertical);
            }
            this.$menu
                .addClass(clsn.join(' '))
                .parent()
                .addClass(_c.wrapper);
            this.trigger('initMenu:after');
        },
        _initPanels: function ($panels) {
            this.trigger('initPanels:before', $panels);
            $panels = $panels || this.$pnls.children(this.conf.panelNodetype);
            var $newpanels = $();
            var that = this;
            var init = function ($panels) {
                $panels
                    .filter(this.conf.panelNodetype)
                    .each(function () {
                    var $panel = that._initPanel($(this));
                    if ($panel) {
                        that._initNavbar($panel);
                        that._initListview($panel);
                        $newpanels = $newpanels.add($panel);
                        //	init child panels
                        var $child = $panel
                            .children('.' + _c.listview)
                            .children('li')
                            .children(that.conf.panelNodeType)
                            .add($panel.children('.' + that.conf.classNames.panel));
                        if ($child.length) {
                            init.call(that, $child);
                        }
                    }
                });
            };
            init.call(this, $panels);
            //	Init via options
            this.opts.initPanels.call(this, $newpanels);
            this.trigger('initPanels:after', $newpanels);
        },
        _initPanel: function ($panel) {
            this.trigger('initPanel:before', $panel);
            var that = this;
            //	Stop if already a panel
            if ($panel.hasClass(_c.panel)) {
                return $panel;
            }
            //	Refactor panel classnames
            this.__refactorClass($panel, this.conf.classNames.panel, 'panel');
            this.__refactorClass($panel, this.conf.classNames.nopanel, 'nopanel');
            this.__refactorClass($panel, this.conf.classNames.vertical, 'vertical');
            this.__refactorClass($panel, this.conf.classNames.inset, 'inset');
            $panel.filter('.' + _c.inset)
                .addClass(_c.nopanel);
            //	Stop if not supposed to be a panel
            if ($panel.hasClass(_c.nopanel)) {
                return false;
            }
            //	Wrap UL/OL in DIV
            var vertical = ($panel.hasClass(_c.vertical) || !this.opts.slidingSubmenus);
            $panel.removeClass(_c.vertical);
            var id = $panel.attr('id') || this.__getUniqueId();
            $panel.removeAttr('id');
            if ($panel.is('ul, ol')) {
                $panel.wrap('<div />');
                $panel = $panel.parent();
            }
            $panel
                .addClass(_c.panel + ' ' + _c.hidden)
                .attr('id', id);
            var $parent = $panel.parent('li');
            if (vertical) {
                $panel
                    .add($parent)
                    .addClass(_c.vertical);
            }
            else {
                $panel.appendTo(this.$pnls);
            }
            //	Store parent/child relation
            if ($parent.length) {
                $parent.data(_d.child, $panel);
                $panel.data(_d.parent, $parent);
            }
            this.trigger('initPanel:after', $panel);
            return $panel;
        },
        _initNavbar: function ($panel) {
            this.trigger('initNavbar:before', $panel);
            if ($panel.children('.' + _c.navbar).length) {
                return;
            }
            var $parent = $panel.data(_d.parent), $navbar = $('<div class="' + _c.navbar + '" />');
            var title = $[_PLUGIN_].i18n(this.opts.navbar.title);
            var href = '';
            if ($parent && $parent.length) {
                if ($parent.hasClass(_c.vertical)) {
                    return;
                }
                //	Listview, the panel wrapping this panel
                if ($parent.parent().is('.' + _c.listview)) {
                    var $a = $parent
                        .children('a, span')
                        .not('.' + _c.next);
                }
                else {
                    var $a = $parent
                        .closest('.' + _c.panel)
                        .find('a[href="#' + $panel.attr('id') + '"]');
                }
                $a = $a.first();
                $parent = $a.closest('.' + _c.panel);
                var id = $parent.attr('id');
                title = $a.text();
                switch (this.opts.navbar.titleLink) {
                    case 'anchor':
                        href = $a.attr('href');
                        break;
                    case 'parent':
                        href = '#' + id;
                        break;
                }
                $navbar.append('<a class="' + _c.btn + ' ' + _c.prev + '" href="#' + id + '" />');
            }
            else if (!this.opts.navbar.title) {
                return;
            }
            if (this.opts.navbar.add) {
                $panel.addClass(_c.hasnavbar);
            }
            $navbar.append('<a class="' + _c.title + '"' + (href.length ? ' href="' + href + '"' : '') + '>' + title + '</a>')
                .prependTo($panel);
            this.trigger('initNavbar:after', $panel);
        },
        _initListview: function ($panel) {
            this.trigger('initListview:before', $panel);
            //	Refactor listviews classnames
            var $ul = this.__childAddBack($panel, 'ul, ol');
            this.__refactorClass($ul, this.conf.classNames.nolistview, 'nolistview');
            $ul.filter('.' + this.conf.classNames.inset)
                .addClass(_c.nolistview);
            //	Refactor listitems classnames
            var $li = $ul
                .not('.' + _c.nolistview)
                .addClass(_c.listview)
                .children();
            this.__refactorClass($li, this.conf.classNames.selected, 'selected');
            this.__refactorClass($li, this.conf.classNames.divider, 'divider');
            this.__refactorClass($li, this.conf.classNames.spacer, 'spacer');
            //	Add open link to parent listitem
            var $parent = $panel.data(_d.parent);
            if ($parent && $parent.parent().is('.' + _c.listview)) {
                if (!$parent.children('.' + _c.next).length) {
                    var $a = $parent.children('a, span').first(), $b = $('<a class="' + _c.next + '" href="#' + $panel.attr('id') + '" />').insertBefore($a);
                    if ($a.is('span')) {
                        $b.addClass(_c.fullsubopen);
                    }
                }
            }
            this.trigger('initListview:after', $panel);
        },
        _initOpened: function () {
            this.trigger('initOpened:before');
            var $selected = this.$pnls
                .find('.' + _c.listview)
                .children('.' + _c.selected)
                .removeClass(_c.selected)
                .last()
                .addClass(_c.selected);
            var $current = ($selected.length)
                ? $selected.closest('.' + _c.panel)
                : this.$pnls.children('.' + _c.panel).first();
            this.openPanel($current, false);
            this.trigger('initOpened:after');
        },
        _initAnchors: function () {
            var that = this;
            glbl.$body
                .on(_e.click + '-oncanvas', 'a[href]', function (e) {
                var $t = $(this), fired = false, inMenu = that.$menu.find($t).length;
                //	Find behavior for addons
                for (var a in $[_PLUGIN_].addons) {
                    if ($[_PLUGIN_].addons[a].clickAnchor.call(that, $t, inMenu)) {
                        fired = true;
                        break;
                    }
                }
                var _h = $t.attr('href');
                //	Open/Close panel
                if (!fired && inMenu) {
                    if (_h.length > 1 && _h.slice(0, 1) == '#') {
                        try {
                            var $h = $(_h, that.$menu);
                            if ($h.is('.' + _c.panel)) {
                                fired = true;
                                that[$t.parent().hasClass(_c.vertical) ? 'togglePanel' : 'openPanel']($h);
                            }
                        }
                        catch (err) { }
                    }
                }
                if (fired) {
                    e.preventDefault();
                }
                //	All other anchors in lists
                if (!fired && inMenu) {
                    if ($t.is('.' + _c.listview + ' > li > a') && !$t.is('[rel="external"]') && !$t.is('[target="_blank"]')) {
                        //	Set selected item
                        if (that.__valueOrFn(that.opts.onClick.setSelected, $t)) {
                            that.setSelected($(e.target).parent());
                        }
                        //	Prevent default / don't follow link. Default: false
                        var preventDefault = that.__valueOrFn(that.opts.onClick.preventDefault, $t, _h.slice(0, 1) == '#');
                        if (preventDefault) {
                            e.preventDefault();
                        }
                        //	Close menu. Default: true if preventDefault, false otherwise
                        if (that.__valueOrFn(that.opts.onClick.close, $t, preventDefault)) {
                            if (that.opts.offCanvas && typeof that.close == 'function') {
                                that.close();
                            }
                        }
                    }
                }
            });
        },
        _initMatchMedia: function () {
            var that = this;
            this._fireMatchMedia();
            glbl.$wndw
                .on(_e.resize, function (e) {
                that._fireMatchMedia();
            });
        },
        _fireMatchMedia: function () {
            for (var mdia in this.mtch) {
                var fn = window.matchMedia && window.matchMedia(mdia).matches ? 'yes' : 'no';
                for (var m = 0; m < this.mtch[mdia].length; m++) {
                    this.mtch[mdia][m][fn].call(this);
                }
            }
        },
        _getOriginalMenuId: function () {
            var id = this.$menu.attr('id');
            if (this.conf.clone && id && id.length) {
                id = _c.umm(id);
            }
            return id;
        },
        __api: function () {
            var that = this, api = {};
            $.each(this._api, function (i) {
                var fn = this;
                api[fn] = function () {
                    var re = that[fn].apply(that, arguments);
                    return (typeof re == 'undefined') ? api : re;
                };
            });
            return api;
        },
        __valueOrFn: function (o, $e, d) {
            if (typeof o == 'function') {
                return o.call($e[0]);
            }
            if (typeof o == 'undefined' && typeof d != 'undefined') {
                return d;
            }
            return o;
        },
        __refactorClass: function ($e, o, c) {
            return $e.filter('.' + o).removeClass(o).addClass(_c[c]);
        },
        __findAddBack: function ($e, s) {
            return $e.find(s).add($e.filter(s));
        },
        __childAddBack: function ($e, s) {
            return $e.children(s).add($e.filter(s));
        },
        __filterListItems: function ($li) {
            return $li
                .not('.' + _c.divider)
                .not('.' + _c.hidden);
        },
        __filterListItemAnchors: function ($li) {
            return this.__filterListItems($li)
                .children('a')
                .not('.' + _c.next);
        },
        __transitionend: function ($e, fn, duration) {
            var _ended = false, _fn = function (e) {
                if (typeof e !== 'undefined') {
                    if (e.target != $e[0]) {
                        return;
                    }
                }
                if (!_ended) {
                    $e.off(_e.transitionend);
                    $e.off(_e.webkitTransitionEnd);
                    fn.call($e[0]);
                }
                _ended = true;
            };
            $e.on(_e.transitionend, _fn);
            $e.on(_e.webkitTransitionEnd, _fn);
            setTimeout(_fn, duration * 1.1);
        },
        __getUniqueId: function () {
            return _c.mm($[_PLUGIN_].uniqueId++);
        }
    };
    /*
        jQuery plugin
    */
    $.fn[_PLUGIN_] = function (opts, conf) {
        //	First time plugin is fired
        initPlugin();
        //	Extend options
        opts = $.extend(true, {}, $[_PLUGIN_].defaults, opts);
        conf = $.extend(true, {}, $[_PLUGIN_].configuration, conf);
        var $result = $();
        this.each(function () {
            var $menu = $(this);
            if ($menu.data(_PLUGIN_)) {
                return;
            }
            var _menu = new $[_PLUGIN_]($menu, opts, conf);
            _menu.$menu.data(_PLUGIN_, _menu.__api());
            $result = $result.add(_menu.$menu);
        });
        return $result;
    };
    /*
        I18N
    */
    $[_PLUGIN_].i18n = (function () {
        var trns = {};
        return function (t) {
            switch (typeof t) {
                case 'object':
                    $.extend(trns, t);
                    return trns;
                case 'string':
                    return trns[t] || t;
                case 'undefined':
                default:
                    return trns;
            }
        };
    })();
    /*
        SUPPORT
    */
    $[_PLUGIN_].support = {
        touch: 'ontouchstart' in window || navigator.msMaxTouchPoints || false,
        csstransitions: (function () {
            if (typeof Modernizr !== 'undefined' &&
                typeof Modernizr.csstransitions !== 'undefined') {
                return Modernizr.csstransitions;
            }
            //	w/o Modernizr, we'll assume you only support modern browsers :/
            return true;
        })(),
        csstransforms: (function () {
            if (typeof Modernizr !== 'undefined' &&
                typeof Modernizr.csstransforms !== 'undefined') {
                return Modernizr.csstransforms;
            }
            //	w/o Modernizr, we'll assume you only support modern browsers :/
            return true;
        })(),
        csstransforms3d: (function () {
            if (typeof Modernizr !== 'undefined' &&
                typeof Modernizr.csstransforms3d !== 'undefined') {
                return Modernizr.csstransforms3d;
            }
            //	w/o Modernizr, we'll assume you only support modern browsers :/
            return true;
        })()
    };
    //	Global variables
    var _c, _d, _e, glbl;
    function initPlugin() {
        if ($[_PLUGIN_].glbl) {
            return;
        }
        glbl = {
            $wndw: $(window),
            $docu: $(document),
            $html: $('html'),
            $body: $('body')
        };
        //	Classnames, Datanames, Eventnames
        _c = {};
        _d = {};
        _e = {};
        $.each([_c, _d, _e], function (i, o) {
            o.add = function (a) {
                a = a.split(' ');
                for (var b = 0, l = a.length; b < l; b++) {
                    o[a[b]] = o.mm(a[b]);
                }
            };
        });
        //	Classnames
        _c.mm = function (c) { return 'mm-' + c; };
        _c.add('wrapper menu panels panel nopanel highest opened subopened navbar hasnavbar title btn prev next listview nolistview inset vertical selected divider spacer hidden fullsubopen noanimation');
        _c.umm = function (c) {
            if (c.slice(0, 3) == 'mm-') {
                c = c.slice(3);
            }
            return c;
        };
        //	Datanames
        _d.mm = function (d) { return 'mm-' + d; };
        _d.add('parent child');
        //	Eventnames
        _e.mm = function (e) { return e + '.mm'; };
        _e.add('transitionend webkitTransitionEnd click scroll resize keydown mousedown mouseup touchstart touchmove touchend orientationchange');
        $[_PLUGIN_]._c = _c;
        $[_PLUGIN_]._d = _d;
        $[_PLUGIN_]._e = _e;
        $[_PLUGIN_].glbl = glbl;
    }
})(jQuery);

/*
 * jQuery mmenu offCanvas add-on
 * mmenu.frebsite.nl
 *
 * Copyright (c) Fred Heusschen
 */
(function ($) {
    var _PLUGIN_ = 'mmenu';
    var _ADDON_ = 'offCanvas';
    $[_PLUGIN_].addons[_ADDON_] = {
        //	setup: fired once per menu
        setup: function () {
            if (!this.opts[_ADDON_]) {
                return;
            }
            var that = this, opts = this.opts[_ADDON_], conf = this.conf[_ADDON_];
            glbl = $[_PLUGIN_].glbl;
            //	Add methods to api
            this._api = $.merge(this._api, ['open', 'close', 'setPage']);
            //	Extend shorthand options
            if (typeof opts != 'object') {
                opts = {};
            }
            if (opts.position == 'top' || opts.position == 'bottom') {
                opts.zposition = 'front';
            }
            opts = this.opts[_ADDON_] = $.extend(true, {}, $[_PLUGIN_].defaults[_ADDON_], opts);
            //	Extend configuration
            if (typeof conf.pageSelector != 'string') {
                conf.pageSelector = '> ' + conf.pageNodetype;
            }
            //	Setup the menu
            this.vars.opened = false;
            var clsn = [_c.offcanvas];
            //	position classes
            if (opts.position != 'left') {
                clsn.push(_c.mm(opts.position));
            }
            if (opts.zposition != 'back') {
                clsn.push(_c.mm(opts.zposition));
            }
            //	support classes
            if (!$[_PLUGIN_].support.csstransforms) {
                clsn.push(_c['no-csstransforms']);
            }
            if (!$[_PLUGIN_].support.csstransforms3d) {
                clsn.push(_c['no-csstransforms3d']);
            }
            //	Add off-canvas behavior
            this.bind('initMenu:after', function () {
                var that = this;
                //	Setup the page
                this.setPage(glbl.$page);
                //	Setup the UI blocker and the window
                this._initBlocker();
                this['_initWindow_' + _ADDON_]();
                //	Setup the menu
                this.$menu
                    .addClass(clsn.join(' '))
                    .parent('.' + _c.wrapper)
                    .removeClass(_c.wrapper);
                //	Append to the <body>
                this.$menu[conf.menuInsertMethod](conf.menuInsertSelector);
                //	Open if url hash equals menu id (usefull when user clicks the hamburger icon before the menu is created)
                var hash = window.location.hash;
                if (hash) {
                    var id = this._getOriginalMenuId();
                    if (id && id == hash.slice(1)) {
                        setTimeout(function () {
                            that.open();
                        }, 1000);
                    }
                }
            });
            //	Add extension classes to <html>
            this.bind('initExtensions:after', function () {
                var exts = [_c.mm('widescreen'), _c.mm('iconbar')];
                for (var e = 0; e < exts.length; e++) {
                    for (var mdia in this.opts.extensions) {
                        if (this.opts.extensions[mdia].indexOf(exts[e]) > -1) {
                            (function (mdia, e) {
                                that.matchMedia(mdia, function () {
                                    glbl.$html.addClass(exts[e]);
                                }, function () {
                                    glbl.$html.removeClass(exts[e]);
                                });
                            })(mdia, e);
                            break;
                        }
                    }
                }
            });
            //	Add screenreader / aria support
            this.bind('open:start:sr-aria', function () {
                this.__sr_aria(this.$menu, 'hidden', false);
            });
            this.bind('close:finish:sr-aria', function () {
                this.__sr_aria(this.$menu, 'hidden', true);
            });
            this.bind('initMenu:after:sr-aria', function () {
                this.__sr_aria(this.$menu, 'hidden', true);
            });
        },
        //	add: fired once per page load
        add: function () {
            _c = $[_PLUGIN_]._c;
            _d = $[_PLUGIN_]._d;
            _e = $[_PLUGIN_]._e;
            _c.add('offcanvas slideout blocking modal background opening blocker page no-csstransforms3d');
            _d.add('style');
        },
        //	clickAnchor: prevents default behavior when clicking an anchor
        clickAnchor: function ($a, inMenu) {
            var that = this;
            if (!this.opts[_ADDON_]) {
                return;
            }
            //	Open menu
            var id = this._getOriginalMenuId();
            if (id) {
                if ($a.is('[href="#' + id + '"]')) {
                    //	Opening this menu from within this menu
                    //		-> Do nothing
                    if (inMenu) {
                        return true;
                    }
                    //	Opening this menu from within a second menu
                    //		-> Close the second menu before opening this menu
                    var $m = $a.closest('.' + _c.menu);
                    if ($m.length) {
                        var _m = $m.data('mmenu');
                        if (_m && _m.close) {
                            _m.close();
                            that.__transitionend($m, function () {
                                that.open();
                            }, that.conf.transitionDuration);
                            return true;
                        }
                    }
                    //	Opening this menu
                    this.open();
                    return true;
                }
            }
            //	Close menu
            if (!glbl.$page) {
                return;
            }
            id = glbl.$page.first().attr('id');
            if (id) {
                if ($a.is('[href="#' + id + '"]')) {
                    this.close();
                    return true;
                }
            }
            return;
        }
    };
    //	Default options and configuration
    $[_PLUGIN_].defaults[_ADDON_] = {
        position: 'left',
        zposition: 'back',
        blockUI: true,
        moveBackground: true
    };
    $[_PLUGIN_].configuration[_ADDON_] = {
        pageNodetype: 'div',
        pageSelector: null,
        noPageSelector: [],
        wrapPageIfNeeded: true,
        menuInsertMethod: 'prependTo',
        menuInsertSelector: 'body'
    };
    //	Methods
    $[_PLUGIN_].prototype.open = function () {
        this.trigger('open:before');
        if (this.vars.opened) {
            return;
        }
        var that = this;
        this._openSetup();
        //	Without the timeout, the animation won't work because the menu had display: none;
        setTimeout(function () {
            that._openFinish();
        }, this.conf.openingInterval);
        this.trigger('open:after');
    };
    $[_PLUGIN_].prototype._openSetup = function () {
        var that = this, opts = this.opts[_ADDON_];
        //	Close other menus
        this.closeAllOthers();
        //	Store style and position
        glbl.$page.each(function () {
            $(this).data(_d.style, $(this).attr('style') || '');
        });
        //	Trigger window-resize to measure height
        glbl.$wndw.trigger(_e.resize + '-' + _ADDON_, [true]);
        var clsn = [_c.opened];
        //	Add options
        if (opts.blockUI) {
            clsn.push(_c.blocking);
        }
        if (opts.blockUI == 'modal') {
            clsn.push(_c.modal);
        }
        if (opts.moveBackground) {
            clsn.push(_c.background);
        }
        if (opts.position != 'left') {
            clsn.push(_c.mm(this.opts[_ADDON_].position));
        }
        if (opts.zposition != 'back') {
            clsn.push(_c.mm(this.opts[_ADDON_].zposition));
        }
        glbl.$html.addClass(clsn.join(' '));
        //	Open
        //	Without the timeout, the animation won't work because the menu had display: none;
        setTimeout(function () {
            that.vars.opened = true;
        }, this.conf.openingInterval);
        this.$menu.addClass(_c.opened);
    };
    $[_PLUGIN_].prototype._openFinish = function () {
        var that = this;
        //	Callback
        this.__transitionend(glbl.$page.first(), function () {
            that.trigger('open:finish');
        }, this.conf.transitionDuration);
        //	Opening
        this.trigger('open:start');
        glbl.$html.addClass(_c.opening);
    };
    $[_PLUGIN_].prototype.close = function () {
        this.trigger('close:before');
        if (!this.vars.opened) {
            return;
        }
        var that = this;
        //	Callback
        this.__transitionend(glbl.$page.first(), function () {
            that.$menu.removeClass(_c.opened);
            var clsn = [
                _c.opened,
                _c.blocking,
                _c.modal,
                _c.background,
                _c.mm(that.opts[_ADDON_].position),
                _c.mm(that.opts[_ADDON_].zposition)
            ];
            glbl.$html.removeClass(clsn.join(' '));
            //	Restore style and position
            glbl.$page.each(function () {
                $(this).attr('style', $(this).data(_d.style));
            });
            that.vars.opened = false;
            that.trigger('close:finish');
        }, this.conf.transitionDuration);
        //	Closing
        this.trigger('close:start');
        glbl.$html.removeClass(_c.opening);
        this.trigger('close:after');
    };
    $[_PLUGIN_].prototype.closeAllOthers = function () {
        glbl.$body
            .find('.' + _c.menu + '.' + _c.offcanvas)
            .not(this.$menu)
            .each(function () {
            var api = $(this).data(_PLUGIN_);
            if (api && api.close) {
                api.close();
            }
        });
    };
    $[_PLUGIN_].prototype.setPage = function ($page) {
        this.trigger('setPage:before', $page);
        var that = this, conf = this.conf[_ADDON_];
        if (!$page || !$page.length) {
            $page = glbl.$body.find(conf.pageSelector);
            if (conf.noPageSelector.length) {
                $page = $page.not(conf.noPageSelector.join(', '));
            }
            if ($page.length > 1 && conf.wrapPageIfNeeded) {
                $page = $page
                    .wrapAll('<' + this.conf[_ADDON_].pageNodetype + ' />')
                    .parent();
            }
        }
        $page.each(function () {
            $(this).attr('id', $(this).attr('id') || that.__getUniqueId());
        });
        $page.addClass(_c.page + ' ' + _c.slideout);
        glbl.$page = $page;
        this.trigger('setPage:after', $page);
    };
    $[_PLUGIN_].prototype['_initWindow_' + _ADDON_] = function () {
        //	Prevent tabbing
        glbl.$wndw
            .off(_e.keydown + '-' + _ADDON_)
            .on(_e.keydown + '-' + _ADDON_, function (e) {
            if (glbl.$html.hasClass(_c.opened)) {
                if (e.keyCode == 9) {
                    e.preventDefault();
                    return false;
                }
            }
        });
        //	Set page min-height to window height
        var _h = 0;
        glbl.$wndw
            .off(_e.resize + '-' + _ADDON_)
            .on(_e.resize + '-' + _ADDON_, function (e, force) {
            if (glbl.$page.length == 1) {
                if (force || glbl.$html.hasClass(_c.opened)) {
                    var nh = glbl.$wndw.height();
                    if (force || nh != _h) {
                        _h = nh;
                        glbl.$page.css('minHeight', nh);
                    }
                }
            }
        });
    };
    $[_PLUGIN_].prototype._initBlocker = function () {
        var that = this;
        if (!this.opts[_ADDON_].blockUI) {
            return;
        }
        if (!glbl.$blck) {
            glbl.$blck = $('<div id="' + _c.blocker + '" class="' + _c.slideout + '" />');
        }
        glbl.$blck
            .appendTo(glbl.$body)
            .off(_e.touchstart + '-' + _ADDON_ + ' ' + _e.touchmove + '-' + _ADDON_)
            .on(_e.touchstart + '-' + _ADDON_ + ' ' + _e.touchmove + '-' + _ADDON_, function (e) {
            e.preventDefault();
            e.stopPropagation();
            glbl.$blck.trigger(_e.mousedown + '-' + _ADDON_);
        })
            .off(_e.mousedown + '-' + _ADDON_)
            .on(_e.mousedown + '-' + _ADDON_, function (e) {
            e.preventDefault();
            if (!glbl.$html.hasClass(_c.modal)) {
                that.closeAllOthers();
                that.close();
            }
        });
    };
    var _c, _d, _e, glbl;
})(jQuery);

/*
 * jQuery mmenu scrollBugFix add-on
 * mmenu.frebsite.nl
 *
 * Copyright (c) Fred Heusschen
 */
(function ($) {
    var _PLUGIN_ = 'mmenu';
    var _ADDON_ = 'scrollBugFix';
    $[_PLUGIN_].addons[_ADDON_] = {
        //	setup: fired once per menu
        setup: function () {
            var that = this, opts = this.opts[_ADDON_], conf = this.conf[_ADDON_];
            glbl = $[_PLUGIN_].glbl;
            if (!$[_PLUGIN_].support.touch || !this.opts.offCanvas || !this.opts.offCanvas.blockUI) {
                return;
            }
            //	Extend shorthand options
            if (typeof opts == 'boolean') {
                opts = {
                    fix: opts
                };
            }
            if (typeof opts != 'object') {
                opts = {};
            }
            opts = this.opts[_ADDON_] = $.extend(true, {}, $[_PLUGIN_].defaults[_ADDON_], opts);
            if (!opts.fix) {
                return;
            }
            this.bind('open:start', function () {
                this.$pnls.children('.' + _c.opened).scrollTop(0);
            });
            this.bind('initMenu:after', function () {
                this['_initWindow_' + _ADDON_]();
            });
        },
        //	add: fired once per page load
        add: function () {
            _c = $[_PLUGIN_]._c;
            _d = $[_PLUGIN_]._d;
            _e = $[_PLUGIN_]._e;
        },
        //	clickAnchor: prevents default behavior when clicking an anchor
        clickAnchor: function ($a, inMenu) { }
    };
    //	Default options and configuration
    $[_PLUGIN_].defaults[_ADDON_] = {
        fix: true
    };
    $[_PLUGIN_].prototype['_initWindow_' + _ADDON_] = function () {
        var that = this;
        //	Prevent body scroll
        glbl.$docu
            .off(_e.touchmove + '-' + _ADDON_)
            .on(_e.touchmove + '-' + _ADDON_, function (e) {
            if (glbl.$html.hasClass(_c.opened)) {
                e.preventDefault();
            }
        });
        var scrolling = false;
        glbl.$body
            .off(_e.touchstart + '-' + _ADDON_)
            .on(_e.touchstart + '-' + _ADDON_, '.' + _c.panels + '> .' + _c.panel, function (e) {
            if (glbl.$html.hasClass(_c.opened)) {
                if (!scrolling) {
                    scrolling = true;
                    if (e.currentTarget.scrollTop === 0) {
                        e.currentTarget.scrollTop = 1;
                    }
                    else if (e.currentTarget.scrollHeight === e.currentTarget.scrollTop + e.currentTarget.offsetHeight) {
                        e.currentTarget.scrollTop -= 1;
                    }
                    scrolling = false;
                }
            }
        })
            .off(_e.touchmove + '-' + _ADDON_)
            .on(_e.touchmove + '-' + _ADDON_, '.' + _c.panels + '> .' + _c.panel, function (e) {
            if (glbl.$html.hasClass(_c.opened)) {
                if ($(this)[0].scrollHeight > $(this).innerHeight()) {
                    e.stopPropagation();
                }
            }
        });
        //	Fix issue after device rotation change
        glbl.$wndw
            .off(_e.orientationchange + '-' + _ADDON_)
            .on(_e.orientationchange + '-' + _ADDON_, function () {
            that.$pnls
                .children('.' + _c.opened)
                .scrollTop(0)
                .css({ '-webkit-overflow-scrolling': 'auto' })
                .css({ '-webkit-overflow-scrolling': 'touch' });
        });
    };
    var _c, _d, _e, glbl;
})(jQuery);

/*
 * jQuery mmenu screenReader add-on
 * mmenu.frebsite.nl
 *
 * Copyright (c) Fred Heusschen
 */
(function ($) {
    var _PLUGIN_ = 'mmenu';
    var _ADDON_ = 'screenReader';
    $[_PLUGIN_].addons[_ADDON_] = {
        //	setup: fired once per menu
        setup: function () {
            var that = this, opts = this.opts[_ADDON_], conf = this.conf[_ADDON_];
            glbl = $[_PLUGIN_].glbl;
            //	Extend shorthand options
            if (typeof opts == 'boolean') {
                opts = {
                    aria: opts,
                    text: opts
                };
            }
            if (typeof opts != 'object') {
                opts = {};
            }
            opts = this.opts[_ADDON_] = $.extend(true, {}, $[_PLUGIN_].defaults[_ADDON_], opts);
            //	Aria
            if (opts.aria) {
                //	Add screenreader / aria hooks for add-ons
                //	In orde to keep this list short, only extend hooks that are actually used by other add-ons
                this.bind('initAddons:after', function () {
                    this.bind('initMenu:after', function () { this.trigger('initMenu:after:sr-aria'); });
                    this.bind('initNavbar:after', function () { this.trigger('initNavbar:after:sr-aria', arguments[0]); });
                    this.bind('openPanel:start', function () { this.trigger('openPanel:start:sr-aria', arguments[0]); });
                    this.bind('close:start', function () { this.trigger('close:start:sr-aria'); });
                    this.bind('close:finish', function () { this.trigger('close:finish:sr-aria'); });
                    this.bind('open:start', function () { this.trigger('open:start:sr-aria'); });
                    this.bind('open:finish', function () { this.trigger('open:finish:sr-aria'); });
                });
                //	Update aria-hidden for hidden / visible listitems
                this.bind('updateListview', function () {
                    this.$pnls
                        .find('.' + _c.listview)
                        .children()
                        .each(function () {
                        that.__sr_aria($(this), 'hidden', $(this).is('.' + _c.hidden));
                    });
                });
                //	Update aria-hidden for the panels when opening a panel
                this.bind('openPanel:start', function ($panel) {
                    var $hidden = this.$menu
                        .find('.' + _c.panel)
                        .not($panel)
                        .not($panel.parents('.' + _c.panel));
                    var $shown = $panel.add($panel
                        .find('.' + _c.vertical + '.' + _c.opened)
                        .children('.' + _c.panel));
                    this.__sr_aria($hidden, 'hidden', true);
                    this.__sr_aria($shown, 'hidden', false);
                });
                this.bind('closePanel', function ($panel) {
                    this.__sr_aria($panel, 'hidden', true);
                });
                //	Add aria-haspopup and aria-owns to prev- and next buttons
                this.bind('initPanels:after', function ($panels) {
                    var $btns = $panels
                        .find('.' + _c.prev + ', .' + _c.next)
                        .each(function () {
                        that.__sr_aria($(this), 'owns', $(this).attr('href').replace('#', ''));
                    });
                    this.__sr_aria($btns, 'haspopup', true);
                });
                //	Add aria-hidden for navbars in panels
                this.bind('initNavbar:after', function ($panel) {
                    var $navbar = $panel.children('.' + _c.navbar);
                    this.__sr_aria($navbar, 'hidden', !$panel.hasClass(_c.hasnavbar));
                });
                //	Text
                if (opts.text) {
                    //	Add aria-hidden to item text if the full-width next button has screen reader text
                    this.bind('initlistview:after', function ($panel) {
                        var $span = $panel
                            .find('.' + _c.listview)
                            .find('.' + _c.fullsubopen)
                            .parent()
                            .children('span');
                        this.__sr_aria($span, 'hidden', true);
                    });
                    //	Add aria-hidden to titles in navbars
                    if (this.opts.navbar.titleLink == 'parent') {
                        this.bind('initNavbar:after', function ($panel) {
                            var $navbar = $panel.children('.' + _c.navbar), hidden = ($navbar.children('.' + _c.prev).length) ? true : false;
                            this.__sr_aria($navbar.children('.' + _c.title), 'hidden', hidden);
                        });
                    }
                }
            }
            //	Text
            if (opts.text) {
                //	Add screenreader / text hooks for add-ons
                //	In orde to keep this list short, only extend hooks that are actually used by other add-ons
                this.bind('initAddons:after', function () {
                    this.bind('setPage:after', function () { this.trigger('setPage:after:sr-text', arguments[0]); });
                });
                //	Add text to the prev-buttons
                this.bind('initNavbar:after', function ($panel) {
                    var $navbar = $panel.children('.' + _c.navbar), _text = $navbar.children('.' + _c.title).text();
                    var txt = $[_PLUGIN_].i18n(conf.text.closeSubmenu);
                    if (_text) {
                        txt += ' (' + _text + ')';
                    }
                    $navbar.children('.' + _c.prev).html(this.__sr_text(txt));
                });
                //	Add text to the next-buttons
                this.bind('initListview:after', function ($panel) {
                    var $parent = $panel.data(_d.parent);
                    if ($parent && $parent.length) {
                        var $next = $parent.children('.' + _c.next), _text = $next.nextAll('span, a').first().text();
                        var txt = $[_PLUGIN_].i18n(conf.text[$next.parent().is('.' + _c.vertical) ? 'toggleSubmenu' : 'openSubmenu']);
                        if (_text) {
                            txt += ' (' + _text + ')';
                        }
                        $next.html(that.__sr_text(txt));
                    }
                });
            }
        },
        //	add: fired once per page load
        add: function () {
            _c = $[_PLUGIN_]._c;
            _d = $[_PLUGIN_]._d;
            _e = $[_PLUGIN_]._e;
            _c.add('sronly');
        },
        //	clickAnchor: prevents default behavior when clicking an anchor
        clickAnchor: function ($a, inMenu) { }
    };
    //	Default options and configuration
    $[_PLUGIN_].defaults[_ADDON_] = {
        aria: true,
        text: true
    };
    $[_PLUGIN_].configuration[_ADDON_] = {
        text: {
            closeMenu: 'Close menu',
            closeSubmenu: 'Close submenu',
            openSubmenu: 'Open submenu',
            toggleSubmenu: 'Toggle submenu'
        }
    };
    //	Methods
    $[_PLUGIN_].prototype.__sr_aria = function ($elem, attr, value) {
        $elem
            .prop('aria-' + attr, value)[value ? 'attr' : 'removeAttr']('aria-' + attr, value);
    };
    $[_PLUGIN_].prototype.__sr_text = function (text) {
        return '<span class="' + _c.sronly + '">' + text + '</span>';
    };
    var _c, _d, _e, glbl;
})(jQuery);

/*
 * jQuery mmenu autoHeight add-on
 * mmenu.frebsite.nl
 *
 * Copyright (c) Fred Heusschen
 */
(function ($) {
    var _PLUGIN_ = 'mmenu';
    var _ADDON_ = 'autoHeight';
    $[_PLUGIN_].addons[_ADDON_] = {
        //	setup: fired once per menu
        setup: function () {
            var that = this, opts = this.opts[_ADDON_], conf = this.conf[_ADDON_];
            glbl = $[_PLUGIN_].glbl;
            //	Extend shorthand options
            if (typeof opts == 'boolean' && opts) {
                opts = {
                    height: 'auto'
                };
            }
            if (typeof opts == 'string') {
                opts = {
                    height: opts
                };
            }
            if (typeof opts != 'object') {
                opts = {};
            }
            opts = this.opts[_ADDON_] = $.extend(true, {}, $[_PLUGIN_].defaults[_ADDON_], opts);
            if (opts.height != 'auto' && opts.height != 'highest') {
                return;
            }
            this.bind('initMenu:after', function () {
                this.$menu.addClass(_c.autoheight);
            });
            //	Set the height
            var setHeight = function ($panel) {
                if (this.opts.offCanvas && !this.vars.opened) {
                    return;
                }
                var _top = Math.max(parseInt(this.$pnls.css('top'), 10), 0) || 0, _bot = Math.max(parseInt(this.$pnls.css('bottom'), 10), 0) || 0, _hgh = 0;
                this.$menu.addClass(_c.measureheight);
                if (opts.height == 'auto') {
                    $panel = $panel || this.$pnls.children('.' + _c.opened);
                    if ($panel.is('.' + _c.vertical)) {
                        $panel = $panel
                            .parents('.' + _c.panel)
                            .not('.' + _c.vertical);
                    }
                    if (!$panel.length) {
                        $panel = this.$pnls.children('.' + _c.panel);
                    }
                    _hgh = $panel.first().outerHeight();
                }
                else if (opts.height == 'highest') {
                    this.$pnls.children()
                        .each(function () {
                        var $panel = $(this);
                        if ($panel.is('.' + _c.vertical)) {
                            $panel = $panel
                                .parents('.' + _c.panel)
                                .not('.' + _c.vertical)
                                .first();
                        }
                        _hgh = Math.max(_hgh, $panel.outerHeight());
                    });
                }
                this.$menu
                    .height(_hgh + _top + _bot)
                    .removeClass(_c.measureheight);
            };
            if (this.opts.offCanvas) {
                this.bind('open:start', setHeight);
            }
            if (opts.height == 'highest') {
                this.bind('initPanels:after', setHeight);
            }
            if (opts.height == 'auto') {
                this.bind('updateListview', setHeight);
                this.bind('openPanel:start', setHeight);
                this.bind('closePanel', setHeight);
            }
        },
        //	add: fired once per page load
        add: function () {
            _c = $[_PLUGIN_]._c;
            _d = $[_PLUGIN_]._d;
            _e = $[_PLUGIN_]._e;
            _c.add('autoheight measureheight');
            _e.add('resize');
        },
        //	clickAnchor: prevents default behavior when clicking an anchor
        clickAnchor: function ($a, inMenu) { }
    };
    //	Default options and configuration
    $[_PLUGIN_].defaults[_ADDON_] = {
        height: 'default' // 'default/highest/auto'
    };
    var _c, _d, _e, glbl;
})(jQuery);

/*
 * jQuery mmenu backButton add-on
 * mmenu.frebsite.nl
 *
 * Copyright (c) Fred Heusschen
 */
(function ($) {
    var _PLUGIN_ = 'mmenu';
    var _ADDON_ = 'backButton';
    $[_PLUGIN_].addons[_ADDON_] = {
        //	setup: fired once per menu
        setup: function () {
            if (!this.opts.offCanvas) {
                return;
            }
            var that = this, opts = this.opts[_ADDON_], conf = this.conf[_ADDON_];
            glbl = $[_PLUGIN_].glbl;
            //	Extend shorthand options
            if (typeof opts == 'boolean') {
                opts = {
                    close: opts
                };
            }
            if (typeof opts != 'object') {
                opts = {};
            }
            opts = $.extend(true, {}, $[_PLUGIN_].defaults[_ADDON_], opts);
            //	Close menu
            if (opts.close) {
                var _hash = '#' + that.$menu.attr('id');
                this.bind('open:finish', function (e) {
                    if (location.hash != _hash) {
                        history.pushState(null, document.title, _hash);
                    }
                });
                $(window).on('popstate', function (e) {
                    if (glbl.$html.hasClass(_c.opened)) {
                        e.stopPropagation();
                        that.close();
                    }
                    else if (location.hash == _hash) {
                        e.stopPropagation();
                        that.open();
                    }
                });
            }
        },
        //	add: fired once per page load
        add: function () {
            if (!window.history || !window.history.pushState) {
                $[_PLUGIN_].addons[_ADDON_].setup = function () { };
                return;
            }
            _c = $[_PLUGIN_]._c;
            _d = $[_PLUGIN_]._d;
            _e = $[_PLUGIN_]._e;
        },
        //	clickAnchor: prevents default behavior when clicking an anchor
        clickAnchor: function ($a, inMenu) { }
    };
    //	Default options and configuration
    $[_PLUGIN_].defaults[_ADDON_] = {
        close: false
    };
    var _c, _d, _e, glbl;
})(jQuery);

/*
 * jQuery mmenu columns add-on
 * mmenu.frebsite.nl
 *
 * Copyright (c) Fred Heusschen
 */
(function ($) {
    var _PLUGIN_ = 'mmenu';
    var _ADDON_ = 'columns';
    $[_PLUGIN_].addons[_ADDON_] = {
        //	setup: fired once per menu
        setup: function () {
            var that = this, opts = this.opts[_ADDON_], conf = this.conf[_ADDON_];
            glbl = $[_PLUGIN_].glbl;
            //	Extend shorthand options
            if (typeof opts == 'boolean') {
                opts = {
                    add: opts
                };
            }
            if (typeof opts == 'number') {
                opts = {
                    add: true,
                    visible: opts
                };
            }
            if (typeof opts != 'object') {
                opts = {};
            }
            if (typeof opts.visible == 'number') {
                opts.visible = {
                    min: opts.visible,
                    max: opts.visible
                };
            }
            opts = this.opts[_ADDON_] = $.extend(true, {}, $[_PLUGIN_].defaults[_ADDON_], opts);
            //	Add the columns
            if (opts.add) {
                opts.visible.min = Math.max(1, Math.min(6, opts.visible.min));
                opts.visible.max = Math.max(opts.visible.min, Math.min(6, opts.visible.max));
                var $mnu = (this.opts.offCanvas) ? this.$menu.add(glbl.$html) : this.$menu, clsn = '';
                for (var i = 0; i <= opts.visible.max; i++) {
                    clsn += ' ' + _c.columns + '-' + i;
                }
                if (clsn.length) {
                    clsn = clsn.slice(1);
                }
                var countPanels = function ($panel) {
                    var _num = this.$pnls.children('.' + _c.subopened).length;
                    if ($panel && !$panel.hasClass(_c.subopened)) {
                        _num++;
                    }
                    _num = Math.min(opts.visible.max, Math.max(opts.visible.min, _num));
                    $mnu.removeClass(clsn)
                        .addClass(_c.columns + '-' + _num);
                };
                var uncountPanels = function () {
                    $mnu.removeClass(clsn);
                };
                var setupPanels = function ($panel) {
                    $panel = $panel || this.$pnls.children('.' + _c.opened);
                    this.$pnls
                        .children('.' + _c.panel)
                        .removeClass(clsn)
                        .filter('.' + _c.subopened)
                        .add($panel)
                        .slice(-opts.visible.max)
                        .each(function (i) {
                        $(this).addClass(_c.columns + '-' + i);
                    });
                };
                this.bind('initMenu:after', function () {
                    this.$menu.addClass(_c.columns);
                });
                this.bind('openPanel:start', countPanels);
                this.bind('openPanel:start', setupPanels);
            }
        },
        //	add: fired once per page load
        add: function () {
            _c = $[_PLUGIN_]._c;
            _d = $[_PLUGIN_]._d;
            _e = $[_PLUGIN_]._e;
            _c.add('columns');
        },
        //	clickAnchor: prevents default behavior when clicking an anchor
        clickAnchor: function ($a, inMenu) {
            if (!this.opts[_ADDON_].add) {
                return false;
            }
            if (inMenu) {
                var that = this;
                var _h = $a.attr('href');
                if (_h.length > 1 && _h.slice(0, 1) == '#') {
                    try {
                        var $h = $(_h, this.$menu);
                        if ($h.is('.' + _c.panel)) {
                            var colnr = parseInt($a.closest('.' + _c.panel).attr('class').split(_c.columns + '-')[1].split(' ')[0], 10) + 1;
                            while (colnr > 0) {
                                var $panl = this.$pnls.children('.' + _c.columns + '-' + colnr);
                                if ($panl.length) {
                                    colnr++;
                                    $panl
                                        .removeClass(_c.subopened)
                                        .removeClass(_c.opened)
                                        .removeClass(_c.highest)
                                        .addClass(_c.hidden);
                                }
                                else {
                                    colnr = -1;
                                    break;
                                }
                            }
                        }
                    }
                    catch (err) { }
                }
            }
        }
    };
    //	Default options and configuration
    $[_PLUGIN_].defaults[_ADDON_] = {
        add: false,
        visible: {
            min: 1,
            max: 3
        }
    };
    var _c, _d, _e, glbl;
})(jQuery);

/*
 * jQuery mmenu counters add-on
 * mmenu.frebsite.nl
 *
 * Copyright (c) Fred Heusschen
 */
(function ($) {
    var _PLUGIN_ = 'mmenu';
    var _ADDON_ = 'counters';
    $[_PLUGIN_].addons[_ADDON_] = {
        //	setup: fired once per menu
        setup: function () {
            var that = this, opts = this.opts[_ADDON_], conf = this.conf[_ADDON_];
            glbl = $[_PLUGIN_].glbl;
            //	Extend shorthand options
            if (typeof opts == 'boolean') {
                opts = {
                    add: opts,
                    update: opts
                };
            }
            if (typeof opts != 'object') {
                opts = {};
            }
            opts = this.opts[_ADDON_] = $.extend(true, {}, $[_PLUGIN_].defaults[_ADDON_], opts);
            //	Refactor counter class
            this.bind('initListview:after', function ($panel) {
                this.__refactorClass($('em', $panel), this.conf.classNames[_ADDON_].counter, 'counter');
            });
            //	Add the counters
            if (opts.add) {
                this.bind('initListview:after', function ($panel) {
                    var $wrapper;
                    switch (opts.addTo) {
                        case 'panels':
                            $wrapper = $panel;
                            break;
                        default:
                            $wrapper = $panel.filter(opts.addTo);
                            break;
                    }
                    $wrapper
                        .each(function () {
                        var $parent = $(this).data(_d.parent);
                        if ($parent) {
                            if (!$parent.children('em.' + _c.counter).length) {
                                $parent.prepend($('<em class="' + _c.counter + '" />'));
                            }
                        }
                    });
                });
            }
            if (opts.update) {
                var count = function ($panels) {
                    $panels = $panels || this.$pnls.children('.' + _c.panel);
                    $panels.each(function () {
                        var $panel = $(this), $parent = $panel.data(_d.parent);
                        if (!$parent) {
                            return;
                        }
                        var $counter = $parent.children('em.' + _c.counter);
                        if (!$counter.length) {
                            return;
                        }
                        $panel = $panel.children('.' + _c.listview);
                        if (!$panel.length) {
                            return;
                        }
                        $counter.html(that.__filterListItems($panel.children()).length);
                    });
                };
                this.bind('initListview:after', count);
                this.bind('updateListview', count);
            }
        },
        //	add: fired once per page load
        add: function () {
            _c = $[_PLUGIN_]._c;
            _d = $[_PLUGIN_]._d;
            _e = $[_PLUGIN_]._e;
            _c.add('counter search noresultsmsg');
        },
        //	clickAnchor: prevents default behavior when clicking an anchor
        clickAnchor: function ($a, inMenu) { }
    };
    //	Default options and configuration
    $[_PLUGIN_].defaults[_ADDON_] = {
        add: false,
        addTo: 'panels',
        count: false
    };
    $[_PLUGIN_].configuration.classNames[_ADDON_] = {
        counter: 'Counter'
    };
    var _c, _d, _e, glbl;
})(jQuery);

/*
 * jQuery mmenu dividers add-on
 * mmenu.frebsite.nl
 *
 * Copyright (c) Fred Heusschen
 */
(function ($) {
    var _PLUGIN_ = 'mmenu';
    var _ADDON_ = 'dividers';
    $[_PLUGIN_].addons[_ADDON_] = {
        //	setup: fired once per menu
        setup: function () {
            var that = this, opts = this.opts[_ADDON_], conf = this.conf[_ADDON_];
            glbl = $[_PLUGIN_].glbl;
            //	Extend shorthand options
            if (typeof opts == 'boolean') {
                opts = {
                    add: opts,
                    fixed: opts
                };
            }
            if (typeof opts != 'object') {
                opts = {};
            }
            opts = this.opts[_ADDON_] = $.extend(true, {}, $[_PLUGIN_].defaults[_ADDON_], opts);
            //	Refactor collapsed class
            this.bind('initListview:after', function ($panel) {
                this.__refactorClass($panel.find('li'), this.conf.classNames[_ADDON_].collapsed, 'collapsed');
            });
            //	Add dividers
            if (opts.add) {
                this.bind('initListview:after', function ($panel) {
                    var $wrapper;
                    switch (opts.addTo) {
                        case 'panels':
                            $wrapper = $panel;
                            break;
                        default:
                            $wrapper = $panel.filter(opts.addTo);
                            break;
                    }
                    if (!$wrapper.length) {
                        return;
                    }
                    $wrapper
                        .find('.' + _c.listview)
                        .find('.' + _c.divider)
                        .remove()
                        .end()
                        .each(function () {
                        var last = '';
                        that.__filterListItems($(this).children())
                            .each(function () {
                            var letter = $.trim($(this).children('a, span').text()).slice(0, 1).toLowerCase();
                            if (letter != last && letter.length) {
                                last = letter;
                                $('<li class="' + _c.divider + '">' + letter + '</li>').insertBefore(this);
                            }
                        });
                    });
                });
            }
            //	Toggle collapsed list items
            if (opts.collapse) {
                this.bind('initListview:after', function ($panel) {
                    $panel
                        .find('.' + _c.divider)
                        .each(function () {
                        var $l = $(this), $e = $l.nextUntil('.' + _c.divider, '.' + _c.collapsed);
                        if ($e.length) {
                            if (!$l.children('.' + _c.next).length) {
                                $l.wrapInner('<span />');
                                $l.prepend('<a href="#" class="' + _c.next + ' ' + _c.fullsubopen + '" />');
                            }
                        }
                    });
                });
            }
            //	Fixed dividers
            if (opts.fixed) {
                //	Add the fixed divider
                this.bind('initPanels:after', function () {
                    if (typeof this.$fixeddivider == 'undefined') {
                        this.$fixeddivider = $('<ul class="' + _c.listview + ' ' + _c.fixeddivider + '"><li class="' + _c.divider + '"></li></ul>')
                            .prependTo(this.$pnls)
                            .children();
                    }
                });
                var setValue = function ($panel) {
                    $panel = $panel || this.$pnls.children('.' + _c.opened);
                    if ($panel.is(':hidden')) {
                        return;
                    }
                    var $dvdr = $panel
                        .children('.' + _c.listview)
                        .children('.' + _c.divider)
                        .not('.' + _c.hidden);
                    var scrl = $panel.scrollTop() || 0, text = '';
                    $dvdr.each(function () {
                        if ($(this).position().top + scrl < scrl + 1) {
                            text = $(this).text();
                        }
                    });
                    this.$fixeddivider.text(text);
                    this.$pnls[text.length ? 'addClass' : 'removeClass'](_c.hasdividers);
                };
                //	Set correct value when opening menu
                this.bind('open:start', setValue);
                //	Set correct value when opening a panel
                this.bind('openPanel:start', setValue);
                //	Set correct value after updating listviews
                this.bind('updateListview', setValue);
                //	Set correct value after scrolling
                this.bind('initPanel:after', function ($panel) {
                    $panel
                        .off(_e.scroll + '-' + _ADDON_ + ' ' + _e.touchmove + '-' + _ADDON_)
                        .on(_e.scroll + '-' + _ADDON_ + ' ' + _e.touchmove + '-' + _ADDON_, function (e) {
                        setValue.call(that, $panel);
                    });
                });
            }
        },
        //	add: fired once per page load
        add: function () {
            _c = $[_PLUGIN_]._c;
            _d = $[_PLUGIN_]._d;
            _e = $[_PLUGIN_]._e;
            _c.add('collapsed uncollapsed fixeddivider hasdividers');
            _e.add('scroll');
        },
        //	clickAnchor: prevents default behavior when clicking an anchor
        clickAnchor: function ($a, inMenu) {
            if (this.opts[_ADDON_].collapse && inMenu) {
                var $l = $a.parent();
                if ($l.is('.' + _c.divider)) {
                    var $e = $l.nextUntil('.' + _c.divider, '.' + _c.collapsed);
                    $l.toggleClass(_c.opened);
                    $e[$l.hasClass(_c.opened) ? 'addClass' : 'removeClass'](_c.uncollapsed);
                    return true;
                }
            }
            return false;
        }
    };
    //	Default options and configuration
    $[_PLUGIN_].defaults[_ADDON_] = {
        add: false,
        addTo: 'panels',
        fixed: false,
        collapse: false
    };
    $[_PLUGIN_].configuration.classNames[_ADDON_] = {
        collapsed: 'Collapsed'
    };
    var _c, _d, _e, glbl;
})(jQuery);

/*
 * jQuery mmenu drag add-on
 * mmenu.frebsite.nl
 *
 * Copyright (c) Fred Heusschen
 */
(function ($) {
    var _PLUGIN_ = 'mmenu';
    var _ADDON_ = 'drag';
    $[_PLUGIN_].addons[_ADDON_] = {
        //	setup: fired once per menu
        setup: function () {
            if (!this.opts.offCanvas) {
                return;
            }
            var that = this, opts = this.opts[_ADDON_], conf = this.conf[_ADDON_];
            glbl = $[_PLUGIN_].glbl;
            //	Extend shorthand options
            if (typeof opts == 'boolean') {
                opts = {
                    menu: opts,
                    panels: opts
                };
            }
            if (typeof opts != 'object') {
                opts = {};
            }
            if (typeof opts.menu == 'boolean') {
                opts.menu = {
                    open: opts.menu
                };
            }
            if (typeof opts.menu != 'object') {
                opts.menu = {};
            }
            if (typeof opts.panels == 'boolean') {
                opts.panels = {
                    close: opts.panels
                };
            }
            if (typeof opts.panels != 'object') {
                opts.panels = {};
            }
            opts = this.opts[_ADDON_] = $.extend(true, {}, $[_PLUGIN_].defaults[_ADDON_], opts);
            //	Drag open the menu
            if (opts.menu.open) {
                this.bind('setPage:after', function () {
                    dragOpenMenu.call(this, opts.menu, conf.menu, glbl);
                });
            }
            //	Drag close panels
            if (opts.panels.close) {
                this.bind('initPanel:after', function ($panel) {
                    dragClosePanel.call(this, $panel, opts.panels, conf.panels, glbl);
                });
            }
        },
        //	add: fired once per page load
        add: function () {
            if (typeof Hammer != 'function' || Hammer.VERSION < 2) {
                $[_PLUGIN_].addons[_ADDON_].add = function () { };
                $[_PLUGIN_].addons[_ADDON_].setup = function () { };
                return;
            }
            _c = $[_PLUGIN_]._c;
            _d = $[_PLUGIN_]._d;
            _e = $[_PLUGIN_]._e;
            _c.add('dragging');
        },
        //	clickAnchor: prevents default behavior when clicking an anchor
        clickAnchor: function ($a, inMenu) { }
    };
    //	Default options and configuration
    $[_PLUGIN_].defaults[_ADDON_] = {
        menu: {
            open: false,
            //		node	: null,
            maxStartPos: 100,
            threshold: 50
        },
        panels: {
            close: false
        },
        vendors: {
            hammer: {}
        }
    };
    $[_PLUGIN_].configuration[_ADDON_] = {
        menu: {
            width: {
                perc: 0.8,
                min: 140,
                max: 440
            },
            height: {
                perc: 0.8,
                min: 140,
                max: 880
            }
        },
        panels: {}
    };
    var _c, _d, _e, glbl;
    function minMax(val, min, max) {
        if (val < min) {
            val = min;
        }
        if (val > max) {
            val = max;
        }
        return val;
    }
    function dragOpenMenu(opts, conf, glbl) {
        //	Set up variables
        var that = this;
        //	defaults for "left"
        var drag = {
            events: 'panleft panright',
            typeLower: 'x',
            typeUpper: 'X',
            open_dir: 'right',
            close_dir: 'left',
            negative: false
        };
        var _dimension = 'width', _direction = drag.open_dir;
        var doPanstart = function (pos) {
            if (pos <= opts.maxStartPos) {
                _stage = 1;
            }
        };
        var getSlideNodes = function () {
            return $('.' + _c.slideout);
        };
        var _stage = 0, _distance = 0, _maxDistance = 0;
        var new_distance, drag_distance, css_value;
        switch (this.opts.offCanvas.position) {
            case 'top':
            case 'bottom':
                drag.events = 'panup pandown';
                drag.typeLower = 'y';
                drag.typeUpper = 'Y';
                _dimension = 'height';
                break;
        }
        switch (this.opts.offCanvas.position) {
            case 'right':
            case 'bottom':
                drag.negative = true;
                doPanstart = function (pos) {
                    if (pos >= glbl.$wndw[_dimension]() - opts.maxStartPos) {
                        _stage = 1;
                    }
                };
                break;
        }
        switch (this.opts.offCanvas.position) {
            case 'left':
                break;
            case 'right':
                drag.open_dir = 'left';
                drag.close_dir = 'right';
                break;
            case 'top':
                drag.open_dir = 'down';
                drag.close_dir = 'up';
                break;
            case 'bottom':
                drag.open_dir = 'up';
                drag.close_dir = 'down';
                break;
        }
        switch (this.opts.offCanvas.zposition) {
            case 'front':
                getSlideNodes = function () {
                    return this.$menu;
                };
                break;
        }
        var $dragNode = this.__valueOrFn(opts.node, this.$menu, glbl.$page);
        if (typeof $dragNode == 'string') {
            $dragNode = $($dragNode);
        }
        //	Bind events
        var _hammer = new Hammer($dragNode[0], this.opts[_ADDON_].vendors.hammer);
        _hammer
            .on('panstart', function (e) {
            doPanstart(e.center[drag.typeLower]);
            glbl.$slideOutNodes = getSlideNodes();
            _direction = drag.open_dir;
        });
        _hammer
            .on(drag.events + ' panend', function (e) {
            if (_stage > 0) {
                e.preventDefault();
            }
        });
        _hammer
            .on(drag.events, function (e) {
            new_distance = e['delta' + drag.typeUpper];
            if (drag.negative) {
                new_distance = -new_distance;
            }
            if (new_distance != _distance) {
                _direction = (new_distance >= _distance) ? drag.open_dir : drag.close_dir;
            }
            _distance = new_distance;
            if (_distance > opts.threshold) {
                if (_stage == 1) {
                    if (glbl.$html.hasClass(_c.opened)) {
                        return;
                    }
                    _stage = 2;
                    that._openSetup();
                    that.trigger('open:start');
                    glbl.$html.addClass(_c.dragging);
                    _maxDistance = minMax(glbl.$wndw[_dimension]() * conf[_dimension].perc, conf[_dimension].min, conf[_dimension].max);
                }
            }
            if (_stage == 2) {
                drag_distance = minMax(_distance, 10, _maxDistance) - (that.opts.offCanvas.zposition == 'front' ? _maxDistance : 0);
                if (drag.negative) {
                    drag_distance = -drag_distance;
                }
                css_value = 'translate' + drag.typeUpper + '(' + drag_distance + 'px )';
                glbl.$slideOutNodes.css({
                    '-webkit-transform': '-webkit-' + css_value,
                    'transform': css_value
                });
            }
        });
        _hammer
            .on('panend', function (e) {
            if (_stage == 2) {
                glbl.$html.removeClass(_c.dragging);
                glbl.$slideOutNodes.css('transform', '');
                that[_direction == drag.open_dir ? '_openFinish' : 'close']();
            }
            _stage = 0;
        });
    }
    function dragClosePanel($panel, opts, conf, glbl) {
        var that = this;
        var $parent = $panel.data(_d.parent);
        if ($parent) {
            $parent = $parent.closest('.' + _c.panel);
            var _hammer = new Hammer($panel[0], that.opts[_ADDON_].vendors.hammer), timeout = null;
            _hammer
                .on('panright', function (e) {
                if (timeout) {
                    return;
                }
                that.openPanel($parent);
                //	prevent dragging while panel still open.
                timeout = setTimeout(function () {
                    clearTimeout(timeout);
                    timeout = null;
                }, that.conf.openingInterval + that.conf.transitionDuration);
            });
        }
    }
})(jQuery);

/*
 * jQuery mmenu dropdown add-on
 * mmenu.frebsite.nl
 *
 * Copyright (c) Fred Heusschen
 */
(function ($) {
    var _PLUGIN_ = 'mmenu';
    var _ADDON_ = 'dropdown';
    $[_PLUGIN_].addons[_ADDON_] = {
        //	setup: fired once per menu
        setup: function () {
            if (!this.opts.offCanvas) {
                return;
            }
            var that = this, opts = this.opts[_ADDON_], conf = this.conf[_ADDON_];
            glbl = $[_PLUGIN_].glbl;
            //	Extend shorthand options
            if (typeof opts == 'boolean' && opts) {
                opts = {
                    drop: opts
                };
            }
            if (typeof opts != 'object') {
                opts = {};
            }
            if (typeof opts.position == 'string') {
                opts.position = {
                    of: opts.position
                };
            }
            opts = this.opts[_ADDON_] = $.extend(true, {}, $[_PLUGIN_].defaults[_ADDON_], opts);
            if (!opts.drop) {
                return;
            }
            var $bttn;
            this.bind('initMenu:after', function () {
                this.$menu.addClass(_c.dropdown);
                if (opts.tip) {
                    this.$menu.addClass(_c.tip);
                }
                if (typeof opts.position.of != 'string') {
                    var id = this._getOriginalMenuId();
                    if (id && id.length) {
                        opts.position.of = '[href="#' + id + '"]';
                    }
                }
                if (typeof opts.position.of != 'string') {
                    return;
                }
                //	Get the button to put the menu next to
                $bttn = $(opts.position.of);
                //	Emulate hover effect
                opts.event = opts.event.split(' ');
                if (opts.event.length == 1) {
                    opts.event[1] = opts.event[0];
                }
                if (opts.event[0] == 'hover') {
                    $bttn
                        .on(_e.mouseenter + '-' + _ADDON_, function () {
                        that.open();
                    });
                }
                if (opts.event[1] == 'hover') {
                    this.$menu
                        .on(_e.mouseleave + '-' + _ADDON_, function () {
                        that.close();
                    });
                }
            });
            //	Add/remove classname and style when opening/closing the menu
            this.bind('open:start', function () {
                this.$menu.data(_d.style, this.$menu.attr('style') || '');
                glbl.$html.addClass(_c.dropdown);
            });
            this.bind('close:finish', function () {
                this.$menu.attr('style', this.$menu.data(_d.style));
                glbl.$html.removeClass(_c.dropdown);
            });
            //	Update the position and sizes
            var getPosition = function (dir, obj) {
                var css = obj[0], cls = obj[1];
                var _scr = dir == 'x' ? 'scrollLeft' : 'scrollTop', _out = dir == 'x' ? 'outerWidth' : 'outerHeight', _str = dir == 'x' ? 'left' : 'top', _stp = dir == 'x' ? 'right' : 'bottom', _siz = dir == 'x' ? 'width' : 'height', _max = dir == 'x' ? 'maxWidth' : 'maxHeight', _pos = null;
                var scrl = glbl.$wndw[_scr](), strt = $bttn.offset()[_str] -= scrl, stop = strt + $bttn[_out](), wndw = glbl.$wndw[_siz]();
                var offs = conf.offset.button[dir] + conf.offset.viewport[dir];
                //	Position set in option
                if (opts.position[dir]) {
                    switch (opts.position[dir]) {
                        case 'left':
                        case 'bottom':
                            _pos = 'after';
                            break;
                        case 'right':
                        case 'top':
                            _pos = 'before';
                            break;
                    }
                }
                //	Position not set in option, find most space
                if (_pos === null) {
                    _pos = (strt + ((stop - strt) / 2) < wndw / 2) ? 'after' : 'before';
                }
                //	Set position and max
                var val, max;
                if (_pos == 'after') {
                    val = (dir == 'x') ? strt : stop;
                    max = wndw - (val + offs);
                    css[_str] = val + conf.offset.button[dir];
                    css[_stp] = 'auto';
                    cls.push(_c[(dir == 'x') ? 'tipleft' : 'tiptop']);
                }
                else {
                    val = (dir == 'x') ? stop : strt;
                    max = val - offs;
                    css[_stp] = 'calc( 100% - ' + (val - conf.offset.button[dir]) + 'px )';
                    css[_str] = 'auto';
                    cls.push(_c[(dir == 'x') ? 'tipright' : 'tipbottom']);
                }
                css[_max] = Math.min(conf[_siz].max, max);
                return [css, cls];
            };
            var position = function ($panl) {
                if (!this.vars.opened) {
                    return;
                }
                this.$menu.attr('style', this.$menu.data(_d.style));
                var obj = [{}, []];
                obj = getPosition.call(this, 'y', obj);
                obj = getPosition.call(this, 'x', obj);
                this.$menu.css(obj[0]);
                if (opts.tip) {
                    this.$menu
                        .removeClass(_c.tipleft + ' ' +
                        _c.tipright + ' ' +
                        _c.tiptop + ' ' +
                        _c.tipbottom)
                        .addClass(obj[1].join(' '));
                }
            };
            this.bind('open:start', position);
            glbl.$wndw
                .on(_e.resize + '-' + _ADDON_, function (e) {
                position.call(that);
            });
            if (!this.opts.offCanvas.blockUI) {
                glbl.$wndw
                    .on(_e.scroll + '-' + _ADDON_, function (e) {
                    position.call(that);
                });
            }
        },
        //	add: fired once per page load
        add: function () {
            _c = $[_PLUGIN_]._c;
            _d = $[_PLUGIN_]._d;
            _e = $[_PLUGIN_]._e;
            _c.add('dropdown tip tipleft tipright tiptop tipbottom');
            _e.add('mouseenter mouseleave resize scroll');
        },
        //	clickAnchor: prevents default behavior when clicking an anchor
        clickAnchor: function ($a, inMenu) { }
    };
    //	Default options and configuration
    $[_PLUGIN_].defaults[_ADDON_] = {
        drop: false,
        event: 'click',
        position: {},
        tip: true
    };
    $[_PLUGIN_].configuration[_ADDON_] = {
        offset: {
            button: {
                x: -10,
                y: 10
            },
            viewport: {
                x: 20,
                y: 20
            }
        },
        height: {
            max: 880
        },
        width: {
            max: 440
        }
    };
    var _c, _d, _e, glbl;
})(jQuery);

/*
 * jQuery mmenu fixedElements add-on
 * mmenu.frebsite.nl
 *
 * Copyright (c) Fred Heusschen
 */
(function ($) {
    var _PLUGIN_ = 'mmenu';
    var _ADDON_ = 'fixedElements';
    $[_PLUGIN_].addons[_ADDON_] = {
        //	setup: fired once per menu
        setup: function () {
            if (!this.opts.offCanvas) {
                return;
            }
            var that = this, opts = this.opts[_ADDON_], conf = this.conf[_ADDON_];
            glbl = $[_PLUGIN_].glbl;
            var setPage = function ($page) {
                //	Fixed elements
                var _fixd = this.conf.classNames[_ADDON_].fixed, $fixd = $page.find('.' + _fixd);
                this.__refactorClass($fixd, _fixd, 'slideout');
                $fixd[conf.elemInsertMethod](conf.elemInsertSelector);
                //	Sticky elements
                var _stck = this.conf.classNames[_ADDON_].sticky, $stck = $page.find('.' + _stck);
                this.__refactorClass($stck, _stck, 'sticky');
                $stck = $page.find('.' + _c.sticky);
                if ($stck.length) {
                    this.bind('open:before', function () {
                        var _s = glbl.$wndw.scrollTop() + conf.sticky.offset;
                        $stck.each(function () {
                            $(this).css('top', parseInt($(this).css('top'), 10) + _s);
                        });
                    });
                    this.bind('close:finish', function () {
                        $stck.css('top', '');
                    });
                }
            };
            this.bind('setPage:after', setPage);
        },
        //	add: fired once per page load
        add: function () {
            _c = $[_PLUGIN_]._c;
            _d = $[_PLUGIN_]._d;
            _e = $[_PLUGIN_]._e;
            _c.add('sticky');
        },
        //	clickAnchor: prevents default behavior when clicking an anchor
        clickAnchor: function ($a, inMenu) { }
    };
    //	Default options and configuration
    $[_PLUGIN_].configuration[_ADDON_] = {
        sticky: {
            offset: 0
        },
        elemInsertMethod: 'appendTo',
        elemInsertSelector: 'body'
    };
    $[_PLUGIN_].configuration.classNames[_ADDON_] = {
        fixed: 'Fixed',
        sticky: 'Sticky'
    };
    var _c, _d, _e, glbl;
})(jQuery);

/*
 * jQuery mmenu iconPanels add-on
 * mmenu.frebsite.nl
 *
 * Copyright (c) Fred Heusschen
 */
(function ($) {
    var _PLUGIN_ = 'mmenu';
    var _ADDON_ = 'iconPanels';
    $[_PLUGIN_].addons[_ADDON_] = {
        //	setup: fired once per menu
        setup: function () {
            var that = this, opts = this.opts[_ADDON_], conf = this.conf[_ADDON_];
            glbl = $[_PLUGIN_].glbl;
            //	Extend shorthand options
            if (typeof opts == 'boolean') {
                opts = {
                    add: opts
                };
            }
            if (typeof opts == 'number') {
                opts = {
                    add: true,
                    visible: opts
                };
            }
            if (typeof opts != 'object') {
                opts = {};
            }
            opts = this.opts[_ADDON_] = $.extend(true, {}, $[_PLUGIN_].defaults[_ADDON_], opts);
            opts.visible++;
            //	Add the iconbars
            if (opts.add) {
                var clsn = '';
                for (var i = 0; i <= opts.visible; i++) {
                    clsn += ' ' + _c.iconpanel + '-' + i;
                }
                if (clsn.length) {
                    clsn = clsn.slice(1);
                }
                var setPanels = function ($panel) {
                    if ($panel.hasClass(_c.vertical)) {
                        return;
                    }
                    that.$pnls
                        .children('.' + _c.panel)
                        .removeClass(clsn)
                        .filter('.' + _c.subopened)
                        .removeClass(_c.hidden)
                        .add($panel)
                        .not('.' + _c.vertical)
                        .slice(-opts.visible)
                        .each(function (i) {
                        $(this).addClass(_c.iconpanel + '-' + i);
                    });
                };
                this.bind('initMenu:after', function () {
                    this.$menu.addClass(_c.iconpanel);
                });
                this.bind('openPanel:start', setPanels);
                this.bind('initPanels:after', function ($panels) {
                    setPanels.call(that, that.$pnls.children('.' + _c.opened));
                });
                this.bind('initListview:after', function ($panel) {
                    if (!$panel.hasClass(_c.vertical)) {
                        if (!$panel.children('.' + _c.subblocker).length) {
                            $panel.prepend('<a href="#' + $panel.closest('.' + _c.panel).attr('id') + '" class="' + _c.subblocker + '" />');
                        }
                    }
                });
            }
        },
        //	add: fired once per page load
        add: function () {
            _c = $[_PLUGIN_]._c;
            _d = $[_PLUGIN_]._d;
            _e = $[_PLUGIN_]._e;
            _c.add('iconpanel subblocker');
        },
        //	clickAnchor: prevents default behavior when clicking an anchor
        clickAnchor: function ($a, inMenu) { }
    };
    //	Default options and configuration
    $[_PLUGIN_].defaults[_ADDON_] = {
        add: false,
        visible: 3
    };
    var _c, _d, _e, glbl;
})(jQuery);

/*
 * jQuery mmenu keyboardNavigation add-on
 * mmenu.frebsite.nl
 *
 * Copyright (c) Fred Heusschen
 */
(function ($) {
    var _PLUGIN_ = 'mmenu';
    var _ADDON_ = 'keyboardNavigation';
    $[_PLUGIN_].addons[_ADDON_] = {
        //	setup: fired once per menu
        setup: function () {
            //	Keyboard navigation on touchscreens opens the virtual keyboard :/
            if ($[_PLUGIN_].support.touch) {
                return;
            }
            var that = this, opts = this.opts[_ADDON_], conf = this.conf[_ADDON_];
            glbl = $[_PLUGIN_].glbl;
            //	Extend shorthand options
            if (typeof opts == 'boolean' || typeof opts == 'string') {
                opts = {
                    enable: opts
                };
            }
            if (typeof opts != 'object') {
                opts = {};
            }
            opts = this.opts[_ADDON_] = $.extend(true, {}, $[_PLUGIN_].defaults[_ADDON_], opts);
            //	Enable keyboard navigation
            if (opts.enable) {
                var $start = $('<button class="' + _c.tabstart + '" tabindex="0" type="button" />'), $end = $('<button class="' + _c.tabend + '" tabindex="0" type="button" />');
                this.bind('initMenu:after', function () {
                    if (opts.enhance) {
                        this.$menu.addClass(_c.keyboardfocus);
                    }
                    this['_initWindow_' + _ADDON_](opts.enhance);
                });
                this.bind('initOpened:before', function () {
                    this.$menu
                        .prepend($start)
                        .append($end)
                        .children('.' + _c.mm('navbars-top') + ', .' + _c.mm('navbars-bottom'))
                        .children('.' + _c.navbar)
                        .children('a.' + _c.title)
                        .attr('tabindex', -1);
                });
                this.bind('open:start', function () {
                    tabindex.call(this);
                });
                this.bind('open:finish', function () {
                    focus.call(this, null, opts.enable);
                });
                this.bind('openPanel:start', function ($panl) {
                    tabindex.call(this, $panl);
                });
                this.bind('openPanel:finish', function ($panl) {
                    focus.call(this, $panl, opts.enable);
                });
                //	Add screenreader / aria support
                this.bind('initOpened:after', function () {
                    this.__sr_aria(this.$menu.children('.' + _c.mm('tabstart') + ', .' + _c.mm('tabend')), 'hidden', true);
                });
            }
        },
        //	add: fired once per page load
        add: function () {
            _c = $[_PLUGIN_]._c;
            _d = $[_PLUGIN_]._d;
            _e = $[_PLUGIN_]._e;
            _c.add('tabstart tabend keyboardfocus');
            _e.add('focusin keydown');
        },
        //	clickAnchor: prevents default behavior when clicking an anchor
        clickAnchor: function ($a, inMenu) { }
    };
    //	Default options and configuration
    $[_PLUGIN_].defaults[_ADDON_] = {
        enable: false,
        enhance: false
    };
    $[_PLUGIN_].configuration[_ADDON_] = {};
    //	Methods
    $[_PLUGIN_].prototype['_initWindow_' + _ADDON_] = function (enhance) {
        //	Re-enable tabbing in general
        glbl.$wndw
            .off(_e.keydown + '-offCanvas');
        //	Prevent tabbing outside an offcanvas menu
        glbl.$wndw
            .off(_e.focusin + '-' + _ADDON_)
            .on(_e.focusin + '-' + _ADDON_, function (e) {
            if (glbl.$html.hasClass(_c.opened)) {
                var $t = $(e.target);
                if ($t.is('.' + _c.tabend)) {
                    $t.parent().find('.' + _c.tabstart).focus();
                }
            }
        });
        //	Default keyboard navigation
        glbl.$wndw
            .off(_e.keydown + '-' + _ADDON_)
            .on(_e.keydown + '-' + _ADDON_, function (e) {
            var $t = $(e.target), $m = $t.closest('.' + _c.menu);
            if ($m.length) {
                var api = $m.data('mmenu');
                //	special case for input and textarea
                if ($t.is('input, textarea')) {
                }
                else {
                    switch (e.keyCode) {
                        //	press enter to toggle and check
                        case 13:
                            if ($t.is('.mm-toggle') ||
                                $t.is('.mm-check')) {
                                $t.trigger(_e.click);
                            }
                            break;
                        //	prevent spacebar or arrows from scrolling the page
                        case 32: //	space
                        case 37: //	left
                        case 38: //	top
                        case 39: //	right
                        case 40://	bottom
                            e.preventDefault();
                            break;
                    }
                }
            }
        });
        //	Enhanced keyboard navigation
        if (enhance) {
            glbl.$wndw
                .off(_e.keydown + '-' + _ADDON_)
                .on(_e.keydown + '-' + _ADDON_, function (e) {
                var $t = $(e.target), $m = $t.closest('.' + _c.menu);
                if ($m.length) {
                    var api = $m.data('mmenu');
                    //	special case for input and textarea
                    if ($t.is('input, textarea')) {
                        switch (e.keyCode) {
                            //	empty searchfield with esc
                            case 27:
                                $t.val('');
                                break;
                        }
                    }
                    else {
                        switch (e.keyCode) {
                            //	close submenu with backspace
                            case 8:
                                var $p = $t.closest('.' + _c.panel).data(_d.parent);
                                if ($p && $p.length) {
                                    api.openPanel($p.closest('.' + _c.panel));
                                }
                                break;
                            //	close menu with esc
                            case 27:
                                if ($m.hasClass(_c.offcanvas)) {
                                    api.close();
                                }
                                break;
                        }
                    }
                }
            });
        }
    };
    var _c, _d, _e, glbl;
    var focs = 'input, select, textarea, button, label, a[href]';
    function focus($panl, enable) {
        $panl = $panl || this.$pnls.children('.' + _c.opened);
        var $focs = $(), $navb = this.$menu
            .children('.' + _c.mm('navbars-top') + ', .' + _c.mm('navbars-bottom'))
            .children('.' + _c.navbar);
        //	already focus in navbar
        if ($navb.find(focs).filter(':focus').length) {
            return;
        }
        if (enable == 'default') {
            //	first anchor in listview
            $focs = $panl.children('.' + _c.listview).find('a[href]').not('.' + _c.hidden);
            //	first element in panel
            if (!$focs.length) {
                $focs = $panl.find(focs).not('.' + _c.hidden);
            }
            //	first anchor in navbar
            if (!$focs.length) {
                $focs = $navb
                    .find(focs)
                    .not('.' + _c.hidden);
            }
        }
        //	default
        if (!$focs.length) {
            $focs = this.$menu.children('.' + _c.tabstart);
        }
        $focs.first().focus();
    }
    function tabindex($panl) {
        if (!$panl) {
            $panl = this.$pnls.children('.' + _c.opened);
        }
        var $pnls = this.$pnls.children('.' + _c.panel), $hidn = $pnls.not($panl);
        $hidn.find(focs).attr('tabindex', -1);
        $panl.find(focs).attr('tabindex', 0);
        //	_c.toggle will result in an empty string if the toggle addon is not loaded
        $panl.find('.' + _c.mm('toggle') + ', .' + _c.mm('check')).attr('tabindex', -1);
        $panl.children('.' + _c.navbar).children('.' + _c.title).attr('tabindex', -1);
    }
})(jQuery);

/*
 * jQuery mmenu lazySubmenus add-on
 * mmenu.frebsite.nl
 *
 * Copyright (c) Fred Heusschen
 */
(function ($) {
    var _PLUGIN_ = 'mmenu';
    var _ADDON_ = 'lazySubmenus';
    $[_PLUGIN_].addons[_ADDON_] = {
        //	setup: fired once per menu
        setup: function () {
            var that = this, opts = this.opts[_ADDON_], conf = this.conf[_ADDON_];
            glbl = $[_PLUGIN_].glbl;
            //	Extend shorthand options
            if (typeof opts == 'boolean') {
                opts = {
                    load: opts
                };
            }
            if (typeof opts != 'object') {
                opts = {};
            }
            opts = this.opts[_ADDON_] = $.extend(true, {}, $[_PLUGIN_].defaults[_ADDON_], opts);
            //	Sliding submenus
            if (opts.load) {
                //	prevent all sub panels from initPanels
                this.bind('initMenu:after', function () {
                    this.$pnls
                        .find('li')
                        .children(this.conf.panelNodetype)
                        .not('.' + _c.inset)
                        .not('.' + _c.nolistview)
                        .not('.' + _c.nopanel)
                        .addClass(_c.lazysubmenu + ' ' + _c.nolistview + ' ' + _c.nopanel);
                });
                //	prepare current and one level sub panels for initPanels
                this.bind('initPanels:before', function ($panels) {
                    $panels = $panels || this.$pnls.children(this.conf.panelNodetype);
                    this.__findAddBack($panels, '.' + _c.lazysubmenu)
                        .not('.' + _c.lazysubmenu + ' .' + _c.lazysubmenu)
                        .removeClass(_c.lazysubmenu + ' ' + _c.nolistview + ' ' + _c.nopanel);
                });
                //	initPanels for the default opened panel
                this.bind('initOpened:before', function () {
                    var $selected = this.$pnls
                        .find('.' + this.conf.classNames.selected)
                        .parents('.' + _c.lazysubmenu);
                    if ($selected.length) {
                        $selected.removeClass(_c.lazysubmenu + ' ' + _c.nolistview + ' ' + _c.nopanel);
                        this.initPanels($selected.last());
                    }
                });
                //	initPanels for current- and sub panels before openPanel
                this.bind('openPanel:before', function ($panel) {
                    var $panels = this.__findAddBack($panel, '.' + _c.lazysubmenu)
                        .not('.' + _c.lazysubmenu + ' .' + _c.lazysubmenu);
                    if ($panels.length) {
                        this.initPanels($panels);
                    }
                });
            }
        },
        //	add: fired once per page load
        add: function () {
            _c = $[_PLUGIN_]._c;
            _d = $[_PLUGIN_]._d;
            _e = $[_PLUGIN_]._e;
            _c.add('lazysubmenu');
            _d.add('lazysubmenu');
        },
        //	clickAnchor: prevents default behavior when clicking an anchor
        clickAnchor: function ($a, inMenu) { }
    };
    //	Default options and configuration
    $[_PLUGIN_].defaults[_ADDON_] = {
        load: false
    };
    $[_PLUGIN_].configuration[_ADDON_] = {};
    var _c, _d, _e, glbl;
})(jQuery);

/*
 * jQuery mmenu navbar add-on
 * mmenu.frebsite.nl
 *
 * Copyright (c) Fred Heusschen
 */
(function ($) {
    var _PLUGIN_ = 'mmenu';
    var _ADDON_ = 'navbars';
    $[_PLUGIN_].addons[_ADDON_] = {
        //	setup: fired once per menu
        setup: function () {
            var that = this, navs = this.opts[_ADDON_], conf = this.conf[_ADDON_];
            glbl = $[_PLUGIN_].glbl;
            if (typeof navs == 'undefined') {
                return;
            }
            if (!(navs instanceof Array)) {
                navs = [navs];
            }
            var _pos = {}, $pos = {};
            if (!navs.length) {
                return;
            }
            $.each(navs, function (n) {
                var opts = navs[n];
                //	Extend shorthand options
                if (typeof opts == 'boolean' && opts) {
                    opts = {};
                }
                if (typeof opts != 'object') {
                    opts = {};
                }
                if (typeof opts.content == 'undefined') {
                    opts.content = ['prev', 'title'];
                }
                if (!(opts.content instanceof Array)) {
                    opts.content = [opts.content];
                }
                opts = $.extend(true, {}, that.opts.navbar, opts);
                //	Create node
                var $navbar = $('<div class="' + _c.navbar + '" />');
                //	Get height
                var hght = opts.height;
                if (typeof hght != 'number') {
                    hght = 1;
                }
                hght = Math.min(4, Math.max(1, hght));
                $navbar.addClass(_c.navbar + '-size-' + hght);
                //	Get position
                var poss = opts.position;
                if (poss != 'bottom') {
                    poss = 'top';
                }
                if (!_pos[poss]) {
                    _pos[poss] = 0;
                }
                _pos[poss] += hght;
                if (!$pos[poss]) {
                    $pos[poss] = $('<div class="' + _c.navbars + '-' + poss + '" />');
                }
                $pos[poss].append($navbar);
                //	Add content
                var cont = 0;
                for (var c = 0, l = opts.content.length; c < l; c++) {
                    var ctnt = $[_PLUGIN_].addons[_ADDON_][opts.content[c]] || false;
                    if (ctnt) {
                        cont += ctnt.call(that, $navbar, opts, conf);
                    }
                    else {
                        ctnt = opts.content[c];
                        if (!(ctnt instanceof $)) {
                            ctnt = $(opts.content[c]);
                        }
                        $navbar.append(ctnt);
                    }
                }
                cont += Math.ceil($navbar.children().not('.' + _c.btn).length / hght);
                if (cont > 1) {
                    $navbar.addClass(_c.navbar + '-content-' + cont);
                }
                if ($navbar.children('.' + _c.btn).length) {
                    $navbar.addClass(_c.hasbtns);
                }
            });
            //	Add to menu
            this.bind('initMenu:after', function () {
                for (var poss in _pos) {
                    this.$menu.addClass(_c.hasnavbar + '-' + poss + '-' + _pos[poss]);
                    this.$menu[poss == 'bottom' ? 'append' : 'prepend']($pos[poss]);
                }
            });
        },
        //	add: fired once per page load
        add: function () {
            _c = $[_PLUGIN_]._c;
            _d = $[_PLUGIN_]._d;
            _e = $[_PLUGIN_]._e;
            _c.add('navbars close hasbtns');
        },
        //	clickAnchor: prevents default behavior when clicking an anchor
        clickAnchor: function ($a, inMenu) { }
    };
    //	Default options and configuration
    $[_PLUGIN_].configuration[_ADDON_] = {
        breadcrumbSeparator: '/'
    };
    $[_PLUGIN_].configuration.classNames[_ADDON_] = {};
    var _c, _d, _e, glbl;
})(jQuery);

/*
 * jQuery mmenu navbar add-on breadcrumbs content
 * mmenu.frebsite.nl
 *
 * Copyright (c) Fred Heusschen
 */
(function ($) {
    var _PLUGIN_ = 'mmenu';
    var _ADDON_ = 'navbars';
    var _CONTENT_ = 'breadcrumbs';
    $[_PLUGIN_].addons[_ADDON_][_CONTENT_] = function ($navbar, opts, conf) {
        var that = this;
        //	Get vars
        var _c = $[_PLUGIN_]._c, _d = $[_PLUGIN_]._d;
        _c.add('breadcrumbs separator');
        //	Add content
        var $crumbs = $('<span class="' + _c.breadcrumbs + '" />').appendTo($navbar);
        this.bind('initNavbar:after', function ($panel) {
            $panel.removeClass(_c.hasnavbar);
            var crumbs = [], $bcrb = $('<span class="' + _c.breadcrumbs + '"></span>'), $crnt = $panel, first = true;
            while ($crnt && $crnt.length) {
                if (!$crnt.is('.' + _c.panel)) {
                    $crnt = $crnt.closest('.' + _c.panel);
                }
                if (!$crnt.hasClass(_c.vertical)) {
                    var text = $crnt.children('.' + _c.navbar).children('.' + _c.title).text();
                    crumbs.unshift(first ? '<span>' + text + '</span>' : '<a href="#' + $crnt.attr('id') + '">' + text + '</a>');
                    first = false;
                }
                $crnt = $crnt.data(_d.parent);
            }
            $bcrb
                .append(crumbs.join('<span class="' + _c.separator + '">' + conf.breadcrumbSeparator + '</span>'))
                .appendTo($panel.children('.' + _c.navbar));
        });
        //	Update for to opened panel
        this.bind('openPanel:start', function ($panel) {
            $crumbs.html($panel
                .children('.' + _c.navbar)
                .children('.' + _c.breadcrumbs)
                .html() || '');
        });
        //	Add screenreader / aria support
        this.bind('initNavbar:after:sr-aria', function ($panel) {
            $panel
                .children('.' + _c.navbar)
                .children('.' + _c.breadcrumbs)
                .children('a')
                .each(function () {
                that.__sr_aria($(this), 'owns', $(this).attr('href').slice(1));
            });
        });
        //	Maintain content count
        return 0;
    };
})(jQuery);

/*
 * jQuery mmenu navbar add-on close content
 * mmenu.frebsite.nl
 *
 * Copyright (c) Fred Heusschen
 */
(function ($) {
    var _PLUGIN_ = 'mmenu';
    var _ADDON_ = 'navbars';
    var _CONTENT_ = 'close';
    $[_PLUGIN_].addons[_ADDON_][_CONTENT_] = function ($navbar, opts) {
        //	Get vars
        var _c = $[_PLUGIN_]._c, glbl = $[_PLUGIN_].glbl;
        //	Add content
        var $close = $('<a class="' + _c.close + ' ' + _c.btn + '" href="#" />')
            .appendTo($navbar);
        //	Update to page node
        this.bind('setPage:after', function ($page) {
            $close.attr('href', '#' + $page.attr('id'));
        });
        //	Add screenreader / text support
        this.bind('setPage:after:sr-text', function ($page) {
            $close.html(this.__sr_text($[_PLUGIN_].i18n(this.conf.screenReader.text.closeMenu)));
            this.__sr_aria($close, 'owns', $close.attr('href').slice(1));
        });
        //	Detract content count
        return -1;
    };
})(jQuery);

/*
 * jQuery mmenu navbar add-on next content
 * mmenu.frebsite.nl
 *
 * Copyright (c) Fred Heusschen
 */
(function ($) {
    var _PLUGIN_ = 'mmenu';
    var _ADDON_ = 'navbars';
    var _CONTENT_ = 'next';
    $[_PLUGIN_].addons[_ADDON_][_CONTENT_] = function ($navbar, opts) {
        //	Get vars
        var _c = $[_PLUGIN_]._c;
        //	Add content
        var $next = $('<a class="' + _c.next + ' ' + _c.btn + '" href="#" />')
            .appendTo($navbar);
        //	Update to opened panel
        var $org;
        var _url, _txt;
        this.bind('openPanel:start', function ($panel) {
            $org = $panel.find('.' + this.conf.classNames[_ADDON_].panelNext);
            _url = $org.attr('href');
            _txt = $org.html();
            if (_url) {
                $next.attr('href', _url);
            }
            else {
                $next.removeAttr('href');
            }
            $next[_url || _txt ? 'removeClass' : 'addClass'](_c.hidden);
            $next.html(_txt);
        });
        //	Add screenreader / aria support
        this.bind('openPanel:start:sr-aria', function ($panel) {
            this.__sr_aria($next, 'hidden', $next.hasClass(_c.hidden));
            this.__sr_aria($next, 'owns', ($next.attr('href') || '').slice(1));
        });
        //	Detract content count
        return -1;
    };
    $[_PLUGIN_].configuration.classNames[_ADDON_].panelNext = 'Next';
})(jQuery);

/*
 * jQuery mmenu navbar add-on prev content
 * mmenu.frebsite.nl
 *
 * Copyright (c) Fred Heusschen
 */
(function ($) {
    var _PLUGIN_ = 'mmenu';
    var _ADDON_ = 'navbars';
    var _CONTENT_ = 'prev';
    $[_PLUGIN_].addons[_ADDON_][_CONTENT_] = function ($navbar, opts) {
        //	Get vars
        var _c = $[_PLUGIN_]._c;
        //	Add content
        var $prev = $('<a class="' + _c.prev + ' ' + _c.btn + '" href="#" />')
            .appendTo($navbar);
        this.bind('initNavbar:after', function ($panel) {
            $panel.removeClass(_c.hasnavbar);
        });
        //	Update to opened panel
        var $org;
        var _url, _txt;
        this.bind('openPanel:start', function ($panel) {
            if ($panel.hasClass(_c.vertical)) {
                return;
            }
            $org = $panel.find('.' + this.conf.classNames[_ADDON_].panelPrev);
            if (!$org.length) {
                $org = $panel.children('.' + _c.navbar).children('.' + _c.prev);
            }
            _url = $org.attr('href');
            _txt = $org.html();
            if (_url) {
                $prev.attr('href', _url);
            }
            else {
                $prev.removeAttr('href');
            }
            $prev[_url || _txt ? 'removeClass' : 'addClass'](_c.hidden);
            $prev.html(_txt);
        });
        //	Add screenreader / aria support
        this.bind('initNavbar:after:sr-aria', function ($panel) {
            var $navbar = $panel.children('.' + _c.navbar);
            this.__sr_aria($navbar, 'hidden', true);
        });
        this.bind('openPanel:start:sr-aria', function ($panel) {
            this.__sr_aria($prev, 'hidden', $prev.hasClass(_c.hidden));
            this.__sr_aria($prev, 'owns', ($prev.attr('href') || '').slice(1));
        });
        //	Detract content count
        return -1;
    };
    $[_PLUGIN_].configuration.classNames[_ADDON_].panelPrev = 'Prev';
})(jQuery);

/*
 * jQuery mmenu navbar add-on searchfield content
 * mmenu.frebsite.nl
 *
 * Copyright (c) Fred Heusschen
 */
(function ($) {
    var _PLUGIN_ = 'mmenu';
    var _ADDON_ = 'navbars';
    var _CONTENT_ = 'searchfield';
    $[_PLUGIN_].addons[_ADDON_][_CONTENT_] = function ($navbar, opts) {
        var _c = $[_PLUGIN_]._c;
        var $srch = $('<div class="' + _c.search + '" />')
            .appendTo($navbar);
        if (typeof this.opts.searchfield != 'object') {
            this.opts.searchfield = {};
        }
        this.opts.searchfield.add = true;
        this.opts.searchfield.addTo = $srch;
        //	Maintain content count
        return 0;
    };
})(jQuery);

/*
 * jQuery mmenu navbar add-on title content
 * mmenu.frebsite.nl
 *
 * Copyright (c) Fred Heusschen
 */
(function ($) {
    var _PLUGIN_ = 'mmenu';
    var _ADDON_ = 'navbars';
    var _CONTENT_ = 'title';
    $[_PLUGIN_].addons[_ADDON_][_CONTENT_] = function ($navbar, opts) {
        //	Get vars
        var _c = $[_PLUGIN_]._c;
        //	Add content
        var $title = $('<a class="' + _c.title + '" />')
            .appendTo($navbar);
        //	Update to opened panel
        var _url, _txt;
        var $org;
        this.bind('openPanel:start', function ($panel) {
            if ($panel.hasClass(_c.vertical)) {
                return;
            }
            $org = $panel.find('.' + this.conf.classNames[_ADDON_].panelTitle);
            if (!$org.length) {
                $org = $panel.children('.' + _c.navbar).children('.' + _c.title);
            }
            _url = $org.attr('href');
            _txt = $org.html() || opts.title;
            if (_url) {
                $title.attr('href', _url);
            }
            else {
                $title.removeAttr('href');
            }
            $title[_url || _txt ? 'removeClass' : 'addClass'](_c.hidden);
            $title.html(_txt);
        });
        //	Add screenreader / aria support
        var $prev;
        this.bind('openPanel:start:sr-aria', function ($panel) {
            if (this.opts.screenReader.text) {
                if (!$prev) {
                    $prev = this.$menu
                        .children('.' + _c.navbars + '-top' + ', .' + _c.navbars + '-bottom')
                        .children('.' + _c.navbar)
                        .children('.' + _c.prev);
                }
                if ($prev.length) {
                    var hidden = true;
                    if (this.opts.navbar.titleLink == 'parent') {
                        hidden = !$prev.hasClass(_c.hidden);
                    }
                    this.__sr_aria($title, 'hidden', hidden);
                }
            }
        });
        //	Maintain content count
        return 0;
    };
    $[_PLUGIN_].configuration.classNames[_ADDON_].panelTitle = 'Title';
})(jQuery);

/*
 * jQuery mmenu pageScroll add-on
 * mmenu.frebsite.nl
 *
 * Copyright (c) Fred Heusschen
 */
(function ($) {
    var _PLUGIN_ = 'mmenu';
    var _ADDON_ = 'pageScroll';
    $[_PLUGIN_].addons[_ADDON_] = {
        //	setup: fired once per menu
        setup: function () {
            var that = this, opts = this.opts[_ADDON_], conf = this.conf[_ADDON_];
            glbl = $[_PLUGIN_].glbl;
            //	Extend shorthand options
            if (typeof opts == 'boolean') {
                opts = {
                    scroll: opts
                };
            }
            opts = this.opts[_ADDON_] = $.extend(true, {}, $[_PLUGIN_].defaults[_ADDON_], opts);
            if (opts.scroll) {
                this.bind('close:finish', function () {
                    scrollTo(conf.scrollOffset);
                });
            }
            if (opts.update) {
                var that = this, orgs = [], scts = [];
                that.bind('initListview:after', function ($panel) {
                    that.__filterListItemAnchors($panel.find('.' + _c.listview).children('li'))
                        .each(function () {
                        var href = $(this).attr('href');
                        if (anchorInPage(href)) {
                            orgs.push(href);
                        }
                    });
                    scts = orgs.reverse();
                });
                var _selected = -1;
                glbl.$wndw
                    .on(_e.scroll + '-' + _ADDON_, function (e) {
                    var ofst = glbl.$wndw.scrollTop();
                    for (var s = 0; s < scts.length; s++) {
                        if ($(scts[s]).offset().top < ofst + conf.updateOffset) {
                            if (_selected !== s) {
                                _selected = s;
                                that.setSelected(that.__filterListItemAnchors(that.$pnls.children('.' + _c.opened).find('.' + _c.listview).children('li'))
                                    .filter('[href="' + scts[s] + '"]')
                                    .parent());
                            }
                            break;
                        }
                    }
                });
            }
        },
        //	add: fired once per page load
        add: function () {
            _c = $[_PLUGIN_]._c;
            _d = $[_PLUGIN_]._d;
            _e = $[_PLUGIN_]._e;
        },
        //	clickAnchor: prevents default behavior when clicking an anchor
        clickAnchor: function ($a, inMenu) {
            $section = false;
            if (!inMenu ||
                !this.opts[_ADDON_].scroll ||
                !this.opts.offCanvas ||
                !glbl.$page ||
                !glbl.$page.length) {
                return;
            }
            var href = $a.attr('href');
            if (anchorInPage(href)) {
                $section = $(href);
                if (glbl.$html.hasClass(_c.mm('widescreen'))) {
                    scrollTo(this.conf[_ADDON_].scrollOffset);
                }
            }
        }
    };
    //	Default options and configuration
    $[_PLUGIN_].defaults[_ADDON_] = {
        scroll: false,
        update: false
    };
    $[_PLUGIN_].configuration[_ADDON_] = {
        scrollOffset: 0,
        updateOffset: 50
    };
    var _c, _d, _e, glbl;
    //	Should be 'JQuery | boolean' and not 'any', but 'JQuery' gives an error on "offset"
    var $section = false;
    function scrollTo(offset) {
        if ($section && $section.length && $section.is(':visible')) {
            glbl.$html.add(glbl.$body).animate({
                scrollTop: $section.offset().top + offset
            });
        }
        $section = false;
    }
    function anchorInPage(href) {
        try {
            if (href != '#' &&
                href.slice(0, 1) == '#' &&
                glbl.$page.find(href).length) {
                return true;
            }
            return false;
        }
        catch (err) {
            return false;
        }
    }
})(jQuery);

/*
 * jQuery mmenu RTL add-on
 * mmenu.frebsite.nl
 *
 * Copyright (c) Fred Heusschen
 */
(function ($) {
    var _PLUGIN_ = 'mmenu';
    var _ADDON_ = 'rtl';
    $[_PLUGIN_].addons[_ADDON_] = {
        //	setup: fired once per menu
        setup: function () {
            var that = this, opts = this.opts[_ADDON_], conf = this.conf[_ADDON_];
            glbl = $[_PLUGIN_].glbl;
            //	Extend shorthand options
            if (typeof opts != 'object') {
                opts = {
                    use: opts
                };
            }
            opts = this.opts[_ADDON_] = $.extend(true, {}, $[_PLUGIN_].defaults[_ADDON_], opts);
            //	Autodetect
            if (typeof opts.use != 'boolean') {
                opts.use = ((glbl.$html.attr('dir') || '').toLowerCase() == 'rtl');
            }
            //	Use RTL
            if (opts.use) {
                this.bind('initMenu:after', function () {
                    this.$menu.addClass(_c.rtl);
                });
            }
        },
        //	add: fired once per page load
        add: function () {
            _c = $[_PLUGIN_]._c;
            _d = $[_PLUGIN_]._d;
            _e = $[_PLUGIN_]._e;
            _c.add('rtl');
        },
        //	clickAnchor: prevents default behavior when clicking an anchor
        clickAnchor: function ($a, inMenu) { }
    };
    //	Default options and configuration
    $[_PLUGIN_].defaults[_ADDON_] = {
        use: 'detect'
    };
    var _c, _d, _e, glbl;
})(jQuery);

/*
 * jQuery mmenu searchfield add-on
 * mmenu.frebsite.nl
 *
 * Copyright (c) Fred Heusschen
 */
(function ($) {
    var _PLUGIN_ = 'mmenu';
    var _ADDON_ = 'searchfield';
    $[_PLUGIN_].addons[_ADDON_] = {
        //	setup: fired once per menu
        setup: function () {
            var that = this, opts = this.opts[_ADDON_], conf = this.conf[_ADDON_];
            glbl = $[_PLUGIN_].glbl;
            //	Extend shorthand options
            if (typeof opts == 'boolean') {
                opts = {
                    add: opts
                };
            }
            if (typeof opts != 'object') {
                opts = {};
            }
            if (typeof opts.resultsPanel == 'boolean') {
                opts.resultsPanel = {
                    add: opts.resultsPanel
                };
            }
            opts = this.opts[_ADDON_] = $.extend(true, {}, $[_PLUGIN_].defaults[_ADDON_], opts);
            conf = this.conf[_ADDON_] = $.extend(true, {}, $[_PLUGIN_].configuration[_ADDON_], conf);
            //	Blur searchfield
            this.bind('close:start', function () {
                this.$menu
                    .find('.' + _c.search)
                    .find('input')
                    .blur();
            });
            //	Bind functions to update
            this.bind('initPanels:after', function ($panels) {
                //	Add the searchfield(s)
                if (opts.add) {
                    var $wrapper;
                    switch (opts.addTo) {
                        case 'panels':
                            $wrapper = $panels;
                            break;
                        default:
                            $wrapper = this.$menu.find(opts.addTo);
                            break;
                    }
                    $wrapper
                        .each(function () {
                        //	Add the searchfield
                        var $panl = $(this);
                        if ($panl.is('.' + _c.panel) && $panl.is('.' + _c.vertical)) {
                            return;
                        }
                        if (!$panl.children('.' + _c.search).length) {
                            var clear = that.__valueOrFn(conf.clear, $panl), form = that.__valueOrFn(conf.form, $panl), input = that.__valueOrFn(conf.input, $panl), submit = that.__valueOrFn(conf.submit, $panl);
                            var $srch = $('<' + (form ? 'form' : 'div') + ' class="' + _c.search + '" />'), $inpt = $('<input placeholder="' + $[_PLUGIN_].i18n(opts.placeholder) + '" type="text" autocomplete="off" />');
                            $srch.append($inpt);
                            var k;
                            if (input) {
                                for (k in input) {
                                    $inpt.attr(k, input[k]);
                                }
                            }
                            if (clear) {
                                $('<a class="' + _c.btn + ' ' + _c.clear + '" href="#" />')
                                    .appendTo($srch)
                                    .on(_e.click + '-searchfield', function (e) {
                                    e.preventDefault();
                                    $inpt
                                        .val('')
                                        .trigger(_e.keyup + '-searchfield');
                                });
                            }
                            if (form) {
                                for (k in form) {
                                    $srch.attr(k, form[k]);
                                }
                                if (submit && !clear) {
                                    $('<a class="' + _c.btn + ' ' + _c.next + '" href="#" />')
                                        .appendTo($srch)
                                        .on(_e.click + '-searchfield', function (e) {
                                        e.preventDefault();
                                        $srch.submit();
                                    });
                                }
                            }
                            if ($panl.hasClass(_c.search)) {
                                $panl.replaceWith($srch);
                            }
                            else {
                                $panl.prepend($srch)
                                    .addClass(_c.hassearch);
                            }
                        }
                        if (opts.noResults) {
                            var inPanel = $panl.closest('.' + _c.panel).length;
                            //	Not in a panel
                            if (!inPanel) {
                                $panl = that.$pnls.children('.' + _c.panel).first();
                            }
                            //	Add no-results message
                            if (!$panl.children('.' + _c.noresultsmsg).length) {
                                var $lst = $panl.children('.' + _c.listview).first();
                                //	Should be 'JQuery' and not 'any', but 'JQuery' gives an error...
                                var $nrm = $('<div class="' + _c.noresultsmsg + ' ' + _c.hidden + '" />');
                                $nrm.append($[_PLUGIN_].i18n(opts.noResults))[$lst.length ? 'insertAfter' : 'prependTo']($lst.length ? $lst : $panl);
                            }
                        }
                    });
                    //	Search through list items
                    if (opts.search) {
                        if (opts.resultsPanel.add) {
                            opts.showSubPanels = false;
                            var $rpnl = this.$pnls.children('.' + _c.resultspanel);
                            if (!$rpnl.length) {
                                $rpnl = $('<div class="' + _c.resultspanel + ' ' + _c.noanimation + ' ' + _c.hidden + '" />')
                                    .appendTo(this.$pnls)
                                    .append('<div class="' + _c.navbar + ' ' + _c.hidden + '"><a class="' + _c.title + '">' + $[_PLUGIN_].i18n(opts.resultsPanel.title) + '</a></div>')
                                    .append('<ul class="' + _c.listview + '" />')
                                    .append(this.$pnls.find('.' + _c.noresultsmsg).first().clone());
                                this._initPanel($rpnl);
                            }
                        }
                        this.$menu
                            .find('.' + _c.search)
                            .each(function () {
                            var $srch = $(this), inPanel = $srch.closest('.' + _c.panel).length;
                            var $pnls, $panl;
                            //	In a panel
                            if (inPanel) {
                                $pnls = $srch.closest('.' + _c.panel);
                                $panl = $pnls;
                            }
                            else {
                                $pnls = that.$pnls.find('.' + _c.panel);
                                $panl = that.$menu;
                            }
                            if (opts.resultsPanel.add) {
                                $pnls = $pnls.not($rpnl);
                            }
                            var $inpt = $srch.children('input'), $itms = that.__findAddBack($pnls, '.' + _c.listview).children('li'), $dvdr = $itms.filter('.' + _c.divider), $rslt = that.__filterListItems($itms);
                            var _anchor = 'a', _both = _anchor + ', span';
                            var query = '';
                            var search = function () {
                                var q = $inpt.val().toLowerCase();
                                if (q == query) {
                                    return;
                                }
                                query = q;
                                if (opts.resultsPanel.add) {
                                    $rpnl
                                        .children('.' + _c.listview)
                                        .empty();
                                }
                                //	Scroll to top
                                $pnls.scrollTop(0);
                                //	Search through items
                                $rslt
                                    .add($dvdr)
                                    .addClass(_c.hidden)
                                    .find('.' + _c.fullsubopensearch)
                                    .removeClass(_c.fullsubopen + ' ' + _c.fullsubopensearch);
                                $rslt
                                    .each(function () {
                                    var $item = $(this), _search = _anchor;
                                    if (opts.showTextItems || (opts.showSubPanels && $item.find('.' + _c.next))) {
                                        _search = _both;
                                    }
                                    var txt = $item.data(_d.searchtext) || $item.children(_search).not('.' + _c.next).text();
                                    if (txt.toLowerCase().indexOf(query) > -1) {
                                        $item.add($item.prevAll('.' + _c.divider).first()).removeClass(_c.hidden);
                                    }
                                });
                                //	Update sub items
                                if (opts.showSubPanels) {
                                    $pnls.each(function (i) {
                                        var $panl = $(this);
                                        that.__filterListItems($panl.find('.' + _c.listview).children())
                                            .each(function () {
                                            var $li = $(this), $su = $li.data(_d.child);
                                            $li.removeClass(_c.nosubresults);
                                            if ($su) {
                                                $su.find('.' + _c.listview).children().removeClass(_c.hidden);
                                            }
                                        });
                                    });
                                }
                                //	All results in one panel
                                if (opts.resultsPanel.add) {
                                    if (query === '') {
                                        this.closeAllPanels(this.$pnls.children('.' + _c.subopened).last());
                                    }
                                    else {
                                        var $itms = $();
                                        $pnls
                                            .each(function () {
                                            var $i = that.__filterListItems($(this).find('.' + _c.listview).children()).not('.' + _c.hidden).clone(true);
                                            if ($i.length) {
                                                if (opts.resultsPanel.dividers) {
                                                    $itms = $itms.add('<li class="' + _c.divider + '">' + $(this).children('.' + _c.navbar).children('.' + _c.title).text() + '</li>');
                                                }
                                                $i.children('.' + _c.mm('toggle') + ', .' + _c.mm('check')).remove();
                                                $itms = $itms.add($i);
                                            }
                                        });
                                        $itms
                                            .find('.' + _c.next)
                                            .remove();
                                        $rpnl
                                            .children('.' + _c.listview)
                                            .append($itms);
                                        this.openPanel($rpnl);
                                    }
                                }
                                else {
                                    $($pnls.get().reverse())
                                        .each(function (i) {
                                        var $panl = $(this), $prnt = $panl.data(_d.parent);
                                        if ($prnt) {
                                            if (that.__filterListItems($panl.find('.' + _c.listview).children()).length) {
                                                if ($prnt.hasClass(_c.hidden)) {
                                                    $prnt.children('.' + _c.next)
                                                        .not('.' + _c.fullsubopen)
                                                        .addClass(_c.fullsubopen)
                                                        .addClass(_c.fullsubopensearch);
                                                }
                                                $prnt
                                                    .removeClass(_c.hidden)
                                                    .removeClass(_c.nosubresults)
                                                    .prevAll('.' + _c.divider)
                                                    .first()
                                                    .removeClass(_c.hidden);
                                            }
                                            else if (!inPanel) {
                                                if ($panl.hasClass(_c.opened) || $panl.hasClass(_c.subopened)) {
                                                    //	Compensate the timeout for the opening animation
                                                    setTimeout(function () {
                                                        that.openPanel($prnt.closest('.' + _c.panel));
                                                    }, (i + 1) * (that.conf.openingInterval * 1.5));
                                                }
                                                $prnt.addClass(_c.nosubresults);
                                            }
                                        }
                                    });
                                }
                                //	Show/hide no results message
                                $panl.find('.' + _c.noresultsmsg)[$rslt.not('.' + _c.hidden).length ? 'addClass' : 'removeClass'](_c.hidden);
                                //	Update for other addons
                                this.trigger('updateListview');
                            };
                            $inpt
                                .off(_e.keyup + '-' + _ADDON_ + ' ' + _e.change + '-' + _ADDON_)
                                .on(_e.keyup + '-' + _ADDON_, function (e) {
                                if (!preventKeypressSearch(e.keyCode)) {
                                    search.call(that);
                                }
                            })
                                .on(_e.change + '-' + _ADDON_, function (e) {
                                search.call(that);
                            });
                            var $btn = $srch.children('.' + _c.btn);
                            if ($btn.length) {
                                $inpt
                                    .on(_e.keyup + '-' + _ADDON_, function (e) {
                                    $btn[$inpt.val().length ? 'removeClass' : 'addClass'](_c.hidden);
                                });
                            }
                            $inpt.trigger(_e.keyup + '-' + _ADDON_);
                        });
                    }
                }
            });
        },
        //	add: fired once per page load
        add: function () {
            _c = $[_PLUGIN_]._c;
            _d = $[_PLUGIN_]._d;
            _e = $[_PLUGIN_]._e;
            _c.add('clear search hassearch resultspanel noresultsmsg noresults nosubresults fullsubopensearch');
            _d.add('searchtext');
            _e.add('change keyup');
        },
        //	clickAnchor: prevents default behavior when clicking an anchor
        clickAnchor: function ($a, inMenu) { }
    };
    //	Default options and configuration
    $[_PLUGIN_].defaults[_ADDON_] = {
        add: false,
        addTo: 'panels',
        placeholder: 'Search',
        noResults: 'No results found.',
        resultsPanel: {
            add: false,
            dividers: true,
            title: 'Search results'
        },
        search: true,
        showTextItems: false,
        showSubPanels: true
    };
    $[_PLUGIN_].configuration[_ADDON_] = {
        clear: false,
        form: false,
        input: false,
        submit: false
    };
    var _c, _d, _e, glbl;
    function preventKeypressSearch(c) {
        switch (c) {
            case 9: //	tab
            case 16: //	shift
            case 17: //	control
            case 18: //	alt
            case 37: //	left
            case 38: //	top
            case 39: //	right
            case 40://	bottom
                return true;
        }
        return false;
    }
})(jQuery);

/*
 * jQuery mmenu sectionIndexer add-on
 * mmenu.frebsite.nl
 *
 * Copyright (c) Fred Heusschen
 */
(function ($) {
    var _PLUGIN_ = 'mmenu';
    var _ADDON_ = 'sectionIndexer';
    $[_PLUGIN_].addons[_ADDON_] = {
        //	setup: fired once per menu
        setup: function () {
            var that = this, opts = this.opts[_ADDON_], conf = this.conf[_ADDON_];
            glbl = $[_PLUGIN_].glbl;
            //	Extend shorthand options
            if (typeof opts == 'boolean') {
                opts = {
                    add: opts
                };
            }
            if (typeof opts != 'object') {
                opts = {};
            }
            opts = this.opts[_ADDON_] = $.extend(true, {}, $[_PLUGIN_].defaults[_ADDON_], opts);
            this.bind('initPanels:after', function ($panels) {
                //	Set the panel(s)
                if (opts.add) {
                    var $wrapper;
                    switch (opts.addTo) {
                        case 'panels':
                            $wrapper = $panels;
                            break;
                        default:
                            $wrapper = $(opts.addTo, this.$menu).filter('.' + _c.panel);
                            break;
                    }
                    $wrapper
                        .find('.' + _c.divider)
                        .closest('.' + _c.panel)
                        .addClass(_c.hasindexer);
                    //	Add the indexer, only if it does not allready excists
                    if (!this.$indexer) {
                        this.$indexer = $('<div class="' + _c.indexer + '" />')
                            .prependTo(this.$pnls)
                            .append('<a href="#a">a</a>' +
                            '<a href="#b">b</a>' +
                            '<a href="#c">c</a>' +
                            '<a href="#d">d</a>' +
                            '<a href="#e">e</a>' +
                            '<a href="#f">f</a>' +
                            '<a href="#g">g</a>' +
                            '<a href="#h">h</a>' +
                            '<a href="#i">i</a>' +
                            '<a href="#j">j</a>' +
                            '<a href="#k">k</a>' +
                            '<a href="#l">l</a>' +
                            '<a href="#m">m</a>' +
                            '<a href="#n">n</a>' +
                            '<a href="#o">o</a>' +
                            '<a href="#p">p</a>' +
                            '<a href="#q">q</a>' +
                            '<a href="#r">r</a>' +
                            '<a href="#s">s</a>' +
                            '<a href="#t">t</a>' +
                            '<a href="#u">u</a>' +
                            '<a href="#v">v</a>' +
                            '<a href="#w">w</a>' +
                            '<a href="#x">x</a>' +
                            '<a href="#y">y</a>' +
                            '<a href="#z">z</a>');
                        //	Scroll onMouseOver
                        this.$indexer
                            .children()
                            .on(_e.mouseover + '-' + _ADDON_ + ' ' + _e.touchstart + '-' + _ADDON_, function (e) {
                            var lttr = $(this).attr('href').slice(1), $panl = that.$pnls.children('.' + _c.opened), $list = $panl.find('.' + _c.listview);
                            var newTop = -1;
                            var oldTop = $panl.scrollTop();
                            $panl.scrollTop(0);
                            $list
                                .children('.' + _c.divider)
                                .not('.' + _c.hidden)
                                .each(function () {
                                if (newTop < 0 &&
                                    lttr == $(this).text().slice(0, 1).toLowerCase()) {
                                    newTop = $(this).position().top;
                                }
                            });
                            $panl.scrollTop(newTop > -1 ? newTop : oldTop);
                        });
                    }
                    //	Show or hide the indexer
                    var update = function ($panel) {
                        $panel = $panel || this.$pnls.children('.' + _c.opened);
                        this.$menu[($panel.hasClass(_c.hasindexer) ? 'add' : 'remove') + 'Class'](_c.hasindexer);
                    };
                    this.bind('openPanel:start', update);
                    this.bind('initPanels:after', update);
                }
            });
        },
        //	add: fired once per page load
        add: function () {
            _c = $[_PLUGIN_]._c;
            _d = $[_PLUGIN_]._d;
            _e = $[_PLUGIN_]._e;
            _c.add('indexer hasindexer');
            _e.add('mouseover');
        },
        //	clickAnchor: prevents default behavior when clicking an anchor
        clickAnchor: function ($a, inMenu) {
            if ($a.parent().is('.' + _c.indexer)) {
                return true;
            }
        }
    };
    //	Default options and configuration
    $[_PLUGIN_].defaults[_ADDON_] = {
        add: false,
        addTo: 'panels'
    };
    var _c, _d, _e, glbl;
})(jQuery);

/*
 * jQuery mmenu setSelected add-on
 * mmenu.frebsite.nl
 *
 * Copyright (c) Fred Heusschen
 */
(function ($) {
    var _PLUGIN_ = 'mmenu';
    var _ADDON_ = 'setSelected';
    $[_PLUGIN_].addons[_ADDON_] = {
        //	setup: fired once per menu
        setup: function () {
            var that = this, opts = this.opts[_ADDON_], conf = this.conf[_ADDON_];
            glbl = $[_PLUGIN_].glbl;
            //	Extend shorthand options
            if (typeof opts == 'boolean') {
                opts = {
                    hover: opts,
                    parent: opts
                };
            }
            if (typeof opts != 'object') {
                opts = {};
            }
            opts = this.opts[_ADDON_] = $.extend(true, {}, $[_PLUGIN_].defaults[_ADDON_], opts);
            //	Find current by URL
            if (opts.current == 'detect') {
                var findCurrent = function (url) {
                    url = url.split("?")[0].split("#")[0];
                    var $a = that.$menu.find('a[href="' + url + '"], a[href="' + url + '/"]');
                    if ($a.length) {
                        that.setSelected($a.parent(), true);
                    }
                    else {
                        url = url.split('/').slice(0, -1);
                        if (url.length) {
                            findCurrent(url.join('/'));
                        }
                    }
                };
                this.bind('initMenu:after', function () {
                    findCurrent(window.location.href);
                });
            }
            else if (!opts.current) {
                this.bind('initListview:after', function ($panel) {
                    this.$pnls
                        .find('.' + _c.listview)
                        .children('.' + _c.selected)
                        .removeClass(_c.selected);
                });
            }
            //	Add :hover effect on items
            if (opts.hover) {
                this.bind('initMenu:after', function () {
                    this.$menu.addClass(_c.hoverselected);
                });
            }
            //	Set parent item selected for submenus
            if (opts.parent) {
                this.bind('openPanel:finish', function ($panel) {
                    //	Remove all
                    this.$pnls
                        .find('.' + _c.listview)
                        .find('.' + _c.next)
                        .removeClass(_c.selected);
                    //	Move up the DOM tree
                    var $parent = $panel.data(_d.parent);
                    while ($parent) {
                        $parent
                            .not('.' + _c.vertical)
                            .children('.' + _c.next)
                            .addClass(_c.selected);
                        $parent = $parent
                            .closest('.' + _c.panel)
                            .data(_d.parent);
                    }
                });
                this.bind('initMenu:after', function () {
                    this.$menu.addClass(_c.parentselected);
                });
            }
        },
        //	add: fired once per page load
        add: function () {
            _c = $[_PLUGIN_]._c;
            _d = $[_PLUGIN_]._d;
            _e = $[_PLUGIN_]._e;
            _c.add('hoverselected parentselected');
        },
        //	clickAnchor: prevents default behavior when clicking an anchor
        clickAnchor: function ($a, inMenu) { }
    };
    //	Default options
    $[_PLUGIN_].defaults[_ADDON_] = {
        current: true,
        hover: false,
        parent: false
    };
    var _c, _d, _e, glbl;
})(jQuery);

/*
 * jQuery mmenu toggles add-on
 * mmenu.frebsite.nl
 *
 * Copyright (c) Fred Heusschen
 */
(function ($) {
    var _PLUGIN_ = 'mmenu';
    var _ADDON_ = 'toggles';
    $[_PLUGIN_].addons[_ADDON_] = {
        //	setup: fired once per menu
        setup: function () {
            var that = this, opts = this.opts[_ADDON_], conf = this.conf[_ADDON_];
            glbl = $[_PLUGIN_].glbl;
            this.bind('initListview:after', function ($panel) {
                //	Refactor toggle classes
                this.__refactorClass($panel.find('input'), this.conf.classNames[_ADDON_].toggle, 'toggle');
                this.__refactorClass($panel.find('input'), this.conf.classNames[_ADDON_].check, 'check');
                //	Add markup
                $panel
                    .find('input.' + _c.toggle + ', input.' + _c.check)
                    .each(function () {
                    var $inpt = $(this), $prnt = $inpt.closest('li'), cl = $inpt.hasClass(_c.toggle) ? 'toggle' : 'check', id = $inpt.attr('id') || that.__getUniqueId();
                    if (!$prnt.children('label[for="' + id + '"]').length) {
                        $inpt.attr('id', id);
                        $prnt.prepend($inpt);
                        $('<label for="' + id + '" class="' + _c[cl] + '"></label>')
                            .insertBefore($prnt.children('a, span').last());
                    }
                });
            });
        },
        //	add: fired once per page load
        add: function () {
            _c = $[_PLUGIN_]._c;
            _d = $[_PLUGIN_]._d;
            _e = $[_PLUGIN_]._e;
            _c.add('toggle check');
        },
        //	clickAnchor: prevents default behavior when clicking an anchor
        clickAnchor: function ($a, inMenu) { }
    };
    //	Default options and configuration
    $[_PLUGIN_].configuration.classNames[_ADDON_] = {
        toggle: 'Toggle',
        check: 'Check'
    };
    var _c, _d, _e, glbl;
})(jQuery);

return true;
}));
