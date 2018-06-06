var app = angular.module("myApp", []);
app.controller("profiloController", ['$scope', '$http', function ($scope, $http) {

    $scope.messaggi = [];
    $scope.user = "";
    $scope.nome = "";
    $scope.invioCorretto = false;
    $scope.invioNonCorretto = false;
    $scope.destinatario = "";
    $scope.destinatario2 = "";
    $scope.messaggio = "";
    $scope.inv = false;
    $scope.ric = false;
    $scope.amici = false;
    $scope.stringa = "ricevuti";
    $scope.errore = "";

    $http.post('src/profiloHandler.php', {'function': 'controllo'})
        .then(function (data, status, headers, config) {
            //console.log(data.data);
            if (data.data == false)
                window.location.href = "index.html";


            $scope.user = data.data;
            $scope.nome = $scope.user;
            /*$http.post('src/profiloHandler.php', {'function': 'stampa', 'user': $scope.user})
                .then(function (data, status, headers, config) {
                    console.log(data.data);
                    $scope.messaggi = data.data;

                });*/

            $scope.stampa($scope.stringa);



        });

    $scope.esci = function () {
        $http.post('src/profiloHandler.php', {'function': 'esci'})
            .then(function (data, status, headers, config) {
                window.location.href = "index.html";

            });
    }

    $scope.manda = function () {
        $http.post('src/profiloHandler.php', {
            'function': 'manda',
            'destinatario': $scope.destinatario,
            'messaggio': $scope.messaggio,
            'user': $scope.user
        })
            .then(function (data, status, headers, config) {

                console.log(data.data);
                /*if (data.data == false) {
                    $scope.amici = true;
                }*/
                $scope.errore = data.data.errore;

                if($scope.errore == 'Messaggio mandato') {
                    $scope.invioCorretto = true;
                    $scope.errore = "";
                }
                else  {
                    $scope.invioCorretto = false;
                }

                /* if (data.data == true) {
                    $scope.destinatario2 = $scope.destinatario;
                    $scope.destinatario = "";
                    $scope.messaggio = "";
                    $scope.invioCorretto = true;
                    $scope.invioNonCorretto = false;

                    /!*$scope.invioCorretto = true;
                    $scope.invioNonCorretto = false;*!/
                    //console.log("Messaggio mandato");
                }
                else if (!(data.data)) {
                    $scope.invioNonCorretto = true;
                    $scope.invioCorretto = false;
                }*/


            });


    }


    $scope.stampa = function (stringa) {
        //console.log(stringa);
        $http.post('src/profiloHandler.php', {'function': 'stampa', 'user': $scope.user, 'oggetto': stringa})
            .then(function (data, status, headers, config) {
                //console.log(data.data);
                $scope.messaggi = data.data;

                if (stringa == "ricevuti") {
                    $scope.ric = true;
                    $scope.inv = false;
                }

                else {
                    $scope.inv = true;
                    $scope.ric = false;
                }

            });
    }


}
]);