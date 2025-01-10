/******/ (() => { // webpackBootstrap
/*!************************************************!*\
  !*** ./resources/coreui/src/js/color-modes.js ***!
  \************************************************/
function _createForOfIteratorHelper(r, e) { var t = "undefined" != typeof Symbol && r[Symbol.iterator] || r["@@iterator"]; if (!t) { if (Array.isArray(r) || (t = _unsupportedIterableToArray(r)) || e && r && "number" == typeof r.length) { t && (r = t); var _n = 0, F = function F() {}; return { s: F, n: function n() { return _n >= r.length ? { done: !0 } : { done: !1, value: r[_n++] }; }, e: function e(r) { throw r; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var o, a = !0, u = !1; return { s: function s() { t = t.call(r); }, n: function n() { var r = t.next(); return a = r.done, r; }, e: function e(r) { u = !0, o = r; }, f: function f() { try { a || null == t["return"] || t["return"](); } finally { if (u) throw o; } } }; }
function _unsupportedIterableToArray(r, a) { if (r) { if ("string" == typeof r) return _arrayLikeToArray(r, a); var t = {}.toString.call(r).slice(8, -1); return "Object" === t && r.constructor && (t = r.constructor.name), "Map" === t || "Set" === t ? Array.from(r) : "Arguments" === t || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(t) ? _arrayLikeToArray(r, a) : void 0; } }
function _arrayLikeToArray(r, a) { (null == a || a > r.length) && (a = r.length); for (var e = 0, n = Array(a); e < a; e++) n[e] = r[e]; return n; }
/*!
* Color mode toggler for CoreUI's docs (https://coreui.io/)
* Copyright (c) 2024 creativeLabs Łukasz Holeczek
* Licensed under the Creative Commons Attribution 3.0 Unported License.
*/

(function () {
  var THEME = 'coreui-free-bootstrap-admin-template-theme';
  var getStoredTheme = function getStoredTheme() {
    return localStorage.getItem(THEME);
  };
  var setStoredTheme = function setStoredTheme(theme) {
    return localStorage.setItem(THEME, theme);
  };
  var getPreferredTheme = function getPreferredTheme() {
    var storedTheme = getStoredTheme();
    if (storedTheme) {
      return storedTheme;
    }
    return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
  };
  var setTheme = function setTheme(theme) {
    if (theme === 'auto' && window.matchMedia('(prefers-color-scheme: dark)').matches) {
      document.documentElement.setAttribute('data-coreui-theme', 'dark');
    } else {
      document.documentElement.setAttribute('data-coreui-theme', theme);
    }
    var event = new Event('ColorSchemeChange');
    document.documentElement.dispatchEvent(event);
  };
  setTheme(getPreferredTheme());
  var showActiveTheme = function showActiveTheme(theme) {
    var activeThemeIcon = document.querySelector('.theme-icon-active use');
    var btnToActive = document.querySelector("[data-coreui-theme-value=\"".concat(theme, "\"]"));
    var svgOfActiveBtn = btnToActive.querySelector('svg use').getAttribute('xlink:href');
    var _iterator = _createForOfIteratorHelper(document.querySelectorAll('[data-coreui-theme-value]')),
      _step;
    try {
      for (_iterator.s(); !(_step = _iterator.n()).done;) {
        var element = _step.value;
        element.classList.remove('active');
      }
    } catch (err) {
      _iterator.e(err);
    } finally {
      _iterator.f();
    }
    btnToActive.classList.add('active');
    activeThemeIcon.setAttribute('xlink:href', svgOfActiveBtn);
  };
  window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function () {
    var storedTheme = getStoredTheme();
    if (storedTheme !== 'light' || storedTheme !== 'dark') {
      setTheme(getPreferredTheme());
    }
  });
  window.addEventListener('DOMContentLoaded', function () {
    showActiveTheme(getPreferredTheme());
    var _iterator2 = _createForOfIteratorHelper(document.querySelectorAll('[data-coreui-theme-value]')),
      _step2;
    try {
      var _loop = function _loop() {
        var toggle = _step2.value;
        toggle.addEventListener('click', function () {
          var theme = toggle.getAttribute('data-coreui-theme-value');
          setStoredTheme(theme);
          setTheme(theme);
          showActiveTheme(theme);
        });
      };
      for (_iterator2.s(); !(_step2 = _iterator2.n()).done;) {
        _loop();
      }
    } catch (err) {
      _iterator2.e(err);
    } finally {
      _iterator2.f();
    }
  });
})();
/******/ })()
;