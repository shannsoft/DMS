app.controller("QuotController", function($scope, $rootScope, userService, $stateParams, Util, NgTableParams, $timeout, $uibModal, $state, UserModel) {
	/*******************************************************/
  	/*******THIS FUNCTION IS USED TO LOAD ENQUIRY DET*******/
  	/*******************************************************/

    $scope.currentDate = moment();
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
      $rootScope.showPreloader = true;
      $scope.enquiryDetails.quot_date = moment($scope.enquiryDetails.date).format('YYYY-MM-DD');
      angular.forEach($scope.enquiryDetails.itemList,function(item){
        item.exp_date = moment(item.date).format('YYYY-MM-DD');
      })
      userService.addQuotation($scope.enquiryDetails).then(function(response) {
        $rootScope.showPreloader = false;
        if(response.data.statusCode == 200){
          $state.go('quotation-list');
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
          $state.go('quotation-list');
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
          if(item.tax ){
            item.tax_price = (item.tot_price * (item.tax / 100));
            console.log(item.tax_price);
          }
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
        if(item.tax){
          item.tax_price = (item.tot_price * (item.tax / 100));
          console.log(item.tax_price);
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
          $state.go('quotation-list');
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
    $scope.loadPoList = function(){
      $rootScope.showPreloader = true;
      userService.getPoList().then(function(response){
        $rootScope.showPreloader = false;
        $scope.po_list = response.data.data;
        $scope.tablePo = new NgTableParams();
        $scope.tablePo.settings({
            dataset: $scope.po_list
        })
      },function(error){
        $rootScope.showPreloader = false;
      });
    }
    $scope.loadPoDetails = function(){
      $rootScope.showPreloader = true;
      var po_id = $stateParams.poId;
      userService.getPoDetails(po_id).then(function(response){
        $rootScope.showPreloader = false;
        $scope.poDetails = response.data.data;
        $scope.tablePo = new NgTableParams();
        $scope.tablePo.settings({
            dataset: $scope.poDetails.itemList
        })
      },function(error){
        $rootScope.showPreloader = false;
      });
    }
    /***************************************************************/
    /**********This is use to open the select item modal************/
    /***************************************************************/
    $scope.openSelectModal = function(po_list){
      $scope.modalInstance = $uibModal.open({
        animation: true,
        templateUrl: 'views/modals/select-po-modal.html',
        controller: 'BillModalCtrl',
        size: 'sm',
        resolve: {
          po_list: function () {
            return $scope.poDetails.itemList;
          },
          po_id : function(){
            return $stateParams.poId;
          }
        }
      })
    }
    /***************************************************************/
    /*******This is use to get selected po list for bill************/
    /***************************************************************/
    $scope.loadSelectedPo = function(){
      $rootScope.showPreloader = true;
      var po_id = $stateParams.poId;
      userService.getPoDetails(po_id).then(function(response){
        $rootScope.showPreloader = false;
        $scope.poDetails = response.data.data;
        $scope.selected_item = UserModel.getSelectedItem();
      },function(error){
        $rootScope.showPreloader = false;
      });
    }
    $scope.itemChanged = function(){
      $scope.selected_po = [];
      angular.forEach($scope.selected_item,function(value){
          angular.forEach($scope.poDetails.itemList,function(item){
            if(value == item.po_sl_no){
              $scope.selected_po.push(item);
            }
          })
      })
      $scope.priceCalculation();
    }
    $scope.priceCalculation = function(){
      $scope.taxable_amount = 0;
      $scope.tax_amount = 0;
      angular.forEach( $scope.selected_po,function(item){
        $scope.taxable_amount += item.net_amount;
        $scope.tax_amount += item.tax_price;
      })
      $scope.rounded_amount = $scope.taxable_amount + $scope.tax_amount;
    }
    $scope.$watch('selected_item', function () {
      $scope.itemChanged(); 
    });
    $scope.billCalculation = function(item){
      if(item.quantity){
        item.net_amount = item.quantity * item.price;
        if(item.discount){
          item.net_amount = item.net_amount - (item.net_amount * (item.discount / 100));
        }
        if(item.tax){
          item.tax_price = (item.net_amount * (item.tax / 100));
          console.log(item.tax_price);
        }
      }
      else {
        item.tot_price = 0;
      }
      $scope.priceCalculation();
    }
    $scope.getVolumeSum = function(items,type) {
      if(type == 'amount'){
        return items
        .map(function(x) { return x.net_amount; })
        .reduce(function(a, b) { return a + b; });
      }
      else if(type == 'tax'){
        return items
        .map(function(x) { return x.tax_price/2; })
        .reduce(function(a, b) {
         return a+b; 
        });
      }
    };

    /***************************************************************/
    /*******This is use print the cover page document***************/
    /***************************************************************/
    $scope.printBill  = function(div){
      var docHead = document.head.outerHTML;
      // document.getElementById('document-title').innerHTML = 'Odisha-eMunicipality';
      console.log(document.getElementById('header'));
      var printContents = document.getElementById(div).outerHTML;
      var winAttr = "location=yes, statusbar=no, menubar=no, titlebar=no, toolbar=no,dependent=no, width=900, height=670, resizable=yes, screenX=200, screenY=200, personalbar=no, scrollbars=yes";
      var newWin = window.open("", "_blank", winAttr);
      var writeDoc = newWin.document;
      writeDoc.open();
      writeDoc.write('<!doctype html><html>' + docHead + '<body onLoad="window.print()">' + printContents + '</body></html>');
      writeDoc.close();
      newWin.focus();
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
app.controller('BillModalCtrl', function ($scope, $uibModalInstance, $localStorage, $state, po_list, UserModel, po_id) {
    $scope.po_list = po_list;
    $scope.ok = function () {
        console.log($scope.selected_po);
        UserModel.setSelectedItem($scope.selected_po);
        $state.go('bill', {poId: po_id});

        $uibModalInstance.close();
    };
    $scope.cancel = function () {
        $uibModalInstance.dismiss('cancel');
    };
});