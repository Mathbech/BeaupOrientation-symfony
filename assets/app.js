// import './bootstrap.js';
import $ from 'jquery';
import 'datatables.net';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */

import './styles/app.css';

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');

$(function () {
  $("#courses-table").DataTable({
    "responsive": true,
    "lengthChange": false,
    "autoWidth": false,
  })
  $("#parcours-table").DataTable({
    "responsive": true,
    "lengthChange": true,
    "autoWidth": true,
  })
});
