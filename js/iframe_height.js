(function() {
	var q = (function() {
		var t = window.localStorage,
		u, v;
		if (t) {
			return {
				get: function(x) {
					return unescape(t.getItem(x))
				},
				set: function(x, y) {
					t.setItem(x, escape(y))
				}
			}
		} else {
			if (window.ActiveXObject) {
				u = document.documentElement;
				v = "localstorage";
				try {
					u.addBehavior("#default#userdata");
					u.save("localstorage")
				} catch(w) {}
				return {
					set: function(x, y) {
						try {
							u.setAttribute(x, y);
							u.save(v)
						} catch(z) {}
					},
					get: function(x) {
						try {
							u.load(v);
							return u.getAttribute(x)
						} catch(y) {}
					}
				}
			} else {
				return {
					get: getCookie,
					set: setCookie
				}
			}
		}
	})();
	var j = navigator.userAgent,
	f = /msie/i.test(j);
	var d = function() {};
	function g(v, u, t) {
		var w, x = {};
		u = u || {};
		for (w in v) {
			x[w] = v[w];
			if (u[w] != null) {
				if (t) {
					if (v.hasOwnProperty[w]) {
						x[w] = u[w]
					}
				} else {
					x[w] = u[w]
				}
			}
		}
		return x
	}
	function n(t) {
		t = document.getElementById(t) || t;
		try {
			t.parentNode.removeChild(t)
		} catch(u) {}
	}
	function i(u) {
		if (u) {
			var t = [];
			for (var v in u) {
				t.push(v + "=" + encodeURIComponent(u[v]))
			}
			if (t.length) {
				return t.join("&")
			} else {
				return ""
			}
		}
	}
	function r(B, z) {
		var x = {};
		var y = {
			url: "",
			charset: "UTF-8",
			timeout: 30 * 1000,
			args: {},
			onComplete: d,
			onTimeout: d,
			isEncode: false,
			uniqueID: null
		};
		var w, v;
		var u = g(y, B);
		if (u.url == "") {
			throw new Error("scriptLoader: url is null")
		}
		var t = u.uniqueID || (new Date().getTime().toString());
		w = x[t];
		if (w != null && f != true) {
			n(w);
			w = null
		}
		if (w == null) {
			x[t] = document.createElement("script");
			w = x[t]
		}
		w.charset = u.charset;
		w.type = "text/javascript";
		if (u.onComplete != null) {
			if (f) {
				w.onreadystatechange = function() {
					if (w.readyState.toLowerCase() == "loaded" || w.readyState.toLowerCase() == "complete") {
						try {
							clearTimeout(v);
							document.getElementsByTagName("head")[0].removeChild(w);
							w.onreadystatechange = null
						} catch(C) {}
						u.onComplete()
					}
				}
			} else {
				w.onload = function() {
					try {
						clearTimeout(v);
						n(w);
						w.onload = null
					} catch(C) {}
					u.onComplete()
				}
			}
		}
		var A = i(u.args);
		if (u.url.indexOf("?") == - 1) {
			if (A != "") {
				A = "?" + A
			}
		} else {
			if (A != "") {
				A = "&" + A
			}
		}
		w.src = u.url + A;
		document.getElementsByTagName("head")[0].appendChild(w);
		if (u.timeout > 0 && u.onTimeout != null) {
			v = setTimeout(function() {
				try {
					document.getElementsByTagName("head")[0].removeChild(w)
				} catch(C) {}
				u.onTimeout()
			},
			u.timeout)
		}
		return w
	}
	function h() {
		r({
			url: "http://js.t.sinajs.cn/open/thirdpart/js/frame/version.js?" + (new Date()).getTime(),
			onComplete: function() {
				var t = new Date();
				b = t.getFullYear() + "/" + (t.getMonth() + 1) + "/" + t.getDate() + ":";
				try {
					b = b + $CONFIG.version.client.split(":")[1]
				} catch(u) {
					b = b + t.getTime()
				}
				m(b);
				q.set("client", b)
			}
		})
	}
	function m(t) {
		var w = t.split(":");
		var v = new Date();
		var u = v.getFullYear() + "/" + (v.getMonth() + 1) + "/" + v.getDate();
		if (u != w[0]) {
			h()
		} else {
			l = w[1];
			c()
		}
	}
	var e;
	var p, k, a;
	var s = false;
	var b = q.get("client"),
	l;
	if (b == null || b == "null" || b.indexOf(":") == - 1) {
		h()
	} else {
		m(b)
	}
	function c() {
		var t = new Date().setFullYear(2013, 11, 16);
		l = (new Date() < t) ? "20131116": l;
		r({
			url: "http://js.t.sinajs.cn/open/thirdpart/js/frame/appclient.js?" + l,
			onComplete: function() {
				if (e != null) {
					App.AuthDialog.show(e)
				}
				if (p && isNaN(p) == false) {
					App.setPageHeight(p)
				}
				if (s != false) {
					App.scrollToTop(s)
				}
				if (a != null) {
					App.oauth(a)
				}
				o.run()
			}
		})
	}
	var o = {
		cacheList: [],
		add: function(t, u) {
			if (typeof t !== "string") {
				return
			}
			o.cacheList.push([t, u])
		},
		run: function() {
			for (var w = 0, t = o.cacheList.length; w < t; w++) {
				var u = o.cacheList[w];
				var x = u[0],
				v = u[1];
				App[x].apply(window, v)
			}
			o.cacheList = []
		}
	};
	if (window.App == null) {
		App = {
			AuthDialog: {
				show: function(t) {
					e = t
				}
			},
			setPageHeight: function(t) {
				if (t == null) {
					p = document.body.clientHeight + 40
				} else {
					if (!isNaN(t)) {
						p = t
					}
				}
			},
			scrollToTop: function(t) {
				s = t || true
			},
			oauth: function(t) {
				a = t
			},
			trigger: function(t, u, v) {
				o.add("trigger", arguments)
			},
			on: function(u, t) {
				o.add("on", arguments)
			},
			off: function(u, t) {
				o.add("off", arguments)
			}
		}
	}
})();

