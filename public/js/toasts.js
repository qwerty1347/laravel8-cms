/******/ (() => { // webpackBootstrap
/*!*******************************************!*\
  !*** ./resources/coreui/src/js/toasts.js ***!
  \*******************************************/
/* global coreui */

/**
 * --------------------------------------------------------------------------
 * CoreUI Boostrap Admin Template toasts.js
 * Licensed under MIT (https://github.com/coreui/coreui-free-bootstrap-admin-template/blob/main/LICENSE)
 * --------------------------------------------------------------------------
 */

var toastTrigger = document.getElementById('liveToastBtn');
var toastLiveExample = document.getElementById('liveToast');
if (toastTrigger) {
  toastTrigger.addEventListener('click', function () {
    var toast = new coreui.Toast(toastLiveExample);
    toast.show();
  });
}
/******/ })()
;