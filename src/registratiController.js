var app = angular.module("myApp", []);
app.controller("registratiController", ['$scope', '$http', function ($scope, $http) {

    $scope.utente = "";
    $scope.password = "";
    $scope.utente2 = "";
    $scope.password2 = "";
    $scope.registrato = false;
    $scope.campivuoti = false;
    $scope.lunghi = false;
    $scope.corti = false;
    $scope.esistente = false;


    $scope.registrati = function () {
        $http.post('src/registratiHandler.php', {
            'function': 'registrati',
            'utente': $scope.utente,
            'password': $scope.password
        })
            .then(function (data, status, headers, config) {

                $scope.errore = data.data.errore;

                if($scope.errore == "REGISTRATO")
                {
                    $scope.registrato = true;
                    $scope.errore = "";
                }
                else {
                    $scope.registrato = false;
                }
                console.log(data);


               /* if (data.data.mancante == 'CAMPI MANCANTI') {
                    console.log("CAMPI MANCANTI");
                    //$scope.controlli = {};
                    $scope.utente = "";
                    $scope.password = "";
                    $scope.campivuoti = true;
                    $scope.registrato = false;
                    $scope.lunghi = false;
                    $scope.corti = false;
                    $scope.esistente = false;
                }

                else if (data.data.lunghi == 'CAMPI TROPPO LUNGHI') {
                    console.log("CAMPI TROPPO LUNGHI");
                    $scope.utente = "";
                    $scope.password = "";
                    $scope.lunghi = true;
                    $scope.registrato = false;
                    $scope.campivuoti = false;
                    $scope.corti = false;
                    $scope.esistente = false;

                }

                else if (data.data.corti == 'CAMPI TROPPO CORTI') {
                    console.log("CAMPI TROPPO CORTI");
                    $scope.utente = "";
                    $scope.password = "";
                    $scope.corti = true;
                    $scope.registrato = false;
                    $scope.campivuoti = false;
                    $scope.lunghi = false;
                    $scope.esistente = false;
                }

                else if (data.data.esistente == 'USER ESISTENTE') {
                    console.log("USER ESISTENTE");
                    $scope.utente = "";
                    $scope.password = "";
                    $scope.esistente = true;
                    $scope.registrato = false;
                    $scope.campivuoti = false;
                    $scope.lunghi = false;
                    $scope.corti = false;
                }

                else if (data.data.registrato == 'REGISTRATO') {
                    console.log("REGISTRATO");
                    $scope.utente = "";
                    $scope.password = "";
                    $scope.registrato = true;
                    $scope.campivuoti = false;
                    $scope.lunghi = false;
                    $scope.corti = false;
                    $scope.esistente = false;
                }*/


            });
    }
}
]);


