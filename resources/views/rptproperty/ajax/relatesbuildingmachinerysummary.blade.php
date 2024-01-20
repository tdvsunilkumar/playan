<style type="text/css">
    

.selected {
    background-color: #20B7CC;
    color: #FFF;
}
</style>
<div class="row">
            <div class="col-xl-12" style="margin-top:-33px;" >
                <div class="card">
                    <div class="card-body table-border-style">
                        <div class="table-responsive">
                            <table class="table" id="newAddedAssessementSummary">
                                <thead>
                                    <tr>
                                        <th >{{__('No')}}</th>
                                        <th >{{__('TD. NO.')}}</th>
                                        <th >{{__('Taxpayer name')}}</th>
                                        <th >{{__('Kind')}}</th>
                                        <th >{{__('pin')}}</th>
                                        <th>{{__("Market Value")}}</th>
                                        <th>{{__("Assessment Level")}}</th>
                                        <th>{{__('Assessed Value')}}</th>
                                        <th>{{__('Status')}}</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $totalAdjustedMarketValue = 0; $i=1;
                                    $totalAsseedValue = 0;
                                    @endphp
                                  @foreach($relatedBuildingMchinary as $land)
                                    <tr class="font-style" data-id="">
                                        <td class="">{{ $i }}</td>
                                        <td class="property_kind">{{ $land->rp_tax_declaration_no }}</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    
                                    @endforeach
                                </tbody>
                                {{ $relatedBuildingMchinary->links() }}
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--------------- Land Apraisal Listing End Here------------------>