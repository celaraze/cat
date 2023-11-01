function le(o, t) {
    var e = Object.keys(o);
    if (Object.getOwnPropertySymbols) {
        var n = Object.getOwnPropertySymbols(o);
        t && (n = n.filter(function (i) {
            return Object.getOwnPropertyDescriptor(o, i).enumerable
        })), e.push.apply(e, n)
    }
    return e
}

function L(o) {
    for (var t = 1; t < arguments.length; t++) {
        var e = arguments[t] != null ? arguments[t] : {};
        t % 2 ? le(Object(e), !0).forEach(function (n) {
            Pe(o, n, e[n])
        }) : Object.getOwnPropertyDescriptors ? Object.defineProperties(o, Object.getOwnPropertyDescriptors(e)) : le(Object(e)).forEach(function (n) {
            Object.defineProperty(o, n, Object.getOwnPropertyDescriptor(e, n))
        })
    }
    return o
}

function Mt(o) {
    "@babel/helpers - typeof";
    return typeof Symbol == "function" && typeof Symbol.iterator == "symbol" ? Mt = function (t) {
        return typeof t
    } : Mt = function (t) {
        return t && typeof Symbol == "function" && t.constructor === Symbol && t !== Symbol.prototype ? "symbol" : typeof t
    }, Mt(o)
}

function Pe(o, t, e) {
    return t in o ? Object.defineProperty(o, t, {
        value: e,
        enumerable: !0,
        configurable: !0,
        writable: !0
    }) : o[t] = e, o
}

function $() {
    return $ = Object.assign || function (o) {
        for (var t = 1; t < arguments.length; t++) {
            var e = arguments[t];
            for (var n in e) Object.prototype.hasOwnProperty.call(e, n) && (o[n] = e[n])
        }
        return o
    }, $.apply(this, arguments)
}

function Me(o, t) {
    if (o == null) return {};
    var e = {}, n = Object.keys(o), i, r;
    for (r = 0; r < n.length; r++) i = n[r], !(t.indexOf(i) >= 0) && (e[i] = o[i]);
    return e
}

function Fe(o, t) {
    if (o == null) return {};
    var e = Me(o, t), n, i;
    if (Object.getOwnPropertySymbols) {
        var r = Object.getOwnPropertySymbols(o);
        for (i = 0; i < r.length; i++) n = r[i], !(t.indexOf(n) >= 0) && Object.prototype.propertyIsEnumerable.call(o, n) && (e[n] = o[n])
    }
    return e
}

var Re = "1.15.0";

function U(o) {
    if (typeof window < "u" && window.navigator) return !!navigator.userAgent.match(o)
}

var q = U(/(?:Trident.*rv[ :]?11\.|msie|iemobile|Windows Phone)/i), Ot = U(/Edge/i), se = U(/firefox/i),
    Et = U(/safari/i) && !U(/chrome/i) && !U(/android/i), me = U(/iP(ad|od|hone)/i), ve = U(/chrome/i) && U(/android/i),
    be = {capture: !1, passive: !1};

function w(o, t, e) {
    o.addEventListener(t, e, !q && be)
}

function b(o, t, e) {
    o.removeEventListener(t, e, !q && be)
}

function Yt(o, t) {
    if (t) {
        if (t[0] === ">" && (t = t.substring(1)), o) try {
            if (o.matches) return o.matches(t);
            if (o.msMatchesSelector) return o.msMatchesSelector(t);
            if (o.webkitMatchesSelector) return o.webkitMatchesSelector(t)
        } catch {
            return !1
        }
        return !1
    }
}

function ke(o) {
    return o.host && o !== document && o.host.nodeType ? o.host : o.parentNode
}

function W(o, t, e, n) {
    if (o) {
        e = e || document;
        do {
            if (t != null && (t[0] === ">" ? o.parentNode === e && Yt(o, t) : Yt(o, t)) || n && o === e) return o;
            if (o === e) break
        } while (o = ke(o))
    }
    return null
}

var ue = /\s+/g;

function F(o, t, e) {
    if (o && t) if (o.classList) o.classList[e ? "add" : "remove"](t); else {
        var n = (" " + o.className + " ").replace(ue, " ").replace(" " + t + " ", " ");
        o.className = (n + (e ? " " + t : "")).replace(ue, " ")
    }
}

function h(o, t, e) {
    var n = o && o.style;
    if (n) {
        if (e === void 0) return document.defaultView && document.defaultView.getComputedStyle ? e = document.defaultView.getComputedStyle(o, "") : o.currentStyle && (e = o.currentStyle), t === void 0 ? e : e[t];
        !(t in n) && t.indexOf("webkit") === -1 && (t = "-webkit-" + t), n[t] = e + (typeof e == "string" ? "" : "px")
    }
}

function ft(o, t) {
    var e = "";
    if (typeof o == "string") e = o; else do {
        var n = h(o, "transform");
        n && n !== "none" && (e = n + " " + e)
    } while (!t && (o = o.parentNode));
    var i = window.DOMMatrix || window.WebKitCSSMatrix || window.CSSMatrix || window.MSCSSMatrix;
    return i && new i(e)
}

function we(o, t, e) {
    if (o) {
        var n = o.getElementsByTagName(t), i = 0, r = n.length;
        if (e) for (; i < r; i++) e(n[i], i);
        return n
    }
    return []
}

function K() {
    var o = document.scrollingElement;
    return o || document.documentElement
}

function O(o, t, e, n, i) {
    if (!(!o.getBoundingClientRect && o !== window)) {
        var r, a, l, s, u, d, f;
        if (o !== window && o.parentNode && o !== K() ? (r = o.getBoundingClientRect(), a = r.top, l = r.left, s = r.bottom, u = r.right, d = r.height, f = r.width) : (a = 0, l = 0, s = window.innerHeight, u = window.innerWidth, d = window.innerHeight, f = window.innerWidth), (t || e) && o !== window && (i = i || o.parentNode, !q)) do if (i && i.getBoundingClientRect && (h(i, "transform") !== "none" || e && h(i, "position") !== "static")) {
            var m = i.getBoundingClientRect();
            a -= m.top + parseInt(h(i, "border-top-width")), l -= m.left + parseInt(h(i, "border-left-width")), s = a + r.height, u = l + r.width;
            break
        } while (i = i.parentNode);
        if (n && o !== window) {
            var E = ft(i || o), v = E && E.a, y = E && E.d;
            E && (a /= y, l /= v, f /= v, d /= y, s = a + d, u = l + f)
        }
        return {top: a, left: l, bottom: s, right: u, width: f, height: d}
    }
}

function ce(o, t, e) {
    for (var n = tt(o, !0), i = O(o)[t]; n;) {
        var r = O(n)[e], a = void 0;
        if (e === "top" || e === "left" ? a = i >= r : a = i <= r, !a) return n;
        if (n === K()) break;
        n = tt(n, !1)
    }
    return !1
}

function dt(o, t, e, n) {
    for (var i = 0, r = 0, a = o.children; r < a.length;) {
        if (a[r].style.display !== "none" && a[r] !== p.ghost && (n || a[r] !== p.dragged) && W(a[r], e.draggable, o, !1)) {
            if (i === t) return a[r];
            i++
        }
        r++
    }
    return null
}

function oe(o, t) {
    for (var e = o.lastElementChild; e && (e === p.ghost || h(e, "display") === "none" || t && !Yt(e, t));) e = e.previousElementSibling;
    return e || null
}

function X(o, t) {
    var e = 0;
    if (!o || !o.parentNode) return -1;
    for (; o = o.previousElementSibling;) o.nodeName.toUpperCase() !== "TEMPLATE" && o !== p.clone && (!t || Yt(o, t)) && e++;
    return e
}

function fe(o) {
    var t = 0, e = 0, n = K();
    if (o) do {
        var i = ft(o), r = i.a, a = i.d;
        t += o.scrollLeft * r, e += o.scrollTop * a
    } while (o !== n && (o = o.parentNode));
    return [t, e]
}

function Xe(o, t) {
    for (var e in o) if (o.hasOwnProperty(e)) {
        for (var n in t) if (t.hasOwnProperty(n) && t[n] === o[e][n]) return Number(e)
    }
    return -1
}

function tt(o, t) {
    if (!o || !o.getBoundingClientRect) return K();
    var e = o, n = !1;
    do if (e.clientWidth < e.scrollWidth || e.clientHeight < e.scrollHeight) {
        var i = h(e);
        if (e.clientWidth < e.scrollWidth && (i.overflowX == "auto" || i.overflowX == "scroll") || e.clientHeight < e.scrollHeight && (i.overflowY == "auto" || i.overflowY == "scroll")) {
            if (!e.getBoundingClientRect || e === document.body) return K();
            if (n || t) return e;
            n = !0
        }
    } while (e = e.parentNode);
    return K()
}

function Ye(o, t) {
    if (o && t) for (var e in t) t.hasOwnProperty(e) && (o[e] = t[e]);
    return o
}

function Lt(o, t) {
    return Math.round(o.top) === Math.round(t.top) && Math.round(o.left) === Math.round(t.left) && Math.round(o.height) === Math.round(t.height) && Math.round(o.width) === Math.round(t.width)
}

var Dt;

function ye(o, t) {
    return function () {
        if (!Dt) {
            var e = arguments, n = this;
            e.length === 1 ? o.call(n, e[0]) : o.apply(n, e), Dt = setTimeout(function () {
                Dt = void 0
            }, t)
        }
    }
}

function Be() {
    clearTimeout(Dt), Dt = void 0
}

function Ee(o, t, e) {
    o.scrollLeft += t, o.scrollTop += e
}

function De(o) {
    var t = window.Polymer, e = window.jQuery || window.Zepto;
    return t && t.dom ? t.dom(o).cloneNode(!0) : e ? e(o).clone(!0)[0] : o.cloneNode(!0)
}

var k = "Sortable" + new Date().getTime();

function He() {
    var o = [], t;
    return {
        captureAnimationState: function () {
            if (o = [], !!this.options.animation) {
                var n = [].slice.call(this.el.children);
                n.forEach(function (i) {
                    if (!(h(i, "display") === "none" || i === p.ghost)) {
                        o.push({target: i, rect: O(i)});
                        var r = L({}, o[o.length - 1].rect);
                        if (i.thisAnimationDuration) {
                            var a = ft(i, !0);
                            a && (r.top -= a.f, r.left -= a.e)
                        }
                        i.fromRect = r
                    }
                })
            }
        }, addAnimationState: function (n) {
            o.push(n)
        }, removeAnimationState: function (n) {
            o.splice(Xe(o, {target: n}), 1)
        }, animateAll: function (n) {
            var i = this;
            if (!this.options.animation) {
                clearTimeout(t), typeof n == "function" && n();
                return
            }
            var r = !1, a = 0;
            o.forEach(function (l) {
                var s = 0, u = l.target, d = u.fromRect, f = O(u), m = u.prevFromRect, E = u.prevToRect, v = l.rect,
                    y = ft(u, !0);
                y && (f.top -= y.f, f.left -= y.e), u.toRect = f, u.thisAnimationDuration && Lt(m, f) && !Lt(d, f) && (v.top - f.top) / (v.left - f.left) === (d.top - f.top) / (d.left - f.left) && (s = We(v, m, E, i.options)), Lt(f, d) || (u.prevFromRect = d, u.prevToRect = f, s || (s = i.options.animation), i.animate(u, v, f, s)), s && (r = !0, a = Math.max(a, s), clearTimeout(u.animationResetTimer), u.animationResetTimer = setTimeout(function () {
                    u.animationTime = 0, u.prevFromRect = null, u.fromRect = null, u.prevToRect = null, u.thisAnimationDuration = null
                }, s), u.thisAnimationDuration = s)
            }), clearTimeout(t), r ? t = setTimeout(function () {
                typeof n == "function" && n()
            }, a) : typeof n == "function" && n(), o = []
        }, animate: function (n, i, r, a) {
            if (a) {
                h(n, "transition", ""), h(n, "transform", "");
                var l = ft(this.el), s = l && l.a, u = l && l.d, d = (i.left - r.left) / (s || 1),
                    f = (i.top - r.top) / (u || 1);
                n.animatingX = !!d, n.animatingY = !!f, h(n, "transform", "translate3d(" + d + "px," + f + "px,0)"), this.forRepaintDummy = Ge(n), h(n, "transition", "transform " + a + "ms" + (this.options.easing ? " " + this.options.easing : "")), h(n, "transform", "translate3d(0,0,0)"), typeof n.animated == "number" && clearTimeout(n.animated), n.animated = setTimeout(function () {
                    h(n, "transition", ""), h(n, "transform", ""), n.animated = !1, n.animatingX = !1, n.animatingY = !1
                }, a)
            }
        }
    }
}

function Ge(o) {
    return o.offsetWidth
}

function We(o, t, e, n) {
    return Math.sqrt(Math.pow(t.top - o.top, 2) + Math.pow(t.left - o.left, 2)) / Math.sqrt(Math.pow(t.top - e.top, 2) + Math.pow(t.left - e.left, 2)) * n.animation
}

var lt = [], jt = {initializeByDefault: !0}, Tt = {
    mount: function (t) {
        for (var e in jt) jt.hasOwnProperty(e) && !(e in t) && (t[e] = jt[e]);
        lt.forEach(function (n) {
            if (n.pluginName === t.pluginName) throw "Sortable: Cannot mount plugin ".concat(t.pluginName, " more than once")
        }), lt.push(t)
    }, pluginEvent: function (t, e, n) {
        var i = this;
        this.eventCanceled = !1, n.cancel = function () {
            i.eventCanceled = !0
        };
        var r = t + "Global";
        lt.forEach(function (a) {
            e[a.pluginName] && (e[a.pluginName][r] && e[a.pluginName][r](L({sortable: e}, n)), e.options[a.pluginName] && e[a.pluginName][t] && e[a.pluginName][t](L({sortable: e}, n)))
        })
    }, initializePlugins: function (t, e, n, i) {
        lt.forEach(function (l) {
            var s = l.pluginName;
            if (!(!t.options[s] && !l.initializeByDefault)) {
                var u = new l(t, e, t.options);
                u.sortable = t, u.options = t.options, t[s] = u, $(n, u.defaults)
            }
        });
        for (var r in t.options) if (t.options.hasOwnProperty(r)) {
            var a = this.modifyOption(t, r, t.options[r]);
            typeof a < "u" && (t.options[r] = a)
        }
    }, getEventProperties: function (t, e) {
        var n = {};
        return lt.forEach(function (i) {
            typeof i.eventProperties == "function" && $(n, i.eventProperties.call(e[i.pluginName], t))
        }), n
    }, modifyOption: function (t, e, n) {
        var i;
        return lt.forEach(function (r) {
            t[r.pluginName] && r.optionListeners && typeof r.optionListeners[e] == "function" && (i = r.optionListeners[e].call(t[r.pluginName], n))
        }), i
    }
};

function Ke(o) {
    var t = o.sortable, e = o.rootEl, n = o.name, i = o.targetEl, r = o.cloneEl, a = o.toEl, l = o.fromEl,
        s = o.oldIndex, u = o.newIndex, d = o.oldDraggableIndex, f = o.newDraggableIndex, m = o.originalEvent,
        E = o.putSortable, v = o.extraEventProperties;
    if (t = t || e && e[k], !!t) {
        var y, Y = t.options, j = "on" + n.charAt(0).toUpperCase() + n.substr(1);
        window.CustomEvent && !q && !Ot ? y = new CustomEvent(n, {
            bubbles: !0,
            cancelable: !0
        }) : (y = document.createEvent("Event"), y.initEvent(n, !0, !0)), y.to = a || e, y.from = l || e, y.item = i || e, y.clone = r, y.oldIndex = s, y.newIndex = u, y.oldDraggableIndex = d, y.newDraggableIndex = f, y.originalEvent = m, y.pullMode = E ? E.lastPutMode : void 0;
        var I = L(L({}, v), Tt.getEventProperties(n, t));
        for (var B in I) y[B] = I[B];
        e && e.dispatchEvent(y), Y[j] && Y[j].call(t, y)
    }
}

var Le = ["evt"], x = function (t, e) {
    var n = arguments.length > 2 && arguments[2] !== void 0 ? arguments[2] : {}, i = n.evt, r = Fe(n, Le);
    Tt.pluginEvent.bind(p)(t, e, L({
        dragEl: c,
        parentEl: _,
        ghostEl: g,
        rootEl: D,
        nextEl: at,
        lastDownEl: Ft,
        cloneEl: S,
        cloneHidden: J,
        dragStarted: bt,
        putSortable: T,
        activeSortable: p.active,
        originalEvent: i,
        oldIndex: ct,
        oldDraggableIndex: St,
        newIndex: R,
        newDraggableIndex: Q,
        hideGhostForTarget: Oe,
        unhideGhostForTarget: Te,
        cloneNowHidden: function () {
            J = !0
        },
        cloneNowShown: function () {
            J = !1
        },
        dispatchSortableEvent: function (l) {
            N({sortable: e, name: l, originalEvent: i})
        }
    }, r))
};

function N(o) {
    Ke(L({
        putSortable: T,
        cloneEl: S,
        targetEl: c,
        rootEl: D,
        oldIndex: ct,
        oldDraggableIndex: St,
        newIndex: R,
        newDraggableIndex: Q
    }, o))
}

var c, _, g, D, at, Ft, S, J, ct, R, St, Q, It, T, ut = !1, Bt = !1, Ht = [], it, H, zt, Ut, de, he, bt, st, _t,
    Ct = !1, Nt = !1, Rt, A, $t = [], Jt = !1, Gt = [], Kt = typeof document < "u", xt = me,
    pe = Ot || q ? "cssFloat" : "float", je = Kt && !ve && !me && "draggable" in document.createElement("div"),
    Se = function () {
        if (Kt) {
            if (q) return !1;
            var o = document.createElement("x");
            return o.style.cssText = "pointer-events:auto", o.style.pointerEvents === "auto"
        }
    }(), _e = function (t, e) {
        var n = h(t),
            i = parseInt(n.width) - parseInt(n.paddingLeft) - parseInt(n.paddingRight) - parseInt(n.borderLeftWidth) - parseInt(n.borderRightWidth),
            r = dt(t, 0, e), a = dt(t, 1, e), l = r && h(r), s = a && h(a),
            u = l && parseInt(l.marginLeft) + parseInt(l.marginRight) + O(r).width,
            d = s && parseInt(s.marginLeft) + parseInt(s.marginRight) + O(a).width;
        if (n.display === "flex") return n.flexDirection === "column" || n.flexDirection === "column-reverse" ? "vertical" : "horizontal";
        if (n.display === "grid") return n.gridTemplateColumns.split(" ").length <= 1 ? "vertical" : "horizontal";
        if (r && l.float && l.float !== "none") {
            var f = l.float === "left" ? "left" : "right";
            return a && (s.clear === "both" || s.clear === f) ? "vertical" : "horizontal"
        }
        return r && (l.display === "block" || l.display === "flex" || l.display === "table" || l.display === "grid" || u >= i && n[pe] === "none" || a && n[pe] === "none" && u + d > i) ? "vertical" : "horizontal"
    }, ze = function (t, e, n) {
        var i = n ? t.left : t.top, r = n ? t.right : t.bottom, a = n ? t.width : t.height, l = n ? e.left : e.top,
            s = n ? e.right : e.bottom, u = n ? e.width : e.height;
        return i === l || r === s || i + a / 2 === l + u / 2
    }, Ue = function (t, e) {
        var n;
        return Ht.some(function (i) {
            var r = i[k].options.emptyInsertThreshold;
            if (!(!r || oe(i))) {
                var a = O(i), l = t >= a.left - r && t <= a.right + r, s = e >= a.top - r && e <= a.bottom + r;
                if (l && s) return n = i
            }
        }), n
    }, Ce = function (t) {
        function e(r, a) {
            return function (l, s, u, d) {
                var f = l.options.group.name && s.options.group.name && l.options.group.name === s.options.group.name;
                if (r == null && (a || f)) return !0;
                if (r == null || r === !1) return !1;
                if (a && r === "clone") return r;
                if (typeof r == "function") return e(r(l, s, u, d), a)(l, s, u, d);
                var m = (a ? l : s).options.group.name;
                return r === !0 || typeof r == "string" && r === m || r.join && r.indexOf(m) > -1
            }
        }

        var n = {}, i = t.group;
        (!i || Mt(i) != "object") && (i = {name: i}), n.name = i.name, n.checkPull = e(i.pull, !0), n.checkPut = e(i.put), n.revertClone = i.revertClone, t.group = n
    }, Oe = function () {
        !Se && g && h(g, "display", "none")
    }, Te = function () {
        !Se && g && h(g, "display", "")
    };
Kt && !ve && document.addEventListener("click", function (o) {
    if (Bt) return o.preventDefault(), o.stopPropagation && o.stopPropagation(), o.stopImmediatePropagation && o.stopImmediatePropagation(), Bt = !1, !1
}, !0);
var rt = function (t) {
    if (c) {
        t = t.touches ? t.touches[0] : t;
        var e = Ue(t.clientX, t.clientY);
        if (e) {
            var n = {};
            for (var i in t) t.hasOwnProperty(i) && (n[i] = t[i]);
            n.target = n.rootEl = e, n.preventDefault = void 0, n.stopPropagation = void 0, e[k]._onDragOver(n)
        }
    }
}, $e = function (t) {
    c && c.parentNode[k]._isOutsideThisEl(t.target)
};

function p(o, t) {
    if (!(o && o.nodeType && o.nodeType === 1)) throw "Sortable: `el` must be an HTMLElement, not ".concat({}.toString.call(o));
    this.el = o, this.options = t = $({}, t), o[k] = this;
    var e = {
        group: null,
        sort: !0,
        disabled: !1,
        store: null,
        handle: null,
        draggable: /^[uo]l$/i.test(o.nodeName) ? ">li" : ">*",
        swapThreshold: 1,
        invertSwap: !1,
        invertedSwapThreshold: null,
        removeCloneOnHide: !0,
        direction: function () {
            return _e(o, this.options)
        },
        ghostClass: "sortable-ghost",
        chosenClass: "sortable-chosen",
        dragClass: "sortable-drag",
        ignore: "a, img",
        filter: null,
        preventOnFilter: !0,
        animation: 0,
        easing: null,
        setData: function (a, l) {
            a.setData("Text", l.textContent)
        },
        dropBubble: !1,
        dragoverBubble: !1,
        dataIdAttr: "data-id",
        delay: 0,
        delayOnTouchOnly: !1,
        touchStartThreshold: (Number.parseInt ? Number : window).parseInt(window.devicePixelRatio, 10) || 1,
        forceFallback: !1,
        fallbackClass: "sortable-fallback",
        fallbackOnBody: !1,
        fallbackTolerance: 0,
        fallbackOffset: {x: 0, y: 0},
        supportPointer: p.supportPointer !== !1 && "PointerEvent" in window && !Et,
        emptyInsertThreshold: 5
    };
    Tt.initializePlugins(this, o, e);
    for (var n in e) !(n in t) && (t[n] = e[n]);
    Ce(t);
    for (var i in this) i.charAt(0) === "_" && typeof this[i] == "function" && (this[i] = this[i].bind(this));
    this.nativeDraggable = t.forceFallback ? !1 : je, this.nativeDraggable && (this.options.touchStartThreshold = 1), t.supportPointer ? w(o, "pointerdown", this._onTapStart) : (w(o, "mousedown", this._onTapStart), w(o, "touchstart", this._onTapStart)), this.nativeDraggable && (w(o, "dragover", this), w(o, "dragenter", this)), Ht.push(this.el), t.store && t.store.get && this.sort(t.store.get(this) || []), $(this, He())
}

p.prototype = {
    constructor: p, _isOutsideThisEl: function (t) {
        !this.el.contains(t) && t !== this.el && (st = null)
    }, _getDirection: function (t, e) {
        return typeof this.options.direction == "function" ? this.options.direction.call(this, t, e, c) : this.options.direction
    }, _onTapStart: function (t) {
        if (t.cancelable) {
            var e = this, n = this.el, i = this.options, r = i.preventOnFilter, a = t.type,
                l = t.touches && t.touches[0] || t.pointerType && t.pointerType === "touch" && t, s = (l || t).target,
                u = t.target.shadowRoot && (t.path && t.path[0] || t.composedPath && t.composedPath()[0]) || s,
                d = i.filter;
            if (nn(n), !c && !(/mousedown|pointerdown/.test(a) && t.button !== 0 || i.disabled) && !u.isContentEditable && !(!this.nativeDraggable && Et && s && s.tagName.toUpperCase() === "SELECT") && (s = W(s, i.draggable, n, !1), !(s && s.animated) && Ft !== s)) {
                if (ct = X(s), St = X(s, i.draggable), typeof d == "function") {
                    if (d.call(this, t, s, this)) {
                        N({
                            sortable: e,
                            rootEl: u,
                            name: "filter",
                            targetEl: s,
                            toEl: n,
                            fromEl: n
                        }), x("filter", e, {evt: t}), r && t.cancelable && t.preventDefault();
                        return
                    }
                } else if (d && (d = d.split(",").some(function (f) {
                    if (f = W(u, f.trim(), n, !1), f) return N({
                        sortable: e,
                        rootEl: f,
                        name: "filter",
                        targetEl: s,
                        fromEl: n,
                        toEl: n
                    }), x("filter", e, {evt: t}), !0
                }), d)) {
                    r && t.cancelable && t.preventDefault();
                    return
                }
                i.handle && !W(u, i.handle, n, !1) || this._prepareDragStart(t, l, s)
            }
        }
    }, _prepareDragStart: function (t, e, n) {
        var i = this, r = i.el, a = i.options, l = r.ownerDocument, s;
        if (n && !c && n.parentNode === r) {
            var u = O(n);
            if (D = r, c = n, _ = c.parentNode, at = c.nextSibling, Ft = n, It = a.group, p.dragged = c, it = {
                target: c,
                clientX: (e || t).clientX,
                clientY: (e || t).clientY
            }, de = it.clientX - u.left, he = it.clientY - u.top, this._lastX = (e || t).clientX, this._lastY = (e || t).clientY, c.style["will-change"] = "all", s = function () {
                if (x("delayEnded", i, {evt: t}), p.eventCanceled) {
                    i._onDrop();
                    return
                }
                i._disableDelayedDragEvents(), !se && i.nativeDraggable && (c.draggable = !0), i._triggerDragStart(t, e), N({
                    sortable: i,
                    name: "choose",
                    originalEvent: t
                }), F(c, a.chosenClass, !0)
            }, a.ignore.split(",").forEach(function (d) {
                we(c, d.trim(), qt)
            }), w(l, "dragover", rt), w(l, "mousemove", rt), w(l, "touchmove", rt), w(l, "mouseup", i._onDrop), w(l, "touchend", i._onDrop), w(l, "touchcancel", i._onDrop), se && this.nativeDraggable && (this.options.touchStartThreshold = 4, c.draggable = !0), x("delayStart", this, {evt: t}), a.delay && (!a.delayOnTouchOnly || e) && (!this.nativeDraggable || !(Ot || q))) {
                if (p.eventCanceled) {
                    this._onDrop();
                    return
                }
                w(l, "mouseup", i._disableDelayedDrag), w(l, "touchend", i._disableDelayedDrag), w(l, "touchcancel", i._disableDelayedDrag), w(l, "mousemove", i._delayedDragTouchMoveHandler), w(l, "touchmove", i._delayedDragTouchMoveHandler), a.supportPointer && w(l, "pointermove", i._delayedDragTouchMoveHandler), i._dragStartTimer = setTimeout(s, a.delay)
            } else s()
        }
    }, _delayedDragTouchMoveHandler: function (t) {
        var e = t.touches ? t.touches[0] : t;
        Math.max(Math.abs(e.clientX - this._lastX), Math.abs(e.clientY - this._lastY)) >= Math.floor(this.options.touchStartThreshold / (this.nativeDraggable && window.devicePixelRatio || 1)) && this._disableDelayedDrag()
    }, _disableDelayedDrag: function () {
        c && qt(c), clearTimeout(this._dragStartTimer), this._disableDelayedDragEvents()
    }, _disableDelayedDragEvents: function () {
        var t = this.el.ownerDocument;
        b(t, "mouseup", this._disableDelayedDrag), b(t, "touchend", this._disableDelayedDrag), b(t, "touchcancel", this._disableDelayedDrag), b(t, "mousemove", this._delayedDragTouchMoveHandler), b(t, "touchmove", this._delayedDragTouchMoveHandler), b(t, "pointermove", this._delayedDragTouchMoveHandler)
    }, _triggerDragStart: function (t, e) {
        e = e || t.pointerType == "touch" && t, !this.nativeDraggable || e ? this.options.supportPointer ? w(document, "pointermove", this._onTouchMove) : e ? w(document, "touchmove", this._onTouchMove) : w(document, "mousemove", this._onTouchMove) : (w(c, "dragend", this), w(D, "dragstart", this._onDragStart));
        try {
            document.selection ? kt(function () {
                document.selection.empty()
            }) : window.getSelection().removeAllRanges()
        } catch {
        }
    }, _dragStarted: function (t, e) {
        if (ut = !1, D && c) {
            x("dragStarted", this, {evt: e}), this.nativeDraggable && w(document, "dragover", $e);
            var n = this.options;
            !t && F(c, n.dragClass, !1), F(c, n.ghostClass, !0), p.active = this, t && this._appendGhost(), N({
                sortable: this,
                name: "start",
                originalEvent: e
            })
        } else this._nulling()
    }, _emulateDragOver: function () {
        if (H) {
            this._lastX = H.clientX, this._lastY = H.clientY, Oe();
            for (var t = document.elementFromPoint(H.clientX, H.clientY), e = t; t && t.shadowRoot && (t = t.shadowRoot.elementFromPoint(H.clientX, H.clientY), t !== e);) e = t;
            if (c.parentNode[k]._isOutsideThisEl(t), e) do {
                if (e[k]) {
                    var n = void 0;
                    if (n = e[k]._onDragOver({
                        clientX: H.clientX,
                        clientY: H.clientY,
                        target: t,
                        rootEl: e
                    }), n && !this.options.dragoverBubble) break
                }
                t = e
            } while (e = e.parentNode);
            Te()
        }
    }, _onTouchMove: function (t) {
        if (it) {
            var e = this.options, n = e.fallbackTolerance, i = e.fallbackOffset, r = t.touches ? t.touches[0] : t,
                a = g && ft(g, !0), l = g && a && a.a, s = g && a && a.d, u = xt && A && fe(A),
                d = (r.clientX - it.clientX + i.x) / (l || 1) + (u ? u[0] - $t[0] : 0) / (l || 1),
                f = (r.clientY - it.clientY + i.y) / (s || 1) + (u ? u[1] - $t[1] : 0) / (s || 1);
            if (!p.active && !ut) {
                if (n && Math.max(Math.abs(r.clientX - this._lastX), Math.abs(r.clientY - this._lastY)) < n) return;
                this._onDragStart(t, !0)
            }
            if (g) {
                a ? (a.e += d - (zt || 0), a.f += f - (Ut || 0)) : a = {a: 1, b: 0, c: 0, d: 1, e: d, f};
                var m = "matrix(".concat(a.a, ",").concat(a.b, ",").concat(a.c, ",").concat(a.d, ",").concat(a.e, ",").concat(a.f, ")");
                h(g, "webkitTransform", m), h(g, "mozTransform", m), h(g, "msTransform", m), h(g, "transform", m), zt = d, Ut = f, H = r
            }
            t.cancelable && t.preventDefault()
        }
    }, _appendGhost: function () {
        if (!g) {
            var t = this.options.fallbackOnBody ? document.body : D, e = O(c, !0, xt, !0, t), n = this.options;
            if (xt) {
                for (A = t; h(A, "position") === "static" && h(A, "transform") === "none" && A !== document;) A = A.parentNode;
                A !== document.body && A !== document.documentElement ? (A === document && (A = K()), e.top += A.scrollTop, e.left += A.scrollLeft) : A = K(), $t = fe(A)
            }
            g = c.cloneNode(!0), F(g, n.ghostClass, !1), F(g, n.fallbackClass, !0), F(g, n.dragClass, !0), h(g, "transition", ""), h(g, "transform", ""), h(g, "box-sizing", "border-box"), h(g, "margin", 0), h(g, "top", e.top), h(g, "left", e.left), h(g, "width", e.width), h(g, "height", e.height), h(g, "opacity", "0.8"), h(g, "position", xt ? "absolute" : "fixed"), h(g, "zIndex", "100000"), h(g, "pointerEvents", "none"), p.ghost = g, t.appendChild(g), h(g, "transform-origin", de / parseInt(g.style.width) * 100 + "% " + he / parseInt(g.style.height) * 100 + "%")
        }
    }, _onDragStart: function (t, e) {
        var n = this, i = t.dataTransfer, r = n.options;
        if (x("dragStart", this, {evt: t}), p.eventCanceled) {
            this._onDrop();
            return
        }
        x("setupClone", this), p.eventCanceled || (S = De(c), S.removeAttribute("id"), S.draggable = !1, S.style["will-change"] = "", this._hideClone(), F(S, this.options.chosenClass, !1), p.clone = S), n.cloneId = kt(function () {
            x("clone", n), !p.eventCanceled && (n.options.removeCloneOnHide || D.insertBefore(S, c), n._hideClone(), N({
                sortable: n,
                name: "clone"
            }))
        }), !e && F(c, r.dragClass, !0), e ? (Bt = !0, n._loopId = setInterval(n._emulateDragOver, 50)) : (b(document, "mouseup", n._onDrop), b(document, "touchend", n._onDrop), b(document, "touchcancel", n._onDrop), i && (i.effectAllowed = "move", r.setData && r.setData.call(n, i, c)), w(document, "drop", n), h(c, "transform", "translateZ(0)")), ut = !0, n._dragStartId = kt(n._dragStarted.bind(n, e, t)), w(document, "selectstart", n), bt = !0, Et && h(document.body, "user-select", "none")
    }, _onDragOver: function (t) {
        var e = this.el, n = t.target, i, r, a, l = this.options, s = l.group, u = p.active, d = It === s, f = l.sort,
            m = T || u, E, v = this, y = !1;
        if (Jt) return;

        function Y(vt, Ne) {
            x(vt, v, L({
                evt: t,
                isOwner: d,
                axis: E ? "vertical" : "horizontal",
                revert: a,
                dragRect: i,
                targetRect: r,
                canSort: f,
                fromSortable: m,
                target: n,
                completed: I,
                onMove: function (ae, xe) {
                    return Pt(D, e, c, i, ae, O(ae), t, xe)
                },
                changed: B
            }, Ne))
        }

        function j() {
            Y("dragOverAnimationCapture"), v.captureAnimationState(), v !== m && m.captureAnimationState()
        }

        function I(vt) {
            return Y("dragOverCompleted", {insertion: vt}), vt && (d ? u._hideClone() : u._showClone(v), v !== m && (F(c, T ? T.options.ghostClass : u.options.ghostClass, !1), F(c, l.ghostClass, !0)), T !== v && v !== p.active ? T = v : v === p.active && T && (T = null), m === v && (v._ignoreWhileAnimating = n), v.animateAll(function () {
                Y("dragOverAnimationComplete"), v._ignoreWhileAnimating = null
            }), v !== m && (m.animateAll(), m._ignoreWhileAnimating = null)), (n === c && !c.animated || n === e && !n.animated) && (st = null), !l.dragoverBubble && !t.rootEl && n !== document && (c.parentNode[k]._isOutsideThisEl(t.target), !vt && rt(t)), !l.dragoverBubble && t.stopPropagation && t.stopPropagation(), y = !0
        }

        function B() {
            R = X(c), Q = X(c, l.draggable), N({
                sortable: v,
                name: "change",
                toEl: e,
                newIndex: R,
                newDraggableIndex: Q,
                originalEvent: t
            })
        }

        if (t.preventDefault !== void 0 && t.cancelable && t.preventDefault(), n = W(n, l.draggable, e, !0), Y("dragOver"), p.eventCanceled) return y;
        if (c.contains(t.target) || n.animated && n.animatingX && n.animatingY || v._ignoreWhileAnimating === n) return I(!1);
        if (Bt = !1, u && !l.disabled && (d ? f || (a = _ !== D) : T === this || (this.lastPutMode = It.checkPull(this, u, c, t)) && s.checkPut(this, u, c, t))) {
            if (E = this._getDirection(t, n) === "vertical", i = O(c), Y("dragOverValid"), p.eventCanceled) return y;
            if (a) return _ = D, j(), this._hideClone(), Y("revert"), p.eventCanceled || (at ? D.insertBefore(c, at) : D.appendChild(c)), I(!0);
            var P = oe(e, l.draggable);
            if (!P || Qe(t, E, this) && !P.animated) {
                if (P === c) return I(!1);
                if (P && e === t.target && (n = P), n && (r = O(n)), Pt(D, e, c, i, n, r, t, !!n) !== !1) return j(), P && P.nextSibling ? e.insertBefore(c, P.nextSibling) : e.appendChild(c), _ = e, B(), I(!0)
            } else if (P && Ze(t, E, this)) {
                var et = dt(e, 0, l, !0);
                if (et === c) return I(!1);
                if (n = et, r = O(n), Pt(D, e, c, i, n, r, t, !1) !== !1) return j(), e.insertBefore(c, et), _ = e, B(), I(!0)
            } else if (n.parentNode === e) {
                r = O(n);
                var G = 0, nt, ht = c.parentNode !== e,
                    M = !ze(c.animated && c.toRect || i, n.animated && n.toRect || r, E), pt = E ? "top" : "left",
                    V = ce(n, "top", "top") || ce(c, "top", "top"), gt = V ? V.scrollTop : void 0;
                st !== n && (nt = r[pt], Ct = !1, Nt = !M && l.invertSwap || ht), G = Je(t, n, r, E, M ? 1 : l.swapThreshold, l.invertedSwapThreshold == null ? l.swapThreshold : l.invertedSwapThreshold, Nt, st === n);
                var z;
                if (G !== 0) {
                    var ot = X(c);
                    do ot -= G, z = _.children[ot]; while (z && (h(z, "display") === "none" || z === g))
                }
                if (G === 0 || z === n) return I(!1);
                st = n, _t = G;
                var mt = n.nextElementSibling, Z = !1;
                Z = G === 1;
                var At = Pt(D, e, c, i, n, r, t, Z);
                if (At !== !1) return (At === 1 || At === -1) && (Z = At === 1), Jt = !0, setTimeout(Ve, 30), j(), Z && !mt ? e.appendChild(c) : n.parentNode.insertBefore(c, Z ? mt : n), V && Ee(V, 0, gt - V.scrollTop), _ = c.parentNode, nt !== void 0 && !Nt && (Rt = Math.abs(nt - O(n)[pt])), B(), I(!0)
            }
            if (e.contains(c)) return I(!1)
        }
        return !1
    }, _ignoreWhileAnimating: null, _offMoveEvents: function () {
        b(document, "mousemove", this._onTouchMove), b(document, "touchmove", this._onTouchMove), b(document, "pointermove", this._onTouchMove), b(document, "dragover", rt), b(document, "mousemove", rt), b(document, "touchmove", rt)
    }, _offUpEvents: function () {
        var t = this.el.ownerDocument;
        b(t, "mouseup", this._onDrop), b(t, "touchend", this._onDrop), b(t, "pointerup", this._onDrop), b(t, "touchcancel", this._onDrop), b(document, "selectstart", this)
    }, _onDrop: function (t) {
        var e = this.el, n = this.options;
        if (R = X(c), Q = X(c, n.draggable), x("drop", this, {evt: t}), _ = c && c.parentNode, R = X(c), Q = X(c, n.draggable), p.eventCanceled) {
            this._nulling();
            return
        }
        ut = !1, Nt = !1, Ct = !1, clearInterval(this._loopId), clearTimeout(this._dragStartTimer), te(this.cloneId), te(this._dragStartId), this.nativeDraggable && (b(document, "drop", this), b(e, "dragstart", this._onDragStart)), this._offMoveEvents(), this._offUpEvents(), Et && h(document.body, "user-select", ""), h(c, "transform", ""), t && (bt && (t.cancelable && t.preventDefault(), !n.dropBubble && t.stopPropagation()), g && g.parentNode && g.parentNode.removeChild(g), (D === _ || T && T.lastPutMode !== "clone") && S && S.parentNode && S.parentNode.removeChild(S), c && (this.nativeDraggable && b(c, "dragend", this), qt(c), c.style["will-change"] = "", bt && !ut && F(c, T ? T.options.ghostClass : this.options.ghostClass, !1), F(c, this.options.chosenClass, !1), N({
            sortable: this,
            name: "unchoose",
            toEl: _,
            newIndex: null,
            newDraggableIndex: null,
            originalEvent: t
        }), D !== _ ? (R >= 0 && (N({rootEl: _, name: "add", toEl: _, fromEl: D, originalEvent: t}), N({
            sortable: this,
            name: "remove",
            toEl: _,
            originalEvent: t
        }), N({rootEl: _, name: "sort", toEl: _, fromEl: D, originalEvent: t}), N({
            sortable: this,
            name: "sort",
            toEl: _,
            originalEvent: t
        })), T && T.save()) : R !== ct && R >= 0 && (N({
            sortable: this,
            name: "update",
            toEl: _,
            originalEvent: t
        }), N({
            sortable: this,
            name: "sort",
            toEl: _,
            originalEvent: t
        })), p.active && ((R == null || R === -1) && (R = ct, Q = St), N({
            sortable: this,
            name: "end",
            toEl: _,
            originalEvent: t
        }), this.save()))), this._nulling()
    }, _nulling: function () {
        x("nulling", this), D = c = _ = g = at = S = Ft = J = it = H = bt = R = Q = ct = St = st = _t = T = It = p.dragged = p.ghost = p.clone = p.active = null, Gt.forEach(function (t) {
            t.checked = !0
        }), Gt.length = zt = Ut = 0
    }, handleEvent: function (t) {
        switch (t.type) {
            case"drop":
            case"dragend":
                this._onDrop(t);
                break;
            case"dragenter":
            case"dragover":
                c && (this._onDragOver(t), qe(t));
                break;
            case"selectstart":
                t.preventDefault();
                break
        }
    }, toArray: function () {
        for (var t = [], e, n = this.el.children, i = 0, r = n.length, a = this.options; i < r; i++) e = n[i], W(e, a.draggable, this.el, !1) && t.push(e.getAttribute(a.dataIdAttr) || en(e));
        return t
    }, sort: function (t, e) {
        var n = {}, i = this.el;
        this.toArray().forEach(function (r, a) {
            var l = i.children[a];
            W(l, this.options.draggable, i, !1) && (n[r] = l)
        }, this), e && this.captureAnimationState(), t.forEach(function (r) {
            n[r] && (i.removeChild(n[r]), i.appendChild(n[r]))
        }), e && this.animateAll()
    }, save: function () {
        var t = this.options.store;
        t && t.set && t.set(this)
    }, closest: function (t, e) {
        return W(t, e || this.options.draggable, this.el, !1)
    }, option: function (t, e) {
        var n = this.options;
        if (e === void 0) return n[t];
        var i = Tt.modifyOption(this, t, e);
        typeof i < "u" ? n[t] = i : n[t] = e, t === "group" && Ce(n)
    }, destroy: function () {
        x("destroy", this);
        var t = this.el;
        t[k] = null, b(t, "mousedown", this._onTapStart), b(t, "touchstart", this._onTapStart), b(t, "pointerdown", this._onTapStart), this.nativeDraggable && (b(t, "dragover", this), b(t, "dragenter", this)), Array.prototype.forEach.call(t.querySelectorAll("[draggable]"), function (e) {
            e.removeAttribute("draggable")
        }), this._onDrop(), this._disableDelayedDragEvents(), Ht.splice(Ht.indexOf(this.el), 1), this.el = t = null
    }, _hideClone: function () {
        if (!J) {
            if (x("hideClone", this), p.eventCanceled) return;
            h(S, "display", "none"), this.options.removeCloneOnHide && S.parentNode && S.parentNode.removeChild(S), J = !0
        }
    }, _showClone: function (t) {
        if (t.lastPutMode !== "clone") {
            this._hideClone();
            return
        }
        if (J) {
            if (x("showClone", this), p.eventCanceled) return;
            c.parentNode == D && !this.options.group.revertClone ? D.insertBefore(S, c) : at ? D.insertBefore(S, at) : D.appendChild(S), this.options.group.revertClone && this.animate(c, S), h(S, "display", ""), J = !1
        }
    }
};

function qe(o) {
    o.dataTransfer && (o.dataTransfer.dropEffect = "move"), o.cancelable && o.preventDefault()
}

function Pt(o, t, e, n, i, r, a, l) {
    var s, u = o[k], d = u.options.onMove, f;
    return window.CustomEvent && !q && !Ot ? s = new CustomEvent("move", {
        bubbles: !0,
        cancelable: !0
    }) : (s = document.createEvent("Event"), s.initEvent("move", !0, !0)), s.to = t, s.from = o, s.dragged = e, s.draggedRect = n, s.related = i || t, s.relatedRect = r || O(t), s.willInsertAfter = l, s.originalEvent = a, o.dispatchEvent(s), d && (f = d.call(u, s, a)), f
}

function qt(o) {
    o.draggable = !1
}

function Ve() {
    Jt = !1
}

function Ze(o, t, e) {
    var n = O(dt(e.el, 0, e.options, !0)), i = 10;
    return t ? o.clientX < n.left - i || o.clientY < n.top && o.clientX < n.right : o.clientY < n.top - i || o.clientY < n.bottom && o.clientX < n.left
}

function Qe(o, t, e) {
    var n = O(oe(e.el, e.options.draggable)), i = 10;
    return t ? o.clientX > n.right + i || o.clientX <= n.right && o.clientY > n.bottom && o.clientX >= n.left : o.clientX > n.right && o.clientY > n.top || o.clientX <= n.right && o.clientY > n.bottom + i
}

function Je(o, t, e, n, i, r, a, l) {
    var s = n ? o.clientY : o.clientX, u = n ? e.height : e.width, d = n ? e.top : e.left, f = n ? e.bottom : e.right,
        m = !1;
    if (!a) {
        if (l && Rt < u * i) {
            if (!Ct && (_t === 1 ? s > d + u * r / 2 : s < f - u * r / 2) && (Ct = !0), Ct) m = !0; else if (_t === 1 ? s < d + Rt : s > f - Rt) return -_t
        } else if (s > d + u * (1 - i) / 2 && s < f - u * (1 - i) / 2) return tn(t)
    }
    return m = m || a, m && (s < d + u * r / 2 || s > f - u * r / 2) ? s > d + u / 2 ? 1 : -1 : 0
}

function tn(o) {
    return X(c) < X(o) ? 1 : -1
}

function en(o) {
    for (var t = o.tagName + o.className + o.src + o.href + o.textContent, e = t.length, n = 0; e--;) n += t.charCodeAt(e);
    return n.toString(36)
}

function nn(o) {
    Gt.length = 0;
    for (var t = o.getElementsByTagName("input"), e = t.length; e--;) {
        var n = t[e];
        n.checked && Gt.push(n)
    }
}

function kt(o) {
    return setTimeout(o, 0)
}

function te(o) {
    return clearTimeout(o)
}

Kt && w(document, "touchmove", function (o) {
    (p.active || ut) && o.cancelable && o.preventDefault()
});
p.utils = {
    on: w,
    off: b,
    css: h,
    find: we,
    is: function (t, e) {
        return !!W(t, e, t, !1)
    },
    extend: Ye,
    throttle: ye,
    closest: W,
    toggleClass: F,
    clone: De,
    index: X,
    nextTick: kt,
    cancelNextTick: te,
    detectDirection: _e,
    getChild: dt
};
p.get = function (o) {
    return o[k]
};
p.mount = function () {
    for (var o = arguments.length, t = new Array(o), e = 0; e < o; e++) t[e] = arguments[e];
    t[0].constructor === Array && (t = t[0]), t.forEach(function (n) {
        if (!n.prototype || !n.prototype.constructor) throw "Sortable: Mounted plugin must be a constructor function, not ".concat({}.toString.call(n));
        n.utils && (p.utils = L(L({}, p.utils), n.utils)), Tt.mount(n)
    })
};
p.create = function (o, t) {
    return new p(o, t)
};
p.version = Re;
var C = [], wt, ee, ne = !1, Vt, Zt, Wt, yt;

function on() {
    function o() {
        this.defaults = {
            scroll: !0,
            forceAutoScrollFallback: !1,
            scrollSensitivity: 30,
            scrollSpeed: 10,
            bubbleScroll: !0
        };
        for (var t in this) t.charAt(0) === "_" && typeof this[t] == "function" && (this[t] = this[t].bind(this))
    }

    return o.prototype = {
        dragStarted: function (e) {
            var n = e.originalEvent;
            this.sortable.nativeDraggable ? w(document, "dragover", this._handleAutoScroll) : this.options.supportPointer ? w(document, "pointermove", this._handleFallbackAutoScroll) : n.touches ? w(document, "touchmove", this._handleFallbackAutoScroll) : w(document, "mousemove", this._handleFallbackAutoScroll)
        }, dragOverCompleted: function (e) {
            var n = e.originalEvent;
            !this.options.dragOverBubble && !n.rootEl && this._handleAutoScroll(n)
        }, drop: function () {
            this.sortable.nativeDraggable ? b(document, "dragover", this._handleAutoScroll) : (b(document, "pointermove", this._handleFallbackAutoScroll), b(document, "touchmove", this._handleFallbackAutoScroll), b(document, "mousemove", this._handleFallbackAutoScroll)), ge(), Xt(), Be()
        }, nulling: function () {
            Wt = ee = wt = ne = yt = Vt = Zt = null, C.length = 0
        }, _handleFallbackAutoScroll: function (e) {
            this._handleAutoScroll(e, !0)
        }, _handleAutoScroll: function (e, n) {
            var i = this, r = (e.touches ? e.touches[0] : e).clientX, a = (e.touches ? e.touches[0] : e).clientY,
                l = document.elementFromPoint(r, a);
            if (Wt = e, n || this.options.forceAutoScrollFallback || Ot || q || Et) {
                Qt(e, this.options, l, n);
                var s = tt(l, !0);
                ne && (!yt || r !== Vt || a !== Zt) && (yt && ge(), yt = setInterval(function () {
                    var u = tt(document.elementFromPoint(r, a), !0);
                    u !== s && (s = u, Xt()), Qt(e, i.options, u, n)
                }, 10), Vt = r, Zt = a)
            } else {
                if (!this.options.bubbleScroll || tt(l, !0) === K()) {
                    Xt();
                    return
                }
                Qt(e, this.options, tt(l, !1), !1)
            }
        }
    }, $(o, {pluginName: "scroll", initializeByDefault: !0})
}

function Xt() {
    C.forEach(function (o) {
        clearInterval(o.pid)
    }), C = []
}

function ge() {
    clearInterval(yt)
}

var Qt = ye(function (o, t, e, n) {
    if (t.scroll) {
        var i = (o.touches ? o.touches[0] : o).clientX, r = (o.touches ? o.touches[0] : o).clientY,
            a = t.scrollSensitivity, l = t.scrollSpeed, s = K(), u = !1, d;
        ee !== e && (ee = e, Xt(), wt = t.scroll, d = t.scrollFn, wt === !0 && (wt = tt(e, !0)));
        var f = 0, m = wt;
        do {
            var E = m, v = O(E), y = v.top, Y = v.bottom, j = v.left, I = v.right, B = v.width, P = v.height,
                et = void 0, G = void 0, nt = E.scrollWidth, ht = E.scrollHeight, M = h(E), pt = E.scrollLeft,
                V = E.scrollTop;
            E === s ? (et = B < nt && (M.overflowX === "auto" || M.overflowX === "scroll" || M.overflowX === "visible"), G = P < ht && (M.overflowY === "auto" || M.overflowY === "scroll" || M.overflowY === "visible")) : (et = B < nt && (M.overflowX === "auto" || M.overflowX === "scroll"), G = P < ht && (M.overflowY === "auto" || M.overflowY === "scroll"));
            var gt = et && (Math.abs(I - i) <= a && pt + B < nt) - (Math.abs(j - i) <= a && !!pt),
                z = G && (Math.abs(Y - r) <= a && V + P < ht) - (Math.abs(y - r) <= a && !!V);
            if (!C[f]) for (var ot = 0; ot <= f; ot++) C[ot] || (C[ot] = {});
            (C[f].vx != gt || C[f].vy != z || C[f].el !== E) && (C[f].el = E, C[f].vx = gt, C[f].vy = z, clearInterval(C[f].pid), (gt != 0 || z != 0) && (u = !0, C[f].pid = setInterval(function () {
                n && this.layer === 0 && p.active._onTouchMove(Wt);
                var mt = C[this.layer].vy ? C[this.layer].vy * l : 0, Z = C[this.layer].vx ? C[this.layer].vx * l : 0;
                typeof d == "function" && d.call(p.dragged.parentNode[k], Z, mt, o, Wt, C[this.layer].el) !== "continue" || Ee(C[this.layer].el, Z, mt)
            }.bind({layer: f}), 24))), f++
        } while (t.bubbleScroll && m !== s && (m = tt(m, !1)));
        ne = u
    }
}, 30), Ae = function (t) {
    var e = t.originalEvent, n = t.putSortable, i = t.dragEl, r = t.activeSortable, a = t.dispatchSortableEvent,
        l = t.hideGhostForTarget, s = t.unhideGhostForTarget;
    if (e) {
        var u = n || r;
        l();
        var d = e.changedTouches && e.changedTouches.length ? e.changedTouches[0] : e,
            f = document.elementFromPoint(d.clientX, d.clientY);
        s(), u && !u.el.contains(f) && (a("spill"), this.onSpill({dragEl: i, putSortable: n}))
    }
};

function ie() {
}

ie.prototype = {
    startIndex: null, dragStart: function (t) {
        var e = t.oldDraggableIndex;
        this.startIndex = e
    }, onSpill: function (t) {
        var e = t.dragEl, n = t.putSortable;
        this.sortable.captureAnimationState(), n && n.captureAnimationState();
        var i = dt(this.sortable.el, this.startIndex, this.options);
        i ? this.sortable.el.insertBefore(e, i) : this.sortable.el.appendChild(e), this.sortable.animateAll(), n && n.animateAll()
    }, drop: Ae
};
$(ie, {pluginName: "revertOnSpill"});

function re() {
}

re.prototype = {
    onSpill: function (t) {
        var e = t.dragEl, n = t.putSortable, i = n || this.sortable;
        i.captureAnimationState(), e.parentNode && e.parentNode.removeChild(e), i.animateAll()
    }, drop: Ae
};
$(re, {pluginName: "removeOnSpill"});
p.mount(new on);
p.mount(re, ie);
var Ie = p;
var sn = ({statePath: o, disabled: t, maxDepth: e}) => ({
    statePath: o, sortable: null, maxDepth: e, init() {
        this.sortable = new Ie(this.$el, {
            disabled: t,
            group: "nested",
            animation: 150,
            fallbackOnBody: !0,
            swapThreshold: .5,
            draggable: "[data-sortable-item]",
            handle: "[data-sortable-handle]",
            onMove: n => {
                if (this.maxDepth >= 0 && this.getDepth(n.related) > this.maxDepth) return !1
            },
            onSort: n => {
                this.$wire.dispatchFormEvent("builder::sort", this.statePath, this.sortable.toArray())
            }
        })
    }, getDepth(n, i = 0) {
        let r = n.parentElement.closest("[data-sortable-item]");
        return r ? this.getDepth(r, ++i) : i
    }
});
export {sn as default};
/*! Bundled license information:

sortablejs/modular/sortable.esm.js:
  (**!
   * Sortable 1.15.0
   * @author	RubaXa   <trash@rubaxa.org>
   * @author	owenm    <owen23355@gmail.com>
   * @license MIT
   *)
*/
