/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import "./styles/app.scss";

// start the Stimulus application
const $ = require("jquery");
var jQueryBridget = require("jquery-bridget");
var Isotope = require("isotope-layout");
// make Isotope a jQuery plugin
jQueryBridget("isotope", Isotope, $);
require("jquery-nice-select");
require("owl.carousel");
require("bootstrap");
require("./js/custom");
require("./js/cart");
require("./js/checkout");
require("./js/wishlist");
require("./js/dashboard");
require("./js/report");
import "./js/product";

// Angular
