// import './bootstrap.js';
import $ from 'jquery';
import 'datatables.net';
import '@fortawesome/fontawesome-free/css/all.min.css';
import '@fortawesome/fontawesome-free/js/all.min.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */

import './styles/app.css';
import './js/clikable.js';

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
  $("#logs-table").DataTable({
    "responsive": true,
    "lengthChange": true,
    "autoWidth": false,
    "paging": true,
    "pageLength": 10,
    "lengthMenu": [5, 10, 25, 50, 100],
    "order": [[0, "desc"]],
    "language": {
      "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json"
    }
  })
});
