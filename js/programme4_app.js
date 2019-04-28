var app = angular.module('programme4_app', []);
app.controller('mainController', function($scope) {


    get_all_users=function(){
        var answer;
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {

                answer = angular.fromJson(this.responseText);


                $scope.all_users = answer.users;
                $scope.users = {};
                $scope.users.user=null;
                $scope.users.search=null;
                $scope.users.auto_c=false;

                $scope.users.user2=null;
                $scope.users.search2=null;
                $scope.users.auto_c2=false;

                $scope.$apply();
            }
        };

        xhttp.open("GET", "php/get_all_users.php", true);
        xhttp.send();
    };

    $scope.fusion=function() {
        $scope.text = null;
        var answer;
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {

                answer = angular.fromJson(this.responseText);


                $scope.text = answer;
                $scope.users.user=null;
                $scope.users.search=null;
                $scope.users.auto_c=false;

                $scope.users.user2=null;
                $scope.users.search2=null;
                $scope.users.auto_c2=false;


                $scope.$apply();
            }
        };

        xhttp.open("POST", "php/fusion.php", true);
        xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhttp.send("id_user1=" + $scope.users.user.id + "&id_user2=" + $scope.users.user2.id);
    };

    get_all_users();

    
});
