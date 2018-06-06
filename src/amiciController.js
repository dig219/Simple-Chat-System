var app = angular.module("myApp", []);
app.controller("amiciController", ['$scope', '$http', function ($scope, $http) {

    $scope.nome = "";
    $scope.utente = "";
    $scope.errore = "";
    $scope.list = [];
    $scope.req = [];
    $scope.fr = "";
    $scope.mandata = false;
    $scope.check;
    $scope.richiesta = "accetta";

    $http.post('src/amiciHandler.php', {'function': 'controllo'})
        .then(function (data, status, headers, config) {
            //console.log(data.data);
            if (data.data == false)
                window.location.href = "index.html";

            $scope.user = data.data;
            $scope.nome = $scope.user;
            $scope.richieste($scope.nome);
            $scope.lista($scope.nome);
            //console.log(data.data);

        });

    $scope.cerca = function () {
        $http.post('src/amiciHandler.php', {'function': 'cerca', 'utente': $scope.utente, 'nome': $scope.nome})
            .then(function (data, status, headers, config) {
                $scope.errore = data.data.err;
                if ($scope.errore == 'RICHIESTA MANDATA') {
                    $scope.mandata = true;
                    $scope.errore = "";
                }
                else {
                    $scope.mandata = false;

                }


            });
    }

    $scope.accetta = function (index,mit) {
        $http.post('src/amiciHandler.php', {
            'function': 'accetta',
            'utente': $scope.utente,
            'nome': $scope.nome,
            'index': index,
            'mit' : mit,
            'nome' : $scope.nome,

        })
            .then(function (data, status, headers, config) {
                console.log(data.data);

            });
    }

    $scope.rifiuta = function (index,mit) {
        $http.post('src/amiciHandler.php', {
            'function': 'rifiuta',
            'utente': $scope.utente,
            'nome': $scope.nome,
            'index': index,
            'mit' : mit,
            'nome' : $scope.nome,

        })
            .then(function (data, status, headers, config) {
                console.log(data.data);

            });
    }

    $scope.elimina = function (index,mit) {
        $http.post('src/amiciHandler.php', {
            'function': 'elimina',
            'utente': $scope.utente,
            'nome': $scope.nome,
            'index': index,
            'mit' : mit,
            'nome' : $scope.nome,

        })
            .then(function (data, status, headers, config) {
                console.log(data.data);

            });
    }

    $scope.richieste = function (stringa) {
        $http.post('src/amiciHandler.php', {'function': 'richieste', 'nome': $scope.nome})
            .then(function (data, status, headers, config) {
                $scope.req = data.data;
                console.log(data.data);

            });
    }

    $scope.lista = function () {
        $http.post('src/amiciHandler.php', {'function': 'lista', 'nome': $scope.nome})
            .then(function (data, status, headers, config) {
                $scope.list = data.data;
                //console.log(data.data);

            });
    }

    $scope.esci = function () {
        $http.post('src/amiciHandler.php', {'function': 'esci'})
            .then(function (data, status, headers, config) {
                window.location.href = "index.html";

            });
    }
}]);