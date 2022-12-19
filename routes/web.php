<?php

Route::get('/', function () {
    if (Auth::check()) {
        return redirect('dashboard');
    } else {
        return view('auth.login');
    }
});
Auth::routes();
//global $prefix;
$prefix = env('PREFIX');
//exit;
Route::group(['middleware' => 'auth'], function () use($prefix) {

    Route::get('/dashboard', 'Admin\DashboardController@index')->name('dashboard');
    Route::post('dashboard/productPricingView', 'Admin\DashboardController@productPricingView');
    Route::post('dashboard/getProductPricing', 'Admin\DashboardController@getProductPricing');
    Route::get('dashboard/productPricingPrintpdf', 'Admin\DashboardController@productPricingPrintpdf');
    Route::post('dashboard/getAuthorizedUserBuyer', 'Admin\DashboardController@getAuthorizedUserBuyer');
    Route::post('dashboard/getAuthorizedUserBuyerByName', 'Admin\DashboardController@getAuthorizedUserBuyerByName');
    Route::post('dashboard/getAllAuthorizedUserBuyer', 'Admin\DashboardController@getAllAuthorizedUserBuyer');
    Route::post('dashboard/getAuthorizedUserProduct', 'Admin\DashboardController@getAuthorizedUserProduct');
    Route::post('dashboard/getAuthorizedUserBrand', 'Admin\DashboardController@getAuthorizedUserBrand');
    Route::post('dashboard/getSalesPersons', 'Admin\DashboardController@getSalesPersons');
    Route::post('dashboard/pendingForLc', 'Admin\DashboardController@pendingForLc');
    Route::post('dashboard/pendingForShipment', 'Admin\DashboardController@pendingForShipment');
    Route::post('dashboard/getPartiallyShipped', 'Admin\DashboardController@getPartiallyShipped');
    Route::post('dashboard/waitingTrackingNo', 'Admin\DashboardController@waitingTrackingNo');
    Route::post('dashboard/getEtsEtaInfo', 'Admin\DashboardController@getEtsEtaInfo');
    Route::post('dashboard/getEtsSummary', 'Admin\DashboardController@getEtsSummary');
    Route::post('dashboard/getEtaSummary', 'Admin\DashboardController@getEtaSummary');
    Route::post('dashboard/getInquirySummary', 'Admin\DashboardController@getInquirySummary');
    Route::post('dashboard/showProductPricing', 'Admin\DashboardController@showProductPricing');
    Route::post('dashboard/getProductPricingSetup', 'Admin\DashboardController@getProductPricingSetup');
    Route::post('dashboard/setProductPricing', 'Admin\DashboardController@setProductPricing');
    Route::post('dashboard/getCrmOpportunityList', 'Admin\DashboardController@getCrmOpportunityList');
    Route::post('dashboard/updateTrackingNo', 'Admin\DashboardController@updateTrackingNo');
    Route::post('dashboard/getProductCatalog', 'Admin\DashboardController@getProductCatalog');

    //*********************** Start :: Default Service **********************//
    //go to confirmed or accomplished order page
//    Route::post('defaultService/getConfirmedOrAccomplishedRedirect', 'Admin\DefaultServiceController@getConfirmedOrAccomplishedRedirect');
    //*********************** End :: Default Service **********************//
    //setRecordPerPage
    Route::post('setRecordPerPage', 'UserController@setRecordPerPage');
    Route::get('changePassword', 'UserController@changePassword');
    Route::post('changePassword', 'UserController@updatePassword');

    /* Acl Access To Methods */
    Route::get('aclAccessToMethods', 'AclAccessToMethodsController@index');
    Route::get('aclAccessToMethods/addAccessMethod', 'AclAccessToMethodsController@addAccessMethod');
    Route::post('aclAccessToMethods/accessToMethodSave', 'AclAccessToMethodsController@accessToMethodSave');
    Route::post('aclAccessToMethods/getAccessMethod', 'AclAccessToMethodsController@getAccessMethod');

    //user
    Route::post('user/getCheckCrmLeader', 'UserController@getCheckCrmLeader')->name('user.getCheckCrmLeader');

    Route::post('lead/getBuyerContPerson', 'LeadController@getBuyerContPerson');
    Route::post('lead/getLeadProduct', 'LeadController@getLeadProduct');
//    Route::post('lead/getLeadSupplier', 'LeadController@getLeadSupplier');
    Route::post('lead/getLeadProductUnit', 'LeadController@getLeadProductUnit');
    Route::post('lead/getLeadBrand', 'LeadController@getLeadBrand');
    Route::post('lead/getProductPricing', 'LeadController@getProductPricing');
    Route::post('lead/rwProceedRequest', 'LeadController@rwProceedRequest');
    Route::post('lead/rwPreviewRequest', 'LeadController@rwPreviewRequest');
    Route::post('lead/rwProceedRequestEdit', 'LeadController@rwProceedRequestEdit');
    Route::post('lead/getLeadRwParametersName', 'LeadController@getLeadRwParametersName');
    Route::post('lead/getProductBrandData', 'LeadController@getProductBrandData');
    Route::post('lead/getLeadGrade', 'LeadController@getLeadGrade');

    //new
    Route::post('lead/rwBreakdownGetBrand', 'LeadController@rwBreakdownGetBrand');
    Route::post('lead/rwBreakdownGetGrade', 'LeadController@rwBreakdownGetGrade');
    Route::post('lead/getRwBreakdownView', 'LeadController@getRwBreakdownView');
    Route::post('lead/getFactoryAddress', 'LeadController@getFactoryAddress');
    Route::post('lead/getOpportunityDetails', 'LeadController@getOpportunityDetails');
    Route::post('lead/getChooseOpportunity', 'LeadController@getChooseOpportunity');
    Route::post('lead/setChooseOpportunity', 'LeadController@setChooseOpportunity');


    //confirmed order
    Route::post('confirmedOrder/newEtsRow', 'ConfirmedOrderController@newEtsRow');
    Route::post('confirmedOrder/newEtaRow', 'ConfirmedOrderController@newEtaRow');
    Route::post('confirmedOrder/newContainerNoRow', 'ConfirmedOrderController@newContainerNoRow');
    Route::post('confirmedOrder/rwProceedRequest', 'ConfirmedOrderController@rwProceedRequest');
    Route::post('confirmedOrder/rwPreviewRequest', 'ConfirmedOrderController@rwPreviewRequest');
    Route::post('confirmedOrder/rwProceedRequestEdit', 'ConfirmedOrderController@rwProceedRequestEdit');
    Route::post('confirmedOrder/getLeadRwParametersName', 'ConfirmedOrderController@getLeadRwParametersName');
    Route::post('confirmedOrder/rwBreakdownGetBrand', 'ConfirmedOrderController@rwBreakdownGetBrand');
    Route::post('confirmedOrder/rwBreakdownGetGrade', 'ConfirmedOrderController@rwBreakdownGetGrade');
    Route::post('confirmedOrder/getRwBreakdownView', 'ConfirmedOrderController@getRwBreakdownView');
    Route::post('confirmedOrder/getShipmentDetails', 'ConfirmedOrderController@getShipmentDetails');
    Route::get('confirmedOrder/getShipmentDetailsPrint', 'ConfirmedOrderController@getShipmentDetailsPrint');
    //new
    Route::post('confirmedOrder/getFactoryAddress', 'ConfirmedOrderController@getFactoryAddress');

    //pending order
    Route::post('pendingOrder/newLsdRow', 'PendingOrderController@newLsdRow');
    Route::post('pendingOrder/rwProceedRequest', 'PendingOrderController@rwProceedRequest');
    Route::post('pendingOrder/rwPreviewRequest', 'PendingOrderController@rwPreviewRequest');
    Route::post('pendingOrder/rwProceedRequestEdit', 'PendingOrderController@rwProceedRequestEdit');
    Route::post('pendingOrder/getLeadRwParametersName', 'PendingOrderController@getLeadRwParametersName');
    Route::post('pendingOrder/rwBreakdownGetBrand', 'PendingOrderController@rwBreakdownGetBrand');
    Route::post('pendingOrder/rwBreakdownGetGrade', 'PendingOrderController@rwBreakdownGetGrade');
    Route::post('pendingOrder/getRwBreakdownView', 'PendingOrderController@getRwBreakdownView');

    //delivery
//    Route::post('delivery/loadTotalQuantity', 'DeliveryController@loadTotalQuantity');
    //buyer
    Route::post('buyer/addGsmVolume', 'BuyerController@addGsmVolume')->name('buyer.addGsmVolume');

    //Billing
    Route::post('billing/billingPreviewData', 'BillingController@billingPreviewData');

    //Product
    Route::post('product/getBrandWisePricingHistory', 'ProductController@getBrandWisePricingHistory');
    Route::post('product/newHsCodeRow', 'ProductController@newHsCodeRow');

    //accomplished order
    Route::post('accomplishedOrder/rwProceedRequest', 'AccomplishedOrderController@rwProceedRequest');
    Route::post('accomplishedOrder/rwPreviewRequest', 'AccomplishedOrderController@rwPreviewRequest');
    Route::post('accomplishedOrder/rwProceedRequestEdit', 'AccomplishedOrderController@rwProceedRequestEdit');
    Route::post('accomplishedOrder/getLeadRwParametersName', 'AccomplishedOrderController@getLeadRwParametersName');
    //new
    Route::post('accomplishedOrder/rwBreakdownGetBrand', 'AccomplishedOrderController@rwBreakdownGetBrand');
    Route::post('accomplishedOrder/rwBreakdownGetGrade', 'AccomplishedOrderController@rwBreakdownGetGrade');
    Route::post('accomplishedOrder/getRwBreakdownView', 'AccomplishedOrderController@getRwBreakdownView');
    Route::post('accomplishedOrder/getShipmentDetails', 'AccomplishedOrderController@getShipmentDetails');
    Route::get('accomplishedOrder/getShipmentDetailsPrint', 'AccomplishedOrderController@getShipmentDetailsPrint');
    //buyer factory
    Route::post('buyerFactory/getBuyerName', 'BuyerFactoryController@getBuyerName')->name('buyerFactory.getBuyerName');
    Route::post('buyerFactory/addPhoneNumber', 'BuyerFactoryController@addPhoneNumber');

    //buyer
    Route::post('buyer/addPhoneNumber', 'BuyerController@addPhoneNumber');
    //new
    Route::post('buyer/getDivision', 'BuyerController@getDivision');
    Route::post('buyer/getInvolvedOrderList', 'BuyerController@getInvolvedOrderList');
    Route::get('buyer/printInvolvedOrderList', 'BuyerController@printInvolvedOrderList');

    //supplier
    Route::post('supplier/getInvolvedOrderList', 'SupplierController@getInvolvedOrderList');
    Route::get('supplier/printInvolvedOrderList', 'SupplierController@printInvolvedOrderList');

    //product to grade
    Route::post('productToGrade/getBrands', 'ProductToGradeController@getBrands');
    Route::post('productToGrade/getGradesToRelate', 'ProductToGradeController@getGradesToRelate');

    //CRM New Opportunity
    Route::post('crmNewOpportunity/newContactRow', 'CrmNewOpportunityController@newContactRow');
    Route::post('crmNewOpportunity/newProductRow', 'CrmNewOpportunityController@newProductRow');
    Route::post('crmNewOpportunity/getBuyerContPerson', 'CrmNewOpportunityController@getBuyerContPerson');
    Route::post('crmNewOpportunity/getProductUnit', 'CrmNewOpportunityController@getProductUnit');
    Route::post('crmNewOpportunity/getGradeOrigin', 'CrmNewOpportunityController@getGradeOrigin');

    //CRM My Opportunity
    Route::post('crmMyOpportunity/newContactRow', 'CrmMyOpportunityController@newContactRow');
    Route::post('crmMyOpportunity/newProductRow', 'CrmMyOpportunityController@newProductRow');
    Route::post('crmMyOpportunity/newTermsRow', 'CrmMyOpportunityController@newTermsRow');
    Route::post('crmMyOpportunity/getBuyerContPerson', 'CrmMyOpportunityController@getBuyerContPerson');
    Route::post('crmMyOpportunity/getProductUnit', 'CrmMyOpportunityController@getProductUnit');
    Route::post('crmMyOpportunity/getGradeOrigin', 'CrmMyOpportunityController@getGradeOrigin');

    //configuration
    Route::post('configuration/addPhoneNumber', 'ConfigurationController@newPhoneNumberRow');

    //buyer summary report
    Route::post('buyerSummaryReport/getRelatedSalesPersonList', 'BuyerSummaryReportController@getRelatedSalesPersonList');
    Route::post('buyerSummaryReport/getInBusinessBrandList', 'BuyerSummaryReportController@getInBusinessBrandList');
    Route::post('buyerSummaryReport/getProductList', 'BuyerSummaryReportController@getProductList');
    Route::post('buyerSummaryReport/getBrandList', 'BuyerSummaryReportController@getBrandList');
    Route::post('buyerSummaryReport/getMachineTypeList', 'BuyerSummaryReportController@getMachineTypeList');
    Route::post('buyerSummaryReport/getDivision', 'BuyerSummaryReportController@getDivision');
    Route::post('buyerSummaryReport/getBuyerSearchList', 'BuyerSummaryReportController@getBuyerSearchList');
    Route::post('buyerSummaryReport/getInvolvedOrderList', 'BuyerSummaryReportController@getInvolvedOrderList');
    Route::get('buyerSummaryReport/printInvolvedOrderList', 'BuyerSummaryReportController@printInvolvedOrderList');

    //buyer summary report
    Route::post('idlyEngagedBuyerReport/getRelatedSalesPersonList', 'IdlyEngagedBuyerReportController@getRelatedSalesPersonList');
    Route::post('idlyEngagedBuyerReport/getInvolvedOrderList', 'IdlyEngagedBuyerReportController@getInvolvedOrderList');
    Route::get('idlyEngagedBuyerReport/printInvolvedOrderList', 'IdlyEngagedBuyerReportController@printInvolvedOrderList');

    //market engagement
    Route::post('marketEngagement/getBuyerProduct', 'MarketEngagementController@getBuyerProduct');
    Route::post('marketEngagement/getProduct', 'MarketEngagementController@getProduct');

    //Quotation Request 
    Route::post('quotationRequest/markAsRead', 'QuotationRequestController@markAsRead');

    Route::group(['middleware' => ['buyer']], function () use($prefix) {
        Route::get('buyerProfile', 'BuyerProfileController@index');
        Route::post('buyerProfile/updateLogo', 'BuyerProfileController@updateLogo');
        Route::post('buyerProfile/getInvolvedOrderList', 'BuyerProfileController@getInvolvedOrderList');
        Route::get('buyerProfile/printInvolvedOrderList', 'BuyerProfileController@printInvolvedOrderList');

        //buyer order
        Route::post('buyerOrder/filter/', 'BuyerOrderController@filter');
        Route::get('buyerOrder', 'BuyerOrderController@index')->name('buyerOrder.index');
        Route::post('buyerOrder/getOrderDetails', 'BuyerOrderController@getOrderDetails');
        Route::post('buyerOrder/quantitySummaryView', 'BuyerOrderController@quantitySummaryView');
        Route::post('buyerOrder/lsdInfo', 'BuyerOrderController@getLsdInfo');
        Route::post('buyerOrder/getShipmentDetails', 'BuyerOrderController@getShipmentDetails');
        Route::get('buyerOrder/getShipmentDetailsPrint', 'BuyerOrderController@getShipmentDetailsPrint');
        Route::post('buyerOrder/getOrderMessaging', 'BuyerOrderController@getOrderMessaging');
        Route::post('buyerOrder/getMessageBody', 'BuyerOrderController@getMessageBody');
        Route::post('buyerOrder/setMessage', 'BuyerOrderController@setMessage');

        Route::get('productCatalog', 'ProductCatalogController@index')->name('productCatalog.index');

        // Buyer Messaging
        Route::get('buyerMessaging', 'BuyerMessagingController@index');
        Route::post('buyerMessaging/filter', 'BuyerMessagingController@filter');
        Route::post('buyerMessaging/getOrderMessaging', 'BuyerMessagingController@getOrderMessaging');
        Route::post('buyerMessaging/getMessageBody', 'BuyerMessagingController@getMessageBody');
        Route::post('buyerMessaging/setMessage', 'BuyerMessagingController@setMessage');

        Route::get('buyerQuotationRequest', 'BuyerQuotationRequestController@index')->name('buyerQuotationRequest.index');
        Route::post('buyerQuotationRequest/filter', 'BuyerQuotationRequestController@filter');
        Route::get('buyerQuotationRequest/quotation', 'BuyerQuotationRequestController@quotation')->name('buyerQuotationRequest.quotation');
        Route::post('buyerQuotationRequest/newProductRow', 'BuyerQuotationRequestController@newProductRow');
        Route::post('buyerQuotationRequest/getProductUnit', 'BuyerQuotationRequestController@getProductUnit');
        Route::post('buyerQuotationRequest/quotationDataSave', 'BuyerQuotationRequestController@quotationDataSave');
        Route::get('buyerQuotationRequest/quotationReqDetails/{id}/{view?}', 'BuyerQuotationRequestController@quotationReqDetails');
        Route::post('buyerQuotationRequest/quotationReqDetails', 'BuyerQuotationRequestController@quotationReqDetails')->name('buyerQuotationRequest.quotationReqDetails');

        //start :: reports
        //purchase summary report
        Route::get('purchaseSummaryReport', 'PurchaseSummaryReportController@index');
        Route::post('purchaseSummaryReport/filter', 'PurchaseSummaryReportController@filter');

        //brand wise purchase summary report
        Route::get('brandWisePurchaseSummaryReport', 'BrandWisePurchaseSummaryReportController@index');
        Route::post('brandWisePurchaseSummaryReport/filter', 'BrandWisePurchaseSummaryReportController@filter');


        //order summary report
        Route::get('orderSummaryReport', 'OrderSummaryReportController@index');
        Route::post('orderSummaryReport/filter', 'OrderSummaryReportController@filter');
        Route::post('orderSummaryReport/getShipmentDetails', 'OrderSummaryReportController@getShipmentDetails');
        Route::get('orderSummaryReport/getShipmentDetailsPrint', 'OrderSummaryReportController@getShipmentDetailsPrint');


        //end :: reports
    });
});


//ACL ACCESS GROUP MIDDLEWARE
Route::group(['middleware' => ['auth', 'aclgroup']], function () use($prefix) {

    //user
    Route::post('user/filter/', 'UserController@filter');
    Route::get('user', 'UserController@index')->name('user.index');
    Route::get('user/create', 'UserController@create')->name('user.create');
    Route::post('user', 'UserController@store')->name('user.store');
    Route::get('user/{id}/edit', 'UserController@edit')->name('user.edit');
    Route::patch('user/{id}', 'UserController@update')->name('user.update');
    Route::delete('user/{id}', 'UserController@destroy')->name('user.destroy');

    //department
    Route::post('department/filter/', 'DepartmentController@filter');
    Route::get('department', 'DepartmentController@index')->name('department.index');
    Route::get('department/create', 'DepartmentController@create')->name('department.create');
    Route::post('department', 'DepartmentController@store')->name('department.store');
    Route::get('department/{id}/edit', 'DepartmentController@edit')->name('department.edit');
    Route::patch('department/{id}', 'DepartmentController@update')->name('department.update');
    Route::delete('department/{id}', 'DepartmentController@destroy')->name('department.destroy');

    //designation
    Route::post('designation/filter/', 'DesignationController@filter');
    Route::get('designation', 'DesignationController@index')->name('designation.index');
    Route::get('designation/create', 'DesignationController@create')->name('designation.create');
    Route::post('designation', 'DesignationController@store')->name('designation.store');
    Route::get('designation/{id}/edit', 'DesignationController@edit')->name('designation.edit');
    Route::patch('designation/{id}', 'DesignationController@update')->name('designation.update');
    Route::delete('designation/{id}', 'DesignationController@destroy')->name('designation.destroy');

    //branch
    Route::post('branch/filter/', 'BranchController@filter');
    Route::get('branch', 'BranchController@index')->name('branch.index');
    Route::get('branch/create', 'BranchController@create')->name('branch.create');
    Route::post('branch/getDivisionToCreate', 'BranchController@getDivisionToCreate');
    Route::post('branch/getDistrictToCreate', 'BranchController@getDistrictToCreate');
    Route::post('branch/getThanaToCreate', 'BranchController@getThanaToCreate');
    Route::post('branch', 'BranchController@store')->name('branch.store');
    Route::get('branch/{id}/edit', 'BranchController@edit')->name('branch.edit');
    Route::post('branch/getDivisionToEdit', 'BranchController@getDivisionToEdit');
    Route::post('branch/getDistrictToEdit', 'BranchController@getDistrictToEdit');
    Route::post('branch/getThanaToEdit', 'BranchController@getThanaToEdit');
    Route::patch('branch/{id}', 'BranchController@update')->name('branch.update');
    Route::delete('branch/{id}', 'BranchController@destroy')->name('branch.destroy');

    //Cofiguration
    Route::get('configuration', 'ConfigurationController@index')->name('configuration.index');
    Route::get('configuration/{id}/edit', 'ConfigurationController@edit')->name('configuration.edit');
    Route::patch('configuration/{id}', 'ConfigurationController@update')->name('configuration.update');

    //product category
    Route::post('productCategory/filter/', 'ProductCategoryController@filter');
    Route::get('productCategory', 'ProductCategoryController@index')->name('productCategory.index');
    Route::get('productCategory/create', 'ProductCategoryController@create')->name('productCategory.create');
    Route::post('productCategory', 'ProductCategoryController@store')->name('productCategory.store');
    Route::get('productCategory/{id}/edit', 'ProductCategoryController@edit')->name('productCategory.edit');
    Route::patch('productCategory/{id}', 'ProductCategoryController@update')->name('productCategory.update');
    Route::delete('productCategory/{id}', 'ProductCategoryController@destroy')->name('productCategory.destroy');

    //measurement unit
    Route::post('measureUnit/filter/', 'MeasureUnitController@filter');
    Route::get('measureUnit', 'MeasureUnitController@index')->name('measureUnit.index');
    Route::get('measureUnit/create', 'MeasureUnitController@create')->name('measureUnit.create');
    Route::post('measureUnit', 'MeasureUnitController@store')->name('measureUnit.store');
    Route::get('measureUnit/{id}/edit', 'MeasureUnitController@edit')->name('measureUnit.edit');
    Route::patch('measureUnit/{id}', 'MeasureUnitController@update')->name('measureUnit.update');
    Route::delete('measureUnit/{id}', 'MeasureUnitController@destroy')->name('measureUnit.destroy');

    //product
    Route::post('product/filter/', 'ProductController@filter');
    Route::get('product', 'ProductController@index')->name('product.index');
    Route::get('product/create', 'ProductController@create')->name('product.create');
    Route::post('product/loadProductNameCreate', 'ProductController@loadProductNameCreate');
    Route::post('product/store', 'ProductController@store')->name('product.store');
    Route::get('product/{id}/edit', 'ProductController@edit')->name('product.edit');
    Route::post('product/loadProductNameEdit', 'ProductController@loadProductNameEdit');
    Route::post('product/update', 'ProductController@update')->name('product.update');
    Route::delete('product/{id}', 'ProductController@destroy')->name('product.destroy');
    Route::post('product/getProductPricing', 'ProductController@getProductPricing');
    Route::post('product/setProductPricing', 'ProductController@setProductPricing');
    Route::post('product/showPricingHistory', 'ProductController@showPricingHistory');
    Route::post('product/getProductQuality', 'ProductController@getProductQuality');
    Route::post('product/newDataSheetRow', 'ProductController@newDataSheetRow');
    Route::post('product/setProductQuality', 'ProductController@setProductQuality');
    Route::post('product/trackProductPricingHistory', 'ProductController@trackProductPricingHistory');
    Route::post('product/brandDetails', 'ProductController@brandDetails');

    //brand
    Route::get('brand', 'BrandController@index')->name('brand.index');
    Route::post('brand/filter', 'BrandController@filter');
    Route::get('brand/create', 'BrandController@create')->name('brand.create');
    Route::post('brand', 'BrandController@store')->name('brand.store');
    Route::get('brand/{id}/edit', 'BrandController@edit')->name('brand.edit');
    Route::post('brandUpdate', 'BrandController@update')->name('brand.update');
    Route::delete('brand/{id}', 'BrandController@destroy')->name('brand.destroy');


    //supplier classification
    Route::post('supplierClassification/filter/', 'SupplierClassificationController@filter');
    Route::get('supplierClassification', 'SupplierClassificationController@index')->name('supplierClassification.index');
    Route::get('supplierClassification/create', 'SupplierClassificationController@create')->name('supplierClassification.create');
    Route::post('supplierClassification', 'SupplierClassificationController@store')->name('supplierClassification.store');
    Route::get('supplierClassification/{id}/edit', 'SupplierClassificationController@edit')->name('supplierClassification.edit');
    Route::patch('supplierClassification/{id}', 'SupplierClassificationController@update')->name('supplierClassification.update');
    Route::delete('supplierClassification/{id}', 'SupplierClassificationController@destroy')->name('supplierClassification.destroy');

    //supplier
    Route::post('supplier/showContactPersonDetails', 'SupplierController@getDetailsOfContactPerson')->name('supplier.detailsOfContactPerson');
    Route::post('supplier/newContactPersonToCreate', 'SupplierController@newContactPersonToCreate')->name('supplier.contactPersonToCreate');
    Route::post('supplier/newContactPersonToEdit', 'SupplierController@newContactPersonToEdit')->name('supplier.contactPersonToEdit');
    Route::post('supplier/filter/', 'SupplierController@filter');
    Route::get('supplier', 'SupplierController@index')->name('supplier.index');
    Route::get('supplier/create', 'SupplierController@create')->name('supplier.create');
    Route::post('supplier', 'SupplierController@store')->name('supplier.store');
    Route::get('supplier/{id}/edit', 'SupplierController@edit')->name('supplier.edit');
    Route::post('supplier/edit', 'SupplierController@update')->name('supplier.update');
    Route::delete('supplier/{id}', 'SupplierController@destroy')->name('supplier.destroy');
    Route::get('supplier/{id}/profile', 'SupplierController@profile');
    Route::get('supplier/{id}/printProfile', 'SupplierController@printProfile');

    //new 
    Route::post('supplier/beneficiaryBank', 'SupplierController@beneficiaryBank');


    //Buyer Category
    Route::post('buyerCategory/filter/', 'BuyerCategoryController@filter');
    Route::get('buyerCategory', 'BuyerCategoryController@index')->name('buyerCategory.index');
    Route::get('buyerCategory/create', 'BuyerCategoryController@create')->name('buyerCategory.create');
    Route::post('buyerCategory', 'BuyerCategoryController@store')->name('buyerCategory.store');
    Route::get('buyerCategory/{id}/edit', 'BuyerCategoryController@edit')->name('buyerCategory.edit');
    Route::patch('buyerCategory/{id}', 'BuyerCategoryController@update')->name('buyerCategory.update');
    Route::delete('buyerCategory/{id}', 'BuyerCategoryController@destroy')->name('buyerCategory.destroy');

    //buyer
    Route::post('buyer/getGsmVolume', 'BuyerController@getGsmVolume');
    Route::post('buyer/removeGsm', 'BuyerController@removeGsm');
    Route::post('buyer/volumeDetails', 'BuyerController@volumeDetails');
    Route::post('buyer/saveGsmVolume', 'BuyerController@saveGsmVolume')->name('buyer.savegsmvolume');
    Route::post('buyer/saveOthers', 'BuyerController@saveOthers')->name('buyer.othersinfo');
    Route::post('buyer/saveFinishedProduct', 'BuyerController@saveFinishedProduct')->name('buyer.finishedproduct');
    Route::post('buyer/saveCompetitorProduct', 'BuyerController@saveCompetitorProduct')->name('buyer.competitorsproduct');
    Route::post('buyer/showLocationView', 'BuyerController@showLocationView')->name('buyer.locationView');
    Route::post('buyer/showContactPersonDetails', 'BuyerController@getDetailsOfContactPerson')->name('buyer.detailsOfContactPerson');
    Route::post('buyer/newContactPersonToCreate', 'BuyerController@newContactPersonToCreate')->name('buyer.createContactPerson');
    Route::post('buyer/newContactPersonToEdit', 'BuyerController@newContactPersonToEdit')->name('buyer.editContactPerson');
    Route::post('buyer/filter/', 'BuyerController@filter');
    Route::get('buyer', 'BuyerController@index')->name('buyer.index');
    Route::get('buyer/create', 'BuyerController@create')->name('buyer.create');
    Route::post('buyer', 'BuyerController@store')->name('buyer.store');
    Route::get('buyer/{id}/edit', 'BuyerController@edit')->name('buyer.edit');
    Route::post('buyer/edit', 'BuyerController@update')->name('buyer.update');
    Route::delete('buyer/{id}', 'BuyerController@destroy')->name('buyer.destroy');
    Route::get('buyer/{id}/manageBuyer', 'BuyerController@manageBuyer')->name('buyer.manage');
    Route::post('buyer/getMachineType', 'BuyerController@getMachineType');
    Route::post('buyer/getBrandForMachineType', 'BuyerController@getBrandForMachineType');
    Route::post('buyer/setMachineType', 'BuyerController@setMachineType');
    Route::get('buyer/{id}/profile', 'BuyerController@profile');
    Route::get('buyer/{id}/printProfile', 'BuyerController@printProfile');


    //Buyer Factory

    Route::post('buyerFactory/getBuyerPrimaryFactoryCreate', 'BuyerFactoryController@getBuyerPrimaryFactoryCreate')->name('buyerFactory.buyerPrimaryFactoryCreate');
    Route::post('buyerFactory/getBuyerPrimaryFactoryEdit', 'BuyerFactoryController@getBuyerPrimaryFactoryEdit')->name('buyerFactory.buyerPrimaryFactoryEdit');
    Route::post('buyerFactory/showLocationView', 'BuyerFactoryController@showLocationView')->name('buyerFactory.locationView');
    Route::post('buyerFactory/showContactPersonDetails', 'BuyerFactoryController@getDetailsOfContactPerson')->name('buyerFactory.detailsOfContactPerson');
    Route::post('buyerFactory/newContactPersonToCreate', 'BuyerFactoryController@newContactPersonToCreate')->name('buyerFactory.createContactPerson');
    Route::post('buyerFactory/newContactPersonToEdit', 'BuyerFactoryController@newContactPersonToEdit')->name('buyerFactory.editContactPerson');
    Route::post('buyerFactory/filter/', 'BuyerFactoryController@filter');
    Route::get('buyerFactory', 'BuyerFactoryController@index')->name('buyerFactory.index');
    Route::get('buyerFactory/create', 'BuyerFactoryController@create')->name('buyerFactory.create');
    Route::post('buyerFactory', 'BuyerFactoryController@store')->name('buyerFactory.store');
    Route::get('buyerFactory/{id}/edit', 'BuyerFactoryController@edit')->name('buyerFactory.edit');
    Route::post('buyerFactory/edit', 'BuyerFactoryController@update')->name('buyerFactory.update');
    Route::delete('buyerFactory/{id}', 'BuyerFactoryController@destroy')->name('buyerFactory.destroy');

    //season
    Route::post('season/filter/', 'SeasonController@filter');
    Route::get('season', 'SeasonController@index')->name('season.index');
    Route::get('season/create', 'SeasonController@create')->name('season.create');
    Route::post('season', 'SeasonController@store')->name('season.store');
    Route::get('season/{id}/edit', 'SeasonController@edit')->name('season.edit');
    Route::patch('season/{id}', 'SeasonController@update')->name('season.update');
    Route::delete('season/{id}', 'SeasonController@destroy')->name('season.destroy');

    //color
    Route::post('color/filter/', 'ColorController@filter');
    Route::get('color', 'ColorController@index')->name('color.index');
    Route::get('color/create', 'ColorController@create')->name('color.create');
    Route::post('color', 'ColorController@store')->name('color.store');
    Route::get('color/{id}/edit', 'ColorController@edit')->name('color.edit');
    Route::patch('color/{id}', 'ColorController@update')->name('color.update');
    Route::delete('color/{id}', 'ColorController@destroy')->name('color.destroy');

    //user group
    Route::post('userGroup/filter/', 'UserGroupController@filter');
    Route::get('userGroup', 'UserGroupController@index')->name('userGroup.index');
    Route::get('userGroup/create', 'UserGroupController@create')->name('userGroup.create');
    Route::post('userGroup', 'UserGroupController@store')->name('userGroup.store');
    Route::get('userGroup/{id}/edit', 'UserGroupController@edit')->name('userGroup.edit');
    Route::patch('userGroup/{id}', 'UserGroupController@update')->name('userGroup.update');
    Route::delete('userGroup/{id}', 'UserGroupController@destroy')->name('userGroup.destroy');

    //acl User Group To Access
    Route::get('aclUserGroupToAccess/moduleAccessControl', 'AclUserGroupToAccessController@moduleAccessControl');
    Route::post('aclUserGroupToAccess/relateUserGroupToAccess/', 'AclUserGroupToAccessController@relateUserGroupToAccess');
    Route::post('aclUserGroupToAccess/getAccessControl/', 'AclUserGroupToAccessController@getAccess');
    Route::get('aclUserGroupToAccess/userGroupToAccess', 'AclUserGroupToAccessController@userGroupToAccess');
    Route::post('aclUserGroupToAccess/getUserGroupListToRevoke', 'AclUserGroupToAccessController@getUserGroupListToRevoke');
    Route::post('aclUserGroupToAccess/revokeUserGroupAccess', 'AclUserGroupToAccessController@revokeUserGroupAccess');

    //sales person to product
    Route::post('salesPersonToProduct/filter/', 'SalesPersonToProductController@filter');
    Route::get('salesPersonToProduct', 'SalesPersonToProductController@index')->name('salesPersonToProduct.index');
    Route::post('salesPersonToProduct/getProductsToRelate', 'SalesPersonToProductController@getProductsToRelate');
    Route::post('salesPersonToProduct/getRelatedProducts', 'SalesPersonToProductController@getRelatedProducts');
    Route::post('salesPersonToProduct/relateSalesPersonToProduct', 'SalesPersonToProductController@relateSalesPersonToProduct');
    Route::post('salesPersonToProduct/getAssignedProducts', 'SalesPersonToProductController@getAssignedProducts');
    Route::post('salesPersonToProduct/removeAssignedProduct', 'SalesPersonToProductController@removeAssignedProduct');
    Route::post('salesPersonToProduct/removeAllAssignment', 'SalesPersonToProductController@removeAllAssignment');

    //sales person to buyer
    Route::get('salesPersonToBuyer', 'SalesPersonToBuyerController@index')->name('salesPersonToBuyer.index');
    Route::post('salesPersonToBuyer/getBuyersToRelate', 'SalesPersonToBuyerController@getBuyersToRelate');
    Route::post('salesPersonToBuyer/getRelatedBuyers', 'SalesPersonToBuyerController@getRelatedBuyers');
    Route::post('salesPersonToBuyer/relateSalesPersonToBuyer', 'SalesPersonToBuyerController@relateSalesPersonToBuyer');
    Route::post('salesPersonToBuyer/getUnassignedBuyers', 'SalesPersonToBuyerController@getUnassignedBuyers');
    Route::get('salesPersonToBuyer/getRelatedBuyersPrint/{id}', 'SalesPersonToBuyerController@getRelatedBuyersPrint');
    Route::get('salesPersonToBuyer/getUnassignedBuyersPrint', 'SalesPersonToBuyerController@getUnassignedBuyersPrint');
    Route::post('salesPersonToBuyer/getAssignSalesPerson', 'SalesPersonToBuyerController@getAssignSalesPerson');
    Route::post('salesPersonToBuyer/setAssignSalesPerson', 'SalesPersonToBuyerController@setAssignSalesPerson');
    Route::post('salesPersonToBuyer/getRelatedSalesPersonList', 'SalesPersonToBuyerController@getRelatedSalesPersonList');
    Route::get('salesPersonToBuyer/getRelatedSalesPersonListPrint', 'SalesPersonToBuyerController@getRelatedSalesPersonListPrint');
    //set sales target
    Route::get('salesTarget', 'SalesTargetController@index')->name('salesTarget.index');
    Route::post('salesTarget/getSalesTarget', 'SalesTargetController@getSalesTarget');
    Route::post('salesTarget/showSalesTarget', 'SalesTargetController@showSalesTarget');
    Route::post('salesTarget/setSalesTarget', 'SalesTargetController@setSalesTarget');
    Route::post('salesTarget/lockSalesTarget', 'SalesTargetController@lockSalesTarget');
    Route::post('salesTarget/getSalesTargetDetail', 'SalesTargetController@getSalesTargetDetail');
    Route::post('salesTarget/showSalesTargetDetail', 'SalesTargetController@showSalesTargetDetail');
    Route::get('salesTarget/reloadView', 'SalesTargetController@reloadView');
    Route::get('salesTarget/getHeirarchyTree', 'SalesTargetController@getHeirarchyTree');
    //supplier to product
    Route::get('supplierToProduct', 'SupplierToProductController@index')->name('supplierToProduct.index');
    Route::post('supplierToProduct/getProductsToRelate', 'SupplierToProductController@getProductsToRelate');
    Route::post('supplierToProduct/getRelatedProducts', 'SupplierToProductController@getRelatedProducts');
    Route::post('supplierToProduct/relateSupplierToProduct', 'SupplierToProductController@relateSupplierToProduct');
    Route::post('supplierToProduct/getAssignedProducts', 'SupplierToProductController@getAssignedProducts');
    Route::post('supplierToProduct/removeAssignedProduct', 'SupplierToProductController@removeAssignedProduct');
    Route::post('supplierToProduct/removeAllAssignment', 'SupplierToProductController@removeAllAssignment');

    //FINIISHED GOODS
    Route::post('finishedGoods/filter/', 'FinishedGoodsController@filter');
    Route::get('finishedGoods', 'FinishedGoodsController@index')->name('finishedGoods.index');
    Route::get('finishedGoods/create', 'FinishedGoodsController@create')->name('finishedGoods.create');
    Route::post('finishedGoods', 'FinishedGoodsController@store')->name('finishedGoods.store');
    Route::get('finishedGoods/{id}/edit', 'FinishedGoodsController@edit')->name('finishedGoods.edit');
    Route::patch('finishedGoods/{id}', 'FinishedGoodsController@update')->name('finishedGoods.update');
    Route::delete('finishedGoods/{id}', 'FinishedGoodsController@destroy')->name('finishedGoods.destroy');

    /*     * ******** Start Lead **** */
    Route::post('lead/getFollowUpModal', 'LeadController@getFollowUpModal');
    Route::post('lead/setFollowUpSave', 'LeadController@setFollowUpSave');
    Route::post('lead/leadConfirmationSave', 'LeadController@leadConfirmationSave');
    Route::get('lead', 'LeadController@index')->name('lead.index');
    Route::get('lead/create', 'LeadController@create')->name('lead.create');
    Route::post('lead', 'LeadController@store')->name('lead.store');
    Route::get('lead/{id}/edit', 'LeadController@edit')->name('lead.edit');
    Route::post('lead/edit', 'LeadController@update')->name('lead.update');
    Route::post('lead/leadConfirmation', 'LeadController@leadConfirmation');
    Route::post('lead/leadCancellationModal', 'LeadController@leadCancellationModal');
    Route::post('lead/leadCancellation', 'LeadController@leadCancellation');
    Route::post('lead/filter', 'LeadController@filter');
    Route::get('lead/rwBreakdown/{id}', 'LeadController@rwBreakdown');
    Route::post('lead/rwBreakDownSave', 'LeadController@rwBreakDownSave');
    Route::post('lead/leadRwBreakdownView', 'LeadController@leadRwBreakdownView');
    Route::delete('lead/{id}', 'LeadController@destroy')->name('lead.destroy');
    Route::post('lead/getCommissionSetupModal', 'LeadController@getCommissionSetupModal');
    Route::post('lead/commissionSetupSave', 'LeadController@commissionSetupSave');
    Route::post('lead/quantitySummaryView', 'LeadController@quantitySummaryView');
    Route::get('lead/quotation/{id}', 'LeadController@quotation');
    Route::post('lead/quotationSave', 'LeadController@quotationSave');
    Route::post('lead/getInquiryReassigned', 'LeadController@getInquiryReassigned');
    Route::post('lead/setInquiryReassigned', 'LeadController@setInquiryReassigned');

    //Cancelled Inquiry
    Route::get('cancelledInquiry', 'CancelledInquiryController@index')->name('cancelledInquiry.index');
    Route::post('cancelledInquiry/filter', 'CancelledInquiryController@filter');
    Route::post('cancelledInquiry/getFollowUpModal', 'CancelledInquiryController@getFollowUpModal');
    Route::post('cancelledInquiry/setFollowUpSave', 'CancelledInquiryController@setFollowUpSave');
    Route::post('cancelledInquiry/reactivate', 'CancelledInquiryController@reactivate');
    Route::post('cancelledInquiry/quantitySummaryView', 'CancelledInquiryController@quantitySummaryView');
    Route::delete('cancelledInquiry/{id}', 'CancelledInquiryController@destroy')->name('cancelledInquiry.destroy');

    /*     * ******** End Lead **** */

//RW UNIT

    Route::get('rwUnit', 'RwUnitController@index')->name('rwUnit.index');
    Route::get('rwUnit/create', 'RwUnitController@create')->name('rwUnit.create');
    Route::post('rwUnit', 'RwUnitController@store')->name('rwUnit.store');
    Route::get('rwUnit/{id}/edit', 'RwUnitController@edit')->name('rwUnit.edit');
    Route::patch('rwUnit/{id}', 'RwUnitController@update')->name('rwUnit.update');
    Route::post('rwUnit/filter', 'RwUnitController@filter');
    Route::delete('rwUnit/{id}', 'RwUnitController@destroy')->name('rwUnit.destroy');
    Route::post('rwUnit/getConversion', 'RwUnitController@getConversion');
    Route::post('rwUnit/setConversion', 'RwUnitController@setConversion');

    //buyer to product
    Route::get('buyerToProduct', 'BuyerToProductController@index')->name('buyerToProduct.index');
    Route::post('buyerToProduct/getProductsToRelate', 'BuyerToProductController@getProductsToRelate');
    Route::post('buyerToProduct/getRelatedProducts', 'BuyerToProductController@getRelatedProducts');
    Route::post('buyerToProduct/relateBuyerToProduct', 'BuyerToProductController@relateBuyerToProduct');
    Route::post('buyerToProduct/getAssignedProducts', 'BuyerToProductController@getAssignedProducts');
    Route::post('buyerToProduct/removeAssignedProduct', 'BuyerToProductController@removeAssignedProduct');
    Route::post('buyerToProduct/removeAllAssignment', 'BuyerToProductController@removeAllAssignment');


    //pending order
    Route::post('pendingOrder/filter/', 'PendingOrderController@filter');
    Route::get('pendingOrder', 'PendingOrderController@index')->name('pendingOrder.index');
    Route::post('pendingOrder/getConfirmOrderModal', 'PendingOrderController@getConfirmOrderModal');
    Route::post('pendingOrder/confirmOrder', 'PendingOrderController@confirmOrder');
    Route::post('pendingOrder/getFollowUpModal', 'PendingOrderController@getFollowUpModal');
    Route::post('pendingOrder/setFollowUpSave', 'PendingOrderController@setFollowUpSave');
    Route::get('pendingOrder/rwBreakdown/{id}', 'PendingOrderController@rwBreakdown');
    Route::post('pendingOrder/rwBreakDownSave', 'PendingOrderController@rwBreakDownSave');
    Route::post('pendingOrder/leadRwBreakdownView', 'PendingOrderController@leadRwBreakdownView');
    Route::post('pendingOrder/getCommissionSetupModal', 'PendingOrderController@getCommissionSetupModal');
    Route::post('pendingOrder/commissionSetupSave', 'PendingOrderController@commissionSetupSave');
    Route::post('pendingOrder/pendingOrderCancelModal', 'PendingOrderController@pendingOrderCancelModal');
    Route::post('pendingOrder/pendingOrderCancelSave', 'PendingOrderController@pendingOrderCancelSave');
    Route::post('pendingOrder/quantitySummaryView', 'PendingOrderController@quantitySummaryView');
    Route::post('pendingOrder/getInquiryReassigned', 'PendingOrderController@getInquiryReassigned');
    Route::post('pendingOrder/setInquiryReassigned', 'PendingOrderController@setInquiryReassigned');


    //confirmed order
    Route::post('confirmedOrder/filter/', 'ConfirmedOrderController@filter');
    Route::get('confirmedOrder', 'ConfirmedOrderController@index')->name('confirmedOrder.index');
    Route::post('confirmedOrder/getOrderDetails', 'ConfirmedOrderController@getOrderDetails');
    Route::post('confirmedOrder/cancel', 'ConfirmedOrderController@cancel');
    Route::post('confirmedOrder/orderCancellationModal', 'ConfirmedOrderController@orderCancellationModal');
    Route::post('confirmedOrder/edit', 'ConfirmedOrderController@edit');
    Route::post('confirmedOrder/update', 'ConfirmedOrderController@update');
    Route::post('confirmedOrder/showOrderDelivery', 'ConfirmedOrderController@showOrderDelivery');
    Route::post('confirmedOrder/previewOrderDelivery', 'ConfirmedOrderController@previewOrderDelivery');
    Route::post('confirmedOrder/setOrderDelivery', 'ConfirmedOrderController@setOrderDelivery');
    Route::post('confirmedOrder/accomplish', 'ConfirmedOrderController@accomplish');
    Route::post('confirmedOrder/markOrderAccomplishedModal', 'ConfirmedOrderController@markOrderAccomplishedModal');
    Route::post('confirmedOrder/getFollowUpModal', 'ConfirmedOrderController@getFollowUpModal');
    Route::post('confirmedOrder/setFollowUpSave', 'ConfirmedOrderController@setFollowUpSave');
    Route::get('confirmedOrder/getShipmentInfoView/{id}', 'ConfirmedOrderController@getShipmentInfoView');
    Route::post('confirmedOrder/getNewShipmentAdd', 'ConfirmedOrderController@getNewShipmentAdd');
    Route::post('confirmedOrder/saveEtsEtaInfo', 'ConfirmedOrderController@saveEtsEtaInfo');
    Route::post('confirmedOrder/getCommissionSetupModal', 'ConfirmedOrderController@getCommissionSetupModal');
    Route::post('confirmedOrder/commissionSetupSave', 'ConfirmedOrderController@commissionSetupSave');
    Route::post('confirmedOrder/editEtsEtaInfo', 'ConfirmedOrderController@editEtsEtaInfo');
    Route::post('confirmedOrder/updateEtsEtaInfo', 'ConfirmedOrderController@updateEtsEtaInfo');
    Route::post('confirmedOrder/getCarrierInfo', 'ConfirmedOrderController@getCarrierInfo');
    Route::post('confirmedOrder/setCarrierInfo', 'ConfirmedOrderController@setCarrierInfo');
    Route::post('confirmedOrder/getBlInfo', 'ConfirmedOrderController@getBlInfo');
    Route::post('confirmedOrder/setBlInfo', 'ConfirmedOrderController@setBlInfo');
    Route::post('confirmedOrder/getShipmentFullDetail', 'ConfirmedOrderController@getShipmentFullDetail');
    Route::post('confirmedOrder/lsdInfo', 'ConfirmedOrderController@getLsdInfo');
    Route::get('confirmedOrder/rwBreakdown/{id}', 'ConfirmedOrderController@rwBreakdown');
    Route::post('confirmedOrder/rwBreakDownSave', 'ConfirmedOrderController@rwBreakDownSave');
    Route::post('confirmedOrder/leadRwBreakdownView', 'ConfirmedOrderController@leadRwBreakdownView');
    Route::post('confirmedOrder/quantitySummaryView', 'ConfirmedOrderController@quantitySummaryView');
    Route::get('confirmedOrder/poGenerate/{id}', 'ConfirmedOrderController@poGenerate');
    Route::post('confirmedOrder/poGenerateSave', 'ConfirmedOrderController@poGenerateSave');
    Route::get('confirmedOrder/piGenerate/{id}', 'ConfirmedOrderController@piGenerate');
    Route::post('confirmedOrder/piGenerateSave', 'ConfirmedOrderController@piGenerateSave');
    Route::post('confirmedOrder/getLeadTime', 'ConfirmedOrderController@getLeadTime');
    Route::post('confirmedOrder/updateTrackingNo', 'ConfirmedOrderController@updateTrackingNo');
    Route::post('confirmedOrder/getOrderMessaging', 'ConfirmedOrderController@getOrderMessaging');
    Route::post('confirmedOrder/getMessageBody', 'ConfirmedOrderController@getMessageBody');
    Route::post('confirmedOrder/setMessage', 'ConfirmedOrderController@setMessage');





    //accomplished order
    Route::post('accomplishedOrder/filter/', 'AccomplishedOrderController@filter');
    Route::get('accomplishedOrder', 'AccomplishedOrderController@index')->name('accomplishedOrder.index');
    Route::post('accomplishedOrder/getOrderDetails', 'AccomplishedOrderController@getOrderDetails');
    Route::post('accomplishedOrder/getFollowUpModal', 'AccomplishedOrderController@getFollowUpModal');
    Route::post('accomplishedOrder/setFollowUpSave', 'AccomplishedOrderController@setFollowUpSave');
    Route::get('accomplishedOrder/rwBreakdown/{id}', 'AccomplishedOrderController@rwBreakdown');
    Route::post('accomplishedOrder/rwBreakDownSave', 'AccomplishedOrderController@rwBreakDownSave');
    Route::post('accomplishedOrder/leadRwBreakdownView', 'AccomplishedOrderController@leadRwBreakdownView');
    Route::post('accomplishedOrder/getCommissionSetupModal', 'AccomplishedOrderController@getCommissionSetupModal');
    Route::post('accomplishedOrder/commissionSetupSave', 'AccomplishedOrderController@commissionSetupSave');
    Route::post('accomplishedOrder/quantitySummaryView', 'AccomplishedOrderController@quantitySummaryView');
    Route::post('accomplishedOrder/updateTrackingNo', 'AccomplishedOrderController@updateTrackingNo');
    Route::post('accomplishedOrder/getLeadTime', 'AccomplishedOrderController@getLeadTime');
    Route::post('accomplishedOrder/cancel', 'AccomplishedOrderController@cancel');
    Route::post('accomplishedOrder/orderCancellationModal', 'AccomplishedOrderController@orderCancellationModal');

    //cancelled order
    Route::post('cancelledOrder/filter/', 'CancelledOrderController@filter');
    Route::get('cancelledOrder', 'CancelledOrderController@index')->name('cancelledOrder.index');
    Route::post('cancelledOrder/getOrderDetails', 'CancelledOrderController@getOrderDetails');
    Route::post('cancelledOrder/getFollowUpModal', 'CancelledOrderController@getFollowUpModal');
    Route::post('cancelledOrder/setFollowUpSave', 'CancelledOrderController@setFollowUpSave');
    Route::post('cancelledOrder/quantitySummaryView', 'CancelledOrderController@quantitySummaryView');
    Route::delete('cancelledOrder/{id}', 'CancelledOrderController@destroy')->name('cancelledOrder.destroy');

    //new
    Route::post('cancelledOrder/reactivate', 'CancelledOrderController@reactivate');


    //product to brand
    Route::get('productToBrand', 'ProductToBrandController@index')->name('productToBrand.index');
    Route::post('productToBrand/getBrandsToRelate', 'ProductToBrandController@getBrandsToRelate');
    Route::post('productToBrand/getRelatedBrands', 'ProductToBrandController@getRelatedBrands');
    Route::post('productToBrand/relateProductToBrand', 'ProductToBrandController@relateProductToBrand');


    //precarrier
    Route::post('preCarrier/filter/', 'PreCarrierController@filter');
    Route::get('preCarrier', 'PreCarrierController@index')->name('preCarrier.index');
    Route::get('preCarrier/create', 'PreCarrierController@create')->name('preCarrier.create');
    Route::post('preCarrier', 'PreCarrierController@store')->name('preCarrier.store');
    Route::get('preCarrier/{id}/edit', 'PreCarrierController@edit')->name('preCarrier.edit');
    Route::patch('preCarrier/{id}', 'PreCarrierController@update')->name('preCarrier.update');
    Route::delete('preCarrier/{id}', 'PreCarrierController@destroy')->name('preCarrier.destroy');

    //Payment Terms
    Route::post('paymentTerms/filter/', 'PaymentTermsController@filter');
    Route::get('paymentTerms', 'PaymentTermsController@index')->name('paymentTerms.index');
    Route::get('paymentTerms/create', 'PaymentTermsController@create')->name('paymentTerms.create');
    Route::post('paymentTerms', 'PaymentTermsController@store')->name('paymentTerms.store');
    Route::get('paymentTerms/{id}/edit', 'PaymentTermsController@edit')->name('paymentTerms.edit');
    Route::patch('paymentTerms/{id}', 'PaymentTermsController@update')->name('paymentTerms.update');
    Route::delete('paymentTerms/{id}', 'PaymentTermsController@destroy')->name('paymentTerms.destroy');

//Shipping Terms
    Route::post('shippingTerms/filter/', 'ShippingTermsController@filter');
    Route::get('shippingTerms', 'ShippingTermsController@index')->name('shippingTerms.index');
    Route::get('shippingTerms/create', 'ShippingTermsController@create')->name('shippingTerms.create');
    Route::post('shippingTerms', 'ShippingTermsController@store')->name('shippingTerms.store');
    Route::get('shippingTerms/{id}/edit', 'ShippingTermsController@edit')->name('shippingTerms.edit');
    Route::patch('shippingTerms/{id}', 'ShippingTermsController@update')->name('shippingTerms.update');
    Route::delete('shippingTerms/{id}', 'ShippingTermsController@destroy')->name('shippingTerms.destroy');
//Bank
    Route::post('bank/filter/', 'BankController@filter');
    Route::get('bank', 'BankController@index')->name('bank.index');
    Route::get('bank/create', 'BankController@create')->name('bank.create');
    Route::post('bank', 'BankController@store')->name('bank.store');
    Route::get('bank/{id}/edit', 'BankController@edit')->name('bank.edit');
    Route::patch('bank/{id}', 'BankController@update')->name('bank.update');
    Route::delete('bank/{id}', 'BankController@destroy')->name('bank.destroy');

    //Contact Designation Managment
    Route::post('contactDesignation/filter/', 'ContactDesignationController@filter');
    Route::get('contactDesignation', 'ContactDesignationController@index')->name('contactDesignation.index');
    Route::get('contactDesignation/create', 'ContactDesignationController@create')->name('contactDesignation.create');
    Route::post('contactDesignation', 'ContactDesignationController@store')->name('contactDesignation.store');
    Route::get('contactDesignation/{id}/edit', 'ContactDesignationController@edit')->name('contactDesignation.edit');
    Route::patch('contactDesignation/{id}', 'ContactDesignationController@update')->name('contactDesignation.update');
    Route::delete('contactDesignation/{id}', 'ContactDesignationController@destroy')->name('contactDesignation.destroy');

    //CONFIGURATION
    Route::get('configuration', 'ConfigurationController@index')->name('configuration.index');
    Route::get('configuration/create', 'ConfigurationController@create')->name('configuration.create');
    Route::post('configuration', 'ConfigurationController@store')->name('configuration.store');
    Route::post('configuration/saveCompanyInfo', 'ConfigurationController@saveCompanyInfo');


    //KONITA BANK
    Route::post('konitaBank/filter/', 'KonitaBankController@filter');
    Route::get('konitaBank', 'KonitaBankController@index')->name('konitaBank.index');
    Route::get('konitaBank/create', 'KonitaBankController@create')->name('konitaBank.create');
    Route::post('konitaBank', 'KonitaBankController@store')->name('konitaBank.store');
    Route::get('konitaBank/{id}/edit', 'KonitaBankController@edit')->name('konitaBank.edit');
    Route::patch('konitaBank/{id}', 'KonitaBankController@update')->name('konitaBank.update');
    Route::delete('konitaBank/{id}', 'KonitaBankController@destroy')->name('konitaBank.destroy');

    //Shipping Line
    Route::post('shippingLine/filter/', 'ShippingLineController@filter');
    Route::get('shippingLine', 'ShippingLineController@index')->name('shippingLine.index');
    Route::get('shippingLine/create', 'ShippingLineController@create')->name('shippingLine.create');
    Route::post('shippingLine', 'ShippingLineController@store')->name('shippingLine.store');
    Route::get('shippingLine/{id}/edit', 'ShippingLineController@edit')->name('shippingLine.edit');
    Route::patch('shippingLine/{id}', 'ShippingLineController@update')->name('shippingLine.update');
    Route::delete('shippingLine/{id}', 'ShippingLineController@destroy')->name('shippingLine.destroy');

    //Billing
    Route::get('billing/billingCreate', 'BillingController@billingCreate');
    Route::post('billing/getBillingCreateData', 'BillingController@getBillingCreateData');
    Route::post('billing/billingInvoiceStore', 'BillingController@billingInvoiceStore');
    Route::get('billing/billingLedgerView', 'BillingController@billingLedgerView');
    Route::post('billing/billingLedgerDetails', 'BillingController@billingLedgerDetails');
    Route::post('billing/billingFullLedgerDetails', 'BillingController@billingFullLedgerDetails');
    Route::get('billing/billingFullLedgerDetailsPrint', 'BillingController@billingFullLedgerDetailsPrint');
    Route::post('billing/filter', 'BillingController@filter');
    Route::get('billing/billingLedgerPrint', 'BillingController@billingLedgerPrint');
    Route::get('billing/billingLedgerPdf', 'BillingController@billingLedgerPdf');
    Route::post('billing/commissionDetails', 'BillingController@commissionDetails');
    //new
    Route::post('billing/getCommissionSetupModal', 'BillingController@getCommissionSetupModal');
    Route::post('billing/commissionSetupSave', 'BillingController@commissionSetupSave');
    Route::post('billing/approve', 'BillingController@approve');
    Route::post('billing/deny', 'BillingController@deny');
    Route::delete('billing/ledger/{id}', 'BillingController@destroy')->name('billing.destroy');

    //principal ledger
    Route::get('principalLedger', 'PrincipalLedgerController@index');
    Route::post('principalLedger/filter', 'PrincipalLedgerController@filter');

    //certificate 
    Route::post('certificate/filter/', 'CertificateController@filter');
    Route::get('certificate', 'CertificateController@index')->name('certificate.index');
    Route::get('certificate/create', 'CertificateController@create')->name('certificate.create');
    Route::post('certificate', 'CertificateController@store')->name('certificate.store');
    Route::get('certificate/{id}/edit', 'CertificateController@edit')->name('certificate.edit');
    Route::patch('certificate/{id}', 'CertificateController@update')->name('certificate.update');
    Route::delete('certificate/{id}', 'CertificateController@destroy')->name('certificate.destroy');

    //Grade Mgt
    Route::post('grade/filter/', 'GradeController@filter');
    Route::get('grade', 'GradeController@index')->name('grade.index');
    Route::get('grade/create', 'GradeController@create')->name('grade.create');
    Route::post('grade', 'GradeController@store')->name('grade.store');
    Route::get('grade/{id}/edit', 'GradeController@edit')->name('grade.edit');
    Route::patch('grade/{id}', 'GradeController@update')->name('grade.update');
    Route::delete('grade/{id}', 'GradeController@destroy')->name('grade.destroy');


    //cause of failure
    Route::post('causeOfFailure/filter/', 'CauseOfFailureController@filter');
    Route::get('causeOfFailure', 'CauseOfFailureController@index')->name('causeOfFailure.index');
    Route::get('causeOfFailure/create', 'CauseOfFailureController@create')->name('causeOfFailure.create');
    Route::post('causeOfFailure', 'CauseOfFailureController@store')->name('causeOfFailure.store');
    Route::get('causeOfFailure/{id}/edit', 'CauseOfFailureController@edit')->name('causeOfFailure.edit');
    Route::patch('causeOfFailure/{id}', 'CauseOfFailureController@update')->name('causeOfFailure.update');
    Route::delete('causeOfFailure/{id}', 'CauseOfFailureController@destroy')->name('causeOfFailure.destroy');



    //product to grade
    Route::get('productToGrade', 'ProductToGradeController@index')->name('productToGrade.index');
    Route::post('productToGrade/getRelatedGrades', 'ProductToGradeController@getRelatedGrades');
    Route::post('productToGrade/relateProductToGrade', 'ProductToGradeController@relateProductToGrade');

    //recieve
    Route::get('receive', 'ReceiveController@create');
    Route::post('receive/getReceiveData', 'ReceiveController@getReceiveData');
    Route::post('receive/previewReceiveData', 'ReceiveController@previewReceiveData');
    Route::post('receive/setReceiveData', 'ReceiveController@setReceiveData');


    //Beneficiary bank
    Route::post('beneficiaryBank/filter/', 'BeneficiaryBankController@filter');
    Route::get('beneficiaryBank', 'BeneficiaryBankController@index')->name('beneficiaryBank.index');
    Route::get('beneficiaryBank/create', 'BeneficiaryBankController@create')->name('beneficiaryBank.create');
    Route::post('beneficiaryBank', 'BeneficiaryBankController@store')->name('beneficiaryBank.store');
    Route::get('beneficiaryBank/{id}/edit', 'BeneficiaryBankController@edit')->name('beneficiaryBank.edit');
    Route::patch('beneficiaryBank/{id}', 'BeneficiaryBankController@update')->name('beneficiaryBank.update');
    Route::delete('beneficiaryBank/{id}', 'BeneficiaryBankController@destroy')->name('beneficiaryBank.destroy');

    //payment status
    Route::get('paymentStatus', 'PaymentStatusController@create');
    Route::post('paymentStatus/getPaymentStatus', 'PaymentStatusController@getPaymentStatus');
    Route::post('paymentStatus/setPaymentStatus', 'PaymentStatusController@setPaymentStatus');

    //supplier ledger
    Route::get('supplierLedger', 'SupplierLedgerController@index');
    Route::post('supplierLedger/filter', 'SupplierLedgerController@filter');


    //followup status
    Route::post('followupStatus/filter/', 'FollowupStatusController@filter');
    Route::get('followupStatus', 'FollowupStatusController@index')->name('followupStatus.index');
    Route::get('followupStatus/create', 'FollowupStatusController@create')->name('followupStatus.create');
    Route::post('followupStatus', 'FollowupStatusController@store')->name('followupStatus.store');
    Route::get('followupStatus/{id}/edit', 'FollowupStatusController@edit')->name('followupStatus.edit');
    Route::patch('followupStatus/{id}', 'FollowupStatusController@update')->name('followupStatus.update');
    Route::delete('followupStatus/{id}', 'FollowupStatusController@destroy')->name('followupStatus.destroy');

    //transfer buyer to sales person
    Route::get('transferBuyerToSalesPerson', 'TransferBuyerToSalesPersonController@index')->name('salesPersonToBuyer.index');
    Route::post('transferBuyerToSalesPerson/getBuyersToRelate', 'TransferBuyerToSalesPersonController@getBuyersToRelate');
    Route::post('transferBuyerToSalesPerson/getRelatedProducts', 'TransferBuyerToSalesPersonController@getRelatedProducts');
    Route::post('transferBuyerToSalesPerson/getSalesPersonToTransfer', 'TransferBuyerToSalesPersonController@getSalesPersonToTransfer');
    Route::post('transferBuyerToSalesPerson/relateSalesPersonToBuyer', 'TransferBuyerToSalesPersonController@relateSalesPersonToBuyer');
    Route::post('transferBuyerToSalesPerson/getRelatedSalesPersonList', 'TransferBuyerToSalesPersonController@getRelatedSalesPersonList');
    Route::get('transferBuyerToSalesPerson/getRelatedSalesPersonListPrint', 'TransferBuyerToSalesPersonController@getRelatedSalesPersonListPrint');

    //sales person payment
    Route::get('salesPersonPayment', 'SalesPersonPaymentController@create');
    Route::post('salesPersonPayment/getPayment', 'SalesPersonPaymentController@getPayment');
    Route::post('salesPersonPayment/previewPayment', 'SalesPersonPaymentController@previewPayment');
    Route::post('salesPersonPayment/setPayment', 'SalesPersonPaymentController@setPayment');
    Route::get('salesPersonPayment/setPaymentWithPrint', 'SalesPersonPaymentController@setPaymentWithPrint');

    //sales person payment voucher
    Route::get('salesPersonPaymentVoucher', 'SalesPersonPaymentVoucherController@index');
    Route::post('salesPersonPaymentVoucher/filter', 'SalesPersonPaymentVoucherController@filter');
    Route::get('salesPersonPaymentVoucher/voucherPrint', 'SalesPersonPaymentVoucherController@voucherPrint');
    Route::post('salesPersonPaymentVoucher/approve', 'SalesPersonPaymentVoucherController@approve');
    Route::post('salesPersonPaymentVoucher/deny', 'SalesPersonPaymentVoucherController@deny');

    //sales person ledger
    Route::get('salesPersonLedger', 'SalesPersonLedgerController@index');
    Route::post('salesPersonLedger/filter', 'SalesPersonLedgerController@filter');
    Route::post('salesPersonLedger/shipment', 'SalesPersonLedgerController@shipment');

    //buyer payment
    Route::get('buyerPayment', 'BuyerPaymentController@create');
    Route::post('buyerPayment/getPayment', 'BuyerPaymentController@getPayment');
    Route::post('buyerPayment/previewPayment', 'BuyerPaymentController@previewPayment');
    Route::post('buyerPayment/setPayment', 'BuyerPaymentController@setPayment');
    Route::get('buyerPayment/setPaymentWithPrint', 'BuyerPaymentController@setPaymentWithPrint');

    //buyer payment voucher
    Route::get('buyerPaymentVoucher', 'BuyerPaymentVoucherController@index');
    Route::post('buyerPaymentVoucher/filter', 'BuyerPaymentVoucherController@filter');
    Route::get('buyerPaymentVoucher/voucherPrint', 'BuyerPaymentVoucherController@voucherPrint');
    Route::post('buyerPaymentVoucher/approve', 'BuyerPaymentVoucherController@approve');
    Route::post('buyerPaymentVoucher/deny', 'BuyerPaymentVoucherController@deny');

    //buyer ledger
    Route::get('buyerLedger', 'BuyerLedgerController@index');
    Route::post('buyerLedger/filter', 'BuyerLedgerController@filter');
    Route::post('buyerLedger/shipment', 'BuyerLedgerController@shipment');

    //buyer followup
    Route::get('buyerFollowup', 'BuyerFollowupController@index');
    Route::post('buyerFollowup/filter', 'BuyerFollowupController@filter');
    Route::post('buyerFollowup/getAddFollowup', 'BuyerFollowupController@getAddFollowup');
    Route::post('buyerFollowup/setAddFollowup', 'BuyerFollowupController@setAddFollowup');

    //**************************** CRM *******************************
    //CRM Activity Type
    Route::post('crmActivityType/filter/', 'CrmActivityTypeController@filter');
    Route::get('crmActivityType', 'CrmActivityTypeController@index')->name('crmActivityType.index');
    Route::get('crmActivityType/create', 'CrmActivityTypeController@create')->name('crmActivityType.create');
    Route::post('crmActivityType', 'CrmActivityTypeController@store')->name('crmActivityType.store');
    Route::get('crmActivityType/{id}/edit', 'CrmActivityTypeController@edit')->name('crmActivityType.edit');
    Route::patch('crmActivityType/{id}', 'CrmActivityTypeController@update')->name('crmActivityType.update');
    Route::delete('crmActivityType/{id}', 'CrmActivityTypeController@destroy')->name('crmActivityType.destroy');

    //CRM Source
    Route::post('crmSource/filter/', 'CrmSourceController@filter');
    Route::get('crmSource', 'CrmSourceController@index')->name('crmSource.index');
    Route::get('crmSource/create', 'CrmSourceController@create')->name('crmSource.create');
    Route::post('crmSource', 'CrmSourceController@store')->name('crmSource.store');
    Route::get('crmSource/{id}/edit', 'CrmSourceController@edit')->name('crmSource.edit');
    Route::patch('crmSource/{id}', 'CrmSourceController@update')->name('crmSource.update');
    Route::delete('crmSource/{id}', 'CrmSourceController@destroy')->name('crmSource.destroy');

    //CRM New Opportunity
    Route::post('crmNewOpportunity/filter/', 'CrmNewOpportunityController@filter');
    Route::get('crmNewOpportunity', 'CrmNewOpportunityController@index')->name('crmNewOpportunity.index');
    Route::get('crmNewOpportunity/create', 'CrmNewOpportunityController@create')->name('crmNewOpportunity.create');
    Route::post('crmNewOpportunitystore/store', 'CrmNewOpportunityController@store')->name('crmNewOpportunity.store');
    Route::get('crmNewOpportunity/{id}/edit', 'CrmNewOpportunityController@edit')->name('crmNewOpportunity.edit');
    Route::post('crmNewOpportunity/update', 'CrmNewOpportunityController@update')->name('crmNewOpportunity.update');
    Route::delete('crmNewOpportunity/{id}', 'CrmNewOpportunityController@destroy')->name('crmNewOpportunity.destroy');
    Route::post('crmNewOpportunity/getOpportunityDetails', 'CrmNewOpportunityController@getOpportunityDetails');
    Route::post('crmNewOpportunity/getOpportunityToMemberToRelate', 'CrmNewOpportunityController@getOpportunityToMemberToRelate');
    Route::post('crmNewOpportunity/relateOpportunityToMember', 'CrmNewOpportunityController@relateOpportunityToMember');

    //CRM My Opportunity
    Route::post('crmMyOpportunity/filter/', 'CrmMyOpportunityController@filter');
    Route::get('crmMyOpportunity', 'CrmMyOpportunityController@index')->name('crmMyOpportunity.index');
    Route::get('crmMyOpportunity/{id}/edit', 'CrmMyOpportunityController@edit')->name('crmMyOpportunity.edit');
    Route::post('crmMyOpportunity/update', 'CrmMyOpportunityController@update')->name('crmMyOpportunity.update');
    Route::post('crmMyOpportunity/getOpportunityDetails', 'CrmMyOpportunityController@getOpportunityDetails');
    Route::post('crmMyOpportunity/opportunityCancellationModal', 'CrmMyOpportunityController@opportunityCancellationModal');
    Route::post('crmMyOpportunity/cancel', 'CrmMyOpportunityController@cancel');
    Route::post('crmMyOpportunity/opportunityVoidModal', 'CrmMyOpportunityController@opportunityVoidModal');
    Route::post('crmMyOpportunity/void', 'CrmMyOpportunityController@void');
    Route::get('crmMyOpportunity/quotation/{id}', 'CrmMyOpportunityController@quotation');
    Route::post('crmMyOpportunity/quotationSave', 'CrmMyOpportunityController@quotationSave');
    Route::post('crmMyOpportunity/getOpportunityActivityLogModal', 'CrmMyOpportunityController@getOpportunityActivityLogModal');
    Route::post('crmMyOpportunity/getActivityContactPersonData', 'CrmMyOpportunityController@getActivityContactPersonData');
    Route::post('crmMyOpportunity/saveActivityContactPersonData', 'CrmMyOpportunityController@saveActivityContactPersonData');
    Route::post('crmMyOpportunity/saveActivityModal', 'CrmMyOpportunityController@saveActivityModal');

    //CRM Booked Opportunity
    Route::post('crmBookedOpportunity/filter/', 'CrmBookedOpportunityController@filter');
    Route::get('crmBookedOpportunity', 'CrmBookedOpportunityController@index')->name('crmBookedOpportunity.index');
    Route::post('crmBookedOpportunity/getOpportunityDetails', 'CrmBookedOpportunityController@getOpportunityDetails');
    Route::post('crmBookedOpportunity/getOpportunityActivityLogModal', 'CrmBookedOpportunityController@getOpportunityActivityLogModal');
    Route::post('crmBookedOpportunity/dispatch', 'CrmBookedOpportunityController@doDispatch');

    //CRM Void Opportunity
    Route::post('crmVoidOpportunity/filter/', 'CrmVoidOpportunityController@filter');
    Route::get('crmVoidOpportunity', 'CrmVoidOpportunityController@index')->name('crmVoidOpportunity.index');
    Route::post('crmVoidOpportunity/getOpportunityActivityLogModal', 'CrmVoidOpportunityController@getOpportunityActivityLogModal');
    Route::post('crmVoidOpportunity/getOpportunityDetails', 'CrmVoidOpportunityController@getOpportunityDetails');

    //CRM Cancelled Opportunity
    Route::post('crmCancelledOpportunity/filter/', 'CrmCancelledOpportunityController@filter');
    Route::get('crmCancelledOpportunity', 'CrmCancelledOpportunityController@index')->name('crmCancelledOpportunity.index');
    Route::post('crmCancelledOpportunity/reactivate', 'CrmCancelledOpportunityController@reactivate');
    Route::post('crmCancelledOpportunity/getOpportunityActivityLogModal', 'CrmCancelledOpportunityController@getOpportunityActivityLogModal');
    Route::post('crmCancelledOpportunity/getOpportunityDetails', 'CrmCancelledOpportunityController@getOpportunityDetails');

    Route::group(['middleware' => ['crmMemberSuperAdminGroup']], function () use($prefix) {
        //CRM Schedule Calendar
        Route::get('crmScheduleCalendar', 'CrmScheduleCalendarController@index')->name('crmScheduleCalendar.index');
        Route::post('crmScheduleCalendar/scheduleDone', 'CrmScheduleCalendarController@scheduleDone');
    });

    //Pending Inquiry
    Route::post('pendingInquiry/filter/', 'PendingInquiryController@filter');
    Route::get('pendingInquiry', 'PendingInquiryController@index')->name('pendingInquiry.index');
    Route::post('pendingInquiry/getOpportunityDetails', 'PendingInquiryController@getOpportunityDetails');
    Route::post('pendingInquiry/showRemarksModal', 'PendingInquiryController@showRemarksModal');
    Route::post('pendingInquiry/approve', 'PendingInquiryController@approve');
    Route::post('pendingInquiry/deny', 'PendingInquiryController@deny');
    Route::post('pendingInquiry/getOpportunityActivityLogModal', 'PendingInquiryController@getOpportunityActivityLogModal');

    Route::group(['middleware' => ['crmLeaderSuperAdminGroup']], function () use($prefix) {
        //CRM All Opportunity
        Route::post('crmAllOpportunity/filter/', 'CrmAllOpportunityController@filter');
        Route::get('crmAllOpportunity', 'CrmAllOpportunityController@index')->name('crmAllOpportunity.index');
        Route::post('crmAllOpportunity/getOpportunityDetails', 'CrmAllOpportunityController@getOpportunityDetails');
        Route::post('crmAllOpportunity/getOpportunityToMemberToRelate', 'CrmAllOpportunityController@getOpportunityToMemberToRelate');
        Route::post('crmAllOpportunity/relateOpportunityToMember', 'CrmAllOpportunityController@relateOpportunityToMember');
        Route::post('crmAllOpportunity/getOpportunityReassigned', 'CrmAllOpportunityController@getOpportunityReassigned');
        Route::post('crmAllOpportunity/setOpportunityReassigned', 'CrmAllOpportunityController@setOpportunityReassigned');
        Route::post('crmAllOpportunity/revoke', 'CrmAllOpportunityController@revoke');
        Route::post('crmAllOpportunity/getOpportunityActivityLogModal', 'CrmAllOpportunityController@getOpportunityActivityLogModal');
    });

    Route::group(['middleware' => ['crmLeaderGroup']], function () use($prefix) {
        //CRM Team Distribution
        Route::get('crmOpportunityToMember', 'CrmOpportunityToMemberController@index')->name('crmOpportunityToMember.index');
        Route::post('crmOpportunityToMember/getOpportunityToRelate', 'CrmOpportunityToMemberController@getOpportunityToRelate');
        Route::post('crmOpportunityToMember/getRelatedOpportunities', 'CrmOpportunityToMemberController@getRelatedOpportunities');
        Route::post('crmOpportunityToMember/relateOpportunityToMember', 'CrmOpportunityToMemberController@relateOpportunityToMember');

        //Opportunity Reassignment
        Route::get('crmReassignmentOpportunity', 'CrmReassignmentOpportunityController@index')->name('crmReassignmentOpportunity.index');
        Route::post('crmReassignmentOpportunity/getOpportunityToRelate', 'CrmReassignmentOpportunityController@getOpportunityToRelate');
        Route::post('crmReassignmentOpportunity/getMemberToTransfer', 'CrmReassignmentOpportunityController@getMemberToTransfer');
        Route::post('crmReassignmentOpportunity/relateMemberToOpportunity', 'CrmReassignmentOpportunityController@relateMemberToOpportunity');

        //Opportunity Revoke
        Route::get('crmRevokeOpportunity', 'CrmRevokeOpportunityController@index')->name('crmRevokeOpportunity.index');
        Route::post('crmRevokeOpportunity/revoke', 'CrmRevokeOpportunityController@revoke');
    });
    //**************************** CRM *******************************
    //************************ REPORT START ******************************
    //sales volume report
    Route::get('salesVolumeReport', 'SalesVolumeReportController@index');
    Route::post('salesVolumeReport/filter', 'SalesVolumeReportController@filter');
    Route::post('salesVolumeReport/getShipmentDetails', 'SalesVolumeReportController@getShipmentDetails');
    Route::get('salesVolumeReport/getShipmentDetailsPrint', 'SalesVolumeReportController@getShipmentDetailsPrint');

    //sales status report
    Route::get('salesStatusReport', 'SalesStatusReportController@index');
    Route::post('salesStatusReport/filter', 'SalesStatusReportController@filter');
    Route::post('salesStatusReport/getShipmentDetails', 'SalesStatusReportController@getShipmentDetails');
    Route::get('salesStatusReport/getShipmentDetailsPrint', 'SalesStatusReportController@getShipmentDetailsPrint');

    //market engagement
    Route::get('marketEngagement', 'MarketEngagementController@index');
    Route::post('marketEngagement/filter', 'MarketEngagementController@filter');

    //market forecast
    Route::get('newMarketForecast', 'NewMarketForecastController@index');
    Route::post('newMarketForecast/filter', 'NewMarketForecastController@filter');

    //sales summary report
    Route::get('salesSummaryReport', 'SalesSummaryReportController@index');
    Route::post('salesSummaryReport/filter', 'SalesSummaryReportController@filter');

    //brand wise sales summary report
    Route::get('brandWiseSalesSummaryReport', 'BrandWiseSalesSummaryReportController@index');
    Route::post('brandWiseSalesSummaryReport/filter', 'BrandWiseSalesSummaryReportController@filter');

    //supplier wise sales summary report
    Route::get('supplierWiseSalesSummaryReport', 'SupplierWiseSalesSummaryReportController@index');
    Route::post('supplierWiseSalesSummaryReport/filter', 'SupplierWiseSalesSummaryReportController@filter');

    //buyer summary report
    Route::get('buyerSummaryReport', 'BuyerSummaryReportController@index');
    Route::post('buyerSummaryReport/filter', 'BuyerSummaryReportController@filter');
    Route::get('buyerSummaryReport/{id}/profile', 'BuyerSummaryReportController@profile');
    Route::get('buyerSummaryReport/{id}/printProfile', 'BuyerSummaryReportController@printProfile');

    //idly engaged buyer report
    Route::get('idlyEngagedBuyerReport', 'IdlyEngagedBuyerReportController@index');
    Route::post('idlyEngagedBuyerReport/filter', 'IdlyEngagedBuyerReportController@filter');
    Route::get('idlyEngagedBuyerReport/{id}/profile', 'IdlyEngagedBuyerReportController@profile');
    Route::get('idlyEngagedBuyerReport/{id}/printProfile', 'IdlyEngagedBuyerReportController@printProfile');

    //CRM status report
    Route::get('crmStatusReport', 'CrmStatusReportController@index');
    Route::post('crmStatusReport/filter', 'CrmStatusReportController@filter');
    Route::post('crmStatusReport/getOpportunityActivityLogModal', 'CrmStatusReportController@getOpportunityActivityLogModal');

    //CRM summary report
    Route::get('crmSummaryReport', 'CrmSummaryReportController@index');
    Route::post('crmSummaryReport/filter', 'CrmSummaryReportController@filter');

    //DB Backup
    Route::post('dbBackup/filter', 'DbBackupController@filter');
    Route::post('dbBackup/download', 'DbBackupController@download');
    Route::get('dbBackup', 'DbBackupController@index');
    Route::post('dbBackup/downloadFile', 'DbBackupController@downloadFile');

    //DB Backup Download Log Report
    Route::get('dbBackupDownloadLogReport', 'DbBackupDownloadLogReportController@index');
    Route::get('dbBackupDownloadLogReport/{view?}', 'DbBackupDownloadLogReportController@index');
    Route::post('dbBackupDownloadLogReport/filter', 'DbBackupDownloadLogReportController@Filter');

    // Buyer Message
    Route::group(['middleware' => ['messagingAllowedUser']], function () use($prefix) {
        Route::get('buyerMessage', 'BuyerMessageController@index');
        Route::post('buyerMessage/getOrderMessaging', 'BuyerMessageController@getOrderMessaging');
        Route::post('buyerMessage/filter', 'BuyerMessageController@filter');
        Route::post('buyerMessage/getMessageBody', 'BuyerMessageController@getMessageBody');
        Route::post('buyerMessage/setMessage', 'BuyerMessageController@setMessage');
    });


    Route::group(['middleware' => ['quotationAllowedUser']], function () use($prefix) {
        // Quotation Request
        Route::get('quotationRequest', 'QuotationRequestController@index');
        Route::get('quotationRequest/buyerQuotationReqDetails/{id}/{view?}', 'QuotationRequestController@buyerQuotationReqDetails');
        Route::post('quotationRequest/filter', 'QuotationRequestController@filter');
        Route::post('quotationRequest/buyerQuotationReqDetails', 'QuotationRequestController@buyerQuotationReqDetails')->name('quotationRequest.buyerQuotationReqDetails');
    });
});

