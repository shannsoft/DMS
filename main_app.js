var app = angular.module("doc_app", ['ui.router', 'ui.bootstrap', 'ngResource', 'ngStorage', 'ngAnimate','datePicker','ngTable']);
app.config(function($stateProvider, $urlRouterProvider) {
  $urlRouterProvider.otherwise('/login');
  $stateProvider
  .state('login', {
      templateUrl: 'views/login.html',
      url: '/login',
      resolve: {
        loggedout: checkLoggedin
      }
  })
  .state('dashboard', {
    templateUrl: 'views/dashboard.html',
    url: '/dashboard',
    resolve: {
      loggedout: checkLoggedout
    }
  })
  .state('form', {
    templateUrl: 'views/form.html',
    url: '/form',
  })
  .state('client', {
    templateUrl: 'views/clients/client.html',
    url: '/client',
    controller:'ClientController',
    resolve: {
      loggedout: checkLoggedout
    }
  })
  .state('client-details', {
    templateUrl: 'views/clients/client-details.html',
    url: '/client-details/:clientId',
    controller:'ClientController',
    resolve: {
      loggedout: checkLoggedout
    }
  })
  .state('client-edit', {
    templateUrl: 'views/clients/client-edit.html',
    url: '/client-edit/:clientId',
    controller:'ClientController',
    resolve: {
      loggedout: checkLoggedout
    }
  })
  .state('new-client', {
    templateUrl: 'views/clients/new-client.html',
    url: '/new-client',
    controller:'ClientController',
    resolve: {
      loggedout: checkLoggedout
    }
  })
  .state('enquiry', {
    templateUrl: 'views/enquiry/enquiry.html',
    url: '/enquiry',
    controller:'ClientController',
    resolve: {
      loggedout: checkLoggedout
    }
  })
  .state('new-enquiry', {
    templateUrl: 'views/enquiry/new-enquiry.html',
    url: '/new-enquiry',
    controller:'ClientController',
    resolve: {
      loggedout: checkLoggedout
    }
  })
  .state('new-quotation', {
    templateUrl: 'views/quotation/new-quotation.html',
    url: '/new-quotation/:enqId',
    controller:'QuotController',
    resolve: {
      loggedout: checkLoggedout
    }
  })
  .state('quotation-list', {
    templateUrl: 'views/quotation/quotation-list.html',
    url: '/quotation-list',
    controller:'QuotController',
    resolve: {
      loggedout: checkLoggedout
    }
  })
  .state('quotation-details', {
    templateUrl: 'views/quotation/quotation-details.html',
    url: '/quotation-details/:quotId',
    controller:'QuotController',
    resolve: {
      loggedout: checkLoggedout
    }
  })
  .state('history-details', {
    templateUrl: 'views/quotation/history-details.html',
    url: '/history-details/:historyId',
    controller:'QuotController',
    resolve: {
      loggedout: checkLoggedout
    }
  })
  .state('quotation-update', {
    templateUrl: 'views/quotation/quotation-update.html',
    url: '/quotation-update/:quotId',
    controller:'QuotController',
    resolve: {
      loggedout: checkLoggedout
    }
  })
  .state('purchase-order', {
    templateUrl: 'views/purchaseOrder/new-po.html',
    url: '/purchase-order/:quotId',
    controller:'QuotController',
    resolve: {
      loggedout: checkLoggedout
    }
  })

  function checkLoggedout($q, $timeout, $rootScope, $state, $localStorage) {
    var deferred = $q.defer();
    accessToken = localStorage.getItem('accessToken')
    $timeout(function(){
      if(accessToken){
        deferred.resolve();
      }
      else{
        deferred.resolve();
        $state.go('login');
      }
    },100)
  }
  function checkLoggedin($q, $timeout, $rootScope, $state, $localStorage) {
    var deferred = $q.defer();
    accessToken = localStorage.getItem('accessToken')
    $timeout(function(){
      if(accessToken){
        deferred.resolve();
        $state.go('dashboard');
      }
      else{
        deferred.resolve();
      }
    },100)
  }
});
app.constant('CONFIG', {
  'HTTP_HOST': 'server/api.php'
})
app.run(function($http,$rootScope,$localStorage,$timeout){
  $rootScope.$on('$stateChangeSuccess', function(event, toState, toParams, fromState, fromParams){
    $rootScope.stateName = toState.name;
  })
});
app.factory('Util', ['$rootScope',  '$timeout' , function( $rootScope, $timeout){
    var Util = {};
    $rootScope.alerts =[];
    Util.alertMessage = function(msgType, message){
        var alert = { type:msgType , msg: message };
        $rootScope.alerts.push( alert );
        $timeout(function(){
            $rootScope.alerts.splice($rootScope.alerts.indexOf(alert), 1);
        }, 5000);
    };
    return Util;
}]);

app.directive('fileModel', ['$parse', function ($parse) {
    return {
       restrict: 'A',
       link: function(scope, element, attrs) {
          var model = $parse(attrs.fileModel);
          var modelSetter = model.assign;

          element.bind('change', function(){
             scope.$apply(function(){
                modelSetter(scope, element[0].files[0]);
             });
          });
       }
    };
 }]);
 app.filter('getShortName', function () {
     return function (value) {
       if(value){
         var temp = angular.copy(value);
         temp = temp.split(" ");
         temp = temp[0].charAt(0)+temp[temp.length-1].charAt(0);
         return temp.toUpperCase();
       }
     };
 });
