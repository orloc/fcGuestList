'use strict';

angular.module('admin', [])
.controller('listCtrl', [ '$scope', function($scope){
    $scope.exists = false;
    $scope.not_exists = false;
    $scope.show_edit = false;
    
    $scope.open_edit_box = false;
    
    $scope.currently_editing = {};
    
    var exists = window.location.search
        .split('&')
        .filter(function(i) {
            return i.indexOf('exists') >= 0;
        }).pop();

    var notexists = window.location.search
        .split('&')
        .filter(function(i) {
            return i.indexOf('notExists') >= 0;
        }).pop();
    
    var vals = exists ? exists.split('=') : 0;
    var vals2 = notexists ? notexists.split('=') : 0;
    
    if (vals.length && Boolean(vals[1]) === true) {
        $scope.exists = true;
        $scope.show_edit = true;
    }

    if (vals2.length && Boolean(vals[1]) === true) {
        $scope.not_exists = true;
    }

    angular.element('.delete').on('click',function($e){
        if (!confirm('Are you sure you want to delete this item?')) {
            $e.preventDefault();
            $e.stopPropagation();
        }
    });
    
    $scope.closeEdit = function(){
        $scope.currently_editing = {};
        $scope.open_edit_box = false;
        $scope.show_edit = false;
    };
    
    $scope.openEdit = function(item){
        $scope.currently_editing = item;
        $scope.open_edit_box = true;
        $scope.show_edit = false;
    };
    
    $scope.toggleNew = function(){
        $scope.show_edit = !$scope.show_edit;
        $scope.open_edit_box = false;
    };
    
}]);