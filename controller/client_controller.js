app.controller('ClientController',function($scope,$rootScope,userService,Util,NgTableParams,$uibModal,$stateParams){
  $scope.client = {};
	$scope.enq = {};
  $scope.enq.itemList = [
    {
      'name':'',
      'qua':'',
      'uom':''
    }
  ]
  $scope.enq.date = new Date(); 
	/*******************************************************/
  	/***********THIS FUNCTION IS USED TO ADD CLIENT*********/
  	/*******************************************************/
  	$scope.addClient = function() {
  		$rootScope.showPreloader = true;
  		userService.addClient($scope.client).then(function(response){
  			$rootScope.showPreloader = false;
  			if(response.data.statusCode == 200){
  				Util.alertMessage('success',response.data.message);
  			}
  			else{
  				Util.alertMessage('danger',response.data.message);
  			}
  		},function(error){
  			$rootScope.showPreloader = false;
  		})
  	}
  	/*******************************************************/
    /***********THIS FUNCTION IS USED TO ADD CLIENT*********/
    /*******************************************************/
    $scope.getClientList = function(){
      $rootScope.showPreloader = true;
      userService.getClient().then(function(response){
        $rootScope.showPreloader = false;
        if(response.data.statusCode == 200){
          $scope.clientList = response.data.data;
          $scope.tableParams = new NgTableParams();
          $scope.tableParams.settings({
              dataset: $scope.clientList
          })
        }
      },function(error){
        $rootScope.showPreloader = false;
      })
    }
    $scope.clientDetails = function(){

    }
    $scope.addItem = function(){
      var obj = {name:'',qua:'',uom:''};
      $scope.enq.itemList.push(obj);
    }
    $scope.removeItem = function(index){
      $scope.enq.itemList.splice(index,1);
    }
    /*******************************************************/
    /******THIS FUNCTION IS USED TO GET CLIENT DETAILS******/
    /*******************************************************/
    $scope.loadClientDetails = function(){
      var client_id = $stateParams.clientId;
      $rootScope.showPreloader = true;
      userService.clientDetails(client_id).then(function(response){
        $rootScope.showPreloader = false;
        if(response.data.statusCode = 200){
          $scope.client = response.data.data;
        }
      })
    }
    /*******************************************************/
    /*****THIS FUNCTION IS USED TO UPDATE CLIENT DETAILS****/
    /*******************************************************/
    $scope.updateClientDetails = function(){
      $rootScope.showPreloader = true;
      userService.updateClientDetails($scope.client).then(function(response){
        $rootScope.showPreloader = false;
        if(response.data.statusCode == 200){
          Util.alertMessage('success',response.data.message);
        }
        else{
          Util.alertMessage('danger',response.data.message);
        }
      },function(error){
        $rootScope.showPreloader = false; 
      })
    }
    /*******************************************************/
    /**********THIS FUNCTION IS USED TO ADD ENQUIRY*********/
    /*******************************************************/
    $scope.addEnquiry = function(){
      if(moment($scope.enq.date).isAfter($scope.enq.due_date)){
        Util.alertMessage('danger',"Due date should not be less than current date ");
      }
      else{
        $rootScope.showPreloader = true;
        $scope.enq.cur_date = moment($scope.enq.date).format('YYYY-MM-DD');
        $scope.enq.cur_due_date = moment($scope.enq.due_date).format('YYYY-MM-DD');
        userService.addEnquiry($scope.enq).then(function(response){
          $rootScope.showPreloader = false;
          if(response.data.statusCode == 200){
            Util.alertMessage('success',response.data.message);
          }
          else{
            Util.alertMessage('danger',response.data.message);
          }
        },function(error){
          $rootScope.showPreloader = false;
        })
      }
    }
    /*******************************************************/
    /***********THIS FUNCTION IS USED TO ADD CLIENT*********/
    /*******************************************************/
    $scope.loadEnquiry = function(){
      $rootScope.showPreloader = true;
      userService.getEnquiry().then(function(response){
        $rootScope.showPreloader = false;
        if(response.data.statusCode == 200){
          $scope.enqList = response.data.data;
          $scope.tableParams = new NgTableParams();
          $scope.tableParams.settings({
              dataset: $scope.enqList
          })
        }
      },function(error){
        $rootScope.showPreloader = false;
      })
    }
    /*******************************************************/
    /*******THIS FUNCTION IS USED OPEN DELETE MODAL*********/
    /*******************************************************/
    $scope.openClientDelete = function(clientID,index){
      $scope.modalInstance = $uibModal.open({
       animation: true,
       templateUrl: 'views/modals/client-delete-modal.html',
       controller: 'ClientModalCtrl',
       size: 'sm',
       resolve: {
         deleteClient: function () {
           return $scope.deleteClient;
         },
         clientID:function () {
           return clientID;
         }
       }
      })
    }
    $scope.deleteClient = function(clientID){
      console.log(clientID);
      userService.deleteClient(clientID).then(function(response){
        $rootScope.showPreloader = false;
        if(response.data.statusCode == 200){
          Util.alertMessage('success',response.data.message);
          $scope.getClientList(); 
        }
        else{
          Util.alertMessage('danger',response.data.message);
        }
      },function(error){
        $rootScope.showPreloader = false;
      })
    }
})

app.controller('ClientModalCtrl', function ($scope, $uibModalInstance,deleteClient,clientID) {
    $scope.ok = function () {
        deleteClient(clientID);
        $uibModalInstance.close();
    };
    $scope.cancel = function () {
        $uibModalInstance.dismiss('cancel');
    };
});