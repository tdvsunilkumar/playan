@if(count($paymentFiles) == 0)
                                                            <tr>
                                                                <td colspan="3"><i>No results found.</i></td>
                                                            </tr>
                                                            @else
                                                            <tr>
                                                            @php $count = 1; @endphp
                                                            @foreach($paymentFiles as $key=>$val)
                                                            <td>{{$count}}</td>
                                                            <td>{{$val->attachments}}</td>
                                                            <td><a class="btn" href="{{asset('uploads/rptPaymentFile/location/')}}/{{$val->attachments}}" target='_blank'><i class='ti-download'></i></a></td>
                                                            <td><button type="button" class="btn btn-danger btn_delete_documents" data-id="{{$val->id}}" value="{{$val->id}}" style="padding: 5px 8px;"><i class="ti-trash"></i></button></td>
                                                            </tr>
                                                            @php $count++; @endphp
                                                            @endforeach
                                                            @endif 