var app = angular.module("angApp", ["ngRoute"]);
app.config(function($routeProvider){
	$routeProvider
		.when("/", {
			template : "<a href='#!git'>Git</a> | <a href='#!sms'>SMS</a>"
		})
		.when("/git", {
			templateUrl: "git.html",
			controller: "GitController"
		})
		.when("/sms", {
			templateUrl: "sms.html",
			controller: "SmsController"
		});
});
app.controller("GitController", function($scope, $http){
	$scope.checkGit = function(){
		$http.get("https://api.github.com/users/"+$scope.gitusername)
		.then(function(response) {
			$scope.gitresult = response.data;
			//$scope.$apply();
		}).catch(function(error){
			//console.log(error); //
		});
	}
})
.controller("SmsController", function($scope){
	$scope.typed = 0;
	$scope.total = 120;
	$scope.message = '';
	$scope.url = "http://coffeewithme.comze.com/sms/?uid=8012289528&pwd=navin21594&phone=|MOBILE|&msg=";
	$scope.countChars = function(e){
		$scope.typed = $scope.message.length;
		if($scope.typed >= $scope.total){
			e.preventDefault();
		}
	}
					
	function urlencode(str) {
		str = (str + '').toString();
		return encodeURIComponent(str)
			.replace(/!/g, '%21')
			.replace(/'/g, '%27')
			.replace(/\(/g, '%28')
			.replace(/\)/g, '%29')
			.replace(/\*/g, '%2A')
			.replace(/%20/g, '+');
	}
	
	$scope.createUrl = function(){
		if($scope.mobile && $scope.message){
			$scope.smsurl = $scope.url.replace('|MOBILE|', $scope.mobile);
			$scope.smsurl += urlencode($scope.message);
		}
	}
});