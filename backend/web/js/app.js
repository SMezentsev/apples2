let app = angular.module('app', []);

app.controller('ApplesController', ['$scope', '$http', '$interval', function ($scope, $http, $interval) {

    $scope.trees = [];
    $scope.treesIntervals = [];
    $scope.treesCounters = [];

    //Отобразить все деревья
    $scope.updateTree = function (tree_id) {

        $http({method: 'GET', url: 'api/v1/tree/get-tree?tree_id=' + tree_id + '&' + Math.random(), data:{'tree_id':tree_id} }).then(function success(response) {

            if (response.data.status_code == '200') {

                angular.forEach(angular.copy($scope.trees), function(tree, key){
                    if(tree.id == tree_id) {
                        $scope.trees[key] = response.data.data.tree;
                    }
                });
            }
        }, function error(response) { console.log(response);});
    };

    //Кушаем яблоко
    $scope.eatApple = function (tree_id, apple_id) {
        $http({method: 'PUT', url: 'api/v1/tree/eat-apple?' + Math.random(),data:{'apple_id': apple_id}}).then(function success(response) {
            if (response.data.status_code == '200') {
                $scope.updateTree(tree_id);
            }
        }, function error(response) { console.log(response);});
    }

    //Удаляем яблоко
    $scope.deleteApple = function (tree_id, apple_id) {
        $http({method: 'DELETE', url: 'api/v1/tree/delete-apple?' + Math.random(),data:{'apple_id': apple_id}}).then(function success(response) {
            if (response.data.status_code == '200') {
                $scope.updateTree(tree_id);
            }
        }, function error(response) { console.log(response);});
    }

    $scope.setAppleCondition = function (tree_id, apple_id, condition_id) {
        $http({method: 'PUT', url: 'api/v1/tree/set-apple-condition?' + Math.random(),data:{'apple_id': apple_id, 'condition_id': condition_id}}).then(function success(response) {
            if (response.data.status_code == '200') {
                $scope.updateTree(tree_id);
            }
        }, function error(response) { console.log(response);});
    };

    //Роняем яблоко
    $scope.fellApple = function (tree_id, apple_id) {
        $http({method: 'PUT', url: 'api/v1/tree/fell-apple?' + Math.random(),data:{'apple_id': apple_id}}).then(function success(response) {
            if (response.data.status_code == '200') {
                $scope.updateTree(tree_id);
                treeIntervals(tree_id, apple_id);
            }
        }, function error(response) { console.log(response);});
    }

    //Добавляем дерево
    $scope.addTrees = function () {

        $http({method: 'POST', url: 'api/v1/tree/add?' + Math.random()}).then(function success(response) {
            $scope.trees.push(response.data.data.tree);
            $scope.treesIntervals[response.data.data.tree.id] = [];
            $scope.treesCounters[response.data.data.tree.id] = [];

            angular.forEach(response.data.data.tree.apples, function(apple, key){

                if(apple.position.name == 'На земле') {

                    console.log(response.data.data.tree.id)
                    treeIntervals(response.data.data.tree.id, apple.id);
                }
            });
        }, function error(response) { console.log(response);});
    };

    function treeIntervals(tree_id, apple_id) {

        if(typeof $scope.treesCounters[tree_id][apple_id] == 'undefined') {
            $.extend($scope.treesCounters[tree_id], {[apple_id]:20});
        }
        if(typeof $scope.treesIntervals[tree_id][apple_id] == 'undefined') {
            $.extend($scope.treesIntervals[tree_id], {[apple_id]:null});
        }

        $scope.treesIntervals[tree_id][apple_id] = $interval(function() {
            $scope.treesCounters[tree_id][apple_id] = $scope.treesCounters[tree_id][apple_id] - 1;
            if(!$scope.treesCounters[tree_id][apple_id]) {

                //меняем состояние яблока
                $scope.setAppleCondition(tree_id, apple_id, 2);
                $scope.updateTree(tree_id);
                //удаляем интервал
                $interval.cancel($scope.treesIntervals[tree_id][apple_id]);
            }
        }, 1000);
    }

    $('.body-content').css('visibility', 'visible');
}]);