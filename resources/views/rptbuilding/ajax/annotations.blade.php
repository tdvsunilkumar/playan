<table class="table" id="new_added_land_apraisal">
                                <thead>
                                    <tr>
                                    	<th>No.</th>
                                        <th>{{__('Date')}}</th>
                                        <th>{{__("Annotation")}}</th>
                                        <th>{{__("Action")}}</th>
                                    
                                    </tr>
                                </thead>
                                <tbody>

                                    @php $i=1; @endphp
                               @foreach($propertyAnnotations as $key=>$anno)
                                    <tr class="font-style last-option">
                                        <td>{{ $i }}</td>
                                         @php $anno->rpa_annotation_date_time = date("d/m/Y", strtotime($anno->rpa_annotation_date_time)); @endphp
                                        <td>{{ (isset($anno->rpa_annotation_date_time))?$anno->rpa_annotation_date_time:''}}</td>
                                        <td>
                                                     @php
                                                        $content = $anno->rpa_annotation_desc;
                                                    @endphp
                                                    @if(strlen($content) > 30)
                                                        <div class="content">
                                                            <span class="short">{{ substr($content, 0, 30) }}</span>
                                                            <span class="full" style="display:none;">{{ $content }}</span>
                                                            <a href="#" class="more-btn">More</a>
                                                            <a href="#" class="less-btn" style="display:none;">Less</a>
                                                        </div>
                                                    @else
                                                        {{ $content }}
                                                    @endif
            </td>
                                        <td class="action"><a href="javascript:void(0)" data-sessionid="{{ (isset($anno->id) && $anno->id != '')?'':$key }}" data-id="{{ $anno->id }}" class="deleteAnnotation"><i class="fas fa-trash"></i></a></td>
                                        
                                    </tr>
                                    @php $i++; @endphp
                                    @endforeach
                                    <tr class="font-style">
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        
                                    </tr>
                                    <tr class="font-style">
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        
                                    </tr>
                                    <tr class="font-style">
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        
                                    </tr>
                                    
                                </tbody>
                            </table>
                             <script>
    // Add click event listener to the "Less" button
        document.querySelectorAll('.less-btn').forEach(function(button) {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                var contentWrapper = this.parentElement;
                contentWrapper.querySelector('.short').style.display = 'block';
                contentWrapper.querySelector('.full').style.display = 'none';
                contentWrapper.querySelector('.more-btn').style.display = 'block';
                contentWrapper.querySelector('.less-btn').style.display = 'none';

            });
        });
        // Add click event listener to the "More" button
        document.querySelectorAll('.more-btn').forEach(function(button) {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                var contentWrapper = this.parentElement;
                contentWrapper.querySelector('.short').style.display = 'none';
                contentWrapper.querySelector('.full').style.display = 'block';
                contentWrapper.querySelector('.more-btn').style.display = 'none';
                contentWrapper.querySelector('.less-btn').style.display = 'block';
            });
        });

        
    </script>