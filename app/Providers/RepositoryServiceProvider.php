<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\AcctgFundCodeRepositoryInterface;
use App\Interfaces\AcctgDepartmentRepositoryInterface;
use App\Interfaces\AcctgDebitMemoInterface;
use App\Interfaces\AcctgAccountGeneralLedgerRepositoryInterface;
use App\Interfaces\AcctgAccountGroupMajorRepositoryInterface;
use App\Interfaces\AcctgAccountGroupRepositoryInterface;
use App\Interfaces\AcctgAccountGroupSubmajorRepositoryInterface;
use App\Interfaces\GsoItemRepositoryInterface;
use App\Interfaces\GsoItemCategoryRepositoryInterface;
use App\Interfaces\GsoItemTypeRepositoryInterface;
use App\Interfaces\GsoProductLineRepositoryInterface;
use App\Interfaces\GsoPurchaseTypeRepositoryInterface;
use App\Interfaces\GsoUnitOfMeasurementRepositoryInterface;
use App\Interfaces\GsoSupplierRepositoryInterface;
use App\Interfaces\GsoDepartmentalRequisitionRepositoryInterface;
use App\Interfaces\HrDesignationRepositoryInterface;
use App\Interfaces\HrEmployeeRepositoryInterface;
use App\Interfaces\CboPayeeInterface;
use App\Interfaces\ComponentMenuGroupInterface;
use App\Interfaces\ComponentMenuModuleInterface;
use App\Interfaces\ComponentMenuSubModuleInterface;
use App\Interfaces\ComponentPermissionInterface;
use App\Interfaces\GsoIssuanceRequestorInterface;
use App\Interfaces\ComponentUserRoleInterface;
use App\Interfaces\ComponentUserAccountInterface;
use App\Interfaces\GsoIssuanceApproverInterface;
use App\Interfaces\GsoObligationRequestInterface;
use App\Interfaces\CboBudgetAllocationInterface;
use App\Interfaces\AcctgAccountSubsidiaryLedgerInterface;
use App\Interfaces\InquiriesByArpNoInterface;
use App\Interfaces\GsoPurchaseRequestInterface;
use App\Interfaces\BacProcurementModeInterface;
use App\Interfaces\BacRequestForQuotationInterface;
use App\Interfaces\BacAbstractOfCanvassInterface;
use App\Interfaces\BacResolutionInterface;
use App\Interfaces\GsoPurchaseOrderInterface;
use App\Interfaces\GsoIssuanceInterface;
use App\Interfaces\CboBudgetInterface;
use App\Interfaces\GsoInventoryInterface;
use App\Interfaces\AcctgExpandedVatableTaxesInterface;
use App\Interfaces\AcctgExpandedWithholdingTaxesInterface;
use App\Interfaces\AcctgPaymentTypeInterface;
use App\Interfaces\AcctgAccountPayableInterface;
use App\Interfaces\AcctgAccountDisbursementInterface;
use App\Interfaces\AcctgAccountVoucherInterface;
use App\Interfaces\EngineeringInterface;
use App\Interfaces\HrInterface;
use App\Interfaces\AcctgBankInterface;
use App\Interfaces\GsoPPMPInterface;
use App\Interfaces\ComponentApprovalSettingInterface;
use App\Interfaces\CboObligationTypeInterface;
use App\Interfaces\CtoDisburseInterface;
use App\Interfaces\CtoReplenishInterface;
use App\Interfaces\AcctgFixedAssetInterface;
use App\Interfaces\GsoPreRepairInspectionInterface;
use App\Interfaces\ComponentSMSNotificationInterface;
use App\Interfaces\ReportItemCanvassInterface;
use App\Interfaces\AcctgGeneralJournalInterface;
use App\Interfaces\AcctgCollectionReportInterface;
use App\Interfaces\ReportAcctgLedgerInterface;
use App\Interfaces\CtoCollectionInterface;
use App\Interfaces\ReportAcctgFixedAssetInterface;
use App\Interfaces\ReportAcctgJournalInterface;
use App\Interfaces\EconCemeteryInterface;
use App\Interfaces\EconRentalInterface;
use App\Interfaces\AcctgAccountIncomeInterface;
use App\Interfaces\EcoHousingPenaltyInterface;
use App\Interfaces\AcctgAccountReceivableInterface;
use App\Interfaces\ReportTreasuryCollectionInterface;
use App\Interfaces\GsoWasteMaterialInterface;
use App\Interfaces\ReportAcctgRecapInterface;
use App\Interfaces\ReportAcctgTrialBalanceInterface;
use App\Interfaces\ComponentFAQInterface;

use App\Repositories\AcctgFundCodeRepository;
use App\Repositories\AcctgAccountGeneralLedgerRepository;
use App\Repositories\AcctgAccountGroupMajorRepository;
use App\Repositories\AcctgAccountGroupRepository;
use App\Repositories\AcctgAccountGroupSubmajorRepository;
use App\Repositories\AcctgDepartmentRepository;
use App\Repositories\AcctgDebitMemoRepository;
use App\Repositories\GsoItemRepository;
use App\Repositories\GsoItemCategoryRepository;
use App\Repositories\GsoItemTypeRepository;
use App\Repositories\GsoProductLineRepository;
use App\Repositories\GsoPurchaseTypeRepository;
use App\Repositories\GsoUnitOfMeasurementRepository;
use App\Repositories\GsoSupplierRepository;
use App\Repositories\GsoDepartmentalRequisitionRepository;
use App\Repositories\HrDesignationRepository;
use App\Repositories\HrEmployeeRepository;
use App\Repositories\CboPayeeRepository;
use App\Repositories\ComponentMenuGroupRepository;
use App\Repositories\ComponentMenuModuleRepository;
use App\Repositories\ComponentMenuSubModuleRepository;
use App\Repositories\ComponentPermissionRepository;
use App\Repositories\GsoIssuanceRequestorRepository;
use App\Repositories\ComponentUserRoleRepository;
use App\Repositories\ComponentUserAccountRepository;
use App\Repositories\GsoIssuanceApproverRepository;
use App\Repositories\GsoObligationRequestRepository;
use App\Repositories\CboBudgetAllocationRepository;
use App\Repositories\AcctgAccountSubsidiaryLedgerRepository;
use App\Repositories\InquiriesByArpNoRepository;
use App\Repositories\GsoPurchaseRequestRepository;
use App\Repositories\BacProcurementModeRepository;
use App\Repositories\BacRequestForQuotationRepository;
use App\Repositories\BacAbstractOfCanvassRepository;
use App\Repositories\BacResolutionRepository;
use App\Repositories\GsoPurchaseOrderRepository;
use App\Repositories\GsoIssuanceRepository;
use App\Repositories\CboBudgetRepository;
use App\Repositories\GsoInventoryRepository;
use App\Repositories\AcctgExpandedVatableTaxesRepository;
use App\Repositories\AcctgExpandedWithholdingTaxesRepository;
use App\Repositories\AcctgPaymentTypeRepository;
use App\Repositories\AcctgAccountPayableRepository;
use App\Repositories\AcctgAccountDisbursementRepository;
use App\Repositories\AcctgAccountVoucherRepository;
use App\Repositories\EngineeringRepository;
use App\Repositories\HrRepository;
use App\Repositories\AcctgBankRepository;
use App\Repositories\GsoPPMPRepository;
use App\Repositories\ComponentApprovalSettingRepository;
use App\Repositories\CboObligationTypeRepository;
use App\Repositories\CtoDisburseRepository;
use App\Repositories\CtoReplenishRepository;
use App\Repositories\AcctgFixedAssetRepository;
use App\Repositories\GsoPreRepairInspectionRepository;
use App\Repositories\ComponentSMSNotificationRepository;
use App\Repositories\ReportItemCanvassRepository;
use App\Repositories\AcctgGeneralJournalRepository;
use App\Repositories\AcctgCollectionReportRepository;
use App\Repositories\ReportAcctgLedgerRepository;
use App\Repositories\CtoCollectionRepository;
use App\Repositories\ReportAcctgFixedAssetRepository;
use App\Repositories\ReportAcctgJournalRepository;
use App\Repositories\EconCemeteryRepository;
use App\Repositories\EconRentalRepository;
use App\Repositories\AcctgAccountIncomeRepository;
use App\Repositories\EcoHousingPenaltyRepository;
use App\Repositories\AcctgAccountReceivableRepository;
use App\Repositories\ReportTreasuryCollectionRepository;
use App\Repositories\GsoWasteMaterialRepository;
use App\Repositories\ReportAcctgRecapRepository;
use App\Repositories\ReportAcctgTrialBalanceRepository;
use App\Repositories\ComponentFAQRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(AcctgDepartmentRepositoryInterface::class, AcctgDepartmentRepository::class);
        $this->app->bind(AcctgFundCodeRepositoryInterface::class, AcctgFundCodeRepository::class);
        $this->app->bind(AcctgAccountGeneralLedgerRepositoryInterface::class, AcctgAccountGeneralLedgerRepository::class);
        $this->app->bind(AcctgAccountGroupMajorRepositoryInterface::class, AcctgAccountGroupMajorRepository::class);
        $this->app->bind(AcctgAccountGroupRepositoryInterface::class, AcctgAccountGroupRepository::class);
        $this->app->bind(AcctgAccountGroupSubmajorRepositoryInterface::class, AcctgAccountGroupSubmajorRepository::class);
        $this->app->bind(AcctgDebitMemoInterface::class, AcctgDebitMemoRepository::class);
        $this->app->bind(GsoItemRepositoryInterface::class, GsoItemRepository::class);
        $this->app->bind(GsoItemCategoryRepositoryInterface::class, GsoItemCategoryRepository::class);
        $this->app->bind(GsoItemTypeRepositoryInterface::class, GsoItemTypeRepository::class);
        $this->app->bind(GsoProductLineRepositoryInterface::class, GsoProductLineRepository::class);
        $this->app->bind(GsoPurchaseTypeRepositoryInterface::class, GsoPurchaseTypeRepository::class);
        $this->app->bind(GsoUnitOfMeasurementRepositoryInterface::class, GsoUnitOfMeasurementRepository::class);
        $this->app->bind(GsoSupplierRepositoryInterface::class, GsoSupplierRepository::class);
        $this->app->bind(GsoDepartmentalRequisitionRepositoryInterface::class, GsoDepartmentalRequisitionRepository::class);
        $this->app->bind(HrDesignationRepositoryInterface::class, HrDesignationRepository::class);
        $this->app->bind(HrEmployeeRepositoryInterface::class, HrEmployeeRepository::class);
        $this->app->bind(CboPayeeInterface::class, CboPayeeRepository::class);
        $this->app->bind(ComponentMenuGroupInterface::class, ComponentMenuGroupRepository::class);
        $this->app->bind(ComponentMenuModuleInterface::class, ComponentMenuModuleRepository::class);
        $this->app->bind(ComponentMenuSubModuleInterface::class, ComponentMenuSubModuleRepository::class);
        $this->app->bind(ComponentPermissionInterface::class, ComponentPermissionRepository::class);
        $this->app->bind(GsoIssuanceRequestorInterface::class, GsoIssuanceRequestorRepository::class);
        $this->app->bind(ComponentUserRoleInterface::class, ComponentUserRoleRepository::class);
        $this->app->bind(ComponentUserAccountInterface::class, ComponentUserAccountRepository::class);
        $this->app->bind(GsoIssuanceApproverInterface::class, GsoIssuanceApproverRepository::class);
        $this->app->bind(GsoObligationRequestInterface::class, GsoObligationRequestRepository::class);
        $this->app->bind(CboBudgetAllocationInterface::class, CboBudgetAllocationRepository::class);
        $this->app->bind(AcctgAccountSubsidiaryLedgerInterface::class, AcctgAccountSubsidiaryLedgerRepository::class);
        $this->app->bind(InquiriesByArpNoInterface::class, InquiriesByArpNoRepository::class);
        $this->app->bind(GsoPurchaseRequestInterface::class, GsoPurchaseRequestRepository::class);
        $this->app->bind(BacProcurementModeInterface::class, BacProcurementModeRepository::class);
        $this->app->bind(BacRequestForQuotationInterface::class, BacRequestForQuotationRepository::class);
        $this->app->bind(BacAbstractOfCanvassInterface::class, BacAbstractOfCanvassRepository::class);
        $this->app->bind(BacResolutionInterface::class, BacResolutionRepository::class);
        $this->app->bind(GsoPurchaseOrderInterface::class, GsoPurchaseOrderRepository::class);
        $this->app->bind(GsoIssuanceInterface::class, GsoIssuanceRepository::class);
        $this->app->bind(CboBudgetInterface::class, CboBudgetRepository::class);
        $this->app->bind(GsoInventoryInterface::class, GsoInventoryRepository::class);
        $this->app->bind(AcctgExpandedVatableTaxesInterface::class, AcctgExpandedVatableTaxesRepository::class);
        $this->app->bind(AcctgExpandedWithholdingTaxesInterface::class, AcctgExpandedWithholdingTaxesRepository::class);
        $this->app->bind(AcctgPaymentTypeInterface::class, AcctgPaymentTypeRepository::class);
        $this->app->bind(AcctgAccountPayableInterface::class, AcctgAccountPayableRepository::class);
        $this->app->bind(AcctgAccountVoucherInterface::class, AcctgAccountVoucherRepository::class);
        $this->app->bind(EngineeringInterface::class, EngineeringRepository::class);
        $this->app->bind(HrInterface::class, HrRepository::class);
        $this->app->bind(AcctgBankInterface::class, AcctgBankRepository::class);
        $this->app->bind(AcctgAccountDisbursementInterface::class, AcctgAccountDisbursementRepository::class);
        $this->app->bind(GsoPPMPInterface::class, GsoPPMPRepository::class);
        $this->app->bind(ComponentApprovalSettingInterface::class, ComponentApprovalSettingRepository::class);
        $this->app->bind(CboObligationTypeInterface::class, CboObligationTypeRepository::class);
        $this->app->bind(CtoDisburseInterface::class, CtoDisburseRepository::class);
        $this->app->bind(CtoReplenishInterface::class, CtoReplenishRepository::class);
        $this->app->bind(AcctgFixedAssetInterface::class, AcctgFixedAssetRepository::class);
        $this->app->bind(GsoPreRepairInspectionInterface::class, GsoPreRepairInspectionRepository::class);
        $this->app->bind(ComponentSMSNotificationInterface::class, ComponentSMSNotificationRepository::class);
        $this->app->bind(ReportItemCanvassInterface::class, ReportItemCanvassRepository::class);
        $this->app->bind(AcctgGeneralJournalInterface::class, AcctgGeneralJournalRepository::class);
        $this->app->bind(AcctgCollectionReportInterface::class, AcctgCollectionReportRepository::class);
        $this->app->bind(ReportAcctgLedgerInterface::class, ReportAcctgLedgerRepository::class);
        $this->app->bind(CtoCollectionInterface::class, CtoCollectionRepository::class);
        $this->app->bind(ReportAcctgFixedAssetInterface::class, ReportAcctgFixedAssetRepository::class);
        $this->app->bind(ReportAcctgJournalInterface::class, ReportAcctgJournalRepository::class);
        $this->app->bind(EconCemeteryInterface::class, EconCemeteryRepository::class);
        $this->app->bind(EconRentalInterface::class, EconRentalRepository::class);
        $this->app->bind(AcctgAccountIncomeInterface::class, AcctgAccountIncomeRepository::class);
        $this->app->bind(EcoHousingPenaltyInterface::class, EcoHousingPenaltyRepository::class);
        $this->app->bind(AcctgAccountReceivableInterface::class, AcctgAccountReceivableRepository::class);
        $this->app->bind(ReportTreasuryCollectionInterface::class, ReportTreasuryCollectionRepository::class);
        $this->app->bind(GsoWasteMaterialInterface::class, GsoWasteMaterialRepository::class);
        $this->app->bind(ReportAcctgRecapInterface::class, ReportAcctgRecapRepository::class);
        $this->app->bind(ReportAcctgTrialBalanceInterface::class, ReportAcctgTrialBalanceRepository::class);
        $this->app->bind(ComponentFAQInterface::class, ComponentFAQRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
