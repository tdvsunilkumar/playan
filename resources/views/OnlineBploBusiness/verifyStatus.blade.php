<style type="text/css"> 
    .btn{padding: 0.4rem 1.3rem !important;font-size: 12px !important;}
    #assessmentModal{
            padding-top:1%;
    }
    .assesment-heading thead tr th{
        color: black;
        background: white;
    }
    .modal-content {
        width: 1072px !important;
    }
</style>
<div class="modal fade" id="assessmentModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
           <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
           </div>
           <div class="container"></div>
                <div class="modal-body">
                <div class="row">
                    <div  class="col-md-4">
                        <h6>ROWILSON ARIZABAL's CHEF RON SNACK HOUSE</h6>
                    </div>
                    <div class="col-md-8">
						<button type="button" class="btn  btn-primary">Link Business Application </button>
						<button type="button" class="btn  btn-primary" data-busn_tax_year="" data-busn_id="" id="VerifyApplications">Verify Application</button>
						<button type="button" class="btn  btn-warning">Declined Application</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 border-right  space-right" style="border: 1px solid gray;margin: 0;float:left;">
                        <h4 class="text-header">Application Requirements</h4>
                        <p style="text-align:left; font-weight: bold;">Generate Barangay Clearance:<a style="color:red;">NO</a></p>
                        <div class="row">
							<div class="col-lg-6 col-md-6 col-sm-6">
								<div class="form-group">
									{{ Form::label('requirement_id', __('Requirements'),['class'=>'form-label']) }}
									<span class="text-danger">*</span>
									<div class="form-icon-user">
										
                                    </div>
								</div>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-6">
								<div class="form-group">
									{{ Form::label('document_name', __('Document'),['class'=>'form-label']) }}
									<div class="form-icon-user">
										{{ Form::input('file','document_name','',array('class'=>'form-control'))}}  
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-6 col-md-6 col-sm-6"></div>
								<div class="col-lg-6 col-md-6 col-sm-6">
									<button type="button" style="float: right;" class="btn btn-primary" id="uploadAttachment">Upload File</button>
								</div>
							</div>
							<div class="col-lg-12 col-md-12 col-sm-12"><br>
								<div class="table-responsive">
									<table class="table">
										<thead>
											<tr>
												<th>Document Title</th>
												<th>Attachment</th>
												<th>Action</th>
											</tr>
											<tr>
												<td colspan="3"><i>No results found.</i></td>
											</tr>
										</thead>
									</table>
								</div>
							</div>
						</div>
                    </div>
                    <!-- print pdf -->
                    <div class="col-md-8 space-left" style="border: 1px solid gray;margin: 0;float:left;">
                        <button class="btn btn-primary"> <i class="ti-printer text-white fs-12"></i> Print</button>
                        <div class="tab-content" id="pills-tabContent" style="background-color:white;">
                            <div class="row mt-5" style="border: 1px solid gray;margin: 5px;" >
                                <div class="col-md-8" style="">
                                    <p style="text-align: center; font-weight: bold;">UNIFIED APPLICATION fORM FOR RENEWAL OF BUSINESS PERMIT<p>
                                    <div style="height:110px;width:150px;border: 1px solid gray;margin: 0;float:left;">
                                        <p style="border-bottom: 1px solid gray;">&nbsp;</p>	
                                        <input type="checkbox"  name="" value="">
                                        <label>New</label><br>
                                        <input type="checkbox"  name="" value="">
                                        <label >RENEWAL</label><br>
                                        <input type="checkbox" name="" value="">
                                        <label >ADDITONAL</label><br>
                                    </div>   
                                    <div style="height:110px;width:150px;border: 1px solid gray;float:left;">
                                        <p style="border-bottom: 1px solid gray;text-align: center;">Payment</p>
                                        <input type="checkbox" name="" value="">
                                        <label for="annually">Annually</label><br>
                                        <input type="checkbox" name="" value="">
                                        <label for="bi_Annually">Bi-Annually</label><br>
                                        <input type="checkbox" name="" value="">
                                        <label for="quarterly">Quarterly</label><br>
                                    </div> 
                                </div>
                                <div class="col-md-4" style="float:right;">
                                    <br><br>
                                    <label for="">Date of Receipt:</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="" type="text" style="border: 0;outline: 0;border-bottom: 1px solid gray;"/><br>
                                    <label for="">Tracking Number:</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="" type="text" style="border: 0;outline: 0;border-bottom: 1px solid gray;"/><br>
                                    <label for="">Business ID Number:</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="" type="text" style="border: 0;outline: 0;border-bottom: 1px solid gray;"/><br>
                                </div>
                                <div style="height:110px;width:100%;border: 1px solid gray;margin: 0;float:left;">
                                    <p>BUSINESS INFORMATION AND REGISTRATION<p/>
                                    <div style="float:left;">
                                        <p for="Please_choose_one" style="font-weight: bold;">Please choose One:-</p>
                                    </div>
                                    <div style="float:left;">
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <input type="checkbox" id="new_form" name="" value="">
                                        <label for="Please_choose_one">Single Proprietorship</label><br>
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <input type="checkbox"  name="" value="">
                                        <label for="Male">Male</label>
                                        <input type="checkbox" name="" value="">
                                        <label for="Female">Female</label>
                                    </div>
                                    <div style="float:left;">
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <input type="checkbox" name="" value="">
                                        <label for="">One Person Corporation</label><br>
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <input type="checkbox" name="" value="">
                                        <label for="Male">Male</label>
                                        <input type="checkbox" name="" value="">
                                        <label for="Female">Female</label>
                                    </div>
                                    <div style="float:left;">
                                        <input type="checkbox" name="" value="">
                                        <label for="Partnership">Partnership</label>
                                        <input type="checkbox" name="" value="">
                                        <label for="Corporation">Corporation</label>&nbsp;&nbsp;&nbsp;
                                        <input type="checkbox" name="" value="">
                                        <label for="Corperative">Corperative</label>
                                    </div>
                                    
                                </div>
                                <div style="height:70px;width:100%;border: 1px solid gray;margin: 0;float:left;">
                                    <div style="float:left;">
                                        <p style="font-weight: bold;">DTI/SEC/CDA Registration Number:</p>
                                        <input name="" placeholder="5655565" type="text" style="border: 0;outline: 0;"/>
                                    </div>
                                    <div style="float:right; margin-right:100px;">
                                        <p style="font-weight: bold;">Tax Identification Number:</p>
                                        <input name=""  placeholder="000-000-000-0000" type="text" style="border: 0;outline: 0;"/>
                                    </div>
                                </div>
                                <div style="height:70px;width:100%;border: 1px solid gray;margin: 0;float:left;">
                                    <div style="float:left;">
                                        <p style="font-weight: bold;">ROXANNE SARI-SARI STROE</p>
                                        <input name="" placeholder="BUSINESS NAME" type="text" style="border: 0;outline: 0;"/>
                                    </div>
                                </div>
                                <div style="height:40px;width:100%;border: 1px solid gray;margin: 0;float:left;">
                                    <div style="float:left;">
                                        <p style="font-weight: bold;">Trade Name/ Franchise(if applicable):<input name="" type="text" style="border: 0;outline: 0;"/></p>
                                        
                                    </div>
                                </div>
                                <div style="height:150px;width:100%;border: 1px solid gray;margin: 0;float:left;">
                                    <p style="font-weight: bold;">Main Office Address: &nbsp;&nbsp;&nbsp;
                                        <label for="">House/Bldg No.</label>
                                        <input name="" type="text" style="border: 0;outline: 0;border-bottom: 1px solid gray;"/>
                                        <label for="">Name of Buidling.</label>
                                        <input name="" type="text" style="border: 0;outline: 0;border-bottom: 1px solid gray;"/>
                                        <label for="">Lot No.</label>
                                        <input name="" type="text" style="border: 0;outline: 0;border-bottom: 1px solid gray;"/><br>
                                        &nbsp;&nbsp;&nbsp&nbsp;&nbsp;&nbsp
                                        <label for="">Block No.</label>
                                        <input name="" type="text" style="border: 0;outline: 0;border-bottom: 1px solid gray;"/>
                                        <label for="">Street No.</label>
                                        <input name="" type="text" style="border: 0;outline: 0;border-bottom: 1px solid gray;"/>
                                        <label for="">Barangay.</label>
                                        <input name="" type="text" style="border: 0;outline: 0;border-bottom: 1px solid gray;"/>
                                        <label for="">Subdivision.</label>
                                        <input name="" type="text" style="border: 0;outline: 0;border-bottom: 1px solid gray;"/><br>
                                        &nbsp;&nbsp;&nbsp&nbsp;&nbsp;&nbsp
                                        <label for="">City/Municipality.</label>
                                        <input name="" type="text" style="border: 0;outline: 0;border-bottom: 1px solid gray;"/>
                                        <label for="">Province.</label>
                                        <input name="" type="text" style="border: 0;outline: 0;border-bottom: 1px solid gray;"/>
                                        <label for="">Zip Code.</label>
                                        <input name="" type="text" style="border: 0;outline: 0;border-bottom: 1px solid gray;"/>
                                    </p>
                                </div>
                                <div style="height:50px;width:33%;border: 1px solid gray;margin: 0;float:left;">
                                    &nbsp;&nbsp;&nbsp;
                                    <label for="">Telephone No:</label><br>
                                    <p></P>
                                </div>
                                <div style="height:50px;width:33%;border: 1px solid gray;margin: 0;float:left;">
                                    &nbsp;&nbsp;&nbsp;
                                    <label for="">Mobile No:</label><br>
                                    <p></P>
                                </div>
                                <div style="height:50px;width:33%;border: 0: 0;float:left;">
                                    &nbsp;&nbsp;&nbsp;
                                    <label for="">Email Address:</label><br>
                                    <p>0</P>
                                </div>
                                <div  style="height:80px;width:100%;border:0;margin: 0;">
                                    <div style="height:80px;width:30%;border:0;border-right: 1px solid gray;margin: 0;float:left;">
                                        <a>(For Sole Proprietorship)<a/><br>
                                        &nbsp;
                                        <label for="">Name of Owner:</label><br>
                                    </div>
                                    <div style="height:80px;width:18%;border:0;border-right: 1px solid gray;;margin: 0;float:left;">
                                        &nbsp;
                                        <label for="">Surname</label><br>
                                        <p></p>
                                    </div>
                                    <div style="height:80px;width:18%;border:0;border-right: 1px solid gray;margin: 0;float:left;">
                                        &nbsp;
                                        <label for="">Given Name</label><br>
                                        <p></p>
                                    </div>
                                    <div style="height:80px;width:17%;border:0;border-right: 1px solid gray;margin: 0;float:left;">
                                        &nbsp;
                                        <label for="">Middle Name:</label><br>
                                        <p></p>
                                    </div>
                                    <div style="height:80px;width:15%;border:0;margin: 0;float:left;">
                                        &nbsp;
                                        <label for="">Suffix:</label><br>
                                        <p></p>
                                    </div>
                                </div>
                                <div style="height:90px;width:100%;border: 1px solid gray;margin: 0;">
                                    <div style="height:90px;width:30%;border:0;border-right: 1px solid gray;margin: 0;float:left;">
                                        <a>(For Corporations/<br>Cooperative/Partnership)<a/><br>
                                        <label for="">Name of President/Office in Charge:</label><br>
                                    </div>
                                    <div style="height:90px;width:18%;border:0;border-right: 1px solid gray;margin: 0;float:left;">
                                        &nbsp;
                                        <label for="">Surname</label><br>
                                        <p></p>
                                    </div>
                                    <div style="height:90px;width:18%;border:0;border-right: 1px solid gray;margin: 0;float:left;">
                                        &nbsp;
                                        <label for="Given Name">Given Name</label><br>
                                        <p></p>
                                    </div>
                                    <div style="height:90px;width:17%;border:0;border-right: 1px solid gray;margin: 0;float:left;">
                                        &nbsp;
                                        <label for="Middle Name">Middle Name</label><br>
                                        <p></p>
                                    </div>
                                    <div style="height:90px;width:15%;border:0;margin: 0;float:left;">
                                        &nbsp;
                                        <label for="Suffix">Suffix</label><br>
                                        <p></p>
                                    </div>
                                </div>
                                <div style="height:30px;width:100%;border: 1px solid gray;margin: 0;float:left;">
                                    <a>For Corporation<a/>
                                    <input type="checkbox" id="" name="" value="">
                                    <label for="Filipino">Filipino</label>&nbsp;&nbsp;&nbsp;
                                    <input type="checkbox" id="" name="" value="">	
                                    <label for="Foreign">Foreign</label>&nbsp;&nbsp;&nbsp;
                                </div>
                                <div style="height:30px;width:100%;border:0: 0;float:left;">
                                    <p>B.&nbsp;&nbsp;&nbsp;BUSINESS OPERATION<p/>
                                    <br>
                                </div>
                                <div style="height:110px;width:100%;border: 1px solid gray;margin: 0;float:left;">
                                    <div style="float:left;width:25%;">
                                        <p>Business Area (In sq. m). 55</p>
                                        <p>Floor Area(in sq. m) 55</p>
                                    </div>
                                    <div style="float:left;width:25%;">
                                        <label for="">Total No. of Employees in  Establishment</label>
                                        <p>55 Female</p>
                                        <p>55 Male</p>
                                    </div>
                                    <div style="float:left;width:25%;">
                                        <a>No.Of Employees<a/>
                                        <label for=""> Residing Within</label>
                                        <p>55</p>
                                    </div>
                                    <div style="float:left;width:25%;">
                                        <label for="">No. of Delivery Vehicles(if applicable)</label>
                                        <p>55 Va/Truck</p>
                                        <p>55 Motorcycle</p>
                                    </div>
                                </div>
                                <div style="height:200px;width:100%;border:0;margin: 0;float:left;">
                                    <label for="">Same as Main Office Address</label>
                                    <input name="" type="checkbox" style="border:1px;"/>
                                    <p style="font-weight: bold;">Business Location Address&nbsp;
                                        <label for="">House/Bldg No.</label>
                                        <input name="" type="text" style="border: 0;outline: 0;border-bottom: 1px solid gray;"/>
                                        <label for="">Name of Buidling.</label>
                                        <input name="" type="text" style="border: 0;outline: 0;border-bottom: 1px solid gray;"/>
                                        <label for="">Lot No.</label>
                                        <input name="" type="text" style="border: 0;outline: 0;border-bottom: 1px solid gray;"/><br>
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp
                                        <label for="">Block No.</label>
                                        <input name="" type="text" style="border: 0;outline: 0;border-bottom: 1px solid gray;"/>
                                        <label for="">Street No.</label>
                                        <input name="" type="text" style="border: 0;outline: 0;border-bottom: 1px solid gray;"/>
                                        <label for="">Barangay.</label>
                                        <input name="" type="text" style="border: 0;outline: 0;border-bottom: 1px solid gray;"/>
                                        <label for="">Subdivision.</label>
                                        <input name="" type="text" style="border: 0;outline: 0;border-bottom: 1px solid gray;"/><br>
                                        &nbsp;&nbsp;&nbsp&nbsp;&nbsp;&nbsp
                                        <label for="">City/Municipality.</label>
                                        <input name="" type="text" style="border: 0;outline: 0;border-bottom: 1px solid gray;"/>
                                        <label for="">Province.</label>
                                        <input name="" type="text" style="border: 0;outline: 0;border-bottom: 1px solid gray;"/>
                                        <label for="">Zip Code.</label>
                                        <input name="" type="text" style="border: 0;outline: 0;border-bottom: 1px solid gray;"/>
                                    </p>
                                </div>
                                <div style="height:120px;width:100%;border: 1px solid gray;margin: 0;float:left;">
                                    <a>Owned?</a>&nbsp;&nbsp;&nbsp;
                                    <input name="" type="checkbox" style="border:1px;"/>
                                    <label for="">Yes</label>&nbsp;&nbsp;&nbsp;
                                    <input name="" type="checkbox" style="border:1px;"/>
                                    <label for="">No</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp
                                    <label for="">If Yes, Tax Declartion No.</label>
                                    <input name="" type="text" style="border: 0;outline: 0;border-bottom: 1px solid gray;"/>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp
                                    <label for="">Of Property Identification No.</label>
                                    <input name="" type="text" style="border: 0;outline: 0;border-bottom: 1px solid gray;"/>
                                    <br>
                                    <label for="">Do you have tax incentives from any Government Entity?</label>&nbsp;&nbsp;&nbsp;
                                    <input name="" type="checkbox" style="border:1px;"/>Yes&nbsp;&nbsp;&nbsp;&nbsp;
                                    <label for="">(Please attach a copy of your Certificate)</label>&nbsp;&nbsp;
                                    <input name="" type="checkbox" style="border:1px;"/>NO
                                        
                                    
                                </div>
                                <div style="height:50px;width:100%;border: 1px solid gray;margin: 0;float:left;">
                                    <a>Business Activity(Please check one):</a>&nbsp;&nbsp;&nbsp;
                                    <input name="" type="checkbox" style="border-right:1px;"/>
                                    <label for="">Main Office</label>&nbsp;&nbsp;&nbsp;
                                    
                                    <input name="" type="checkbox" style="border-right:1px;"/>
                                    <label for="">Branch Office</label>&nbsp;&nbsp;&nbsp;
                                    
                                    <input name="" type="checkbox" style="border-right:1px;"/>
                                    <label for="">Admin Office Only</label>&nbsp;&nbsp;&nbsp;
                                    
                                    <input name="" type="checkbox" style="border-right:1px;"/>
                                    <label for="">Warehouse</label>&nbsp;&nbsp;&nbsp;
                                    
                                    <input name="" type="checkbox" style="border:0px;"/>
                                    <label for="">Other Pls. Speciy</label>&nbsp;&nbsp;&nbsp;
                                </div>
                                <div style="height:85px;width:100%;border: 1px solid gray;margin: 0;float:left;">
                                    <div style="float:left;height:85px;width:25%;border-right: 1px solid gray;">
                                        <label for="">Line of Business</label>
                                    </div>
                                    <div style="float:left;height:85px;width:20%;border-right: 1px solid gray;">
                                        <label for="">Philippine standard <br>Industrial Code(If Available)</label>
                                    </div>
                                    <div style="float:left;height:85px;width:20%;border-right: 1px solid gray;">
                                        <label>product/Services<label/>
                                    </div>
                                    <div style="float:left;height:85px;width:15%;border-right: 1px solid gray;">
                                        <label for="">No. of Units</label>
                                    </div>
                                    <div style="float:left;height:85px;width:19%;">
                                        <label for="">Last Year's Gross Sales/Receipts</label>
                                    </div>
                                </div>
                                <div style="height:40px;width:100%;border: 1px solid gray;margin: 0;float:left;">
                                    <div style="float:left;height:39px;width:25%;border-right: 1px solid gray;">
                                        <label style="font-weight: bold;"for="">Retail Selling in Sari-sari stores</label>
                                    </div>
                                    <div style="float:left;height:39px;width:20%;border-right: 1px solid gray;">
                                        
                                    </div>
                                    <div style="float:left;height:39px;width:20%;border-right: 1px solid gray;">
                                    
                                    </div>
                                    <div style="float:left;height:39px;width:15%;border-right: 1px solid gray;">
                                        
                                    </div>
                                    <div style="float:left;height:79px;width:19%;height:36px;">
                                        
                                    </div>
                                </div> 
                                <div style="height:220px;width:100%;border: 1px solid gray;margin: 0;float:left;">
                                    <p>
                                        <a style="font-weight: bold;">I DECLARE UNDER PENALTY OF PERJURY<a/> that all information in this application are true and correct based on
                                        my Personal knowledge and authentic records submitted to the <a style="font-weight: bold;">City of Palayan.<a> Any false or misleading information 
                                        supplied,or production of fake/falsified doucments shall be grounds for appropriate legal action against me and automatically revokes the permit. I hereby agree that all personal data(as defined under
                                        the Data privacy Law of 2012 and its Implementing Rules and Regulations) and account transaction information or records with the city/Municipal Government may be processed, profiled or shared to requesting
                                        parties or for the purpose of any court,legal process,examination,inquiry and audit or investigation of any authority.
                                    </p>
                                </div>
                                <div style="text-align:center; height:150px;width:100%;border:0;margin: 0;float:left;">
                                    <p style="font-weight: bold;">PINEDA,CAROLINE MAGNO</p>
                                    <p style="font-weight: bold;">SIGNSTURE OF APPLICANT/OWNER OVER PRINTED NAME</p>
                                    <p style="font-weight: bold;">DESIGNSTION/POSITION/TITLE</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>

  