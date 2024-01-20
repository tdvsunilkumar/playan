$(document).ready(function () {
    housingCount = $("#house-group").find("tbody tr").length
    // $("#commonModal").find('.body').css('height','800px')
    removeBtn()
    $('#payment-group').DataTable({
      "language": {
        "infoFiltered":"",
        "processing": "<img src='"+DIR+"public/images/ajax-loader1.gif' style='position: absolute;top: 50%;left: 50%;margin: -50px 0px 0px -50px;' />"
    },
    dom: "<'row'<'col-sm-12'f>>" +"<'row'<'col-sm-3'l><'col-sm-9'p>>" +"<'row'<'col-sm-12'tr>>" +"<'row'<'col-sm-12'p>>",
		"searching": false,
		"bDestroy": true,
		"bProcessing": true,
		"pageLength": 5,
    "bInfo": false,
    "lengthChange": false
    });
    // dropzzone
    var accept = "jpeg,.jpg,.png,.gif,.doc,.docx,.pdf";
    _supplierDropzone = new Dropzone('#upload-file-form', { 
            acceptedFiles: accept,
            maxFilesize: 10,
            timeout: 0,
            headers: {
                'X-CSRF-TOKEN': _token
            },
            init: function () {
                this.on("success", function (file, data) {
                    //when success 
                    console.log(data);
                    // var data = $.parseJSON(response);
                    if (data.message == 'success') {
                        table = $('#supplierUploadTable tbody');
                        table.find('tr.loading').remove()
                        count = table.find('tr').length
                        table.append(
                            '<tr>'+
                                '<td>'+
                                    data.name+
                                    '<input type="hidden" name="upload_file['+count+'_'+data.id+'new][name]" value="'+data.name+'">'+
                                '</td>'+
                                '<td>'+
                                    data.type+
                                    '<input type="hidden" name="upload_file['+count+'_'+data.id+'new][type]" value="'+data.type+'">'+
                                '</td>'+
                                '<td>'+
                                    data.size+
                                    '<input type="hidden" name="upload_file['+count+'_'+data.id+'new][size]" value="'+data.size+'">'+
                                '</td>'+
                                '<td>'+
                                    '<input type="hidden" name="upload_file['+count+'_'+data.id+'new][is_active]" value="1">'+
                                    '<button type="button" class="btn btn-sm btn-danger remove-row"><i class="ti-trash text-white"></i></button>'+
                                '</td>'+
                            '</tr>'
                        );
                        removeBtn()
                    }
                }).on("totaluploadprogress", function (progress) {
                    var progressElement = $("[data-dz-uploadprogress]");
                    progressElement.width(progress + '%');
                    progressElement.find('.progress-text').text(progress + '%');
                });
                this.on('resetFiles', function() {
                    this.removeAllFiles();
                });
                this.on("error", function(file){if (!file.accepted) this.removeFile(file);});            
            }
        });


    $("#client_id").change(function(e){
        e.preventDefault();
        getClientDetails($("#client_id").val());
    })
    // form steps
    $('.step-contain').not('.show').hide()

    $('.next-btn').click(function(e){
      form = $(this).closest('.step-contain')
      step = form.data('step')
      cancel = 0
      $('.validate-err').empty()
      form.find('input.required, select.required').each(function(){
        if( !$(this).val() ) {
          console.log($(this).closest('div'))
          $(this).closest('div').next('.validate-err').text('Required')
          cancel = 1
        }
      })
      if (cancel === 0) {
        console.log('next')
        form.hide()
        next_step = step+1
        formTransition(step,next_step)
      }

    })
    $('.back-btn').click(function(e){
      form = $(this).closest('.step-contain')
      step = form.data('step')
      form.hide()
      next_step = step-1
      formTransition(step,next_step)

    })

    $('.add-housing-btn').click(function(e){
      addHousing()
    })

    selectHousingAjax()
    
    // start date  + term months
    $('.select-end-date').change(function(){
      start = moment($('#terms_date_from').val());
      months = $('#month_terms').val();
      end = start.add(months, 'months').format('YYYY-MM-DD');
      $('#terms_date_to').val(end);
      getBreakdown()
    })

    // drop uploads

  });

function formTransition(step,next_step) {
  $('#'+next_step+'-form').show()
  $('#form-progress-'+step).removeClass('bg-info')
  $('#form-progress-'+step).addClass('bg-light')
  $('#form-progress-'+next_step).addClass('bg-info')
  $('#form-progress-'+next_step).removeClass('bg-light')
}
function getClientDetails(client_id)
{
  showLoader();
  $.ajax({
      url: DIR+'legal-housing-application/getClientDetails/'+client_id,
      type: "GET",
      success: function (response) {
          hideLoader();
          console.log(client_id);
          $('#contact_no').val(response.data.p_mobile_no);
        //   $('#gender').val(response.data.gender);
          $('#email_address').val(response.data.p_email_address);         
      }
  })
}

function removeBtn() {
  $('.remove-row').click(function(params) {
      console.log('csi')
      $(this).closest('tr').remove()
  })
}
function addHousing() {
  var id = housingCount + 'new';
  var html = $('#addHousing').html();
  console.log(html);
  html = html.replace(/0/g, id);
  $('#house-group').find('tbody').append(html);
  selectNormal('#residential_name_id_'+id)
  selectHousingAjax()
  removeBtn()
  housingCount += 1;
}

function selectHousingAjax(params) {
  $('.select-residence').change(function(){
    var id = $(this).val();
    var contain = $(this).closest('tr');
    var select_id = contain.find('.select-phase').attr('id');
    console.log(id);
    select3Ajax(select_id,contain.attr('id'),'legal-housing-application/getPhase/'+id);
  })
  $('.select-phase').change(function(){
    var id = $(this).val();
    var contain = $(this).closest('tr');
    var select_id = contain.find('.select-blk').attr('id');
    console.log(id);
    select3Ajax(select_id,contain.attr('id'),'legal-housing-application/getBlk/'+id);
  })
}

function getBreakdown() {
  $('#payment-group').find('tbody').empty()
  months = $('#month_terms').val()
  startDate = moment($('#terms_date_from').val())
  totalAmount = $('#total_amount').val()
  downpayment = $('#initial_monthly').val()

  remaining = totalAmount - downpayment
  monthly = remaining / months
  firstMonth = parseInt(monthly) + remaining - (parseInt(monthly) * months)

  // for first month
  var html = $('#addPayment').html();
  html = html.replace(/0/g, 'firstmonth');
  html = html.replace(/srNo/g, 1);
  html = html.replace(/amountDue/g, firstMonth);
  html = html.replace(/amountDue/g, firstMonth);
  remaining = remaining - firstMonth
  dueDate = startDate.add(1, 'months').format('YYYY-MM-DD');
  html = html.replace(/dueDate/g, dueDate);
  html = html.replace(/remainAmount/g, remaining - firstMonth);
  $('#payment-group').find('tbody').append(html);

  for(var i = 1; i <= months - 1; i++) {
    sr = 1 + i;
    amount_due = parseInt(monthly)
    remaining = remaining - amount_due
    dueDate = startDate.add(1, 'months').format('YYYY-MM-DD');

    var html = $('#addPayment').html();
    html = html.replace(/0/g, i+'new');
    html = html.replace(/srNo/g, sr);
    html = html.replace(/dueDate/g, dueDate);
    html = html.replace(/amountDue/g, amount_due);
    html = html.replace(/remainAmount/g, remaining);
    $('#payment-group').find('tbody').append(html);
  }

  
  console.log(firstMonth)
}