app.Plat = function ($scope, $http) {
    $scope.productPrice = {};
    $scope.getTotalPrice = function (option, quantity, product) {
        id = product.id;
        default_price = product.price;
        $scope.productPrice[id] = 0;
        if (!option.length && product.size.length == 0) {
            $scope.productPrice[id] = default_price;
        } else {
            for (const key in option) {
                $scope.productPrice[id] += option[key].price;
            }
        }
        $scope.productPrice[id] *= quantity;
        $scope.rebind("priceKey");
    };

    $scope.addToCart = function (item, option) {
        $http({
            url: "/cart/add/" + JSON.stringify(item) + "/" + option,
            method: "POST",
        }).then((response) => {
            console.log(response);
        });
    };
    $scope.subToCart = function (item, option) {
        $http({
            url: "/cart/sub/" + JSON.stringify(item) + "/" + option,
            method: "POST",
        }).then((response) => {
            console.log(response);
        });
    };
};
