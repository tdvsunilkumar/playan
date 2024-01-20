<table>
    <thead>
        <tr>
            <th style="text-align:center;vertical-align:middle" colspan="20">
                <b>CITY HEALTH OFFICE</b>
            </th>
        </tr>
        <tr>
            <th style="text-align:center;vertical-align:middle" colspan="20">
                <b>Brgy. Singalat, Palayan City</b>
            </th>
        </tr>
        <tr>
            <th style="text-align:center;vertical-align:middle" colspan="20">
                <b>{{$year}} UTILIZATION REPORT</b>
            </th>
        </tr>
    </thead>
</table>
<table style="border:5px solid black">
    <thead>
        <tr>
            <td rowspan="2" style="text-align:center;vertical-align:middle;border:5px solid black" width="300px"><b>Product Name and Description</b></td>
            <td rowspan="2" style="text-align:center;vertical-align:middle;border:5px solid black"><b>U/I</b></td>
            <td colspan="3" height="50px" style="text-align:center;vertical-align:middle;word-wrap: break-word;border:5px solid black" width="200px">
                <b>Beginning Balance as of December 31, {{$year - 1}}</b> 
            </td>
            <td colspan="3" style="text-align:center;vertical-align:middle;border:5px solid black">Deliveries / Receipts </td>
            <td colspan="3" style="text-align:center;vertical-align:middle;border:5px solid black"><b>TOTAL</b></td>
            <td colspan="3" style="text-align:center;vertical-align:middle;border:5px solid black">Adjustments</td>
            <td colspan="3" style="text-align:center;vertical-align:middle;border:5px solid black">Issuances</td>
            <td colspan="3" style="text-align:center;vertical-align:middle;border:5px solid black"><b>Ending Balance as of December 31, {{$year}}</b></td>
        </tr>
        <tr>
            <td width="100px" style="text-align:center;border:5px solid black">Qty</td>
            <td width="100px" style="text-align:center;border:5px solid black">Unit cost</td>
            <td width="100px" style="text-align:center;border:5px solid black">Total cost</td>
            <td width="100px" style="text-align:center;border:5px solid black">Qty</td>
            <td width="100px" style="text-align:center;border:5px solid black">Unit cost</td>
            <td width="100px" style="text-align:center;border:5px solid black">Total cost</td>
            <td width="100px" style="text-align:center;border:5px solid black">Qty</td>
            <td width="100px" style="text-align:center;border:5px solid black">Unit cost</td>
            <td width="100px" style="text-align:center;border:5px solid black">Total cost</td>
            <td width="100px" style="text-align:center;border:5px solid black">Qty</td>
            <td width="100px" style="text-align:center;border:5px solid black">Unit cost</td>
            <td width="100px" style="text-align:center;border:5px solid black">Total cost</td>
            <td width="100px" style="text-align:center;border:5px solid black">Qty</td>
            <td width="100px" style="text-align:center;border:5px solid black">Unit cost</td>
            <td width="100px" style="text-align:center;border:5px solid black">Total cost</td>
            <td width="100px" style="text-align:center;border:5px solid black">Qty</td>
            <td width="100px" style="text-align:center;border:5px solid black">Unit cost</td>
            <td width="100px" style="text-align:center;border:5px solid black">Total cost</td>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $key => $value) 
                <tr>
                    <td style="border:5px solid black;text-align:left">{{$value->cip_item_name}}</td>
                    <td style="border:5px solid black;text-align:center">{{$value->unit_measure}}</td>
                    <!-- begining bal -->
                    <td style="border:5px solid black;text-align:right;">{{ ($value->delivery === 0) ? $value->total_qty : ''}}</td>
                    <td style="border:5px solid black;text-align:right;">{{ ($value->delivery === 0) ? number_format($value->cip_unit_cost,2) : ''}}</td>
                    <td style="border:5px solid black;text-align:right;">{{ ($value->delivery === 0) ? number_format($value->total_cost,2) : ''}}</td>
                    <!-- deliveries / returns -->
                    <td style="border:5px solid black;text-align:right;background-color:#ffc0cb">{{ ($value->delivery === 1) ? $value->cip_qty_posted : ''}}</td>
                    <td style="border:5px solid black;text-align:right;background-color:#ffc0cb">{{ ($value->delivery === 1) ? number_format($value->cip_unit_cost,2) : ''}}</td>
                    <td style="border:5px solid black;text-align:right;background-color:#ffc0cb">{{ ($value->delivery === 1) ? number_format($value->total_cost,2) : ''}}</td>
                    <!-- total -->
                    <td style="border:5px solid black;text-align:right;">{{$value->total_qty}}</td>
                    <td style="border:5px solid black;text-align:right;">{{number_format($value->cip_unit_cost,2)}}</td>
                    <td style="border:5px solid black;text-align:right;">{{number_format($value->total_cost,2)}}</td>
                    <!-- adjustment -->
                    <td style="border:5px solid black;text-align:right;background-color:#92CDDC">{{ ($value->adjust_qty) ? $value->adjust_qty : ''}}</td>
                    <td style="border:5px solid black;text-align:right;background-color:#92CDDC">{{ ($value->adjust_qty) ? number_format($value->cip_unit_cost,2) : ''}}</td>
                    <td style="border:5px solid black;text-align:right;background-color:#92CDDC">{{ ($value->adjust_qty) ? number_format($value->adjust_cost,2) : ''}}</td>
                    <!-- issuance -->
                    <td style="border:5px solid black;text-align:right;background-color:#ffceba">{{ ($value->issue_qty) ? $value->issue_qty : ''}}</td>
                    <td style="border:5px solid black;text-align:right;background-color:#ffceba">{{ ($value->issue_qty) ? number_format($value->cip_unit_cost,2) : ''}}</td>
                    <td style="border:5px solid black;text-align:right;background-color:#ffceba">{{ ($value->issue_qty) ? number_format($value->issue_cost,2) : ''}}</td>
                    <!-- remaining -->
                    <td style="border:5px solid black;text-align:right;">{{$value->bal_qty}}</td>
                    <td style="border:5px solid black;text-align:right;">{{number_format($value->cip_unit_cost,2)}}</td>
                    <td style="border:5px solid black;text-align:right;">{{number_format($value->bal_cost,2)}}</td>

                    <!-- <td style="border:5px solid black;text-align:left;">{{$value->cip_date_received}}</td> -->
                </tr>
        @endforeach
        <tr>
            <td style="border:5px solid black;text-align:left;background-color:#fdfdad"><b>TOTAL</b></td>
            <td style="border:5px solid black;text-align:center;background-color:#fdfdad"></td>
            <!-- begining bal -->
            <td style="border:5px solid black;text-align:right;background-color:#fdfdad"></td>
            <td style="border:5px solid black;text-align:right;background-color:#fdfdad"></td>
            <td style="border:5px solid black;text-align:right;background-color:#fdfdad">{{number_format($total->beginning,2)}}</td>
            <!-- deliveries / returns -->
            <td style="border:5px solid black;text-align:right;background-color:#fdfdad"></td>
            <td style="border:5px solid black;text-align:right;background-color:#fdfdad"></td>
            <td style="border:5px solid black;text-align:right;background-color:#fdfdad">{{number_format($total->delivery,2)}}</td>
            <!-- total -->
            <td style="border:5px solid black;text-align:right;background-color:#fdfdad"></td>
            <td style="border:5px solid black;text-align:right;background-color:#fdfdad"></td>
            <td style="border:5px solid black;text-align:right;background-color:#fdfdad">{{number_format($total->total,2)}}</td>
            <!-- Adjustment -->
            <td style="border:5px solid black;text-align:right;background-color:#fdfdad"></td>
            <td style="border:5px solid black;text-align:right;background-color:#fdfdad"></td>
            <td style="border:5px solid black;text-align:right;background-color:#fdfdad">{{number_format($total->adjust,2)}}</td>
            <!-- issuance -->
            <td style="border:5px solid black;text-align:right;background-color:#fdfdad"></td>
            <td style="border:5px solid black;text-align:right;background-color:#fdfdad"></td>
            <td style="border:5px solid black;text-align:right;background-color:#fdfdad">{{number_format($total->issue,2)}}</td>
            <!-- remaining -->
            <td style="border:5px solid black;text-align:right;background-color:#fdfdad"></td>
            <td style="border:5px solid black;text-align:right;background-color:#fdfdad"></td>
            <td style="border:5px solid black;text-align:right;background-color:#fdfdad">{{number_format($total->balance,2)}}</td>
        </tr>
    </tbody>
</table>