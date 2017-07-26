app.factory("userService", function ($http,CONFIG) {
  return{
    login: function (data) {
      var _serializedData = $.param({"reqmethod": 'login', "user_name":data.username,"password":data.password});
      var response = $http({
          method: 'POST',
          url: CONFIG.HTTP_HOST,
          data : _serializedData,
          headers: {
              'Content-Type': 'application/x-www-form-urlencoded'
          }
      });
      return response;
    },
    logout: function () {
      var response = $http({
          method: 'GET',
          url: CONFIG.HTTP_HOST+'?reqmethod=logout',
          headers: {
              'Accesstoken':localStorage.getItem('accessToken')
          }
      });
      return response;
    },
    checkPassword: function (data) {
      console.log(data);
      var _serializedData = $.param({"reqmethod": 'checkPassword', "token":data.token,"password":data.password});
      var response = $http({
          method: 'POST',
          url: CONFIG.HTTP_HOST,
          data : _serializedData,
          headers: {
              'Content-Type': 'application/x-www-form-urlencoded'
          }
      });
      return response;
    },
    changePassword: function (data) {
      console.log(data);
      var _serializedData = $.param({"reqmethod": 'changePassword', "token":data.token,"password":data.password});
      var response = $http({
          method: 'POST',
          url: CONFIG.HTTP_HOST,
          data : _serializedData,
          headers: {
              'Content-Type': 'application/x-www-form-urlencoded'
          }
      });
      return response;
    },
    addClient: function (data) {
      var _serializedData = $.param({"reqmethod": 'client', "client_data":data,"operation":'create'});
      var response = $http({
          method: 'POST',
          url: CONFIG.HTTP_HOST,
          data : _serializedData,
          headers: {
              'Content-Type': 'application/x-www-form-urlencoded',
              'Accesstoken':localStorage.getItem('accessToken')
          }
      });
      return response;
    },
    clientDetails : function(id){
      var response = $http({
          method: 'GET',
          url: CONFIG.HTTP_HOST+'?reqmethod=clientDetails&id='+id,
          headers: {
              'Accesstoken':localStorage.getItem('accessToken')
          }
      });
      return response;
    },
    updateClientDetails: function (data) {
      var _serializedData = $.param({"reqmethod": 'client', "client_data":data,"operation":'update'});
      var response = $http({
          method: 'POST',
          url: CONFIG.HTTP_HOST,
          data : _serializedData,
          headers: {
              'Content-Type': 'application/x-www-form-urlencoded',
              'Accesstoken':localStorage.getItem('accessToken')
          }
      });
      return response;
    },
    getClient: function () {
      var response = $http({
          method: 'GET',
          url: CONFIG.HTTP_HOST+'?reqmethod=client&operation=get',
          headers: {
              'Accesstoken':localStorage.getItem('accessToken')
          }
      });
      return response;
    },
    loadClientDetails: function (id) {
      var response = $http({
          method: 'GET',
          url: CONFIG.HTTP_HOST+'?reqmethod=client&operation=get&id='+id,
          headers: {
              'Accesstoken':localStorage.getItem('accessToken')
          }
      });
      return response;
    },
    deleteClient: function (id) {
      var response = $http({
          method: 'GET',
          url: CONFIG.HTTP_HOST+'?reqmethod=client&operation=delete&id='+id,
          headers: {
              'Accesstoken':localStorage.getItem('accessToken')
          }
      });
      return response;
    },
    addEnquiry: function (data) {
      var _serializedData = $.param({"reqmethod": 'enquiry', "enquiry_data":data,"operation":'create'});
      var response = $http({
          method: 'POST',
          url: CONFIG.HTTP_HOST,
          data : _serializedData,
          headers: {
              'Content-Type': 'application/x-www-form-urlencoded',
              'Accesstoken':localStorage.getItem('accessToken')
          }
      });
      return response;
    },
    getEnquiry: function () {
      var response = $http({
          method: 'GET',
          url: CONFIG.HTTP_HOST+'?reqmethod=enquiry&operation=get',
          headers: {
              'Accesstoken':localStorage.getItem('accessToken')
          }
      });
      return response;
    },
    enquiryDetails : function(id){
      var response = $http({
        method: 'GET',
        url: CONFIG.HTTP_HOST+'?reqmethod=enquiryDetails&id='+id,
        headers: {
            'Accesstoken':localStorage.getItem('accessToken')
        }
      });
      return response;
    },
    addQuotation: function (data) {
      var _serializedData = $.param({"reqmethod": 'createQuotation', "quot_data":data});
      var response = $http({
          method: 'POST',
          url: CONFIG.HTTP_HOST,
          data : _serializedData,
          headers: {
              'Content-Type': 'application/x-www-form-urlencoded',
              'Accesstoken':localStorage.getItem('accessToken')
          }
      });
      return response;
    },
    updateQuotation: function (data) {
      var _serializedData = $.param({"reqmethod": 'updateQuotation', "quot_data":data});
      var response = $http({
          method: 'POST',
          url: CONFIG.HTTP_HOST,
          data : _serializedData,
          headers: {
              'Content-Type': 'application/x-www-form-urlencoded',
              'Accesstoken':localStorage.getItem('accessToken')
          }
      });
      return response;
    },
    quotationList: function(){
      var response = $http({
        method: 'GET',
        url: CONFIG.HTTP_HOST+'?reqmethod=quotationList',
        headers: {
            'Accesstoken':localStorage.getItem('accessToken')
        }
      });
      return response;
    },
    quotationDetails: function(id){
      var response = $http({
        method: 'GET',
        url: CONFIG.HTTP_HOST+'?reqmethod=quotationDetails&id='+id,
        headers: {
            'Accesstoken':localStorage.getItem('accessToken')
        }
      });
      return response;
    },
    getQuotHisDetails: function(id){
      var response = $http({
        method: 'GET',
        url: CONFIG.HTTP_HOST+'?reqmethod=getQuotHistoryDetails&id='+id,
        headers: {
            'Accesstoken':localStorage.getItem('accessToken')
        }
      });
      return response;
    },
    deleteQuot : function(id){
      var response = $http({
          method: 'GET',
          url: CONFIG.HTTP_HOST+'?reqmethod=quotaionDelete&id='+id,
          headers: {
              'Accesstoken':localStorage.getItem('accessToken')
          }
      });
      return response;
    },
    addPurchaseOrder : function(data){
      var _serializedData = $.param({"reqmethod": 'createPurchaseOrder', "po_data":data});
      var response = $http({
          method: 'POST',
          url: CONFIG.HTTP_HOST,
          data : _serializedData,
          headers: {
              'Content-Type': 'application/x-www-form-urlencoded',
              'Accesstoken':localStorage.getItem('accessToken')
          }
      });
      return response;
    },
    getUser: function (id) {
      var response = $http.get(CONFIG.HTTP_HOST+"/?reqmethod=getUserById&id="+id);
      return response;
    },
    getUserDetails: function () {
      var response = $http({
        method: 'GET',
        url: CONFIG.HTTP_HOST+'?reqmethod=getUserDetails',
        headers: {
            'Accesstoken':localStorage.getItem('accessToken')
        }
      });
      return response
    },
    manageUser: function (data,option) {
      var _serializedData = $.param({"reqmethod": 'user',"operation":option, "user_data":data});
      var response = $http({
          method: 'POST',
          url: CONFIG.HTTP_HOST,
          data : _serializedData,
          headers: {
              'Content-Type': 'application/x-www-form-urlencoded'
          }
      });
      return response;
    },
    updateProfile: function (data) {
      var _serializedData = $.param({"reqmethod": 'updateProfile',"user_data":data});
      var response = $http({
          method: 'POST',
          url: CONFIG.HTTP_HOST,
          data : _serializedData,
          headers: {
              'Content-Type': 'application/x-www-form-urlencoded'
          }
      });
      return response;
    }
  }
});