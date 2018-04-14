"use strict";
var uaInfo = {
    ua: '',
    is: function(t) {
        return RegExp(t, "i").test(uaInfo.ua);
    },
    version: function(p, n) {
        n = n.replace(".", "_");
        var i = n.indexOf('_'),
            ver = "";
        while (i > 0) {
            ver += " " + p + n.substring(0, i);
            i = n.indexOf('_', i + 1);
        }
        ver += " " + p + n;
        return ver;
    },
    getBrowser: function() {
        var g = 'gecko',
            w = 'webkit',
            c = 'chrome',
            f = 'firefox',
            s = 'safari',
            o = 'opera',
            a = 'android',
            bb = 'blackberry',
            dv = 'device_',
            ua = uaInfo.ua,
            is = uaInfo.is;
        return [(!(/opera|webtv/i.test(ua)) && /msie\s(\d+)/.test(ua)) ? ('ie ie' + (/trident\/4\.0/.test(ua) ? '8' : RegExp.$1)) : is('edge\/') ? 'edge ie' + (/edge\/(\d+)\.(\d+)/.test(ua) ? RegExp.$1 + ' ie' + RegExp.$1 + '_' + RegExp.$2 : '') : is('trident\/') ? 'ie ie' + (/trident\/.+rv:(\d+)/i.test(ua) ? RegExp.$1 : '') : is('firefox/') ? g + " " + f + (/firefox\/((\d+)(\.(\d+))(\.\d+)*)/.test(ua) ? ' ' + f + RegExp.$2 + ' ' + f + RegExp.$2 + "_" + RegExp.$4 : '') : is('gecko/') ? g : is('opera') ? o + (/version\/((\d+)(\.(\d+))(\.\d+)*)/.test(ua) ? ' ' + o + RegExp.$2 + ' ' + o + RegExp.$2 + "_" + RegExp.$4 : (/opera(\s|\/)(\d+)\.(\d+)/.test(ua) ? ' ' + o + RegExp.$2 + " " + o + RegExp.$2 + "_" + RegExp.$3 : '')) : is('konqueror') ? 'konqueror' : is('blackberry') ? (bb + (/Version\/(\d+)(\.(\d+)+)/i.test(ua) ? " " + bb + RegExp.$1 + " " + bb + RegExp.$1 + RegExp.$2.replace('.', '_') : (/Blackberry ?(([0-9]+)([a-z]?))[\/|;]/gi.test(ua) ? ' ' + bb + RegExp.$2 + (RegExp.$3 ? ' ' + bb + RegExp.$2 + RegExp.$3 : '') : ''))) : is('android') ? (a + (/Version\/(\d+)(\.(\d+))+/i.test(ua) ? " " + a + RegExp.$1 + " " + a + RegExp.$1 + RegExp.$2.replace('.', '_') : '') + (/Android (.+); (.+) Build/i.test(ua) ? ' ' + dv + ((RegExp.$2).replace(/ /g, "_")).replace(/-/g, "_") : '')) : is('chrome') ? w + ' ' + c + (/chrome\/((\d+)(\.(\d+))(\.\d+)*)/.test(ua) ? ' ' + c + RegExp.$2 + ((RegExp.$4 > 0) ? ' ' + c + RegExp.$2 + "_" + RegExp.$4 : '') : '') : is('iron') ? w + ' iron' : is('applewebkit/') ? (w + ' ' + s + (/version\/((\d+)(\.(\d+))(\.\d+)*)/.test(ua) ? ' ' + s + RegExp.$2 + " " + s + RegExp.$2 + RegExp.$3.replace('.', '_') : (/ Safari\/(\d+)/i.test(ua) ? ((RegExp.$1 == "419" || RegExp.$1 == "417" || RegExp.$1 == "416" || RegExp.$1 == "412") ? ' ' + s + '2_0' : RegExp.$1 == "312" ? ' ' + s + '1_3' : RegExp.$1 == "125" ? ' ' + s + '1_2' : RegExp.$1 == "85" ? ' ' + s + '1_0' : '') : ''))) : is('mozilla/') ? g : ''];
    },
    getPlatform: function() {
        var wp = 'winphone',
            a = 'android',
            bb = 'blackberry',
            dv = 'device_',
            ua = uaInfo.ua,
            version = uaInfo.version,
            is = uaInfo.is;
        return [is('j2me') ? 'j2me' : is('windows phone') ? (wp + (/Windows Phone (\d+)(\.(\d+))+/i.test(ua) ? " " + wp + RegExp.$1 + " " + wp + RegExp.$1 + RegExp.$2.replace('.', '_') : (/Windows Phone OS (\d+)(\.(\d+))+/i.test(ua) ? " " + wp + RegExp.$1 + " " + wp + RegExp.$1 + RegExp.$2.replace('.', '_') : ''))) : is('blackberry') ? (bb + (/Version\/(\d+)(\.(\d+)+)/i.test(ua) ? " " + bb + RegExp.$1 + " " + bb + RegExp.$1 + RegExp.$2.replace('.', '_') : (/Blackberry ?(([0-9]+)([a-z]?))[\/|;]/gi.test(ua) ? ' ' + bb + RegExp.$2 + (RegExp.$3 ? ' ' + bb + RegExp.$2 + RegExp.$3 : '') : ''))) : is('android') ? (a + (/Version\/(\d+)(\.(\d+))+/i.test(ua) ? " " + a + RegExp.$1 + " " + a + RegExp.$1 + RegExp.$2.replace('.', '_') : '') + (/Android (.+); (.+) Build/i.test(ua) ? ' ' + dv + ((RegExp.$2).replace(/ /g, "_")).replace(/-/g, "_") : '')) : is('ipad|ipod|iphone') ? ((/CPU( iPhone)? OS (\d+[_|\.]\d+([_|\.]\d+)*)/i.test(ua) ? 'ios' + version('ios', RegExp.$2) : '') + ' ' + (/(ip(ad|od|hone))/gi.test(ua) ? RegExp.$1 : "")) : is('playbook') ? 'playbook' : is('kindle|silk') ? 'kindle' : is('playbook') ? 'playbook' : is('mac') ? 'mac' + (/mac os x ((\d+)[.|_](\d+))/.test(ua) ? (' mac' + (RegExp.$2) + ' mac' + (RegExp.$1).replace('.', "_")) : '') : is('win') ? 'win' + (is('windows nt 10.0') ? ' win10' : is('windows nt 6.3') ? ' win8_1' : is('windows nt 6.2') ? ' win8' : is('windows nt 6.1') ? ' win7' : is('windows nt 6.0') ? ' vista' : is('windows nt 5.2') || is('windows nt 5.1') ? ' win_xp' : is('windows nt 5.0') ? ' win_2k' : is('windows nt 4.0') || is('WinNT4.0') ? ' win_nt' : '') : is('freebsd') ? 'freebsd' : is('x11|linux') ? 'linux' : ''];
    },
    getMobile: function() {
        var is = uaInfo.is;
        return [is("android|mobi|mobile|j2me|iphone|ipod|ipad|blackberry|playbook|kindle|silk") ? 'mobile' : ''];
    },
    getIpadApp: function() {
        var is = uaInfo.is;
        return [(is('ipad|iphone|ipod') && !is('safari')) ? 'ipad_app' : ''];
    },
    getLang: function() {
        var ua = uaInfo.ua;
        return [/[; |\[](([a-z]{2})(\-[a-z]{2})?)[)|;|\]]/i.test(ua) ? ('lang_' + RegExp.$2).replace("-", "_") + (RegExp.$3 != '' ? (' ' + 'lang_' + RegExp.$1).replace("-", "_") : '') : ''];
    }
}
var screenInfo = {
    width: (window.outerWidth || document.documentElement.clientWidth) - 15,
    height: window.outerHeight || document.documentElement.clientHeight,
    screens: [0, 768, 980, 1200],
    screenSize: function() {
        screenInfo.width = (window.outerWidth || document.documentElement.clientWidth) - 15;
        screenInfo.height = window.outerHeight || document.documentElement.clientHeight;
        var screens = screenInfo.screens,
            i = screens.length,
            arr = [],
            maxw, minw;
        while (i--) {
            if (screenInfo.width >= screens[i]) {
                if (i) {
                    arr.push("minw_" + screens[(i)]);
                }
                if (i <= 2) {
                    arr.push("maxw_" + (screens[(i) + 1] - 1));
                }
                break;
            }
        }
        return arr;
    },
    getOrientation: function() {
        return screenInfo.width < screenInfo.height ? ["orientation_portrait"] : ["orientation_landscape"];
    },
    getInfo: function() {
        var arr = [];
        arr = arr.concat(screenInfo.screenSize());
        arr = arr.concat(screenInfo.getOrientation());
        return arr;
    },
    getPixelRatio: function() {
        var arr = [],
            pixelRatio = window.devicePixelRatio ? window.devicePixelRatio : 1;
        if (pixelRatio > 1) {
            arr.push('retina_' + parseInt(pixelRatio) + 'x');
            arr.push('hidpi');
        } else {
            arr.push('no-hidpi');
        }
        return arr;
    }
}
var dataUriInfo = {
    data: new Image(),
    div: document.createElement("div"),
    isIeLessThan9: false,
    getImg: function() {
        dataUriInfo.data.src = "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==";
        dataUriInfo.div.innerHTML = "<!--[if lt IE 9]><i></i><![endif]-->";
        dataUriInfo.isIeLessThan9 = dataUriInfo.div.getElementsByTagName("i").length == 1;
        return dataUriInfo.data;
    },
    checkSupport: function() {
        if (dataUriInfo.data.width != 1 || dataUriInfo.data.height != 1 || dataUriInfo.isIeLessThan9) {
            return ["no-datauri"];
        } else {
            return ["datauri"];
        }
    }
}

function css_browser_selector(u, ns) {
    var html = document.documentElement,
        b = []
    ns = ns ? ns : "";
    uaInfo.ua = u.toLowerCase();
    var browser = uaInfo.getBrowser();
    if (browser == 'gecko') browser = (!(window.ActiveXObject) && "ActiveXObject" in window) ? 'ie ie11' : browser;
    var pattTouch = /no-touch/g;
    if (pattTouch.test(html.className)) b = b.concat('no-touch');
    else b = b.concat('touch');
    var pattAdmin = /admin-mode/g;
    if (pattAdmin.test(html.className)) b = b.concat('admin-mode');
    b = b.concat(browser);
    b = b.concat(uaInfo.getPlatform());
    b = b.concat(uaInfo.getMobile());
    b = b.concat(uaInfo.getIpadApp());
    b = b.concat(uaInfo.getLang());
    b = b.concat(['js']);
    b = b.concat(screenInfo.getPixelRatio());
    b = b.concat(screenInfo.getInfo());
    var updateScreen = function() {
        html.className = html.className.replace(/ ?orientation_\w+/g, "").replace(/ [min|max|cl]+[w|h]_\d+/g, "");
        html.className = html.className + ' ' + screenInfo.getInfo().join(' ');
    }
    window.addEventListener('resize', updateScreen);
    window.addEventListener('orientationchange', updateScreen);
    var data = dataUriInfo.getImg();
    data.onload = data.onerror = function() {
        html.className += ' ' + dataUriInfo.checkSupport().join(' ');
    }
    b = b.filter(function(e) {
        return e;
    });
    b[0] = ns ? ns + b[0] : b[0];
    html.className = b.join(' ' + ns);
    return html.className;
}
var css_browser_selector_ns = css_browser_selector_ns || "";
css_browser_selector(navigator.userAgent, css_browser_selector_ns);
(function() {
    var is_webkit = navigator.userAgent.toLowerCase().indexOf('webkit') > -1,
        is_opera = navigator.userAgent.toLowerCase().indexOf('opera') > -1,
        is_ie = navigator.userAgent.toLowerCase().indexOf('msie') > -1;
    if ((is_webkit || is_opera || is_ie) && document.getElementById && window.addEventListener) {
        window.addEventListener('hashchange', function() {
            var id = location.hash.substring(1),
                element;
            if (!(/^[A-z0-9_-]+$/.test(id))) {
                return;
            }
            element = document.getElementById(id);
            if (element) {
                if (!(/^(?:a|select|input|button|textarea)$/i.test(element.tagName))) {
                    element.tabIndex = -1;
                }
                element.focus();
            }
        }, false);
    }
})();
try {
    new CustomEvent("test");
} catch (e) {
    var CustomEvent = function(event, params) {
        var evt;
        params = params || {
            bubbles: false,
            cancelable: false,
            detail: undefined
        };
        evt = document.createEvent("CustomEvent");
        evt.initCustomEvent(event, params.bubbles, params.cancelable, params.detail);
        return evt;
    };
    CustomEvent.prototype = window.Event.prototype;
    window.CustomEvent = CustomEvent;
}
Array.prototype.indexOf || (Array.prototype.indexOf = function(n) {
    "use strict";
    if (null == this) throw new TypeError;
    var t, e, o = Object(this),
        r = o.length >>> 0;
    if (0 === r) return -1;
    if (t = 0, arguments.length > 1 && (t = Number(arguments[1]), t != t ? t = 0 : 0 != t && 1 / 0 != t && t != -1 / 0 && (t = (t > 0 || -1) * Math.floor(Math.abs(t)))), t >= r) return -1;
    for (e = t >= 0 ? t : Math.max(r - Math.abs(t), 0); r > e; e++)
        if (e in o && o[e] === n) return e;
    return -1
});
var evento = function(n) {
    var t, e, o, r = n,
        i = r.document,
        f = {};
    return t = function() {
        return "function" == typeof i.addEventListener ? function(n, t, e) {
            n.addEventListener(t, e, !1), f[n] = f[n] || {}, f[n][t] = f[n][t] || [], f[n][t].push(e)
        } : "function" == typeof i.attachEvent ? function(n, t, e) {
            n.attachEvent(t, e), f[n] = f[n] || {}, f[n][t] = f[n][t] || [], f[n][t].push(e)
        } : function(n, t, e) {
            n["on" + t] = e, f[n] = f[n] || {}, f[n][t] = f[n][t] || [], f[n][t].push(e)
        }
    }(), e = function() {
        return "function" == typeof i.removeEventListener ? function(n, t, e) {
            n.removeEventListener(t, e, !1), Helio.each(f[n][t], function(o) {
                o === e && (f[n] = f[n] || {}, f[n][t] = f[n][t] || [], f[n][t][f[n][t].indexOf(o)] = void 0)
            })
        } : "function" == typeof i.detachEvent ? function(n, t, e) {
            n.detachEvent(t, e), Helio.each(f[n][t], function(o) {
                o === e && (f[n] = f[n] || {}, f[n][t] = f[n][t] || [], f[n][t][f[n][t].indexOf(o)] = void 0)
            })
        } : function(n, t, e) {
            n["on" + t] = void 0, Helio.each(f[n][t], function(o) {
                o === e && (f[n] = f[n] || {}, f[n][t] = f[n][t] || [], f[n][t][f[n][t].indexOf(o)] = void 0)
            })
        }
    }(), o = function(n, t) {
        f[n] = f[n] || {}, f[n][t] = f[n][t] || [];
        for (var e = 0, o = f[n][t].length; o > e; e += 1) f[n][t][e]()
    }, {
        add: t,
        remove: e,
        trigger: o,
        _handlers: f
    }
}(this);
var player, OKEvents, options, videoWidth, videoHeight, YTplayers, youtubePlayers = new Array();

function onYouTubeIframeAPIReady() {
    YTplayers = new Array();
    jQuery('.no-touch .uncode-video-container.video').each(function() {
        var playerY;
        if (jQuery(this).attr('data-provider') == 'youtube') {
            var id = jQuery(this).attr('data-id');
            options = jQuery(window).data('okoptions-' + id);
            options.time = jQuery(this).attr('data-t');
            playerY = new YT.Player('okplayer-' + id, {
                videoId: options.video ? options.video.id : null,
                playerVars: {
                    'autohide': 1,
                    'autoplay': 0,
                    'disablekb': options.keyControls,
                    'cc_load_policy': options.captions,
                    'controls': options.controls,
                    'enablejsapi': 1,
                    'fs': 0,
                    'modestbranding': 1,
                    'origin': window.location.origin || (window.location.protocol + '//' + window.location.hostname),
                    'iv_load_policy': options.annotations,
                    'loop': options.loop,
                    'showinfo': 0,
                    'rel': 0,
                    'wmode': 'opaque',
                    'hd': options.hd
                },
                events: {
                    'onReady': OKEvents.yt.ready,
                    'onStateChange': OKEvents.yt.onStateChange,
                    'onError': OKEvents.yt.error
                }
            });
            YTplayers[id] = playerY;
            playerY.videoId = id;
        }
    });
}

function vimeoPlayerReady(id) {
    options = jQuery(window).data('okoptions-' + id);
    var jIframe = options.jobject,
        iframe = jIframe[0];
    jIframe.attr('src', jIframe.data('src'));
    var playerV = $f(iframe);
    playerV.addEvent('ready', function(e) {
        OKEvents.v.onReady(iframe);
        var carouselContainer = jQuery(iframe).closest('.owl-carousel');
        if (carouselContainer.length) {
            _UI.owlPlayVideo(carouselContainer);
        }
        if (OKEvents.utils.isMobile()) {
            OKEvents.v.onPlay(playerV);
        } else {
            playerV.addEvent('play', OKEvents.v.onPlay(playerV));
            playerV.addEvent('pause', OKEvents.v.onPause);
            playerV.addEvent('finish', OKEvents.v.onFinish);
        }
        if (options.time != null) {
            playerV.api('seekTo', (options.time).replace('t=', ''));
        }
        playerV.api('play');
        jQuery(iframe).css({
            visibility: 'visible',
            opacity: 1
        });
    });
}
OKEvents = {
    yt: {
        ready: function(event) {
            var id = event.target.videoId;
            youtubePlayers[id] = event.target;
            event.target.setVolume(options.volume);
            if (options.autoplay === 1) {
                if (options.playlist.list) {
                    player.loadPlaylist(options.playlist.list, options.playlist.index, options.playlist.startSeconds, options.playlist.suggestedQuality);
                } else {
                    var inCarousel = jQuery('#okplayer-' + id).closest('.owl-item');
                    if (!inCarousel.length || (inCarousel.length && inCarousel.hasClass('active'))) {
                        if (options.time != null) {
                            event.target.seekTo(parseInt(options.time));
                        }
                        event.target.playVideo();
                    } else {
                        event.target.pauseVideo();
                    }
                }
            }
            OKEvents.utils.isFunction(options.onReady) && options.onReady(event.target);
        },
        onStateChange: function(event) {
            var id = event.target.videoId;
            switch (event.data) {
                case -1:
                    OKEvents.utils.isFunction(options.unstarted) && options.unstarted();
                    break;
                case 0:
                    OKEvents.utils.isFunction(options.onFinished) && options.onFinished();
                    options.loop && event.target.playVideo();
                    break;
                case 1:
                    OKEvents.utils.isFunction(options.onPlay) && options.onPlay();
                    setTimeout(function() {
                        _UI.initVideoComponent(document.body, '.uncode-video-container.video, .uncode-video-container.self-video');
                        jQuery('#okplayer-' + id).closest('.uncode-video-container').css('opacity', '1');
                    }, 300);
                    break;
                case 2:
                    OKEvents.utils.isFunction(options.onPause) && options.onPause();
                    break;
                case 3:
                    OKEvents.utils.isFunction(options.buffering) && options.buffering();
                    break;
                case 5:
                    OKEvents.utils.isFunction(options.cued) && options.cued();
                    break;
                default:
                    throw "OKVideo: received invalid data from YT player.";
            }
        },
        error: function(event) {
            throw event;
        }
    },
    v: {
        onReady: function(target) {
            OKEvents.utils.isFunction(options.onReady) && options.onReady(target);
        },
        onPlay: function(player) {
            if (!OKEvents.utils.isMobile()) player.api('setVolume', options.volume);
            OKEvents.utils.isFunction(options.onPlay) && options.onPlay();
            jQuery(player.element).closest('.uncode-video-container').css('opacity', '1');
        },
        onPause: function() {
            OKEvents.utils.isFunction(options.onPause) && options.onPause();
        },
        onFinish: function() {
            OKEvents.utils.isFunction(options.onFinish) && options.onFinish();
        }
    },
    utils: {
        isFunction: function(func) {
            if (typeof func === 'function') {
                return true;
            } else {
                return false;
            }
        },
        isMobile: function() {
            if (navigator.userAgent.match(/(iPhone|iPod|iPad|Android|BlackBerry)/)) {
                return true;
            } else {
                return false;
            }
        }
    }
};
(function(window) {
    'use strict';

    function classReg(className) {
        return new RegExp("(^|\\s+)" + className + "(\\s+|$)");
    }
    var hasClass, addClass, removeClass;
    if ('classList' in document.documentElement) {
        hasClass = function(elem, c) {
            if (elem !== null) return elem.classList.contains(c);
        };
        addClass = function(elem, c) {
            if (elem !== null) elem.classList.add(c);
        };
        removeClass = function(elem, c) {
            if (elem !== null) elem.classList.remove(c);
        };
    } else {
        hasClass = function(elem, c) {
            if (elem !== null) return classReg(c).test(elem.className);
        };
        addClass = function(elem, c) {
            if (!hasClass(elem, c)) {
                if (elem !== null) elem.className = elem.className + ' ' + c;
            }
        };
        removeClass = function(elem, c) {
            if (elem !== null) elem.className = elem.className.replace(classReg(c), ' ');
        };
    }

    function toggleClass(elem, c) {
        var fn = hasClass(elem, c) ? removeClass : addClass;
        fn(elem, c);
    }
    var classie = {
        hasClass: hasClass,
        addClass: addClass,
        removeClass: removeClass,
        toggleClass: toggleClass,
        has: hasClass,
        add: addClass,
        remove: removeClass,
        toggle: toggleClass
    };
    if (typeof define === 'function' && define.amd) {
        define(classie);
    } else {
        window.classie = classie;
    }
})(window);

function whichTransitionEvent() {
    var t;
    var el = document.createElement('fakeelement');
    var transitions = {
        'transition': 'transitionend',
        'OTransition': 'oTransitionEnd',
        'MozTransition': 'transitionend',
        'WebkitTransition': 'webkitTransitionEnd'
    }
    for (t in transitions) {
        if (el.style[t] !== undefined) {
            return transitions[t];
        }
    }
}
(function(window, undefined) {
    'use strict';
    var bodyTop, scrollbarWidth = false,
        noScroll = false,
        boxEvent = new CustomEvent('boxResized'),
        menuClose = new CustomEvent('menuClose'),
        menuOpen = new CustomEvent('menuOpen'),
        bodyBorder = 0,
        adminBarHeight = 0,
        boxWidth = 0,
        boxLeft = 0,
        parallaxRows, parallaxCols, parallaxHeaders, kenburnsRows, kenburnsCols, visibleRows, headerWithOpacity, speedDivider = SiteParameters.parallax_factor,
        adminBar, pageHeader, masthead, mastheadMobile, mastheadMobilePaddingTop = 0,
        maincontainer, menuwrapper, menuhide, menusticky, menuHeight = 0,
        menuMobileHeight = 0,
        mainmenu = new Array(),
        secmenu = new Array(),
        secmenuHeight = 0,
        transmenuHeight = 0,
        header, transmenuel, logo, logoel, logolink, logoMinScale, lastScrollValue = 0,
        wwidth = window.innerWidth || document.documentElement.clientWidth,
        wheight = window.innerHeight || document.documentElement.clientHeight,
        boxWrapper, docheight = 0,
        isMobile = classie.hasClass(document.documentElement, 'touch') ? true : false,
        isIE = classie.hasClass(document.documentElement, 'ie') || classie.hasClass(document.documentElement, 'opera12') ? true : false,
        isFF = classie.hasClass(document.documentElement, 'firefox') ? true : false,
        isFullPage, isFullPageSnap, transitionEvent = whichTransitionEvent(),
        footerScroller = false,
        mediaQuery = 959,
        mediaQueryMobile = 569,
        menuOpened = false,
        overlayOpened = false,
        menuMobileTriggerEvent = new CustomEvent('menuMobileTrigged'),
        resizeTimer, hidingTimer, isSplitMenu = false,
        mainNavMenu, mainNavWrapper, isMobileTransparent = false,
        checkVisible = function(elm) {
            var rect = elm.getBoundingClientRect();
            var viewHeight = Math.max(document.documentElement.clientHeight, window.innerHeight);
            return !(rect.bottom < 0 || rect.top - viewHeight >= 0);
        },
        initBox = function() {
            var bodyBorderDiv = document.querySelectorAll('.body-borders .top-border');
            if (bodyBorderDiv.length) {
                bodyBorder = outerHeight(bodyBorderDiv[0]);
            } else bodyBorder = 0;
            _UI.bodyBorder = bodyBorder;
            _UI.isFullPage = classie.hasClass(document.body, 'uncode-fullpage-slide') && !(_UI.isMobile && classie.hasClass(document.body, 'uncode-fp-mobile-disable') && window.innerWidth < 570);
            _UI.isFullPageSnap = classie.hasClass(document.body, 'uncode-scroll-snap');
            if (bodyBorder != 0) {
                if (!_UI.isFullPage) {
                    document.documentElement.style.marginTop = bodyBorder + 'px';
                }
                wheight = (window.innerHeight || document.documentElement.clientHeight) - (bodyBorder * 2);
            }
            if (!isMobile && !scrollbarWidth) {
                var scrollDiv = document.createElement("div");
                scrollDiv.className = "scrollbar-measure";
                var dombody = document.body;
                if (dombody != null) {
                    dombody.appendChild(scrollDiv);
                    scrollbarWidth = scrollDiv.offsetWidth - scrollDiv.clientWidth;
                    dombody.removeChild(scrollDiv);
                }
            }
            if (!isMobile) {
                forEachElement('.box-container', function(el, i) {
                    if (!classie.hasClass(el, 'limit-width')) {
                        var elWidth = outerWidth(el),
                            newWidth = 12 * Math.ceil((wwidth - scrollbarWidth) / 12);
                        boxWidth = newWidth - (bodyBorder * 2);
                        boxLeft = (wwidth - boxWidth - scrollbarWidth) / 2;
                        el.style.width = boxWidth + 'px';
                        el.style.marginLeft = boxLeft + 'px';
                        if (mainmenu != undefined && mainmenu[0] != undefined) {
                            mainmenu[0].style.width = boxWidth + 'px';
                        }
                    }
                });
            }
            if (classie.hasClass(document.body, 'menu-mobile-transparent')) isMobileTransparent = true;
            if (!isMobileTransparent) {
                if (wwidth > mediaQuery && classie.hasClass(document.body, 'menu-force-opacity')) classie.removeClass(document.body, 'menu-force-opacity');
                if (wwidth < mediaQuery && !classie.hasClass(document.body, 'menu-force-opacity')) classie.addClass(document.body, 'menu-force-opacity');
            }
        },
        fixMenuHeight = function() {
            if (!classie.hasClass(document.body, 'vmenu')) noScroll = true;
            menuwrapper = document.querySelectorAll(".menu-wrapper");
            masthead = document.getElementById("masthead");
            if (classie.hasClass(document.body, 'hmenu-center-split')) {
                mastheadMobile = new Array(document.getElementById("logo-container-mobile"), document.getElementById("main-logo").parentNode);
            } else mastheadMobile = document.getElementById("logo-container-mobile");
            menuhide = document.querySelector('#masthead .menu-hide, .main-header .menu-hide, #masthead .menu-hide-vertical');
            menusticky = document.querySelectorAll('.menu-sticky, .menu-sticky-vertical');
            transmenuel = document.querySelectorAll('.menu-transparent:not(.vmenu-container)');
            var menuItemsButton = document.querySelectorAll('.menu-item-button .menu-btn-table');
            logo = document.querySelector('#main-logo');
            if (logo != undefined) logolink = (logo.firstElementChild || logo.firstChild);
            if (logolink != undefined) logoMinScale = logolink.getAttribute("data-minheight");
            logoel = document.querySelectorAll('.menu-shrink .logo-container');
            mainmenu = document.querySelectorAll('.vmenu .vmenu-container, .menu-primary .menu-container');
            if (classie.hasClass(document.body, 'hmenu-center')) {
                var mainmenucenter = document.querySelectorAll('.hmenu-center .menu-container-mobile');
                var first_array = Array.prototype.slice.call(mainmenu);
                var second_array = Array.prototype.slice.call(mainmenucenter);
                mainmenu = first_array.concat(second_array);
            }
            secmenu = document.querySelectorAll('.menu-secondary');
            calculateMenuHeight(true);
            for (var k = 0; k < menuItemsButton.length; k++) {
                var a_item = menuItemsButton[k].parentNode,
                    buttonHeight = outerHeight(menuItemsButton[k]);
                a_item.style.height = buttonHeight + 'px';
            }
            if (classie.hasClass(document.body, 'hmenu-center-split')) {
                mainNavMenu = document.querySelector('#masthead .navbar-main .menu-primary-inner');
                mainNavWrapper = document.querySelector('#masthead > .menu-container');
                isSplitMenu = true;
            }
            fixMenu();
        },
        calculateMenuHeight = function(first) {
            menuHeight = transmenuHeight = secmenuHeight = 0;
            if (mastheadMobile != null) {
                if (mastheadMobile.length === 2) {
                    if (wwidth > mediaQuery) {
                        _UI.menuMobileHeight = outerHeight(mastheadMobile[1]);
                        mastheadMobilePaddingTop = parseFloat(getComputedStyle(mastheadMobile[1]).paddingTop);
                    } else {
                        _UI.menuMobileHeight = outerHeight(mastheadMobile[0]);
                        mastheadMobilePaddingTop = parseFloat(getComputedStyle(mastheadMobile[0]).paddingTop);
                    }
                } else {
                    _UI.menuMobileHeight = outerHeight(mastheadMobile);
                    mastheadMobilePaddingTop = parseFloat(getComputedStyle(mastheadMobile).paddingTop);
                }
            }
            if (wwidth > mediaQuery) {
                for (var i = 0; i < mainmenu.length; i++) {
                    if (classie.hasClass(document.body, 'hmenu-center') && i === 1) continue;
                    if (!classie.hasClass(masthead, 'masthead-vertical')) {
                        menuHeight = menuHeight + outerHeight(mainmenu[i]);
                    } else menuHeight = 0;
                    if (isIE && first) {
                        getDivChildren(mainmenu[i], '.menu-horizontal-inner', function(innerMenu, i) {
                            innerMenu.style.height = menuHeight + 'px';
                        });
                    }
                    if (classie.hasClass(mainmenu[i].parentNode, 'menu-transparent')) {
                        transmenuHeight += menuHeight;
                    }
                }
                for (var j = 0; j < secmenu.length; j++) {
                    secmenuHeight += outerHeight(secmenu[j]);
                }
                menuHeight += secmenuHeight;
            } else {
                menuHeight = _UI.menuMobileHeight;
                if (isMobileTransparent) {
                    for (var i = 0; i < mainmenu.length; i++) {
                        if (classie.hasClass(mainmenu[i].parentNode, 'menu-transparent')) {
                            transmenuHeight += menuHeight;
                        }
                    }
                }
                var search_box = document.querySelectorAll('.search-icon .drop-menu');
                for (var i = 0; i < search_box.length; i++) {
                    search_box[i].removeAttribute('style');
                }
            }
            if (classie.hasClass(document.documentElement, 'admin-mode')) {
                adminBar = document.getElementById("wpadminbar");
                if (wwidth > 600) {
                    if (adminBar != null) adminBarHeight = outerHeight(adminBar);
                    else {
                        if (wwidth > 782) adminBarHeight = 32;
                        else adminBarHeight = 46;
                    }
                } else adminBarHeight = 0;
            }
            _UI.adminBarHeight = adminBarHeight;
            _UI.menuHeight = menuHeight;
            if (_UI.isFullPage) {
                if (_UI.adminBarHeight > 0)
                    document.body.style.marginTop = ((_UI.adminBarHeight + _UI.bodyBorder) * -1) + 'px';
                document.body.style.paddingTop = (_UI.adminBarHeight + _UI.bodyBorder) + 'px';
            }
            if (masthead != undefined) {
                if (classie.hasClass(masthead, 'menu-transparent')) {
                    if (wwidth > mediaQuery) masthead.parentNode.style.height = '0px';
                }
            }
            if (typeof menuhide == 'object' && menuhide != null && mainmenu[0] != undefined) {
                var sticky_element = (typeof mainmenu.item === 'undefined' ? ((wwidth > mediaQuery) ? mainmenu[0] : mainmenu[1]) : mainmenu[0]);
                if (sticky_element.style.top != '') {
                    sticky_element.style.top = _UI.bodyBorder + 'px'
                }
            }
        },
        centerSplitMenu = function() {
            if (wwidth > mediaQuery && mainNavMenu) {
                if (mainNavMenu.style.left == '') {
                    mainNavMenu.style.left = '0px';
                    var logoPos = logo.parentNode.getBoundingClientRect();
                    mainNavMenu.style.left = (wwidth / 2) - (logoPos.left + (logoPos.width / 2)) + 'px';
                    mainNavWrapper.style.opacity = '1';
                }
                mainNavWrapper.style.opacity = '1';
            }
        },
        initHeader = function() {
            _UI.adaptive();
            headerHeight('.header-wrapper');
            parallaxHeaders = document.querySelectorAll('.header-parallax > .header-bg-wrapper > .header-bg');
            header = document.querySelectorAll('.header-wrapper.header-uncode-block, .header-wrapper.header-revslider, .header-wrapper.header-layerslider, .header-basic .header-wrapper, .header-uncode-block > .row-container:first-child > .row > .row-inner > .col-lg-12 > .uncol, .header-uncode-block .uncode-slider .owl-carousel > .row-container:first-child .col-lg-12 .uncoltable');
            headerWithOpacity = document.querySelectorAll('.header-scroll-opacity');
            pageHeader = document.getElementById("page-header");
            if (pageHeader != undefined) {
                var backs = pageHeader.querySelectorAll('.header-bg'),
                    backsCarousel = pageHeader.querySelectorAll('.header-uncode-block .background-inner'),
                    uri_pattern = /\b((?:[a-z][\w-]+:(?:\/{1,3}|[a-z0-9%])|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}\/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:'".,<>?«»“�?‘’]))/ig;
                if (backs.length == 0 && backsCarousel.length == 0) {
                    pageHeader.setAttribute('data-imgready', 'true');
                } else {
                    if (backsCarousel.length) {
                        for (var j = 0; j < backsCarousel.length; j++) {
                            if (j == 0) {
                                if (!!backsCarousel[j].style.backgroundImage && backsCarousel[j].style.backgroundImage !== void 0) {
                                    var url = (backsCarousel[j].style.backgroundImage).match(uri_pattern),
                                        image = new Image();
                                    image.onload = function() {
                                        pageHeader.setAttribute('data-imgready', 'true');
                                    };
                                    image.src = url ? url[0] : '';
                                } else {
                                    pageHeader.setAttribute('data-imgready', 'true');
                                }
                            }
                        }
                    } else {
                        for (var i = 0; i < backs.length; i++) {
                            if (i == 0) {
                                if (!!backs[i].style.backgroundImage && backs[i].style.backgroundImage !== void 0) {
                                    var url = (backs[i].style.backgroundImage).match(uri_pattern),
                                        image = new Image();
                                    image.onload = function() {
                                        pageHeader.setAttribute('data-imgready', 'true');
                                    };
                                    image.src = url[0];
                                } else {
                                    pageHeader.setAttribute('data-imgready', 'true');
                                }
                            }
                        }
                    }
                }
            }
            if (masthead != undefined && !classie.hasClass(masthead, 'masthead-vertical')) {
                if (header.length) {
                    masthead.parentNode.style.height = menuHeight + 'px';
                    if (menuwrapper[0] != undefined) classie.addClass(menuwrapper[0], 'with-header');
                    for (var j = 0; j < header.length; j++) {
                        var headerel = header[j],
                            closestStyle = getClosest(headerel, 'style-light');
                        if (closestStyle != null && classie.hasClass(closestStyle, 'style-light')) switchColorsMenu(0, 'light');
                        else if (getClosest(headerel, 'style-dark') != null) switchColorsMenu(0, 'dark');
                        else {
                            if (masthead.style.opacity !== 1) masthead.style.opacity = 1;
                        }
                        if (classie.hasClass(masthead, 'menu-transparent')) {
                            if (isMobileTransparent || wwidth > mediaQuery) {
                                masthead.parentNode.style.height = '0px';
                                if (classie.hasClass(masthead, 'menu-add-padding')) {
                                    var headerBlock = getClosest(headerel, 'header-uncode-block');
                                    if (headerBlock != null) {
                                        var innerRows = headerel.querySelectorAll('.column_parent > .uncol > .uncoltable > .uncell > .uncont, .uncode-slider .column_child > .uncol > .uncoltable > .uncell > .uncont');
                                        for (var k = 0; k < innerRows.length; k++) {
                                            if (innerRows[k] != undefined) {
                                                if (wwidth > mediaQuery) innerRows[k].style.paddingTop = transmenuHeight + 'px';
                                                else innerRows[k].style.paddingTop = (transmenuHeight - mastheadMobilePaddingTop) + 'px';
                                            }
                                        }
                                    } else {
                                        getDivChildren(headerel, '.header-content', function(headerContent, i) {
                                            if (wwidth > mediaQuery) headerContent.style.paddingTop = transmenuHeight + 'px';
                                            else headerContent.style.paddingTop = (transmenuHeight - mastheadMobilePaddingTop) + 'px';
                                        });
                                    }
                                }
                            }
                        }
                    }
                } else {
                    if (menuwrapper[0] != undefined) classie.addClass(menuwrapper[0], 'no-header');
                    classie.removeClass(masthead, 'menu-transparent');
                    transmenuHeight = 0;
                    isMobileTransparent = false;
                }
            } else {
                isMobileTransparent = false;
            }
            if (!transmenuel.length) isMobileTransparent = false;
            bodyTop = document.documentElement['scrollTop'] || document.body['scrollTop'];
            _UI.bodyTop = bodyTop;
            if (!classie.hasClass(document.body, 'vmenu'))
                initBox();
            scrollFunction();
            showHideScrollup(bodyTop);
        },
        initRow = function(currentRow) {
            _UI.adaptive();
            var el = currentRow.parentNode.parentNode.getAttribute("data-parent") == 'true' ? currentRow.parentNode : currentRow.parentNode.parentNode,
                rowParent = el.parentNode,
                rowInner = currentRow.parentNode,
                percentHeight = el.getAttribute("data-height-ratio"),
                minHeight = el.getAttribute("data-minheight"),
                calculateHeight, calculatePadding = 0,
                isHeader = false,
                isFirst = false,
                uri_pattern = /\b((?:[a-z][\w-]+:(?:\/{1,3}|[a-z0-9%])|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}\/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:'".,<>?«»“�?‘’]))/ig;
            getDivChildren(el.parentNode, '.column_parent, .column_child', function(obj, i, total) {
                if ((obj.className).indexOf("col-md-") > -1) classie.addClass(obj.parentNode, 'cols-md-responsive');
                if ((obj.className).indexOf("col-sm-") > -1 && (obj.className).indexOf("col-sm-clear") == -1) classie.addClass(obj.parentNode, 'cols-sm-responsive');
            });
            setRowHeight(el);
            var elements = 0;
            getDivChildren(el, '.row-internal .background-inner', function(obj, i, total) {
                elements++;
                if (i == 0) {
                    if (!!obj.style.backgroundImage && obj.style.backgroundImage !== void 0) {
                        var url = (obj.style.backgroundImage).match(uri_pattern),
                            image = new Image();
                        image.onload = function() {
                            el.setAttribute('data-imgready', 'true');
                            el.dispatchEvent(new CustomEvent('imgLoaded'));
                        };
                        image.src = url[0];
                    } else {
                        el.setAttribute('data-imgready', 'true');
                        el.dispatchEvent(new CustomEvent('imgLoaded'));
                    }
                }
            });
            if (elements == 0) {
                el.setAttribute('data-imgready', 'true');
            }
            if (!_UI.isFullPage) {
                bodyTop = document.documentElement['scrollTop'] || document.body['scrollTop'];
                kenburnsRows = el.parentNode.parentNode.querySelectorAll('.with-kburns > .row-background > .background-wrapper, .with-kburns > .header-bg-wrapper, .with-kburns > .fp-tableCell > .row-background > .background-wrapper, .with-kburns > .fp-tableCell > .fp-scrollable > .fp-scroller > .row-background > .background-wrapper');
                kenburnsCols = el.querySelectorAll('.with-kburns > .column-background > .background-wrapper');
                if (!_UI.isMobile) {
                    parallaxRows = el.parentNode.parentNode.querySelectorAll('.with-parallax > .row-background > .background-wrapper, .with-parallax > .fp-tableCell > .row-background > .background-wrapper, .with-parallax > .fp-tableCell > .fp-scrollable > .fp-scroller > .row-background > .background-wrapper');
                    parallaxCols = el.querySelectorAll('.with-parallax > .column-background > .background-wrapper');
                    visibleRows = el.parentNode.parentNode;
                    parallaxRowCol(bodyTop);
                    visibleRowCol(bodyTop);
                } else {
                    kenburnsRowCol(bodyTop);
                }
            }
        },
        setRowHeight = function(container, forced, resized) {
            var currentTallest = 0,
                percentHeight = 0,
                minHeight = 0,
                el, child, hasSubCols = false;
            if (container.length == undefined) {
                container = [container];
            }
            for (var i = 0; i < container.length; i++) {
                var el = container[i],
                    $row = el,
                    totalHeight = 0,
                    colsArray = new Array(),
                    calculatePadding = 0,
                    $rowParent = $row.parentNode,
                    isHeader = false,
                    isFirst = false;
                $row.oversized = false;
                percentHeight = el.getAttribute("data-height-ratio");
                minHeight = el.getAttribute("data-minheight");
                child = (el.firstElementChild || el.firstChild);
                var childHeight = outerHeight(child);
                if (!!percentHeight || !!minHeight || forced || (isIE && classie.hasClass(el, 'unequal'))) {
                    child.style.height = '';
                    if (!!percentHeight) {
                        if (percentHeight == 'full') {
                            currentTallest = parseInt(wheight);
                        } else {
                            currentTallest = parseInt((wheight * percentHeight) / 100);
                        }
                    } else {
                        currentTallest = el.clientHeight;
                    }
                    if (!!minHeight) {
                        if (currentTallest < minHeight || currentTallest == undefined) currentTallest = parseInt(minHeight);
                    }
                    var computedStyleRow = getComputedStyle(el),
                        computedStyleRowParent = getComputedStyle($rowParent);
                    calculatePadding -= (parseFloat(computedStyleRow.paddingTop) + parseFloat(computedStyleRowParent.paddingTop));
                    calculatePadding -= (parseFloat(computedStyleRow.paddingBottom) + parseFloat(computedStyleRowParent.paddingBottom));
                    if (getClosest(el, 'header-uncode-block') != null) {
                        el.setAttribute('data-row-header', 'true');
                        isHeader = true;
                    } else {
                        if (pageHeader == null) {
                            var prevRow = $rowParent.previousSibling;
                            if (prevRow != null && prevRow.innerText == '_UI.initHeader();') {
                                isFirst = true;
                            }
                        }
                    }
                    if (classie.hasClass(el, 'row-slider')) {
                        percentHeight = el.getAttribute("data-height-ratio");
                        minHeight = el.getAttribute("data-minheight");
                        if (percentHeight == 'full') {
                            currentTallest = parseInt(wheight);
                        } else {
                            currentTallest = parseInt((wheight * percentHeight) / 100);
                        }
                        if (isHeader || isFirst) {
                            if (isMobileTransparent || wwidth > mediaQuery) currentTallest -= menuHeight - transmenuHeight;
                            else currentTallest -= menuHeight - secmenuHeight;
                            currentTallest += calculatePadding;
                        } else {
                            if (isMobileTransparent || wwidth > mediaQuery) currentTallest += calculatePadding;
                            else currentTallest = 'auto';
                        }
                        getDivChildren(el, '.owl-carousel', function(owl, i) {
                            owl.style.height = (currentTallest == 'auto' ? 'auto' : currentTallest + 'px');
                            if (isIE) {
                                getDivChildren(owl, '.owl-stage', function(owlIn, i) {
                                    owlIn.style.height = (currentTallest == 'auto' ? '100%' : currentTallest + 'px');
                                });
                            }
                        });
                        if (_UI.isFullPageSnap || classie.hasClass(masthead, 'menu-transparent'))
                            continue;
                        else {
                            if (classie.hasClass(document.body, 'uncode-fp-menu-hide')) {
                                $rowParent.parentNode.style.paddingTop = menuHeight + 'px';
                            }
                        }
                    } else {
                        if (isHeader || isFirst || _UI.isFullPage) {
                            if (!(_UI.isFullPage && !classie.hasClass(masthead, 'menu-transparent') && classie.hasClass(document.body, 'uncode-fp-menu-hide') && !isHeader && !isFirst)) {
                                if (isMobileTransparent || wwidth > mediaQuery) currentTallest -= menuHeight - transmenuHeight;
                                else currentTallest -= menuHeight - secmenuHeight;
                            }
                            currentTallest += calculatePadding;
                            if (_UI.isFullPage && classie.hasClass(document.body, 'uncode-scroll-safe-padding') && classie.hasClass(masthead, 'menu-transparent') && !classie.hasClass(document.body, 'uncode-fp-menu-hide')) {
                                var safeMenuHeight = parseFloat(document.body.getAttribute("data-additional-padding")) + parseFloat(menuHeight);
                                if ((" " + child.parentNode.className + " ").replace(/[\n\t]/g, " ").indexOf("-top-padding ") < 0 || classie.hasClass(child.parentNode, 'single-top-padding')) {
                                    classie.addClass(child.parentNode, 'fp-safe-padding-top');
                                    child.parentNode.style.paddingTop = safeMenuHeight + 'px';
                                }
                                if ((" " + child.parentNode.className + " ").replace(/[\n\t]/g, " ").indexOf("-bottom-padding ") < 0 || classie.hasClass(child.parentNode, 'single-bottom-padding')) {
                                    classie.addClass(child.parentNode, 'fp-safe-padding-bottom');
                                    child.parentNode.style.paddingBottom = safeMenuHeight + 'px';
                                }
                            }
                            if (!classie.hasClass(masthead, 'menu-transparent') && classie.hasClass(document.body, 'uncode-fp-menu-hide') && (isFirst || isHeader))
                                $rowParent.parentNode.style.paddingTop = menuHeight + 'px';
                        } else {
                            if (wwidth > mediaQuery) currentTallest += calculatePadding;
                            else currentTallest = 'auto';
                        }
                    }
                    if (_UI.isFullPage) {
                        currentTallest -= _UI.adminBarHeight;
                    }
                    if (!!minHeight) {
                        if (currentTallest < minHeight || currentTallest == 'auto') currentTallest = parseInt(minHeight);
                    }
                    child.style.height = (currentTallest == 'auto' ? 'auto' : currentTallest + 'px');
                } else {
                    currentTallest = 0;
                }
                if (wwidth > mediaQuery) {
                    getDivChildren(el, '.column_parent', function(col, i, total) {
                        var $col = col,
                            $colHeight = 0,
                            $colDiff = 0,
                            $colPercDiff = 100;
                        $col.oversized = false;
                        $col.forceHeight = currentTallest;
                        currentTallest = child.clientHeight;
                        if ((isHeader || isFirst) && currentTallest != 'auto') currentTallest -= transmenuHeight;
                        var getFirstCol = null,
                            getMargin = 0,
                            getSubMargin = 0;
                        getDivChildren(col, '.row-child', function(obj, i, total) {
                            var $colChild = obj,
                                $colParent = $colChild.parentNode,
                                computedStyleCol = getComputedStyle($colParent);
                            parseFloat(computedStyleCol.marginTop);
                            getSubMargin += parseFloat(computedStyleCol.marginTop);
                        });
                        currentTallest += getSubMargin;
                        getDivChildren(col, '.row-child', function(obj, i, total) {
                            var $colChild = obj,
                                $colInner = $colChild.children[0],
                                $colParent = $colChild.parentNode,
                                $uncont = $colParent.parentNode;
                            if (i == 0 && total > 1) getFirstCol = $colInner;
                            $colChild.oversized = false;
                            percentHeight = $colChild.getAttribute("data-height");
                            minHeight = $colChild.getAttribute("data-minheight");
                            if (percentHeight != null || minHeight != null) {
                                $colInner.style.height = '';
                                $colParent.style.height = 'auto';
                                $uncont.style.height = '100%';
                                $colChild.removeAttribute("style");
                                var newHeight = (percentHeight != null) ? Math.ceil((currentTallest) * (percentHeight / 100)) : parseInt(minHeight);
                                var computedStyleCol = getComputedStyle($colParent);
                                parseFloat(computedStyleCol.marginTop);
                                getMargin = parseFloat(computedStyleCol.marginTop);
                                newHeight -= (getMargin);
                                $colPercDiff -= (percentHeight != null) ? percentHeight : 0;
                                if (currentTallest > newHeight) {
                                    var getColHeight = outerHeight($colChild);
                                    if (getColHeight > newHeight) {
                                        $colHeight += getColHeight;
                                        $colDiff += getColHeight;
                                        $colChild.oversized = true;
                                        $col.oversized = true;
                                        $row.oversized = true;
                                    } else {
                                        $colHeight += newHeight;
                                        $colInner.style.height = newHeight + 'px';
                                    }
                                }
                            } else {
                                $colHeight += outerHeight($colChild);
                            }
                        });
                        if (getFirstCol != null) {
                            getFirstCol.style.height = (parseFloat(getFirstCol.style.height) - getMargin) + 'px';
                        }
                        colsArray.push({
                            colHeight: $colHeight,
                            colDiv: $col
                        });
                        $col.colDiff = $colDiff;
                        $col.colPercDiff = $colPercDiff;
                    });
                    if ($row.oversized) {
                        child.style.height = '';
                        colsArray.sort(function(a, b) {
                            if (a.colHeight < b.colHeight) return 1;
                            if (a.colHeight > b.colHeight) return -1;
                            return 0;
                        });
                        var $totalHeight = 0;
                        colsArray.forEach(function(col) {
                            var $col = col.colDiv,
                                $colHeight = col.colHeight;
                            getDivChildren($col, '.row-child', function(obj, i, total) {
                                var $colChild = obj,
                                    $colInner = $colChild.children[0],
                                    percentHeight = $colChild.getAttribute("data-height"),
                                    $colParent = $colChild.parentNode,
                                    $uncont = $colParent.parentNode,
                                    newHeight;
                                $colHeight = $col.forceHeight - $col.colDiff;
                                if (percentHeight != null) {
                                    if ($colHeight > 0) {
                                        if ($col.oversized) {
                                            if (!$colChild.oversized) {
                                                newHeight = Math.ceil(($colHeight) * (percentHeight / $col.colPercDiff));
                                                if (i == total - 1 && total > 1) {
                                                    $uncont.style.height = 'auto';
                                                    $colChild.style.display = 'none';
                                                    newHeight = outerHeight($col.parentNode) - outerHeight($uncont);
                                                    $uncont.style.height = '100%';
                                                    $colChild.style.display = 'table';
                                                }
                                                if (newHeight == 0) newHeight = Math.ceil(($col.forceHeight) * (percentHeight / 100));
                                                $colInner.style.height = newHeight + 'px';
                                            }
                                        } else {
                                            if ($totalHeight == 0) newHeight = Math.ceil(($colHeight) * (percentHeight / $col.colPercDiff));
                                            else {
                                                newHeight = Math.ceil(($totalHeight) * (percentHeight / $col.colPercDiff));
                                            }
                                            if (i == total - 1 && total > 1) {
                                                $uncont.style.height = 'auto';
                                                $colChild.style.display = 'none';
                                                newHeight = outerHeight($col.parentNode) - outerHeight($uncont);
                                                $uncont.style.height = '100%';
                                                $colChild.style.display = 'table';
                                            }
                                            $colInner.style.height = newHeight + 'px';
                                        }
                                    } else {
                                        if ($colChild.oversized) {
                                            if ($totalHeight == 0) newHeight = Math.ceil(($colHeight) * (percentHeight / $col.colPercDiff));
                                            else {
                                                if ($col.colPercDiff == 0) $col.colPercDiff = 100;
                                                newHeight = Math.ceil(($totalHeight) * (percentHeight / $col.colPercDiff));
                                            }
                                            if (i == total - 1 && total > 1) {
                                                $uncont.style.height = 'auto';
                                                $colChild.style.display = 'none';
                                                newHeight = outerHeight($col.parentNode) - outerHeight($uncont);
                                                $uncont.style.height = '100%';
                                                $colChild.style.display = 'table';
                                            }
                                            $colInner.style.height = newHeight + 'px';
                                        }
                                    }
                                }
                            });
                            var uncell = $col.getElementsByClassName('uncell');
                            if (uncell[0] != undefined && $totalHeight == 0) $totalHeight = outerHeight(uncell[0]);
                        });
                    }
                    if (isFF) {
                        getDivChildren(el, '.uncoltable', function(col, i, total) {
                            if (col.style.minHeight != '') {
                                col.style.height = '';
                            }
                        });
                    }
                    if (resized) {
                        getDivChildren(el, '.row-child > .row-inner', function(obj, k, total) {
                            if (obj.style.height == '') {
                                if (wwidth > mediaQueryMobile) {
                                    var getStyle = (window.getComputedStyle((obj.parentNode), null)),
                                        getInnerHeight = (parseInt(obj.parentNode.clientHeight) - parseInt(getStyle.paddingTop) - parseInt(getStyle.paddingBottom));
                                    obj.style.height = getInnerHeight + 1 + 'px';
                                    obj.style.marginBottom = '-1px';
                                }
                            }
                        });
                        getDivChildren(el, '.row-parent > .row-inner', function(obj, k, total) {
                            if (obj.style.height != '') {
                                var getStyle = (window.getComputedStyle((obj.parentNode), null)),
                                    getInnerHeight = (parseInt(obj.parentNode.clientHeight) - parseInt(getStyle.paddingTop) - parseInt(getStyle.paddingBottom)),
                                    getTempHeight = parseInt(obj.style.height);
                                if (getInnerHeight > getTempHeight) {
                                    obj.style.height = getInnerHeight + 1 + 'px';
                                    obj.style.marginBottom = '-1px';
                                }
                            }
                        });
                    }
                } else {
                    if (isFF) {
                        getDivChildren(el, '.uncoltable', function(col, i, total) {
                            if (col.style.minHeight != '') {
                                col.style.height = '';
                                col.style.height = outerHeight(col.parentNode) + 'px';
                            }
                        });
                    }
                    if (isIE && (wwidth > mediaQueryMobile)) {
                        if (child.style.height == 'auto') {
                            child.style.height = outerHeight(child) + 'px';
                        }
                    }
                }
                if (isFF) {
                    var sliderColumnFix = document.querySelector('.uncode-slider .row-inner > .column_child:only-child');
                    if (sliderColumnFix != null) {
                        if (wwidth > mediaQuery) {
                            sliderColumnFix.style.setProperty("height", "");
                        } else {
                            sliderColumnFix.style.setProperty("height", "");
                            sliderColumnFix.style.setProperty("height", outerHeight(sliderColumnFix.parentNode) + "px", "important");
                        }
                    }
                }
            };
        },
        headerHeight = function(container) {
            forEachElement(container, function(el, i) {
                var getHeight = el.getAttribute("data-height"),
                    newHeight = ((wheight * getHeight) / 100);
                if (getHeight != 'fixed' && newHeight != 0) {
                    if (isMobileTransparent || wwidth > mediaQuery) newHeight -= menuHeight - transmenuHeight;
                    else newHeight -= menuHeight - secmenuHeight;
                    el.style.height = newHeight + 'px';
                }
            });
            if (masthead != undefined) {
                masthead.parentNode.style.height = menuHeight + 'px';
                if (header != undefined && header.length) {
                    if (classie.hasClass(masthead, 'menu-transparent')) {
                        if (isMobileTransparent || wwidth > mediaQuery) {
                            masthead.parentNode.style.height = '0px';
                            if (classie.hasClass(masthead, 'menu-add-padding')) {
                                for (var j = 0; j < header.length; j++) {
                                    var headerel = header[j];
                                    var headerBlock = getClosest(headerel, 'header-uncode-block');
                                    if (headerBlock != null) {
                                        var innerRows = headerel.querySelectorAll('.column_parent > .uncol > .uncoltable > .uncell > .uncont, .uncode-slider .column_child > .uncol > .uncoltable > .uncell > .uncont');
                                        for (var k = 0; k < innerRows.length; k++) {
                                            if (innerRows[k] != undefined) {
                                                if (wwidth > mediaQuery) innerRows[k].style.paddingTop = transmenuHeight + 'px';
                                                else innerRows[k].style.paddingTop = (transmenuHeight - mastheadMobilePaddingTop) + 'px';
                                            }
                                        }
                                    } else {
                                        getDivChildren(headerel, '.header-content', function(headerContent, i) {
                                            if (wwidth > mediaQuery) headerContent.style.paddingTop = transmenuHeight + 'px';
                                            else headerContent.style.paddingTop = (transmenuHeight - mastheadMobilePaddingTop) + 'px';
                                        });
                                    }
                                }
                            }
                        }
                    }
                }
            }
        },
        initVideoComponent = function(container, classTarget) {
            getDivChildren(container, classTarget, function(el, i) {
                var width = outerWidth(el),
                    pWidth, height = outerHeight(el),
                    pHeight, $tubularPlayer = (el.getElementsByTagName('iframe').length == 1) ? el.getElementsByTagName('iframe') : el.getElementsByTagName('video'),
                    ratio = (el.getAttribute("data-ratio") != null) ? Number(el.getAttribute("data-ratio")) : $tubularPlayer[0].getAttribute("data-ratio"),
                    heightOffset = 80,
                    widthOffset = heightOffset * ratio;
                if ($tubularPlayer[0] != undefined) {
                    if (width / ratio < height) {
                        pWidth = Math.ceil((height + heightOffset) * ratio);
                        $tubularPlayer[0].style.width = pWidth + widthOffset + 'px';
                        $tubularPlayer[0].style.height = height + heightOffset + 'px';
                        $tubularPlayer[0].style.left = ((width - pWidth) / 2) - (widthOffset / 2) + 'px';
                        $tubularPlayer[0].style.top = '-' + (heightOffset / 2) + 'px';
                        $tubularPlayer[0].style.position = 'absolute';
                    } else {
                        pHeight = Math.ceil(width / ratio);
                        $tubularPlayer[0].style.width = width + widthOffset + 'px';
                        $tubularPlayer[0].style.height = pHeight + heightOffset + 'px';
                        $tubularPlayer[0].style.left = '-' + (widthOffset / 2) + 'px';
                        $tubularPlayer[0].style.top = ((height - pHeight) / 2) - (heightOffset / 2) + 'px';
                        $tubularPlayer[0].style.position = 'absolute';
                    }
                }
            });
        },
        init_overlay = function() {
            var triggerButton, closeButtons = new Array();

            function toggleOverlay(btn) {
                Array.prototype.forEach.call(document.querySelectorAll('div.overlay'), function(overlay) {
                    if (btn.getAttribute('data-area') == overlay.getAttribute('data-area')) {
                        var container = document.querySelector('div.' + btn.getAttribute('data-container')),
                            inputField = overlay.querySelector('.search-field');
                        if (classie.has(overlay, 'open')) {
                            window.dispatchEvent(menuClose);
                            overlayOpened = false;
                            classie.remove(overlay, 'open');
                            classie.remove(container, 'overlay-open');
                            classie.add(overlay, 'close');
                            classie.remove(overlay, 'open-items');
                            var onEndTransitionFn = function(ev) {
                                if (transitionEvent) {
                                    if (ev.propertyName !== 'visibility') return;
                                    this.removeEventListener(transitionEvent, onEndTransitionFn);
                                }
                                classie.remove(overlay, 'close');
                            };
                            if (transitionEvent) {
                                overlay.addEventListener(transitionEvent, onEndTransitionFn);
                            } else {
                                onEndTransitionFn();
                            }
                        } else if (!classie.has(overlay, 'close')) {
                            window.dispatchEvent(menuOpen);
                            overlayOpened = true;
                            classie.add(overlay, 'open');
                            classie.add(container, 'overlay-open');
                            if (jQuery('body.menu-overlay').length == 0) {
                                setTimeout(function() {
                                    inputField.focus();
                                }, 1000);
                            }
                            setTimeout(function() {
                                if (classie.has(overlay, 'overlay-sequential')) classie.add(overlay, 'open-items');
                            }, 800);
                        }
                    }
                });
                if (classie.hasClass(btn, 'search-icon') || classie.hasClass(btn, 'menu-close-search')) return;
                if (classie.hasClass(triggerButton, 'close')) {
                    _UI.menuOpened = false;
                    classie.removeClass(triggerButton, 'close');
                    classie.addClass(triggerButton, 'closing');
                    Array.prototype.forEach.call(closeButtons, function(closeButton) {
                        if (!classie.hasClass(closeButton, 'menu-close-search')) {
                            classie.removeClass(closeButton, 'close');
                            classie.addClass(closeButton, 'closing');
                        }
                    });
                    setTimeout(function() {
                        classie.removeClass(triggerButton, 'closing');
                        triggerButton.style.opacity = 1;
                        Array.prototype.forEach.call(closeButtons, function(closeButton) {
                            if (!classie.hasClass(closeButton, 'menu-close-search')) {
                                classie.removeClass(closeButton, 'closing');
                                closeButton.style.opacity = 0;
                            }
                        });
                    }, 800);
                } else {
                    _UI.menuOpened = true;
                    triggerButton.style.opacity = 0;
                    var getBtnRect = !classie.hasClass(triggerButton, 'search-icon') ? triggerButton.getBoundingClientRect() : null;
                    Array.prototype.forEach.call(closeButtons, function(closeButton) {
                        if (!classie.hasClass(closeButton, 'menu-close-search')) {
                            classie.addClass(triggerButton, 'close');
                            if (getBtnRect != null) closeButton.setAttribute('style', 'top:' + getBtnRect.top + 'px; left:' + getBtnRect.left + 'px !important');
                            classie.addClass(closeButton, 'close');
                            closeButton.style.opacity = 1;
                        }
                    });
                    window.addEventListener("resize", function() {
                        positionCloseBtn(triggerButton, closeButtons);
                    });
                }
            }

            function positionCloseBtn(triggerButton, closeButtons) {
                var getBtnRect = !classie.hasClass(triggerButton, 'search-icon') ? triggerButton.getBoundingClientRect() : null;
                Array.prototype.forEach.call(closeButtons, function(closeButton) {
                    if (!classie.hasClass(closeButton, 'menu-close-search')) {
                        if (getBtnRect != null) closeButton.setAttribute('style', 'top:' + getBtnRect.top + 'px; left:' + getBtnRect.left + 'px !important');
                        closeButton.style.opacity = 1;
                    }
                });
            }
            (function bindEscape() {
                document.onkeydown = function(evt) {
                    evt = evt || window.event;
                    var isEscape = false;
                    if ("key" in evt) {
                        isEscape = (evt.key == "Escape" || evt.key == "Esc");
                    } else {
                        isEscape = (evt.keyCode == 27);
                    }
                    if (isEscape && overlayOpened) {
                        Array.prototype.forEach.call(closeButtons, function(closeButton) {
                            if (classie.hasClass(closeButton, 'overlay-close') && classie.hasClass(closeButton, 'menu-button-overlay')) {
                                closeButton.click();
                            }
                        });
                    }
                };
            })();
            Array.prototype.forEach.call(document.querySelectorAll('.trigger-overlay'), function(triggerBttn) {
                if (_UI.menuOpened) return;
                triggerBttn.addEventListener('click', function(e) {
                    triggerButton = e.currentTarget;
                    if (wwidth < mediaQuery && classie.hasClass(triggerButton, 'search-icon')) {
                        return true;
                    } else {
                        e.stopPropagation();
                        if (wwidth > mediaQuery) toggleOverlay(triggerButton);
                        else {
                            if (classie.addClass(triggerButton, 'search-icon')) return true;
                        }
                        e.preventDefault();
                        return false;
                    }
                }, false);
            });
            Array.prototype.forEach.call(document.querySelectorAll('.overlay-close'), function(closeBttn) {
                closeButtons.push(closeBttn);
                closeBttn.addEventListener('click', function(e) {
                    if (wwidth > mediaQuery) toggleOverlay(closeBttn);
                    e.preventDefault();
                    return false;
                }, false);
            });
        },
        shrinkMenu = function(bodyTop) {
            var logoShrink, offset = 100;
            for (var i = 0; i < logoel.length; i++) {
                if (((secmenuHeight == 0) ? bodyTop > menuHeight : bodyTop > secmenuHeight + offset) && !classie.hasClass(logoel[i], 'shrinked') && (wwidth > mediaQuery)) {
                    classie.addClass(logoel[i], 'shrinked');
                    if (logoMinScale != undefined) {
                        logoShrink = logolink.children;
                        Array.prototype.forEach.call(logoShrink, function(singleLogo) {
                            singleLogo.style.height = logoMinScale + 'px';
                            singleLogo.style.lineHeight = logoMinScale + 'px';
                            if (classie.hasClass(singleLogo, 'text-logo')) singleLogo.style.fontSize = logoMinScale + 'px';
                        });
                    }
                    setTimeout(function() {
                        calculateMenuHeight(false);
                    }, 300);
                } else if ((((secmenuHeight == 0) ? bodyTop == 0 : bodyTop <= secmenuHeight + offset) || (wwidth < mediaQuery)) && classie.hasClass(logoel[i], 'shrinked')) {
                    classie.removeClass(logoel[i], 'shrinked');
                    if (logoMinScale != undefined) {
                        logoShrink = logolink.children;
                        Array.prototype.forEach.call(logoShrink, function(singleLogo) {
                            singleLogo.style.height = singleLogo.getAttribute('data-maxheight') + 'px';
                            singleLogo.style.lineHeight = singleLogo.getAttribute('data-maxheight') + 'px';
                            if (classie.hasClass(singleLogo, 'text-logo')) singleLogo.style.fontSize = singleLogo.getAttribute('data-maxheight') + 'px';
                        });
                    }
                    setTimeout(function() {
                        calculateMenuHeight(false);
                    }, 300);
                }
            }
        },
        switchColorsMenu = function(bodyTop, style) {
            for (var i = 0; i < transmenuel.length; i++) {
                if (masthead.style.opacity !== 1) masthead.style.opacity = 1;
                if ((secmenuHeight == 0) ? bodyTop > menuHeight / 2 : bodyTop > secmenuHeight) {
                    if (classie.hasClass(masthead, 'style-dark-original')) {
                        logo.className = logo.className.replace("style-light", "style-dark");
                    }
                    if (classie.hasClass(masthead, 'style-light-original')) {
                        logo.className = logo.className.replace("style-dark", "style-light");
                    }
                    if (style != undefined) {
                        if (style == 'dark') {
                            classie.removeClass(transmenuel[i], 'style-light-override');
                        }
                        if (style == 'light') {
                            classie.removeClass(transmenuel[i], 'style-dark-override');
                        }
                        classie.addClass(transmenuel[i], 'style-' + style + '-override');
                    }
                } else {
                    if (style != undefined) {
                        if (style == 'dark') {
                            classie.removeClass(transmenuel[i], 'style-light-override');
                        }
                        if (style == 'light') {
                            classie.removeClass(transmenuel[i], 'style-dark-override');
                        }
                        classie.addClass(transmenuel[i], 'style-' + style + '-override');
                    }
                }
            }
            if (pageHeader != undefined) {
                if (style != undefined) {
                    if (classie.hasClass(pageHeader, 'header-style-dark')) {
                        classie.removeClass(pageHeader, 'header-style-dark');
                    }
                    if (classie.hasClass(pageHeader, 'header-style-light')) {
                        classie.removeClass(pageHeader, 'header-style-light');
                    }
                    classie.addClass(pageHeader, 'header-style-' + style);
                }
            }
        },
        visibleRowCol = function(bodyTop) {
            if (typeof visibleRows == 'object') {
                for (var i = 0; i < visibleRows.length; i++) {
                    var section = visibleRows[i],
                        sectionHeight = outerHeight(section),
                        offSetTop = bodyTop + (section != null ? section.getBoundingClientRect().top : 0),
                        offSetPosition = wheight + bodyTop - offSetTop;
                    if (offSetPosition > 0 && offSetPosition < (sectionHeight + wheight)) {
                        classie.addClass($kenburnsInner[0], 'uncode-scroll-visible');
                        visibleRows[i].dispatchEvent(new CustomEvent('enter-row'));
                    } else {
                        classie.removeClass($kenburnsInner[0], 'uncode-scroll-visible');
                        visibleRows[i].dispatchEvent(new CustomEvent('exit-row'));
                    }
                }
            }
        },
        kenburnsRowCol = function(bodyTop) {
            var value;
            if (typeof kenburnsRows == 'object' && kenburnsRows.length) {
                for (var i = 0; i < kenburnsRows.length; i++) {
                    var section = kenburnsRows[i].parentNode,
                        sectionHeight = outerHeight(section),
                        offSetTop = bodyTop + (section != null ? (classie.hasClass(section.parentNode.parentNode, 'owl-carousel') ? section.parentNode.parentNode.getBoundingClientRect().top : section.getBoundingClientRect().top) : 0),
                        offSetPosition = wheight + bodyTop - offSetTop,
                        $kenburnsInner = kenburnsRows[i].querySelectorAll('.background-inner, .header-bg');
                    if (offSetPosition > 0 && offSetPosition < (sectionHeight + wheight)) {
                        classie.addClass($kenburnsInner[0], 'uncode-kburns');
                        $kenburnsInner[0].dispatchEvent(new CustomEvent('enter-kburns'));
                    } else {
                        classie.removeClass($kenburnsInner[0], 'uncode-kburns');
                        $kenburnsInner[0].dispatchEvent(new CustomEvent('exit-kburns'));
                    }
                }
            }
            if (typeof kenburnsCols == 'object' && kenburnsCols.length) {
                for (var j = 0; j < kenburnsCols.length; j++) {
                    var elm = kenburnsCols[j],
                        $kenburnsInner = elm.querySelectorAll('.background-inner');
                    if (checkVisible(elm)) {
                        classie.addClass($kenburnsInner[0], 'uncode-kburns');
                        $kenburnsInner[0].dispatchEvent(new CustomEvent('enter-kburns'));
                    } else {
                        classie.removeClass($kenburnsInner[0], 'uncode-kburns');
                        $kenburnsInner[0].dispatchEvent(new CustomEvent('exit-kburns'));
                    }
                }
            }
        },
        parallaxRowCol = function(bodyTop) {
            var value;
            if (typeof parallaxRows == 'object') {
                for (var i = 0; i < parallaxRows.length; i++) {
                    var section = parallaxRows[i].parentNode,
                        thisHeight = outerHeight(parallaxRows[i]),
                        sectionHeight = outerHeight(section),
                        offSetTop = bodyTop + (section != null ? (classie.hasClass(section.parentNode.parentNode, 'owl-carousel') ? section.parentNode.parentNode.getBoundingClientRect().top : section.getBoundingClientRect().top) : 0),
                        offSetPosition = wheight + bodyTop - offSetTop;
                    if (offSetPosition > 0 && offSetPosition < (sectionHeight + wheight)) {
                        value = ((offSetPosition - wheight) * speedDivider);
                        if (Math.abs(value) < (thisHeight - sectionHeight)) {
                            translateElement(parallaxRows[i], value);
                        } else {
                            translateElement(parallaxRows[i], thisHeight - sectionHeight);
                        }
                    }
                }
            }
            if (typeof parallaxCols == 'object') {
                for (var j = 0; j < parallaxCols.length; j++) {
                    var section = parallaxCols[j].parentNode,
                        thisHeight = outerHeight(parallaxCols[j]),
                        sectionHeight = outerHeight(section),
                        offSetTop = bodyTop + (section != null ? section.getBoundingClientRect().top : 0),
                        offSetPosition = wheight + bodyTop - offSetTop;
                    if (offSetPosition > 0 && offSetPosition < (sectionHeight + wheight)) {
                        value = ((offSetPosition - wheight) * speedDivider);
                        value *= .8;
                        if (Math.abs(value) < (thisHeight - sectionHeight)) {
                            translateElement(parallaxCols[j], value);
                        } else {
                            translateElement(parallaxCols[j], thisHeight - sectionHeight);
                        }
                    }
                }
            }
        },
        parallaxHeader = function(bodyTop) {
            var value;
            if (typeof parallaxHeaders == 'object') {
                for (var i = 0; i < parallaxHeaders.length; i++) {
                    var section = parallaxHeaders[i].parentNode,
                        thisSibling = section.nextSibling,
                        thisHeight, sectionHeight, offSetTop, offSetPosition;
                    if (classie.hasClass(parallaxHeaders[i], 'header-carousel-wrapper')) {
                        getDivChildren(parallaxHeaders[i], '.t-background-cover', function(item, l, total) {
                            thisHeight = outerHeight(item);
                            sectionHeight = outerHeight(section);
                            offSetTop = bodyTop + section.getBoundingClientRect().top;
                            offSetPosition = wheight + bodyTop - offSetTop;
                            if (offSetPosition > 0 && offSetPosition < (sectionHeight + wheight)) {
                                value = ((offSetPosition - wheight) * speedDivider);
                                if (Math.abs(value) < (thisHeight - sectionHeight)) {
                                    translateElement(item, value);
                                }
                            }
                        });
                    } else {
                        thisHeight = outerHeight(parallaxHeaders[i]);
                        sectionHeight = outerHeight(section);
                        offSetTop = bodyTop + section.getBoundingClientRect().top;
                        offSetPosition = wheight + bodyTop - offSetTop;
                        if (offSetPosition > 0 && offSetPosition < (sectionHeight + wheight)) {
                            value = ((offSetPosition - wheight) * speedDivider);
                            if (Math.abs(value) < (thisHeight - sectionHeight)) {
                                translateElement(parallaxHeaders[i], value);
                            }
                        }
                    }
                }
            }
        },
        headerOpacity = function(bodyTop) {
            if (headerWithOpacity && headerWithOpacity.length) {
                var thisHeight = outerHeight(headerWithOpacity[0]);
                if (bodyTop > thisHeight / 8) {
                    if (pageHeader != undefined) classie.addClass(pageHeader, 'header-scrolled');
                } else {
                    if (pageHeader != undefined) classie.removeClass(pageHeader, 'header-scrolled');
                }
            }
        },
        showHideScrollup = function(bodyTop) {
            if (bodyTop != 0) {
                if (bodyTop > wheight || ((bodyTop + wheight) >= docheight) && docheight > 0) {
                    classie.addClass(document.body, 'window-scrolled');
                    classie.removeClass(document.body, 'hide-scrollup');
                    if (footerScroller && footerScroller[0] != undefined) {
                        footerScroller[0].style.display = '';
                    }
                } else {
                    if (classie.hasClass(document.body, 'window-scrolled')) classie.addClass(document.body, 'hide-scrollup');
                    classie.removeClass(document.body, 'window-scrolled');
                }
            }
        },
        fixMenu = function() {
            menuwrapper = document.querySelectorAll(".menu-wrapper");
            if (!classie.hasClass(document.body, 'vmenu') && _UI.isFullPage && !_UI.isFullPageSnap && classie.hasClass(document.body, 'uncode-fp-menu-hide')) {
                menuwrapper = document.querySelector(".menu-wrapper");
                pageHeader = document.getElementById("page-header");
                menuwrapper.style.position = 'fixed';
                menuwrapper.style.zIndex = '5';
            }
        },
        hideMenu = function(bodyTop) {
            if (_UI.menuOpened || bodyTop < 0) return;
            if (classie.hasClass(document.body, 'vmenu')) {
                if (wwidth < mediaQuery) menuhide = document.querySelector('#masthead .menu-hide-vertical');
                else menuhide = null;
            }
            if (classie.hasClass(document.body, 'hmenu-center')) {
                if (wwidth > mediaQuery) menuhide = document.querySelector('#masthead .menu-hide');
                else menuhide = document.querySelector('.menu-container-mobile.menu-hide');
            }
            if (typeof menuhide == 'object' && menuhide != null && mainmenu[0] != undefined) {
                var translate, scrollingDown = true;
                var sticky_element = (typeof mainmenu.item === 'undefined' ? ((wwidth > mediaQuery) ? mainmenu[0] : mainmenu[1]) : mainmenu[0]);
                if (lastScrollValue == bodyTop) return;
                if (lastScrollValue > bodyTop) scrollingDown = false;
                else scrollingDown = true;
                lastScrollValue = bodyTop;
                if (!scrollingDown) {
                    if (!_UI.scrolling) {
                        if ((secmenuHeight == 0) ? bodyTop == 0 : bodyTop < secmenuHeight) {
                            classie.removeClass(sticky_element.parentNode, 'is_stuck');
                            if (classie.hasClass(masthead, 'menu-transparent')) {
                                if ((isMobileTransparent || wwidth > mediaQuery) && !classie.hasClass(masthead.parentNode, 'no-header')) masthead.parentNode.style.height = '0px';
                            }
                            if (wwidth < mediaQuery) sticky_element.style.position = 'fixed';
                            else sticky_element.style.position = '';
                            hideMenuReset(sticky_element);
                            clearTimeout(hidingTimer);
                        }
                        if (classie.hasClass(menuhide, 'menu-hided')) {
                            classie.removeClass(menuhide, 'menu-hided');
                            hidingTimer = setTimeout(function() {
                                classie.addClass(sticky_element.parentNode, 'is_stuck');
                                hideMenuReset(sticky_element);
                            }, 400);
                        }
                    }
                } else {
                    if (menusticky.length == 0 && bodyTop < wheight / 3) {
                        if (sticky_element.style.position == 'fixed') sticky_element.style.position = '';
                    }
                    if (bodyTop > wheight / 2) {
                        clearTimeout(hidingTimer);
                        if (!classie.hasClass(menuhide, 'menu-hided')) {
                            classie.addClass(menuhide, 'menu-hided');
                            classie.addClass(sticky_element.parentNode, 'is_stuck');
                            if (sticky_element.style.position != 'fixed') {
                                sticky_element.style.visibility = 'hidden';
                                sticky_element.style.position = 'fixed';
                                sticky_element.style.top = '0px';
                            }
                            translateElement(menuhide, -_UI.menuMobileHeight - 1);
                        }
                    }
                }
            }
        },
        hideMenuReset = function(sticky_element) {
            var topOffset = 0;
            if (sticky_element.style.visibility == 'hidden') sticky_element.style.visibility = '';
            if (bodyBorder > 0) topOffset += bodyBorder;
            if (adminBar != null && window.getComputedStyle(adminBar, null).getPropertyValue("position") != 'fixed') adminBarHeight = 0;
            if (adminBarHeight > 0) topOffset += adminBarHeight;
            if (!classie.hasClass(document.body, 'boxed-width') && boxWidth > 0) sticky_element.style.width = boxWidth + 'px';
            translateElement(menuhide, 0);
        },
        stickMenu = function(bodyTop) {
            if (header && mainmenu[0] != undefined) {
                if (classie.hasClass(mainmenu[0], 'vmenu-container') && wwidth > mediaQuery) return;
                var sticky_element = (typeof mainmenu.item === 'undefined' ? ((isMobileTransparent || wwidth > mediaQuery) ? mainmenu[0] : mainmenu[1]) : mainmenu[0]);
                if ((secmenuHeight == 0 && (isMobileTransparent || wwidth > mediaQuery)) ? bodyTop > (0 + adminBarHeight) : bodyTop > (secmenuHeight + adminBarHeight)) {
                    if (!classie.hasClass(sticky_element.parentNode, 'is_stuck')) {
                        classie.addClass(sticky_element.parentNode, 'is_stuck');
                        sticky_element.style.position = 'fixed';
                        var getAnchorTop = bodyBorder;
                        if (adminBar != null && window.getComputedStyle(adminBar, null).getPropertyValue("position") != 'fixed') adminBarHeight = 0;
                        if (adminBarHeight > 0) getAnchorTop += adminBarHeight;
                        sticky_element.style.top = getAnchorTop + 'px';
                        if (!classie.hasClass(document.body, 'boxed-width') && boxWidth > 0) sticky_element.style.width = boxWidth + 'px';
                    }
                } else {
                    clearTimeout(hidingTimer);
                    classie.removeClass(sticky_element.parentNode, 'is_stuck');
                    sticky_element.style.position = 'fixed';
                    if (isMobileTransparent || wwidth > mediaQuery) sticky_element.style.position = '';
                    if (classie.hasClass(document.body, 'hmenu-center')) sticky_element.style.position = 'absolute';
                    sticky_element.style.top = '';
                }
            }
        },
        translateElement = function(element, valueY) {
            var translate = 'translate3d(0, ' + valueY + 'px' + ', 0)';
            element.style['-webkit-transform'] = translate;
            element.style['-moz-transform'] = translate;
            element.style['-ms-transform'] = translate;
            element.style['-o-transform'] = translate;
            element.style['transform'] = translate;
        },
        scrollFunction = function() {
            if (!_UI.isFullPage) {
                kenburnsRowCol(bodyTop);
                if (logoel != undefined && logoel.length && !isMobile) shrinkMenu(bodyTop);
                if (menusticky != undefined && menusticky.length) stickMenu(bodyTop);
                hideMenu(bodyTop);
                if (!isMobile) {
                    if (header && menusticky != undefined && menusticky.length)
                        switchColorsMenu(bodyTop);
                    parallaxRowCol(bodyTop);
                    parallaxHeader(bodyTop);
                    headerOpacity(bodyTop);
                    visibleRowCol(bodyTop);
                }
            }
        };
    if (!noScroll) {
        window.addEventListener('scroll', function(e) {
            if (!_UI.isFullPage) {
                bodyTop = document.documentElement.scrollTop || document.body.scrollTop;
                scrollFunction();
                showHideScrollup(bodyTop);
            }
        }, false);
    }

    function getClosest(el, tag) {
        do {
            if (el.className != undefined && el.className.indexOf(tag) > -1) return el;
        } while (el = el.parentNode);
        return null;
    }

    function outerHeight(el, includeMargin) {
        if (el != null) {
            var height = el.offsetHeight;
            if (includeMargin) {
                var style = el.currentStyle || getComputedStyle(el);
                height += parseInt(style.marginTop) + parseInt(style.marginBottom);
            }
            return height;
        }
    }

    function outerWidth(el, includeMargin) {
        var width = el.offsetWidth;
        if (includeMargin) {
            var style = el.currentStyle || getComputedStyle(el);
            width += parseInt(style.marginLeft) + parseInt(style.marginRight);
        }
        return width;
    }

    function forEachElement(selector, fn) {
        var elements = document.querySelectorAll(selector);
        for (var i = 0; i < elements.length; i++) fn(elements[i], i);
    }

    function getDivChildren(containerId, selector, fn) {
        if (containerId !== null) {
            var elements = containerId.querySelectorAll(selector);
            for (var i = 0; i < elements.length; i++) fn(elements[i], i, elements.length);
        }
    }

    function hideFooterScroll() {
        if (classie.hasClass(document.body, 'hide-scrollup')) footerScroller[0].style.display = "none";
    }
    document.addEventListener("DOMContentLoaded", function(event) {
        _UI.adaptive();
        boxWrapper = document.querySelectorAll('.box-wrapper');
        docheight = boxWrapper[0] != undefined ? boxWrapper[0].offsetHeight : 0;
        if (!classie.hasClass(document.body, 'vmenu') && !classie.hasClass(document.body, 'menu-offcanvas')) init_overlay();
        kenburnsRows = document.querySelectorAll('.with-kburns > .row-background > .background-wrapper, .with-kburns > .header-bg-wrapper, .with-kburns > .fp-tableCell > .row-background > .background-wrapper, .with-kburns > .fp-tableCell > .fp-scrollable > .fp-scroller > .row-background > .background-wrapper');
        kenburnsCols = document.querySelectorAll('.with-kburns > .column-background > .background-wrapper');
        if (!_UI.isMobile) {
            parallaxRows = document.querySelectorAll('.with-parallax > .row-background > .background-wrapper, .with-parallax > .fp-tableCell > .row-background > .background-wrapper, .with-parallax > .fp-tableCell > .fp-scrollable > .fp-scroller > .row-background > .background-wrapper');
            parallaxCols = document.querySelectorAll('.with-parallax > .column-background > .background-wrapper');
        }
        footerScroller = document.querySelectorAll('.footer-scroll-top');
        if (footerScroller && footerScroller[0] != undefined) {
            if (transitionEvent) {
                footerScroller[0].addEventListener(transitionEvent, hideFooterScroll);
            }
        }
        Array.prototype.forEach.call(document.querySelectorAll('.row-inner'), function(el) {
            el.style.height = '';
            el.style.marginBottom = '';
        });
        setRowHeight(document.querySelectorAll('.page-wrapper .row-parent, footer .row-parent'));
    });
    window.addEventListener("resize", function() {
        docheight = (boxWrapper != undefined && boxWrapper[0] != undefined) ? boxWrapper[0].offsetHeight : 0;
        var oldWidth = wwidth;
        _UI.wwidth = wwidth = window.innerWidth || document.documentElement.clientWidth;
        _UI.wheight = wheight = (window.innerHeight || document.documentElement.clientHeight) - (bodyBorder * 2);
        if (isSplitMenu) centerSplitMenu();
        if (isMobile && (oldWidth == wwidth)) return false;
        calculateMenuHeight(false);
        initBox();
        headerHeight('.header-wrapper');
        window.dispatchEvent(boxEvent);
        scrollFunction();
        showHideScrollup(bodyTop);
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            _UI.wheight = wheight = (window.innerHeight || document.documentElement.clientHeight) - (bodyBorder * 2);
            Array.prototype.forEach.call(document.querySelectorAll('.row-inner'), function(el) {
                el.style.height = '';
                el.style.marginBottom = '';
            });
            setRowHeight(document.querySelectorAll('.page-wrapper .row-parent, footer .row-parent'), false, true);
            if (!isMobile) {
                initVideoComponent(document.body, '.uncode-video-container.video, .uncode-video-container.self-video');
            }
        }, 500);
    });
    window.addEventListener("load", function() {
        if (!_UI.isMobile) {
            setTimeout(function() {
                window.dispatchEvent(_UI.boxEvent);
                Waypoint.refreshAll();
            }, 2000);
        }
        if (_UI.isMobile) {
            kenburnsRowCol(bodyTop);
        }
        showHideScrollup(bodyTop);
        if (document.createEvent) {
            var ev = document.createEvent('Event');
            ev.initEvent('resize', true, true);
            window.dispatchEvent(ev);
        } else {
            document.fireEvent('onresize');
        }
    }, false);
    var _UI = {
        bodyTop: bodyTop,
        boxEvent: boxEvent,
        bodyBorder: bodyBorder,
        initBox: initBox,
        adminBarHeight: 0,
        menuHeight: 0,
        menuMobileHeight: 0,
        fixMenuHeight: fixMenuHeight,
        initHeader: initHeader,
        initRow: initRow,
        setRowHeight: setRowHeight,
        switchColorsMenu: switchColorsMenu,
        isMobile: isMobile,
        scrolling: false,
        menuHiding: false,
        menuOpened: false,
        menuMobileTriggerEvent: menuMobileTriggerEvent,
        mediaQuery: mediaQuery,
        initVideoComponent: initVideoComponent,
        hideMenu: hideMenu,
        wwidth: wwidth,
        wheight: wheight,
    };
    if (typeof define === 'function' && define.amd) {
        define(_UI);
    } else {
        window._UI = _UI;
    }
    _UI.adaptive = function() {
        var images = new Array(),
            getImages = document.querySelectorAll('.adaptive-async:not(.adaptive-fetching)');
        for (var i = 0; i < getImages.length; i++) {
            var imageObj = {},
                el = getImages[i];
            classie.addClass(el, 'adaptive-fetching');
            imageObj.unique = el.getAttribute('data-uniqueid');
            imageObj.url = el.getAttribute('data-guid');
            imageObj.path = el.getAttribute('data-path');
            imageObj.singlew = el.getAttribute('data-singlew');
            imageObj.singleh = el.getAttribute('data-singleh');
            imageObj.origwidth = el.getAttribute('data-width');
            imageObj.origheight = el.getAttribute('data-height');
            imageObj.crop = el.getAttribute('data-crop');
            imageObj.fixed = el.getAttribute('data-fixed') == undefined ? null : el.getAttribute('data-fixed');
            imageObj.screen = window.uncodeScreen;
            imageObj.images = window.uncodeImages;
            images.push(imageObj);
        }
        var jsonString = JSON.stringify(images);
        var data = new Array();
        data['action'] = 'get_adaptive_async';
        data['images'] = jsonString;
        data['cache'] = 'false';
    };
})(window);
'use strict';
(function(window, document) {
    'use strict';

    function Pathformer(element) {
        if (typeof element === 'undefined') {
            throw new Error('Pathformer [constructor]: "element" parameter is required');
        }
        if (element.constructor === String) {
            element = document.getElementById(element);
            if (!element) {
                throw new Error('Pathformer [constructor]: "element" parameter is not related to an existing ID');
            }
        }
        if (element.constructor instanceof window.SVGElement || /^svg$/i.test(element.nodeName)) {
            this.el = element;
        } else {
            throw new Error('Pathformer [constructor]: "element" parameter must be a string or a SVGelement');
        }
        this.scan(element);
    }
    Pathformer.prototype.TYPES = ['line', 'ellipse', 'circle', 'polygon', 'polyline', 'rect'];
    Pathformer.prototype.ATTR_WATCH = ['cx', 'cy', 'points', 'r', 'rx', 'ry', 'x', 'x1', 'x2', 'y', 'y1', 'y2'];
    Pathformer.prototype.scan = function(svg) {
        var fn, element, pathData, pathDom, elements = svg.querySelectorAll(this.TYPES.join(','));
        for (var i = 0; i < elements.length; i++) {
            element = elements[i];
            fn = this[element.tagName.toLowerCase() + 'ToPath'];
            pathData = fn(this.parseAttr(element.attributes));
            pathDom = this.pathMaker(element, pathData);
            element.parentNode.replaceChild(pathDom, element);
        }
    };
    Pathformer.prototype.lineToPath = function(element) {
        var newElement = {},
            x1 = element.x1 || 0,
            y1 = element.y1 || 0,
            x2 = element.x2 || 0,
            y2 = element.y2 || 0;
        newElement.d = 'M' + x1 + ',' + y1 + 'L' + x2 + ',' + y2;
        return newElement;
    };
    Pathformer.prototype.rectToPath = function(element) {
        var newElement = {},
            x = parseFloat(element.x) || 0,
            y = parseFloat(element.y) || 0,
            width = parseFloat(element.width) || 0,
            height = parseFloat(element.height) || 0;
        newElement.d = 'M' + x + ' ' + y + ' ';
        newElement.d += 'L' + (x + width) + ' ' + y + ' ';
        newElement.d += 'L' + (x + width) + ' ' + (y + height) + ' ';
        newElement.d += 'L' + x + ' ' + (y + height) + ' Z';
        return newElement;
    };
    Pathformer.prototype.polylineToPath = function(element) {
        var newElement = {},
            points = element.points.trim().split(' '),
            i, path;
        if (element.points.indexOf(',') === -1) {
            var formattedPoints = [];
            for (i = 0; i < points.length; i += 2) {
                formattedPoints.push(points[i] + ',' + points[i + 1]);
            }
            points = formattedPoints;
        }
        path = 'M' + points[0];
        for (i = 1; i < points.length; i++) {
            if (points[i].indexOf(',') !== -1) {
                path += 'L' + points[i];
            }
        }
        newElement.d = path;
        return newElement;
    };
    Pathformer.prototype.polygonToPath = function(element) {
        var newElement = Pathformer.prototype.polylineToPath(element);
        newElement.d += 'Z';
        return newElement;
    };
    Pathformer.prototype.ellipseToPath = function(element) {
        var newElement = {},
            rx = parseFloat(element.rx) || 0,
            ry = parseFloat(element.ry) || 0,
            cx = parseFloat(element.cx) || 0,
            cy = parseFloat(element.cy) || 0,
            startX = cx - rx,
            startY = cy,
            endX = parseFloat(cx) + parseFloat(rx),
            endY = cy;
        newElement.d = 'M' + startX + ',' + startY +
            'A' + rx + ',' + ry + ' 0,1,1 ' + endX + ',' + endY +
            'A' + rx + ',' + ry + ' 0,1,1 ' + startX + ',' + endY;
        return newElement;
    };
    Pathformer.prototype.circleToPath = function(element) {
        var newElement = {},
            r = parseFloat(element.r) || 0,
            cx = parseFloat(element.cx) || 0,
            cy = parseFloat(element.cy) || 0,
            startX = cx - r,
            startY = cy,
            endX = parseFloat(cx) + parseFloat(r),
            endY = cy;
        newElement.d = 'M' + startX + ',' + startY +
            'A' + r + ',' + r + ' 0,1,1 ' + endX + ',' + endY +
            'A' + r + ',' + r + ' 0,1,1 ' + startX + ',' + endY;
        return newElement;
    };
    Pathformer.prototype.pathMaker = function(element, pathData) {
        var i, attr, pathTag = document.createElementNS('http://www.w3.org/2000/svg', 'path');
        for (i = 0; i < element.attributes.length; i++) {
            attr = element.attributes[i];
            if (this.ATTR_WATCH.indexOf(attr.name) === -1) {
                pathTag.setAttribute(attr.name, attr.value);
            }
        }
        for (i in pathData) {
            pathTag.setAttribute(i, pathData[i]);
        }
        return pathTag;
    };
    Pathformer.prototype.parseAttr = function(element) {
        var attr, output = {};
        for (var i = 0; i < element.length; i++) {
            attr = element[i];
            if (this.ATTR_WATCH.indexOf(attr.name) !== -1 && attr.value.indexOf('%') !== -1) {
                throw new Error('Pathformer [parseAttr]: a SVG shape got values in percentage. This cannot be transformed into \'path\' tags. Please use \'viewBox\'.');
            }
            output[attr.name] = attr.value;
        }
        return output;
    };
    'use strict';
    var requestAnimFrame, cancelAnimFrame, parsePositiveInt;

    function Vivus(element, options, callback) {
        this.isReady = false;
        this.setElement(element, options);
        this.setOptions(options);
        this.setCallback(callback);
        if (this.isReady) {
            this.init();
        }
    }
    Vivus.LINEAR = function(x) {
        return x;
    };
    Vivus.EASE = function(x) {
        return -Math.cos(x * Math.PI) / 2 + 0.5;
    };
    Vivus.EASE_OUT = function(x) {
        return 1 - Math.pow(1 - x, 3);
    };
    Vivus.EASE_IN = function(x) {
        return Math.pow(x, 3);
    };
    Vivus.EASE_OUT_BOUNCE = function(x) {
        var base = -Math.cos(x * (0.5 * Math.PI)) + 1,
            rate = Math.pow(base, 1.5),
            rateR = Math.pow(1 - x, 2),
            progress = -Math.abs(Math.cos(rate * (2.5 * Math.PI))) + 1;
        return (1 - rateR) + (progress * rateR);
    };
    Vivus.prototype.setElement = function(element, options) {
        if (typeof element === 'undefined') {
            throw new Error('Vivus [constructor]: "element" parameter is required');
        }
        if (element.constructor === String) {
            element = document.getElementById(element);
            if (!element) {
                throw new Error('Vivus [constructor]: "element" parameter is not related to an existing ID');
            }
        }
        this.parentEl = element;
        if (options && options.file) {
            var objElm = document.createElement('object');
            objElm.setAttribute('type', 'image/svg+xml');
            objElm.setAttribute('data', options.file);
            objElm.setAttribute('built-by-vivus', 'true');
            element.appendChild(objElm);
            element = objElm;
        }
        switch (element.constructor) {
            case window.SVGSVGElement:
            case window.SVGElement:
                this.el = element;
                this.isReady = true;
                break;
            case window.HTMLObjectElement:
                var onLoad, self;
                self = this;
                onLoad = function(e) {
                    if (self.isReady) {
                        return;
                    }
                    self.el = element.contentDocument && element.contentDocument.querySelector('svg');
                    if (!self.el && e) {
                        throw new Error('Vivus [constructor]: object loaded does not contain any SVG');
                    } else if (self.el) {
                        if (element.getAttribute('built-by-vivus')) {
                            self.parentEl.insertBefore(self.el, element);
                            self.parentEl.removeChild(element);
                            self.el.setAttribute('width', '100%');
                            self.el.setAttribute('height', '100%');
                        }
                        self.isReady = true;
                        self.init();
                        return true;
                    }
                };
                if (!onLoad()) {
                    element.addEventListener('load', onLoad);
                }
                break;
            default:
                throw new Error('Vivus [constructor]: "element" parameter is not valid (or miss the "file" attribute)');
        }
    };
    Vivus.prototype.setOptions = function(options) {
        var allowedTypes = ['delayed', 'sync', 'async', 'nsync', 'oneByOne', 'scenario', 'scenario-sync'];
        var allowedStarts = ['inViewport', 'manual', 'autostart'];
        if (options !== undefined && options.constructor !== Object) {
            throw new Error('Vivus [constructor]: "options" parameter must be an object');
        } else {
            options = options || {};
        }
        if (options.type && allowedTypes.indexOf(options.type) === -1) {
            throw new Error('Vivus [constructor]: ' + options.type + ' is not an existing animation `type`');
        } else {
            this.type = options.type || allowedTypes[0];
        }
        if (options.start && allowedStarts.indexOf(options.start) === -1) {
            throw new Error('Vivus [constructor]: ' + options.start + ' is not an existing `start` option');
        } else {
            this.start = options.start || allowedStarts[0];
        }
        this.isIE = (window.navigator.userAgent.indexOf('MSIE') !== -1 || window.navigator.userAgent.indexOf('Trident/') !== -1 || window.navigator.userAgent.indexOf('Edge/') !== -1);
        this.duration = parsePositiveInt(options.duration, 120);
        this.delay = parsePositiveInt(options.delay, null);
        this.delayStart = parsePositiveInt(options.delayStart, null);
        this.dashGap = parsePositiveInt(options.dashGap, 1);
        this.forceRender = options.hasOwnProperty('forceRender') ? !!options.forceRender : this.isIE;
        this.reverseStack = !!options.reverseStack;
        this.selfDestroy = !!options.selfDestroy;
        this.onReady = options.onReady;
        this.map = [];
        this.frameLength = this.currentFrame = this.delayUnit = this.speed = this.handle = null;
        this.ignoreInvisible = options.hasOwnProperty('ignoreInvisible') ? !!options.ignoreInvisible : false;
        this.animTimingFunction = options.animTimingFunction || Vivus.LINEAR;
        this.pathTimingFunction = options.pathTimingFunction || Vivus.LINEAR;
        if (this.delay >= this.duration) {
            throw new Error('Vivus [constructor]: delay must be shorter than duration');
        }
    };
    Vivus.prototype.setCallback = function(callback) {
        if (!!callback && callback.constructor !== Function) {
            throw new Error('Vivus [constructor]: "callback" parameter must be a function');
        }
        this.callback = callback || function() {};
    };
    Vivus.prototype.mapping = function() {
        var i, paths, path, pAttrs, pathObj, totalLength, lengthMeter, timePoint;
        timePoint = totalLength = lengthMeter = 0;
        paths = this.el.querySelectorAll('path');
        for (i = 0; i < paths.length; i++) {
            path = paths[i];
            if (this.isInvisible(path)) {
                continue;
            }
            pathObj = {
                el: path,
                length: Math.ceil(path.getTotalLength())
            };
            if (isNaN(pathObj.length)) {
                if (window.console && console.warn) {
                    console.warn('Vivus [mapping]: cannot retrieve a path element length', path);
                }
                continue;
            }
            this.map.push(pathObj);
            path.style.strokeDasharray = pathObj.length + ' ' + (pathObj.length + this.dashGap * 2);
            path.style.strokeDashoffset = pathObj.length + this.dashGap;
            pathObj.length += this.dashGap;
            totalLength += pathObj.length;
            this.renderPath(i);
        }
        totalLength = totalLength === 0 ? 1 : totalLength;
        this.delay = this.delay === null ? this.duration / 3 : this.delay;
        this.delayUnit = this.delay / (paths.length > 1 ? paths.length - 1 : 1);
        if (this.reverseStack) {
            this.map.reverse();
        }
        for (i = 0; i < this.map.length; i++) {
            pathObj = this.map[i];
            switch (this.type) {
                case 'delayed':
                    pathObj.startAt = this.delayUnit * i;
                    pathObj.duration = this.duration - this.delay;
                    break;
                case 'oneByOne':
                    pathObj.startAt = lengthMeter / totalLength * this.duration;
                    pathObj.duration = pathObj.length / totalLength * this.duration;
                    break;
                case 'sync':
                case 'async':
                case 'nsync':
                    pathObj.startAt = 0;
                    pathObj.duration = this.duration;
                    break;
                case 'scenario-sync':
                    path = pathObj.el;
                    pAttrs = this.parseAttr(path);
                    pathObj.startAt = timePoint + (parsePositiveInt(pAttrs['data-delay'], this.delayUnit) || 0);
                    pathObj.duration = parsePositiveInt(pAttrs['data-duration'], this.duration);
                    timePoint = pAttrs['data-async'] !== undefined ? pathObj.startAt : pathObj.startAt + pathObj.duration;
                    this.frameLength = Math.max(this.frameLength, (pathObj.startAt + pathObj.duration));
                    break;
                case 'scenario':
                    path = pathObj.el;
                    pAttrs = this.parseAttr(path);
                    pathObj.startAt = parsePositiveInt(pAttrs['data-start'], this.delayUnit) || 0;
                    pathObj.duration = parsePositiveInt(pAttrs['data-duration'], this.duration);
                    this.frameLength = Math.max(this.frameLength, (pathObj.startAt + pathObj.duration));
                    break;
            }
            lengthMeter += pathObj.length;
            this.frameLength = this.frameLength || this.duration;
        }
    };
    Vivus.prototype.drawer = function() {
        var self = this;
        this.currentFrame += this.speed;
        if (this.currentFrame <= 0) {
            this.stop();
            this.reset();
        } else if (this.currentFrame >= this.frameLength) {
            this.stop();
            this.currentFrame = this.frameLength;
            this.trace();
            if (this.selfDestroy) {
                this.destroy();
            }
        } else {
            this.trace();
            this.handle = requestAnimFrame(function() {
                self.drawer();
            });
            return;
        }
        this.callback(this);
        if (this.instanceCallback) {
            this.instanceCallback(this);
            this.instanceCallback = null;
        }
    };
    Vivus.prototype.trace = function() {
        var i, progress, path, currentFrame;
        currentFrame = this.animTimingFunction(this.currentFrame / this.frameLength) * this.frameLength;
        for (i = 0; i < this.map.length; i++) {
            path = this.map[i];
            progress = (currentFrame - path.startAt) / path.duration;
            progress = this.pathTimingFunction(Math.max(0, Math.min(1, progress)));
            if (path.progress !== progress) {
                path.progress = progress;
                path.el.style.strokeDashoffset = Math.floor(path.length * (1 - progress));
                this.renderPath(i);
            }
        }
    };
    Vivus.prototype.renderPath = function(index) {
        if (this.forceRender && this.map && this.map[index]) {
            var pathObj = this.map[index],
                newPath = pathObj.el.cloneNode(true);
            pathObj.el.parentNode.replaceChild(newPath, pathObj.el);
            pathObj.el = newPath;
        }
    };
    Vivus.prototype.init = function() {
        this.frameLength = 0;
        this.currentFrame = 0;
        this.map = [];
        new Pathformer(this.el);
        this.mapping();
        this.starter();
        if (this.onReady) {
            this.onReady(this);
        }
    };
    Vivus.prototype.starter = function() {
        switch (this.start) {
            case 'manual':
                return;
            case 'autostart':
                this.play();
                break;
            case 'inViewport':
                var self = this,
                    listener = function() {
                        if (self.isInViewport(self.parentEl, 1)) {
                            self.play();
                            window.removeEventListener('scroll', listener);
                            window.removeEventListener('fp-slide-changed', listener);
                            window.removeEventListener('fp-slide-scroll', listener);
                        }
                    };
                window.addEventListener('scroll', listener);
                window.addEventListener('fp-slide-changed', listener);
                window.addEventListener('fp-slide-scroll', listener);
                listener();
                break;
        }
    };
    Vivus.prototype.getStatus = function() {
        return this.currentFrame === 0 ? 'start' : this.currentFrame === this.frameLength ? 'end' : 'progress';
    };
    Vivus.prototype.reset = function() {
        return this.setFrameProgress(0);
    };
    Vivus.prototype.finish = function() {
        return this.setFrameProgress(1);
    };
    Vivus.prototype.setFrameProgress = function(progress) {
        progress = Math.min(1, Math.max(0, progress));
        this.currentFrame = Math.round(this.frameLength * progress);
        this.trace();
        return this;
    };
    Vivus.prototype.play = function(speed, callback) {
        this.instanceCallback = null;
        if (speed && typeof speed === 'function') {
            this.instanceCallback = speed;
            speed = null;
        } else if (speed && typeof speed !== 'number') {
            throw new Error('Vivus [play]: invalid speed');
        }
        if (callback && typeof(callback) === 'function' && !this.instanceCallback) {
            this.instanceCallback = callback;
        }
        this.speed = speed || 1;
        if (!this.handle) {
            var $this = this,
                delay = (this.delayStart != null) ? this.delayStart : 0;
            setTimeout(function() {
                $this.drawer();
            }, delay);
        }
        return this;
    };
    Vivus.prototype.stop = function() {
        if (this.handle) {
            cancelAnimFrame(this.handle);
            this.handle = null;
        }
        return this;
    };
    Vivus.prototype.destroy = function() {
        this.stop();
        var i, path;
        for (i = 0; i < this.map.length; i++) {
            path = this.map[i];
            path.el.style.strokeDashoffset = null;
            path.el.style.strokeDasharray = null;
            this.renderPath(i);
        }
    };
    Vivus.prototype.isInvisible = function(el) {
        var rect, ignoreAttr = el.getAttribute('data-ignore');
        if (ignoreAttr !== null) {
            return ignoreAttr !== 'false';
        }
        if (this.ignoreInvisible) {
            rect = el.getBoundingClientRect();
            return !rect.width && !rect.height;
        } else {
            return false;
        }
    };
    Vivus.prototype.parseAttr = function(element) {
        var attr, output = {};
        if (element && element.attributes) {
            for (var i = 0; i < element.attributes.length; i++) {
                attr = element.attributes[i];
                output[attr.name] = attr.value;
            }
        }
        return output;
    };
    Vivus.prototype.isInViewport = function(el, h) {
        var scrolled = this.scrollY(),
            viewed = scrolled + this.getViewportH(),
            elBCR = el.getBoundingClientRect(),
            elHeight = elBCR.height,
            elTop = scrolled + elBCR.top,
            elBottom = elTop + elHeight;
        h = h || 0;
        return (elTop + elHeight * h) <= viewed && (elBottom) >= scrolled;
    };
    Vivus.prototype.docElem = window.document.documentElement;
    Vivus.prototype.getViewportH = function() {
        var client = this.docElem.clientHeight,
            inner = window.innerHeight;
        if (client < inner) {
            return inner;
        } else {
            return client;
        }
    };
    Vivus.prototype.scrollY = function() {
        return window.pageYOffset || this.docElem.scrollTop;
    };
    requestAnimFrame = (function() {
        return (window.requestAnimationFrame || window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame || window.oRequestAnimationFrame || window.msRequestAnimationFrame || function(callback) {
            return window.setTimeout(callback, 1000 / 60);
        });
    })();
    cancelAnimFrame = (function() {
        return (window.cancelAnimationFrame || window.webkitCancelAnimationFrame || window.mozCancelAnimationFrame || window.oCancelAnimationFrame || window.msCancelAnimationFrame || function(id) {
            return window.clearTimeout(id);
        });
    })();
    parsePositiveInt = function(value, defaultValue) {
        var output = parseInt(value, 10);
        return (output >= 0) ? output : defaultValue;
    };
    if (typeof define === 'function' && define.amd) {
        define([], function() {
            return Vivus;
        });
    } else if (typeof exports === 'object') {
        module.exports = Vivus;
    } else {
        window.Vivus = Vivus;
    }
}(window, document));