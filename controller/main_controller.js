app.controller("Main_Controller",function($scope,$rootScope,$state,$localStorage,userService,Util){
  /*******************************************************/
  /*************This is use for check user login**********/
  /*******************************************************/
  // $scope.getUseDetails();
  $scope.getUseDetails = function(){
    if(localStorage.getItem('accessToken')){
      $scope.is_loggedin = true;
      // $rootScope.user_type = localStorage.getItem('userType');
      // userService.getUserDetails(localStorage.getItem('accessToken')).then(function(pRes) {
      //     if(pRes.status == 200){
      //       $scope.profile = pRes.data.data;
      //       $scope.profile.name = $scope.profile.first_name+' '+$scope.profile.last_name;
      //       console.log($scope.profile);
      //     }
      //   },function(err) {
      //   console.log(">>>>>>>>>>>>>   ",err);
      // })
    }
    else{
      $scope.is_loggedin = false;
    }
  }
  /*******************************************************/
  /*************This is use for user login****************/
  /*******************************************************/
  $scope.signIn = function(user){
    userService.login(user).then(function(pRes) {
      if(pRes.data.statusCode == 200){
        $scope.is_loggedin = true;
        localStorage.setItem('accessToken',pRes.data.data.token);
        $scope.getUseDetails();
        $state.go("dashboard");
      }
      else{
          Util.alertMessage('danger', pRes.data.message);
      }
    },
    function(err) {
      console.log(">>>>>>>>>>>>>   ",err);
    })
  }
  /*******************************************************/
  /*************This is use for user signout**************/
  /*******************************************************/
  $scope.signOut = function(){
    userService.logout().then(function(pRes) {
      if(pRes.status == 200){
        $scope.is_loggedin = false;
        localStorage.setItem('accessToken','');
        $state.go("login");
      }
    },
    function(err) {
      console.log(">>>>>>>>>>>>>   ",err);
    })
  }
});
