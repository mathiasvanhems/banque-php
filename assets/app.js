/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

// start the Stimulus application
import './bootstrap';
//import datepicker from 'js-datepicker'
import $ from 'jquery';
window.jQuery = $;
window.$ = $;
//const $ = require('jquery');
// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything
require('bootstrap');
import { Tooltip, Toast, Popover } from 'bootstrap';
// or you can include specific pieces
// require('bootstrap/js/dist/tooltip');
// require('bootstrap/js/dist/popover');

/*require('bootstrap-datepicker/js/bootstrap-datepicker')
require('bootstrap-datepicker/js/locales/bootstrap-datepicker.fr')
require('bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')
$(document).ready(function (){
    $('.js-datepicker input').each(function () {
        $(this).datepicker({
            format: 'dd/mm/YYYY'
        });
    });
});*/
