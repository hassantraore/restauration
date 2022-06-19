var app = angular.module("App", ["ngSanitize", "angular.bind.notifier"]);

app.config(function ($interpolateProvider) {
  $interpolateProvider.startSymbol("__").endSymbol("__");
});
