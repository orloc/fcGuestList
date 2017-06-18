'use strict';

angular.module('admin', [])
.controller('listCtrl', [ '$scope', function($scope){
    $scope.show_edit = false;
    
    $scope.toggleNew = function(){
        $scope.show_edit = !$scope.show_edit;
    };
    
}]);