app.controller("QuotController", function($scope,$rootScope,userService,$stateParams,Util,NgTableParams,$timeout,$uibModal) {
	/*******************************************************/
  	/*******THIS FUNCTION IS USED TO LOAD ENQUIRY DET*******/
  	/*******************************************************/
  	$scope.loadEnqDetails = function(){
  		$rootScope.showPreloader = true;
  		userService.enquiryDetails($stateParams.enqId).then(function(response) {
  			if(response.data.statusCode == 200){
  				$rootScope.showPreloader = false;
  				$scope.enquiryDetails = response.data.data;
  				console.log($scope.enquiryDetails)
  			}
  		},function(error){
  			$rootScope.showPreloader = false;
  		})
  	}
  	/*******************************************************/
    /******THIS FUNCTION IS USED TO ADD QUOTATION DET*******/
    /*******************************************************/
    $scope.addQuation = function(){
      console.log($scope.enquiryDetails);
      $rootScope.showPreloader = true;
      $scope.enquiryDetails.quot_date = moment($scope.enquiryDetails.date).format('YYYY-MM-DD');
      angular.forEach($scope.enquiryDetails.itemList,function(item){
        item.exp_date = moment(item.date).format('YYYY-MM-DD');
      })
      userService.addQuotation($scope.enquiryDetails).then(function(response) {
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
  	/******THIS FUNCTION IS USED TO ADD QUOTATION DET*******/
  	/*******************************************************/
  	$scope.updateQuation = function(){
      $rootScope.showPreloader = true;
  		$scope.quotaionDetails.quot_date = moment($scope.quotaionDetails.date).format('YYYY-MM-DD');
  		angular.forEach($scope.quotaionDetails.itemList,function(item){
  			item.valid_upto = moment(item.date).format('YYYY-MM-DD');
  		})
  		userService.updateQuotation($scope.quotaionDetails).then(function(response) {
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
    /******THIS FUNCTION IS USED TO ADD QUOTATION DET*******/
    /*******************************************************/
    $scope.getQuotationList = function(){
      $rootScope.showPreloader = true;
      userService.quotationList($scope.enquiryDetails).then(function(response) {
        $rootScope.showPreloader = false;
        if(response.data.statusCode == 200){
          $scope.quotaionlist = response.data.data;
          $scope.tableParams = new NgTableParams();
          $scope.tableParams.settings({
              dataset: $scope.quotaionlist
          })
        }
      },function(error){
        $rootScope.showPreloader = false;
      })
    }
    /*******************************************************/
    /******THIS FUNCTION IS USED TO GET THE QUOT DET********/
    /*******************************************************/
    $scope.loadQuotDetails = function(){
      var quot_id = $stateParams.quotId;
      $rootScope.showPreloader = true;
      userService.quotationDetails(quot_id).then(function(response) {
        $rootScope.showPreloader = false;
        if(response.data.statusCode == 200){
          $scope.quotaionDetails = response.data.data;  
          $scope.tableQuot = new NgTableParams();
          $scope.tableQuot.settings({
              dataset: $scope.quotaionDetails.itemList
          })
        }
      },function(error){
        $rootScope.showPreloader = false;
      })
    }
    $scope.calculatePrice = function(item){
      $timeout(function(){
        if (item.quantity) {
          item.tot_price = item.quantity*item.price;
          item.tot_price = item.tot_price - (item.tot_price*(item.discount/100));
        }
        else{
          item.tot_price = 0.00;
        }
      },200)
    }
    $scope.calculateQuotPrice = function(item){
      if(item.price){
        item.tot_price = item.quantity*item.price;
        if(item.discount){
          item.tot_price = item.tot_price - (item.tot_price * (item.discount / 100));
        }
      }
      else {
        item.tot_price = 0;
      }
    }
    $scope.addPurchaseOrder = function(){
      $scope.quotaionDetails.po_date = moment($scope.quotaionDetails.date).format('YYYY-MM-DD');
      angular.forEach($scope.quotaionDetails.itemList,function(item){
        item.delivery_date = moment(item.date).format('YYYY-MM-DD')
      })
      $rootScope.showPreloader = true;
      userService.addPurchaseOrder($scope.quotaionDetails).then(function(response){
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
    /*******THIS FUNCTION IS USED OPEN DELETE MODAL*********/
    /*******************************************************/
    $scope.openQuotationDelete = function(quotaion){
      $scope.modalInstance = $uibModal.open({
       animation: true,
       templateUrl: 'views/modals/quot-delete-modal.html',
       controller: 'QuotModalCtrl',
       size: 'sm',
       resolve: {
         deleteQuotation: function () {
           return $scope.deleteQuotation;
         },
         quotID:function () {
           return quotaion.quot_id;
         }
       }
      })
    }
    $scope.deleteQuotation = function(quotID){
      $rootScope.showPreloader = true;
      userService.deleteQuot(quotID).then(function(response){
        $rootScope.showPreloader = false;
        if(response.data.statusCode == 200){
          Util.alertMessage('success',response.data.message);
          $scope.getQuotationList();
        }
        else{
          Util.alertMessage('danger',response.data.message);
        }
      },function(error){
        $rootScope.showPreloader = false;
      })
    }
    $scope.loadQuotHistoryDetails = function(){
      var his_id = $stateParams.historyId;
      $rootScope.showPreloader = true;
      userService.getQuotHisDetails(his_id).then(function(response){
        $rootScope.showPreloader = false;
        if(response.data.statusCode = 200){
          $scope.quotaionHistory = response.data.data;  
          $scope.historyQuot = new NgTableParams();
          $scope.historyQuot.settings({
              dataset: $scope.quotaionHistory.itemList
          })
        }
      })
    }
})
app.controller('QuotModalCtrl', function ($scope, $uibModalInstance,deleteQuotation,quotID) {
    $scope.ok = function () {
        deleteQuotation(quotID);
        $uibModalInstance.close();
    };
    $scope.cancel = function () {
        $uibModalInstance.dismiss('cancel');
    };
});