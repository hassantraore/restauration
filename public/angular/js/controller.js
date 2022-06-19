app.controller("Controller", function ($scope, $rootScope, $http) {
    app.Plat($scope, $http);
    $scope.rebind = function (key) {
        $scope.$broadcast("$$rebind::" + key);
    };
});
