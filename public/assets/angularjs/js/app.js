// var app = angular.module('AuctionRecords', [])
//         .constant('API_URL', 'http://192.168.10.10/api/auction/');



var app = angular.module("AuctionRecords", ['ngRoute']).config(function($routeProvider){

    $routeProvider.when('/', {
        templateUrl: 'index.php',
        controller: 'auctionController'
    });

    $routeProvider.otherwise({redirectTo : '/index'}); 
});
