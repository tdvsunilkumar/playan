$(document).ready(function () {
    select3Ajax("tfoc_id","group_tfoc_id","getTFOCDtlsAllList");
    select3Ajax("measure_charges_id0","addmoreMeasureDetails","getChargesAllList");
    select3Ajax("cctype_id","group_cctype_id","getTypeComputationAllList");
    $(".numeric").numeric({ decimal : "." });
    getChartAccount();
    computationDetails();
    higherAmountDetails($("#is_higher").is(':checked'))
    commonEventFunction();
    ajaxValidation();
    $("#app_code").change(function(){
        getTfocBasis();
    })
    $('#saveChanges').click(function (e) {
        e.preventDefault();
        $(".validate-err").html('');
        var data = $("form").serialize();
        var obj={};
        for(var key in data){
            obj[data[key].split("=")[0]] = decodeURIComponent(data[key].split("=")[1]);
        }
        $.ajax({
            url :DIR+'PsicTfoc/formValidation', // json datasource
            type: "POST", 
            data: $('#submitLandUnitValueForm').serialize(),
            dataType: 'json',
            success: function(html){
                if(html.ESTATUS){
                  
                    $("#err_"+html.field_name).html(html.error);
                    $("."+html.field_name).focus();
                }else{
                    var areFieldsFilled = checkIfFieldsFilled();
                    if (areFieldsFilled) {
                    Swal.fire({
                    title: "Are you sure?",
                    html: '<span style="color: red;">This will save the current changes.</span>',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No',
                    reverseButtons: true,
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                    $('#submitLandUnitValueForm').submit();
                    form.submit();
                    // location.reload();
                    } else {
                        console.log("Form submission canceled");
                    }
                });
                    
                    }
                }
            }
        })
     
   });
   function checkIfFieldsFilled() {
            var form = $('#submitLandUnitValueForm');
            var requiredFields = form.find('[required="required"]');
            var isValid = true;

            requiredFields.each(function () {
                var field = $(this);
                var fieldValue = field.val();

                if (fieldValue === '') {
                    isValid = false;
                    return false; // Exit the loop early if any field is empty
                }
            });

            if (!isValid) {
                // Swal.fire({
                //     title: "All required fields must be filled",
                //     icon: 'error',
                //     customClass: {
                //         confirmButton: 'btn btn-danger',
                //     },
                //     buttonsStyling: false
                // });
            }

            return isValid;
        }
});
function getTfocBasis(){
    var app_code =$("#app_code").val();
    $.ajax({
        url :DIR+'getTfocBasis', // json datasource
        type: "POST", 
        dataType: "html", 
        data: {
          "app_code": app_code, 
          "_token": $("#_csrf_token").val(),
        },
        success: function(html){
           $("#ptfoc_basis_id").html(html)
        }
    })
}

function addmoreMeasureDetails(){
    var html = $("#hiddenMeasureDtls").html();
    var prevLength = $(".addmoreMeasureDetails").find(".removeMeasureData").length;
    $(".addmoreMeasureDetails").append(html);
    $(".btnCancelMeasureDetails").click(function(){
        $(this).closest(".removeMeasureData").remove();
        var cnt = $(".addmoreMeasureDetails").find(".removeMeasureData").length;
        $("#hiddenMeasureDtls").find('select').attr('id','measure_charges_id'+cnt);
    });
    var classid = $(".addmoreMeasureDetails").find(".removeMeasureData").length;
    // $("#measure_charges_id"+prevLength).select3({dropdownAutoWidth : false,dropdownParent: $("#addmoreMeasureDetails")});
    select3Ajax("measure_charges_id"+prevLength,"addmoreMeasureDetails","getChargesAllList");
    $("#hiddenMeasureDtls").find('select').attr('id','measure_charges_id'+classid);
}
function addmoreDetails(){
    var html = $("#hiddenFormulaDtls").html();
    var prevLength = $(".addmoreDetails").find(".removeDataFormula").length;
    $(".addmoreDetails").append(html);
   
    $(".btnCancelDetails").click(function(){
        $(this).closest(".removeDataFormula").remove();
        var cnt = $(".addmoreDetails").find(".removeDataFormula").length;
        $("#hiddenFormulaDtls").find('select').attr('id','charges_id'+cnt);
        
    });
    var classid = $(".addmoreDetails").find(".removeDataFormula").length;
    $("#charges_id"+prevLength).select3({dropdownAutoWidth : false,dropdownParent: $("#addmoreDetails")});
    $("#hiddenFormulaDtls").find('select').attr('id','charges_id'+classid);
}

function addmoreRangeDetails(){
    var prevLength = $("#addmoreRangeDetails").find(".divRangeDetails").length;

    $("#hiddenRangeDtls").find("#increment").html(prevLength+1);
    var html = $("#hiddenRangeDtls").html();
    var prevLength = $(".addmoreRangeDetails").find(".removeMeasureData").length;
    $(".addmoreRangeDetails").append(html);

    $(".btnCancelRangeDetails").unbind("click");
    $(".btnCancelRangeDetails").click(function(){
        cancelRangeDetails($(this));
    });
    reindexHiddenRangehtmlindex();
    setTimeout( function(){ 
        $(".numeric").numeric({ decimal : "." });
    } , 1000 );
}

function reindexHiddenRangehtmlindex(){
    var classid = $(".addmoreRangeDetails").find(".removeRangeData").length;
    $("#hiddenRangeDtls").find('.range_is_formula').attr('name','range_is_formula_'+classid);
    $("#hiddenRangeDtls").find('.range_is_higher').attr('name','range_is_higher_'+classid);
    $("#hiddenRangeDtls").find('.computation_type').attr('name','computation_type_'+classid);
    // Change dynamically index number for subrange
    $("#hiddenRangeDtls").find(".removeRangeData").find('.JqDivComputationMonth, .JqDivComputationQuartarly').find('input').each(function(id){
        var fname= $(this).attr('fname');
        var mid= $(this).attr('mid');
        $(this).attr("id",fname+'_'+mid+'_'+classid);
        $(this).attr("iid",classid);
        if(fname=='range_month_id' || fname=='range_quarter_id'){
            $(this).attr("name",fname+'_'+classid+'[]');
        }else{
            $(this).attr("name",fname+'_'+mid+'_'+classid);
        }
    });
}
function cancelRangeDetails(thisval){
    thisval.closest(".removeRangeData").remove();
    var cnt = $(".addmoreRangeDetails").find(".removeRangeData").length;
    $("#hiddenRangeDtls").find('.range_is_formula').attr('name','range_is_formula_'+cnt);
    $("#hiddenRangeDtls").find('.range_is_higher').attr('name','range_is_higher_'+cnt);
    $("#hiddenRangeDtls").find('.computation_type').attr('name','computation_type_'+cnt);
    if($(".addmoreRangeDetails").find(".removeRangeData").length>0){
        $(".addmoreRangeDetails").find(".removeRangeData").each(function(i){
            $(this).find(".range_is_formula").attr('name','range_is_formula_'+i);
            $(this).find(".range_is_higher").attr('name','range_is_higher_'+i);
            $(this).find(".computation_type").attr('name','computation_type_'+i);
            // Change dynamically index number for subrange
            $(this).find('.JqDivComputationMonth, .JqDivComputationQuartarly').find('input').each(function(id){
                var fname= $(this).attr('fname');
                var mid= $(this).attr('mid');
                $(this).attr("id",fname+'_'+mid+'_'+i);
                
                if(fname=='range_month_id' || fname=='range_quarter_id'){
                    $(this).attr("name",fname+'_'+i+'[]');
                }else{
                    $(this).attr("name",fname+'_'+mid+'_'+i);
                }
            });
        });
    }
    reindexHiddenRangehtmlindex();
}


function checkDuplication(thisval,field,addMore){
    var charge_id = thisval.val();
    var thisvar = thisval;
    var attrid = thisval.attr('id');
    $('#'+addMore).find('.'+field).each(function(i, obj) {
       $("#err_"+attrid).remove();
       if(attrid!=$(this).attr('id')){
           if(charge_id==$(this).val()){
               $("#"+attrid).after('<span class="validate-err" id="err_'+attrid+'">Don\'t select duplicate variables</span>');
               $("#"+attrid).val('');
               return false;
           }
       }
    })
}

function commonEventFunction(){
    
    $(document).on('change','.computation_type',function(){
        if($(this).val()=='Monthly'){
            $(this).closest(".removeRangeData").find('.JqDivComputationMonth').removeClass("hide");
        }else{
            $(this).closest(".removeRangeData").find('.JqDivComputationMonth').addClass("hide");
            $(this).closest(".removeRangeData").find('.JqDivComputationMonth').find('input, select').attr("required",false);
        }

        if($(this).val()=='Quarterly'){
            $(this).closest(".removeRangeData").find('.JqDivComputationQuartarly').removeClass("hide");
        }else{
            $(this).closest(".removeRangeData").find('.JqDivComputationQuartarly').addClass("hide");
            $(this).closest(".removeRangeData").find('.JqDivComputationQuartarly').find('input, select').attr("required",false);
        }
    });

    $(document).on('click','.range_is_higher',function(){
       if($(this).is(':checked')){
            $(this).closest(".removeRangeData").find('.jqIsRangehigerSet').find('input, select').attr("readonly",false);
            $(this).closest(".removeRangeData").find('.jqIsRangehigerSet').find('input, select').attr("required",true);
        }else{
            $(this).closest(".removeRangeData").find('.jqIsRangehigerSet').find('input, select').attr("readonly",true);
            $(this).closest(".removeRangeData").find('.jqIsRangehigerSet').find('input, select').attr("required",false);
            $(this).closest(".removeRangeData").find('.jqIsRangehigerSet').find('input').val("");
        }
    });
    $(document).on('click','.range_is_formula',function(){
        if($(this).is(':checked')){
            $(this).closest(".removeRangeData").find('.jqRangeFormulaSetAmount').find('input, select').attr("readonly",true);
            $(this).closest(".removeRangeData").find('.jqRangeFormulaSet').find('input, select').attr("readonly",false);
            $(this).closest(".removeRangeData").find('.jqRangeFormulaSet').find('input, select').attr("required",true);
        }else{
            $(this).closest(".removeRangeData").find('.jqRangeFormulaSetAmount').find('input, select').attr("readonly",false);
            $(this).closest(".removeRangeData").find('.jqRangeFormulaSet').find('input, select').attr("readonly",true);
            $(this).closest(".removeRangeData").find('.jqRangeFormulaSet').find('input, select').attr("required",false);
            $(this).closest(".removeRangeData").find('.jqRangeFormulaSet').find('input').val("");
        }
    });

    $(document).on('click','.qurtr_is_higher',function(){
        var mid=$(this).attr("mid");
        setReadonlyRequired($(this).is(':checked'),".jqIsQurtrhigerSet"+mid);
    });
    $(document).on('click','.qurtr_is_formula',function(){
        var mid=$(this).attr("mid");
        setReadonlyRequired($(this).is(':checked'),".jqQurtrFormulaSet"+mid);
    });

    $(document).on('click','.month_is_higher',function(){
        var mid=$(this).attr("mid");
        setReadonlyRequired($(this).is(':checked'),".jqIshigerSet"+mid);
    });
    $(document).on('click','.month_is_formula',function(){
        var mid=$(this).attr("mid");
        setReadonlyRequired($(this).is(':checked'),".jqMonthFormulaSet"+mid);
    });

    $(document).on('click','.range_month_is_higher',function(){
        var mid=$(this).attr("mid");
        var iid=$(this).attr("iid");
        setRangeReadonlyRequired($(this).is(':checked'),'range_month_higher_amount_'+mid+'_'+iid);
    });
    $(document).on('click','.range_month_is_formula',function(){
        var mid=$(this).attr("mid");
        var iid=$(this).attr("iid");
        setRangeReadonlyRequired($(this).is(':checked'),'range_month_formula_'+mid+'_'+iid);
    });

    $(document).on('click','.range_qurtr_is_formula',function(){
        var mid=$(this).attr("mid");
        var iid=$(this).attr("iid");
        setRangeReadonlyRequired($(this).is(':checked'),'qurtr_formula_'+mid+'_'+iid);
    });
    $(document).on('click','.range_qurtr_is_higher',function(){
        var mid=$(this).attr("mid");
        var iid=$(this).attr("iid");
        setRangeReadonlyRequired($(this).is(':checked'),'qurtr_higher_amount_'+mid+'_'+iid);
    });

    $(document).on('change','.charges_id',function(){
        var thisval = $(this);
        checkDuplication(thisval,'charges_id','addmoreDetails');
    });
    $(document).on('change','.measure_charges_id',function(){
        var thisval = $(this);
        checkDuplication(thisval,'measure_charges_id','addmoreMeasureDetails');
    });

    $('#tfoc_id').on('change', function() {
       getChartAccount();
    });
    $('#cctype_id').on('change', function() {
        computationDetails();
    });
   
    $("#is_higher").click(function(){
        higherAmountDetails($(this).is(':checked'));
    })

    // Start Add More And Cancel Details
    $("#btn_addmore").click(function(){
        $('html, body').stop().animate({
          scrollTop: $("#saveChanges").offset().top
        }, 600);
        addmoreDetails();
    });
    $(".btnCancelDetails").click(function(){
        $(this).closest(".removeDataFormula").remove();  
    });

    $("#btn_addmoreMeasure").click(function(){
        $('html, body').stop().animate({
          scrollTop: $("#saveChanges").offset().top
        }, 600);
        addmoreMeasureDetails();
    });
    $(".btnCancelMeasureDetails").click(function(){
        $(this).closest(".removeMeasureData").remove();  
    });

    $("#btn_addmoreRange").click(function(){
        $('html, body').stop().animate({
          scrollTop: $("#saveChanges").offset().top
        }, 600);
        addmoreRangeDetails();
    });
    
    $(".btnCancelRangeDetails").click(function(){
        cancelRangeDetails($(this));
    });
    // End Add More And Cancel Details

    //Start Sub Range Hide close details
    $(".JqOpenSubRange").unbind("click");
    $(document).on('click','.JqOpenSubRange',function(){
        openSubMenu($(this))
    });
    //End Sub Range Hide close details

    
}

function openSubMenu(thisval){
    thisval.closest(".removeRangeData").find('.divComputation').slideToggle();
    if(thisval.closest(".removeRangeData").find('.divComputation').hasClass("hide")){
        thisval.closest(".removeRangeData").find('.divComputation').removeClass("hide");
        thisval.removeClass("ti-plus");
        thisval.addClass("ti-minus");
        if(thisval.closest(".removeRangeData").find('.computation_type').val()=='Monthly'){
            thisval.closest(".removeRangeData").find('.JqDivComputationMonth').removeClass("hide");
        }else if(thisval.closest(".removeRangeData").find('.computation_type').val()=='Quarterly'){
            thisval.closest(".removeRangeData").find('.JqDivComputationQuartarly').removeClass("hide");
        }
    }else{
        thisval.closest(".removeRangeData").find('.divComputation').addClass("hide");
        thisval.addClass("ti-plus");
        thisval.removeClass("ti-minus");
        thisval.closest(".removeRangeData").find('.JqDivComputationMonth').addClass("hide");
        thisval.closest(".removeRangeData").find('.JqDivComputationQuartarly').addClass("hide");
    }
}


function setRangeReadonlyRequired(checked,field){
    if(checked){
        $('input[name="'+field+'"]').attr("readonly",false);
        $('input[name="'+field+'"]').attr("required",true);
    }else{
        $('input[name="'+field+'"]').attr("readonly",true);
        $('input[name="'+field+'"]').attr("required",false);
        $('input[name="'+field+'"]').val("");
    }
}

function setReadonlyRequired(checked,field){
    if(checked){
        $(field).find('input, select').attr("readonly",false);
        $(field).find('input, select').attr("required",true);
    }else{
        $(field).find('input, select').attr("readonly",true);
        $(field).find('input, select').attr("required",false);
        $(field).find('input').val("");
    }
}
function higherAmountDetails(checked){
    if (checked) {
        $("#higher_amount").attr("required",true);
        $("#higher_amount").attr("readonly",false);
    }else{
        $("#higher_amount").attr("required",false);
        $("#higher_amount").attr("readonly",true);
        $("#higher_amount").val(0);
    }
}

function computationDetails(){
    if($("#cctype_id").val()==1){
        showDetails(".divConstant");
    }else{
        hideDetails(".divConstant");
    }

    if($("#cctype_id").val()==2){
        showDetails(".divBasis");
        showDetails(".JqDivFormulaSection");
        
    }else{
        hideDetails(".divBasis");
        hideDetails(".JqDivFormulaSection");
    }

    if($("#cctype_id").val()==3){
        showDetails(".JqDivMeasureSection");
    }else{
        hideDetails(".JqDivMeasureSection");
    }

    if($("#cctype_id").val()==4){
        showDetails(".divBasis");
        $(".JqDivMonthlySection").removeClass("hide");
        
    }else{
        hideDetails(".JqDivMonthlySection");
    }

    if($("#cctype_id").val()==5){
        showDetails(".divBasis");
        $(".JqDivQuarterlySection").removeClass("hide");
    }else{
        hideDetails(".JqDivQuarterlySection");
    }

    if($("#cctype_id").val()==6){
        showDetails(".divBasis");
        $(".JqDivQRangeSection").removeClass("hide");
    }else{
        hideDetails(".JqDivQRangeSection");
    }

}

function showDetails(attr_name){
    $(attr_name).removeClass("hide");
    $(attr_name).find("input, select").attr("required",true);
    if($("#cctype_id").val()==2){
        $("#is_higher").attr("required",false);
        $("#higher_amount").attr("required",false);
    }
}
function hideDetails(attr_name){
    $(attr_name).addClass("hide");
    $(attr_name).find("input, select").attr("required",false);
}

function getChartAccount(){
    var id =$("#tfoc_id").val();
    $.ajax({
        url :DIR+'getChartAccount', // json datasource
        type: "POST", 
        dataType: "JSON", 
        data: {
          "id": id, 
          "hidden_ctype_id":$("#hidden_ctype_id").val(),
          "_token": $("#_csrf_token").val(),
        },
        success: function(html){
            var data = html;
            $("#ptfoc_sl_id").html(data.subSidaryOptions);
            $("#ctype_id").html(data.chargesTypeOptions);
            $("#ptfoc_gl_id").val(data.ptfoc_gl_id)
        }
    })
}

function hasBalancedParentheses(formula) {
    const openParentheses = (formula.match(/\(/g) || []).length;
    const closeParentheses = (formula.match(/\)/g) || []).length;

    return openParentheses === closeParentheses;
}

function isFormulaValid(formula) {
            // Regular expression to match ((a+b)*a) pattern
            var regex = /^\([^()]*\)$/;
            return regex.test(formula);
 }

function ajaxValidation(){
    $('form').submit(function(e) {
        e.preventDefault();
        var f_lower_amt = 0;
        var f_upper_amt = 0;
        var s_lower_amt = 0;
        var s_lower_amt = 0;
        var isError = 0;
        $('.err_range_formula').text('');
        $("#err_LimitAmount").html('');
        $('.addmoreRangeDetails').find('.divRangeDetails').each(function(fcnt){
            f_lower_amt = +$(this).find('.lower_amount').val();
            f_upper_amt = +$(this).find('.upper_amount').val();
            $('.addmoreRangeDetails').find('.divRangeDetails').each(function(scnt){
                s_lower_amt = +$(this).find('.lower_amount').val();
                s_upper_amt = +$(this).find('.upper_amount').val();
               if(fcnt!=scnt){
                   //alert(f_lower_amt+">="+s_lower_amt+" & "+f_lower_amt+" <= "+s_upper_amt+" OR "+f_upper_amt+">="+s_lower_amt+" & "+f_upper_amt+" <= "+s_upper_amt)
                   if((f_lower_amt >= s_lower_amt && f_lower_amt <= s_upper_amt) || (f_upper_amt >= s_lower_amt && f_upper_amt <= s_upper_amt)){
                       $("#err_LimitAmount").html('Please enter valid range.');
                       isError=1;
                       return false;
                   }
               }
            });
        });
        $('#addmoreRangeDetails').find('.divRangeDetails').each(function(){
             var formula = $(this).find('.range_formula').val();
             $(this).find('.err_range_formula').text('');
             if(formula !=""){
                 var iscorrect = hasBalancedParentheses(formula);
                 if(iscorrect == false){
                     $(this).find('.err_range_formula').text('Incorrect Formula'); 
                    isError=1;
                       return false;
                 }
            }
        })
        if(!isError){
            $(".validate-err").html('');
            var obj = $(this).serializeArray();
            $.ajax({
                url :$(this).attr("action")+'/formValidation', // json datasource
                type: "POST", 
                data: obj,
                dataType: 'json',
                success: function(html){
                    if(html.ESTATUS){
                        $("#err_"+html.field_name).html(html.error)
                    }else{
                        $('form').unbind('submit');
                        $("form input[name='submit']").trigger("click");
                    }
                }
            })
        }
    });
} 