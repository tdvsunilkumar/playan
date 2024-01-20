<table>
    <thead>
        <tr>
            <th style="text-align:center;vertical-align:middle" colspan="5">
                <b>CITY HEALTH OFFICE</b>
            </th>
        </tr>
        <tr>
            <th style="text-align:center;vertical-align:middle" colspan="5">
                <b>Brgy. Singalat, Palayan City</b>
            </th>
        </tr>
        <tr>
            <th style="text-align:center;vertical-align:middle" colspan="5">
                <b>{{$year}} VARIANCE REPORT</b>
            </th>
        </tr>
    </thead>
</table>
<table style="border:5px solid black">
    <thead>
        <tr>
            <td style="text-align:center;vertical-align:middle;border:5px solid black" width="300px"><b>Product Name</b></td>
            <td style="text-align:center;vertical-align:middle;border:5px solid black;word-wrap: break-word;" width="100px"><b>Adjustment Code</b></td>
            <td style="text-align:center;vertical-align:middle;border:5px solid black" width="100px"><b>Date</b></td>
            <td style="text-align:center;vertical-align:middle;border:5px solid black" ><b>Quantity</b></td>
            <td style="text-align:center;vertical-align:middle;border:5px solid black" width="300px"><b>Remarks</b></td>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $key => $value) 
                <tr>
                    <td style="border:5px solid black;text-align:left">{{$value->item_name}}</td>
                    <td style="border:5px solid black;text-align:center">{{$value->hiad_series}}</td>
                    <td style="border:5px solid black;text-align:center">{{Carbon\Carbon::parse($value->created_at)->toDateString()}}</td>
                    <td style="border:5px solid black;text-align:center">{{$value->hiad_qty}}</td>
                    <td style="border:5px solid black;text-align:center;word-wrap: break-word;">{{$value->hiad_remarks}}</td>
                </tr>
        @endforeach
    </tbody>
</table>