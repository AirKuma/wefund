app.controller('auctionController', function($scope, $http, API_URL) {
    $http.get("http://192.168.10.10/api/auction/admin")
            .success(function(response) {      
        	$scope.items = response; 
            });

});