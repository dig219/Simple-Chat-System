var app = angular.module("myApp", []);
app.controller("indexController", ['$scope', '$http', function ($scope, $http) {

    $scope.user = "";
    $scope.pwd = "";
    $scope.user2 = "";
    $scope.pwd2 = "";
    $scope.errore = false;
    $scope.erroreLogin = false;
    $scope.registrato = false;
    $scope.noregistrato = false;

    $scope.login = function () {
        $http.post('src/indexHandler.php', {'function': 'login', 'user': $scope.user, 'pwd': $scope.pwd})
            .then(function (data, status, headers, config) {
                //console.log(data.data);
                if (data.data.status == 'KO')
                    $scope.errore = true;
                else if (data.data.loggato == 'NO') {
                    $scope.user = "";
                    $scope.pwd = "";
                    $scope.erroreLogin = true;
                }

                else
                    window.location.href = "profilo.html";
            });
    }

    $scope.registrati = function () {
        $http.post('src/indexHandler.php', {'function': 'registrati', 'user2': $scope.user2, 'pwd2': $scope.pwd2})
            .then(function (data, status, headers, config) {
                /*console.log(data.data);
                console.log(data.data.status);*/
                if(data.data.status)
                    $scope.registrato = true;
                else
                    $scope.noregistrato = true;

            });
    }

}]);