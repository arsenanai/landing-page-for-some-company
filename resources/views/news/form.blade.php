<div class="form-horizontal">
	@component("my.hr-form-slot",['lw'=>3,'fw'=>9])
		<select class="form-control" v-model="selectedLanguage">
			<option v-for="(language,index) in languages" :value="index">
				@{{language.name}}
			</option>
		</select>
	@endcomponent
	<hr-form-group lw=3 fw=9 :title="field.label" v-for="(field,index) in news.fields" :args="field.args">
    <div v-if="(field.type=='image')">
			<div class="input-group" style="width:100%;">
				<input type="text" v-model="field.value" class="form-control my-image-receiver" :id="field.id"
							 placeholder="@lang('URL') @lang('ссылка')" @@change="previewImage(field.value)">
				<div class="input-group-btn">
					<button class="btn btn-default" data-toggle="modal" data-target="#change-image" @@click="opened">
						@lang('Загрузить свой')
					</button>
				</div>
      </div>
      <br v-if="previewUrl">
      <img :src="previewUrl" class="preview" v-if="previewUrl">
    </div>
		<input v-else-if="(field.type!='textarea'&&field.type!='select'&&field.type!='image')"
    :type="field.type" v-model="field.value" class="form-control" :id="field.id">
		<textarea v-else-if="field.type=='textarea'&&field.id!='field#body'"
              v-model="field.values[selectedLanguage].value" 
              class="form-control"
              @@change="update(field)"
              >
    </textarea>
    <ckeditor v-else-if="field.id=='field#body'"
              v-for="(value,i3) in field.values"
              v-show="i3==selectedLanguage"
              v-model="value.value"
              :id="'field_'+index+'_value_'+i3"
              @@change="update(field)"
              >
    </ckeditor>
		<select v-else-if="field.type=='select'" class="form-control" v-model="field.value" @@change="update(field)">
			<option v-for="option in field.options" :value="option.key">@{{option.value}}</option>
		</select>
	</hr-form-group>
	@component("my.hr-form-slot",['lw'=>3,'fw'=>9])
		<button class="btn btn-primary"
					 @@click="validateAndSendForm('{{$link}}',news)" v-if="showSaveButton">
           @lang('Сохранить')
           <img class="loader-btn" src="{{asset('loader/white.svg')}}" v-if="showLoader">
    </button>   
    <div class="alert alert-success" v-if="showSaveButton==false">
    	@lang('Вы можете увидеть добавленную новость в общем списке')
    </div>    
	@endcomponent
</div>