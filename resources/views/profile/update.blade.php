@extends('layouts.master')

@section('title') @lang('translation.Form_Elements') @endsection

@section('css')
    <link href="{{ URL::asset('/assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('/assets/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('/assets/libs/thailand/jquery.Thailand.min.css') }}" rel="stylesheet" type="text/css">
@endsection

@section('content')

    @component('components.breadcrumb')
        @slot('li_1') โปรไฟล์ @endslot
        @slot('title') แก้ไขข้อมูลส่วนตัว @endslot
    @endcomponent
    <div class="row">
        <div class="col-lg-12">
            <form id="form_update_profile">
                @method('PUT')
                <input type="hidden" id="csrf_token" name="_token" value="{{ csrf_token() }}" />
            <div class="card">
                <div class="card-body">    
                        <div class="row">
                             
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <div class="col-lg-12">
                                        <label class="form-label">ชื่อ - สกุล</label>
                                        <input class="form-control" type="text" name="fullname" value="{{$userData->fullname}}" disabled>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">คำนำหน้า</label>
                                    <select class="form-control select2-selection select2-selection--single" aria-hidden="true" id="prefix_name" name="prefix_name">
                                    @if($userData->prefix_name !=null || $userData->prefix_name !="")
                                    <option value="{{$userData->prefix_name}}" selected>{{$userData->prefix_name}}</option>
                                    @else
                                    <option value="" selected disabled>เลือก คำนำหน้า</option>
                                    @endif
                                    <option value="นาย">นาย</option>
                                    <option value="นาง">นาง</option>
                                    <option value="นางสาว">นางสาว</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">เพศ</label>
                                    <select class="form-control select2 select2-selection--single" id="sex" name="sex" value="{{$userData->sex}}">
                                    @if($userData->sex !=null || $userData->sex !="")
                                    <option value="{{$userData->sex}}" selected>{{$userData->sex}}</option>
                                    @else
                                    <option value="" disabled selected>เลือก เพศ</option>
                                    @endif
                                    <option value="หญิง">หญิง</option>
                                    <option value="ชาย">ชาย</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <div class="col-lg-12">
                                        <label class="form-label">สัญชาติ</label>
                                        <input class="form-control" type="text" name="nationality" id="nationality" value="{{$userData->nationality}}">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 col-sm-12">
                                <div class="mb-3">
                                    <div class="col-lg-12">
                                        <label class="form-label">Line ID</label>
                                        <input class="form-control" type="text" name="line" id="line" value="{{$userData->line}}">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 col-sm-12">
                                <div class="mb-3">
                                    <div class="col-lg-12">
                                        <label class="form-label">Facebook URL</label>
                                        <input class="form-control" type="text" id="fb"  name="fb" value="{{$userData->fb}}">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 col-sm-12">
                                <div class="mb-3">
                                    <div class="col-lg-12">
                                        <label class="form-label">Instagram URL</label>
                                        <input class="form-control" type="text" id="ig" name="ig" value="{{$userData->ig}}">
                                    </div>
                                </div>
                            </div>

                        </div>

                </div>
            </div>

        </div>
        <!-- end card one -->

      
        <!-- card two -->
        <div class="col-lg-6 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <h4 class="card-title mb-3">ที่อยู่ตามเอกสาร</h4>

                        <div class="row">

                            <div class="col-lg-12">
                                <div class="mb-3">
                                        <label class="form-label">ที่อยู่</label>
                                        <textarea class="form-control" rows="3" disabled>{{$userData->address}}</textarea>
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">ภูมิภาค</label>
                                    <input class="form-control" type="text" value="{{$userData->region}}" disabled>
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">จังหวัด</label>
                                    <input class="form-control" type="text" value="{{$userData->province}}" disabled>
                                </div>
                            </div>
                            
                            <div class="col-md-6 col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">เขต/อำเภอ</label>
                                    <input class="form-control" type="text" value="{{$userData->district}}" disabled>
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">แขวง/ตำบล</label>
                                    <input class="form-control" type="text" value="{{$userData->sub_district}}" disabled>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="mb-3">
                                        <label class="form-label">รหัสไปรษณีย์</label>
                                        <input class="form-control" type="text" value="{{$userData->zip_code}}" disabled>
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-6">
                                <div class="mb-3">
                                        <label class="form-label">มือถือ</label>
                                        <input class="form-control" type="text" value="{{$userData->phone_number}}" disabled>
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-6">
                                <div class="mb-3">
                                        <label class="form-label">อีเมล์</label>
                                        <input class="form-control" type="text" value="{{$userData->email}}" disabled>
                                </div>
                            </div>

                        </div>

                </div>
            </div>

        </div>

         <!-- card three -->
         <div class="col-lg-6 mb-3">
            <div class="card h-100">

                <div class="card-body">
                    <h4 class="card-title mb-3">ที่อยู่ปัจจุบัน</h4>
                    
                        <div class="row">

                            <div class="col-md-6 col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label"><code>*</code> ประเทศ</label>
                                    <select class="form-control select2 select2-selection--single" id="country" name="country" required>
                                        @if($userData->country !=null || $userData->country !="")
                                        <option value="{{$userData->country}}" selected>{{$userData->country}}</option>
                                        @else
                                        <option value="" selected disabled>เลือกประเทศ</option>
                                        @endif
                                        <option value="Afganistan">Afghanistan</option>
                                        <option value="Albania">Albania</option>
                                        <option value="Algeria">Algeria</option>
                                        <option value="American Samoa">American Samoa</option>
                                        <option value="Andorra">Andorra</option>
                                        <option value="Angola">Angola</option>
                                        <option value="Anguilla">Anguilla</option>
                                        <option value="Antigua & Barbuda">Antigua & Barbuda</option>
                                        <option value="Argentina">Argentina</option>
                                        <option value="Armenia">Armenia</option>
                                        <option value="Aruba">Aruba</option>
                                        <option value="Australia">Australia</option>
                                        <option value="Austria">Austria</option>
                                        <option value="Azerbaijan">Azerbaijan</option>
                                        <option value="Bahamas">Bahamas</option>
                                        <option value="Bahrain">Bahrain</option>
                                        <option value="Bangladesh">Bangladesh</option>
                                        <option value="Barbados">Barbados</option>
                                        <option value="Belarus">Belarus</option>
                                        <option value="Belgium">Belgium</option>
                                        <option value="Belize">Belize</option>
                                        <option value="Benin">Benin</option>
                                        <option value="Bermuda">Bermuda</option>
                                        <option value="Bhutan">Bhutan</option>
                                        <option value="Bolivia">Bolivia</option>
                                        <option value="Bonaire">Bonaire</option>
                                        <option value="Bosnia & Herzegovina">Bosnia & Herzegovina</option>
                                        <option value="Botswana">Botswana</option>
                                        <option value="Brazil">Brazil</option>
                                        <option value="British Indian Ocean Ter">British Indian Ocean Ter</option>
                                        <option value="Brunei">Brunei</option>
                                        <option value="Bulgaria">Bulgaria</option>
                                        <option value="Burkina Faso">Burkina Faso</option>
                                        <option value="Burundi">Burundi</option>
                                        <option value="Cambodia">Cambodia</option>
                                        <option value="Cameroon">Cameroon</option>
                                        <option value="Canada">Canada</option>
                                        <option value="Canary Islands">Canary Islands</option>
                                        <option value="Cape Verde">Cape Verde</option>
                                        <option value="Cayman Islands">Cayman Islands</option>
                                        <option value="Central African Republic">Central African Republic</option>
                                        <option value="Chad">Chad</option>
                                        <option value="Channel Islands">Channel Islands</option>
                                        <option value="Chile">Chile</option>
                                        <option value="China">China</option>
                                        <option value="Christmas Island">Christmas Island</option>
                                        <option value="Cocos Island">Cocos Island</option>
                                        <option value="Colombia">Colombia</option>
                                        <option value="Comoros">Comoros</option>
                                        <option value="Congo">Congo</option>
                                        <option value="Cook Islands">Cook Islands</option>
                                        <option value="Costa Rica">Costa Rica</option>
                                        <option value="Cote DIvoire">Cote DIvoire</option>
                                        <option value="Croatia">Croatia</option>
                                        <option value="Cuba">Cuba</option>
                                        <option value="Curaco">Curacao</option>
                                        <option value="Cyprus">Cyprus</option>
                                        <option value="Czech Republic">Czech Republic</option>
                                        <option value="Denmark">Denmark</option>
                                        <option value="Djibouti">Djibouti</option>
                                        <option value="Dominica">Dominica</option>
                                        <option value="Dominican Republic">Dominican Republic</option>
                                        <option value="East Timor">East Timor</option>
                                        <option value="Ecuador">Ecuador</option>
                                        <option value="Egypt">Egypt</option>
                                        <option value="El Salvador">El Salvador</option>
                                        <option value="Equatorial Guinea">Equatorial Guinea</option>
                                        <option value="Eritrea">Eritrea</option>
                                        <option value="Estonia">Estonia</option>
                                        <option value="Ethiopia">Ethiopia</option>
                                        <option value="Falkland Islands">Falkland Islands</option>
                                        <option value="Faroe Islands">Faroe Islands</option>
                                        <option value="Fiji">Fiji</option>
                                        <option value="Finland">Finland</option>
                                        <option value="France">France</option>
                                        <option value="French Guiana">French Guiana</option>
                                        <option value="French Polynesia">French Polynesia</option>
                                        <option value="French Southern Ter">French Southern Ter</option>
                                        <option value="Gabon">Gabon</option>
                                        <option value="Gambia">Gambia</option>
                                        <option value="Georgia">Georgia</option>
                                        <option value="Germany">Germany</option>
                                        <option value="Ghana">Ghana</option>
                                        <option value="Gibraltar">Gibraltar</option>
                                        <option value="Great Britain">Great Britain</option>
                                        <option value="Greece">Greece</option>
                                        <option value="Greenland">Greenland</option>
                                        <option value="Grenada">Grenada</option>
                                        <option value="Guadeloupe">Guadeloupe</option>
                                        <option value="Guam">Guam</option>
                                        <option value="Guatemala">Guatemala</option>
                                        <option value="Guinea">Guinea</option>
                                        <option value="Guyana">Guyana</option>
                                        <option value="Haiti">Haiti</option>
                                        <option value="Hawaii">Hawaii</option>
                                        <option value="Honduras">Honduras</option>
                                        <option value="Hong Kong">Hong Kong</option>
                                        <option value="Hungary">Hungary</option>
                                        <option value="Iceland">Iceland</option>
                                        <option value="Indonesia">Indonesia</option>
                                        <option value="India">India</option>
                                        <option value="Iran">Iran</option>
                                        <option value="Iraq">Iraq</option>
                                        <option value="Ireland">Ireland</option>
                                        <option value="Isle of Man">Isle of Man</option>
                                        <option value="Israel">Israel</option>
                                        <option value="Italy">Italy</option>
                                        <option value="Jamaica">Jamaica</option>
                                        <option value="Japan">Japan</option>
                                        <option value="Jordan">Jordan</option>
                                        <option value="Kazakhstan">Kazakhstan</option>
                                        <option value="Kenya">Kenya</option>
                                        <option value="Kiribati">Kiribati</option>
                                        <option value="Korea North">Korea North</option>
                                        <option value="Korea Sout">Korea South</option>
                                        <option value="Kuwait">Kuwait</option>
                                        <option value="Kyrgyzstan">Kyrgyzstan</option>
                                        <option value="Laos">Laos</option>
                                        <option value="Latvia">Latvia</option>
                                        <option value="Lebanon">Lebanon</option>
                                        <option value="Lesotho">Lesotho</option>
                                        <option value="Liberia">Liberia</option>
                                        <option value="Libya">Libya</option>
                                        <option value="Liechtenstein">Liechtenstein</option>
                                        <option value="Lithuania">Lithuania</option>
                                        <option value="Luxembourg">Luxembourg</option>
                                        <option value="Macau">Macau</option>
                                        <option value="Macedonia">Macedonia</option>
                                        <option value="Madagascar">Madagascar</option>
                                        <option value="Malaysia">Malaysia</option>
                                        <option value="Malawi">Malawi</option>
                                        <option value="Maldives">Maldives</option>
                                        <option value="Mali">Mali</option>
                                        <option value="Malta">Malta</option>
                                        <option value="Marshall Islands">Marshall Islands</option>
                                        <option value="Martinique">Martinique</option>
                                        <option value="Mauritania">Mauritania</option>
                                        <option value="Mauritius">Mauritius</option>
                                        <option value="Mayotte">Mayotte</option>
                                        <option value="Mexico">Mexico</option>
                                        <option value="Midway Islands">Midway Islands</option>
                                        <option value="Moldova">Moldova</option>
                                        <option value="Monaco">Monaco</option>
                                        <option value="Mongolia">Mongolia</option>
                                        <option value="Montserrat">Montserrat</option>
                                        <option value="Morocco">Morocco</option>
                                        <option value="Mozambique">Mozambique</option>
                                        <option value="Myanmar">Myanmar</option>
                                        <option value="Nambia">Nambia</option>
                                        <option value="Nauru">Nauru</option>
                                        <option value="Nepal">Nepal</option>
                                        <option value="Netherland Antilles">Netherland Antilles</option>
                                        <option value="Netherlands">Netherlands (Holland, Europe)</option>
                                        <option value="Nevis">Nevis</option>
                                        <option value="New Caledonia">New Caledonia</option>
                                        <option value="New Zealand">New Zealand</option>
                                        <option value="Nicaragua">Nicaragua</option>
                                        <option value="Niger">Niger</option>
                                        <option value="Nigeria">Nigeria</option>
                                        <option value="Niue">Niue</option>
                                        <option value="Norfolk Island">Norfolk Island</option>
                                        <option value="Norway">Norway</option>
                                        <option value="Oman">Oman</option>
                                        <option value="Pakistan">Pakistan</option>
                                        <option value="Palau Island">Palau Island</option>
                                        <option value="Palestine">Palestine</option>
                                        <option value="Panama">Panama</option>
                                        <option value="Papua New Guinea">Papua New Guinea</option>
                                        <option value="Paraguay">Paraguay</option>
                                        <option value="Peru">Peru</option>
                                        <option value="Phillipines">Philippines</option>
                                        <option value="Pitcairn Island">Pitcairn Island</option>
                                        <option value="Poland">Poland</option>
                                        <option value="Portugal">Portugal</option>
                                        <option value="Puerto Rico">Puerto Rico</option>
                                        <option value="Qatar">Qatar</option>
                                        <option value="Republic of Montenegro">Republic of Montenegro</option>
                                        <option value="Republic of Serbia">Republic of Serbia</option>
                                        <option value="Reunion">Reunion</option>
                                        <option value="Romania">Romania</option>
                                        <option value="Russia">Russia</option>
                                        <option value="Rwanda">Rwanda</option>
                                        <option value="St Barthelemy">St Barthelemy</option>
                                        <option value="St Eustatius">St Eustatius</option>
                                        <option value="St Helena">St Helena</option>
                                        <option value="St Kitts-Nevis">St Kitts-Nevis</option>
                                        <option value="St Lucia">St Lucia</option>
                                        <option value="St Maarten">St Maarten</option>
                                        <option value="St Pierre & Miquelon">St Pierre & Miquelon</option>
                                        <option value="St Vincent & Grenadines">St Vincent & Grenadines</option>
                                        <option value="Saipan">Saipan</option>
                                        <option value="Samoa">Samoa</option>
                                        <option value="Samoa American">Samoa American</option>
                                        <option value="San Marino">San Marino</option>
                                        <option value="Sao Tome & Principe">Sao Tome & Principe</option>
                                        <option value="Saudi Arabia">Saudi Arabia</option>
                                        <option value="Senegal">Senegal</option>
                                        <option value="Seychelles">Seychelles</option>
                                        <option value="Sierra Leone">Sierra Leone</option>
                                        <option value="Singapore">Singapore</option>
                                        <option value="Slovakia">Slovakia</option>
                                        <option value="Slovenia">Slovenia</option>
                                        <option value="Solomon Islands">Solomon Islands</option>
                                        <option value="Somalia">Somalia</option>
                                        <option value="South Africa">South Africa</option>
                                        <option value="Spain">Spain</option>
                                        <option value="Sri Lanka">Sri Lanka</option>
                                        <option value="Sudan">Sudan</option>
                                        <option value="Suriname">Suriname</option>
                                        <option value="Swaziland">Swaziland</option>
                                        <option value="Sweden">Sweden</option>
                                        <option value="Switzerland">Switzerland</option>
                                        <option value="Syria">Syria</option>
                                        <option value="Tahiti">Tahiti</option>
                                        <option value="Taiwan">Taiwan</option>
                                        <option value="Tajikistan">Tajikistan</option>
                                        <option value="Tanzania">Tanzania</option>
                                        <option value="Thailand">Thailand</option>
                                        <option value="Togo">Togo</option>
                                        <option value="Tokelau">Tokelau</option>
                                        <option value="Tonga">Tonga</option>
                                        <option value="Trinidad & Tobago">Trinidad & Tobago</option>
                                        <option value="Tunisia">Tunisia</option>
                                        <option value="Turkey">Turkey</option>
                                        <option value="Turkmenistan">Turkmenistan</option>
                                        <option value="Turks & Caicos Is">Turks & Caicos Is</option>
                                        <option value="Tuvalu">Tuvalu</option>
                                        <option value="Uganda">Uganda</option>
                                        <option value="United Kingdom">United Kingdom</option>
                                        <option value="Ukraine">Ukraine</option>
                                        <option value="United Arab Erimates">United Arab Emirates</option>
                                        <option value="United States of America">United States of America</option>
                                        <option value="Uraguay">Uruguay</option>
                                        <option value="Uzbekistan">Uzbekistan</option>
                                        <option value="Vanuatu">Vanuatu</option>
                                        <option value="Vatican City State">Vatican City State</option>
                                        <option value="Venezuela">Venezuela</option>
                                        <option value="Vietnam">Vietnam</option>
                                        <option value="Virgin Islands (Brit)">Virgin Islands (Brit)</option>
                                        <option value="Virgin Islands (USA)">Virgin Islands (USA)</option>
                                        <option value="Wake Island">Wake Island</option>
                                        <option value="Wallis & Futana Is">Wallis & Futana Is</option>
                                        <option value="Yemen">Yemen</option>
                                        <option value="Zaire">Zaire</option>
                                        <option value="Zambia">Zambia</option>
                                        <option value="Zimbabwe">Zimbabwe</option>
                                    </select>
                                    <div class="text-danger" id="countryErr" data-ajax-feedback="country"></div>

                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="mb-3">
                                        <label class="form-label">ที่อยู่</label>
                                        <textarea class="form-control" rows="3" id="send_address" name="send_address">{{$userData->send_address}}</textarea>
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">ภูมิภาค</label>
                                    <select class="form-control select2 select2-selection--single" id="send_region" name="send_region">
                                        @if($userData->send_region !=null || $userData->send_region != "")
                                        <option value="{{$userData->send_region}}" selected>{{$userData->send_region}}</option>
                                        @else
                                        <option value="" selected disabled>เลือกภูมิภาค</option>
                                        @endif
                                        <option value="เหนือ">เหนือ</option>
                                        <option value="กลาง">กลาง</option>
                                        <option value="ตะวันออกเฉียงเหนือ">ตะวันออกเฉียงเหนือ</option>
                                        <option value="ตะวันออก">ตะวันออก</option>
                                        <option value="ตะวันตก">ตะวันตก</option>
                                        <option value="ใต้">ใต้</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">จังหวัด</label>
                                    <input class="form-control" id="send_province" id="send_province" name="send_province" type="text" value="{{$userData->send_province}}">
                                </div>
                            </div>
                            
                            <div class="col-md-6 col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">เขต/อำเภอ</label>
                                    <input class="form-control" id="send_district" id="send_district" name="send_district" type="text" value="{{$userData->send_district}}">
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">แขวง/ตำบล</label>
                                    <input class="form-control" id="send_sub_district" id="send_sub_district" name="send_sub_district" type="text" value="{{$userData->send_sub_district}}">
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="mb-3">
                                        <label class="form-label">รหัสไปรษณีย์</label>
                                        <input class="form-control" id="send_zip_code" id="send_zip_code" name="send_zip_code" type="text" value="{{$userData->send_zip_code}}">
                                        <div class="text-danger" id="zipcodeErr" data-ajax-feedback="send_zip_code"></div>
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-6">
                                <div class="mb-3">
                                        <label class="form-label">มือถือ</label>
                                        <input class="form-control" type="text" id="send_phone_number" name="send_phone_number" value="{{$userData->send_phone_number}}">
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-6">
                                <div class="mb-3">
                                        <label class="form-label">อีเมล์</label>
                                        <input class="form-control" type="email" id="send_email" name="send_email" parsley-type="email" value="{{$userData->send_email}}">
                                </div>
                            </div>

                        </div>

                </div>

            </div>

        </div>
        <!-- end card three -->

    

        <div class="col-12">
            <center>
                <button id="saveBtn" type="submit" class="btn btn-success btn-lg waves-effect waves-light">บันทึก </button>
            </center>

        </div>
    </form>


    </div>

@endsection

@section('script')
<script src="{{ URL::asset('/assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ URL::asset('/assets/libs/parsleyjs/parsleyjs.min.js') }}"></script>
<script src="{{ URL::asset('/assets/js/pages/form-validation.init.js') }}"></script>
<script src="{{ URL::asset('/assets/libs/select2/select2.min.js') }}"></script>
<script src="{{ URL::asset('/assets/libs/thailand/JQL.min.js') }}"></script>
<script src="{{ URL::asset('/assets/libs/thailand/typeahead.bundle.js') }}"></script>
<script src="{{ URL::asset('/assets/libs/thailand/jquery.Thailand.min.js') }}"></script>
<script>
$.Thailand({
    $district: $('#send_sub_district'), // input ของตำบล
    $amphoe: $('#send_district'), // input ของอำเภอ
    $province: $('#send_province'), // input ของจังหวัด
    $zipcode: $('#send_zip_code'), // input ของรหัสไปรษณีย์
    onDataFill: function(data){
       
    }
    
});

$('#form_update_profile').on('submit',function(event){
        event.preventDefault();
        
        $('#saveBtn').append('<i id="loadingBtn" class="bx bx-loader bx-spin font-size-16 align-middle me-2"></i>');
        $("#saveBtn").attr("disabled", true);
        var country = $('#country').val();
        var csrf = $('#csrf_token').val();
        var prefixName = $('#prefix_name').val();
        var sex = $('#sex').val();
        var nationality = $('#nationality').val();
        var line = $('#line').val();
        var fb = $('#fb').val();
        var ig = $('#ig').val();
        var line = $('#line').val();
        var country = $('#country').val();
        
        var sendAddress = $('#send_address').val();
        var sendRegion = $('#send_region').val();
        var sendProvince = $('#send_province').val();
        var sendDistrict = $('#send_district').val();
        var sendSubDistrict = $('#send_sub_district').val();
        var sendZipCode = $('#send_zip_code').val();
        var sendPhoneNumber = $('#send_phone_number').val();
        var sendEmail = $('#send_email').val();
        
        $('#current_passwordErr').text('');
        $('#passwordErr').text('');
        $('#password_confirmErr').text('');
        $.ajax({
            url: "{{ route('accountProfileUpdate') }}",
            type:"put",
            data:{
                "_token": csrf,
                "prefix_name": prefixName,
                "sex": sex,
                "nationality": nationality,
                "line": line,
                "fb": fb,
                "ig": ig,
                "country": country,
                "send_region":sendRegion,
                "send_address": sendAddress,
                "send_province": sendProvince,
                "send_district": sendDistrict,
                "send_sub_district": sendSubDistrict,
                "send_zip_code": sendZipCode,
                "send_phone_number": sendPhoneNumber,
                "send_email": sendEmail
            },
            
            success:function(response){
                
                $('#zipcodeErr').text('');
                $('#countryErr').text('');
                $("#loadingBtn").remove();
                $("#saveBtn").attr("disabled", false);
                if(response.isSuccess == false){

                    $('#zipcodeErr').text(response.errors.send_zip_code);
                    $('#countryErr').text(response.errors.country);

                }else if(response.isSuccess == true){
                        Swal.fire(
                            'Success!',
                             response.Message,
                            'success'
                        )          
                }
                
            },
            error: function(response) {
                //console.log(response.responseJSON)
                $('#zipcodeErr').text(response.responseJSON.errors.send_zip_code);
            }
        });
        
       
    });
</script>
@endsection

