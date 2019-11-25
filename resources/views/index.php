<!DOCTYPE html>
<html lang="en-US" ng-app="AuctionRecords">
    <head>
        <title>Laravel 5 AngularJS CRUD Example</title>

        <!-- Load Bootstrap CSS -->

    </head>
    <body>
    
        <h2>Employees Database</h2>
        <div  ng-controller="auctionController">
            
            <!-- Table-to-load-the-data Part -->
            <table class="table">
                <tbody>
                    <tr ng-repeat="item in items">
                        <td>{{ item.id }}</td>
                        <td>{{ item.name }}</td>


                    </tr>
                </tbody>
            </table>
            <!-- End of Table-to-load-the-data Part -->
            <!-- Modal (Pop up when detail button clicked) -->

        </div>

        <!-- Load Javascript Libraries (AngularJS, JQuery, Bootstrap) -->
        <script src="<?= asset('assets/angularjs/lib/angular.min.js') ?>"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.0/angular-route.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.0/angular-resource.min.js"></script>
        
        
        <!-- AngularJS Application Scripts -->
        <script src="<?= asset('assets/angularjs/js/app.js') ?>"></script>
        <script src="<?= asset('assets/angularjs/js/controllers/auction.js') ?>"></script>
    </body>
</html>