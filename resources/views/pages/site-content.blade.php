@extends('layouts.admin.app')

@section('title',__('Правка сведения о сайте'))

@section('content')
  @component('my.panel')
    @component('my.page-header',['title'=>__('Правка сведения о сайте')]) @endcomponent
    <div id="page" xmlns:v-bind="http://www.w3.org/1999/xhtml">
			<span class="form-horizontal">
				@component("my.hr-form-group",['lw'=>4,'fw'=>6,'id'=>'lang','title'=>__('Язык')])
          <select id="lang" class="form-control" v-model="selectedLanguage">
					<option v-for="(language,index) in languages" :value="index">
						@{{language.name}}
					</option>
				</select>
        @endcomponent
			</span>
      @component('my.accordion',['id'=>'myac'])
        @component('my.accordion-tab',['aid'=>'myac','id'=>'tab1',
        'title'=>__('На шапке'),'open'=>true])
          <span class="form-horizontal">
						@component("my.hr-form-group",['lw'=>4,'fw'=>6,'id'=>'shortname','title'=>__('Краткое имя компаний')])
              <input class="form-control" type="text"
                     v-model="content.companyShortNames[selectedLanguage].value">
            @endcomponent
              @component('my.panel')
                @component("my.hr-form-group",['lw'=>4,'fw'=>6,'id'=>'contactus','title'=>__('Имя ссылки: "Оставить заявку"')])
                  <input class="form-control" type="text"
                         v-model="content.contactUsLinkNames[selectedLanguage].value">
                @endcomponent
                @component("my.hr-form-group",['lw'=>4,'fw'=>6,'id'=>'contactust','title'=>__('Имя заголовка: "Подать заявку"')])
                  <input class="form-control" type="text"
                         v-model="content.contactUsTitleNames[selectedLanguage].value">
                @endcomponent
                  @component("my.hr-form-group",['lw'=>4,'fw'=>6,'id'=>'fullname','title'=>__('ФИО')])
                    <input class="form-control" type="text"
                           v-model="content.contactUsModal.fullNameTitles[selectedLanguage].value">
                  @endcomponent
                  @component("my.hr-form-group",['lw'=>4,'fw'=>6,'id'=>'contactphone','title'=>__('Контакный телефон')])
                    <input class="form-control" type="text"
                           v-model="content.contactUsModal.contactPhoneTitles[selectedLanguage].value"/>
                  @endcomponent
                  @component("my.hr-form-group",['lw'=>4,'fw'=>6,'id'=>'email','title'=>__('Электронная почта')])
                    <input class="form-control" type="text"
                           v-model="content.contactUsModal.emailTitles[selectedLanguage].value"/>
                  @endcomponent
                  @component("my.hr-form-group",['lw'=>4,'fw'=>6,'id'=>'message','title'=>__('Сообщение')])
                    <input class="form-control" type="text"
                           v-model="content.contactUsModal.messageTitles[selectedLanguage].value"/>
                  @endcomponent
                  @component("my.hr-form-group",['lw'=>4,'fw'=>6,'id'=>'sendbutton','title'=>__('ОТПРАВИТЬ')])
                    <input class="form-control" type="text"
                           v-model="content.contactUsModal.sendButtonTitle[selectedLanguage].value"/>
                  @endcomponent
                  @component("my.hr-form-group",['lw'=>4,'fw'=>6,'id'=>'feedback','title'=>__('Сообщение после отправки')])
                    <input class="form-control" type="text"
                           v-model="content.contactUsModal.feedback[selectedLanguage].value"/>
                  @endcomponent
              @endcomponent
              @component("my.hr-form-group",['lw'=>4,'fw'=>6,'id'=>'shortname','title'=>__('Основной телефон компаний')])
                <input class="form-control" type="text"
                       v-model="content.companyMainPhone">
              @endcomponent
					</span>
        @endcomponent
        @component('my.accordion-tab',['aid'=>'myac','id'=>'tab2',
        'title'=>__('Социальные сети'),'open'=>false])
            <ul class="list-group">
              <li class="list-group-item" v-for="(sn,index) in sortedSocialNetworks">
                  <input type="text" v-model="sn.link">
                   - @{{ sn.name }}
                    <span class="pull-right">
                  <button class="btn btn-default btn-xs" @@click="rowDown(index)"
                     v-bind:class="{disabled: (index===(sortedSocialNetworks.length-1))}">
                    <i class="fa fa-chevron-down"></i>
                  </button>
                  <button class="btn btn-default btn-xs" @@click="rowUp(index)"
                     v-bind:class="{disabled: (index===0)}">
                    <i class="fa fa-chevron-up"></i>
                  </button>
                </span>
              </li>
            </ul>
          @endcomponent
        @component('my.accordion-tab',['aid'=>'myac','id'=>'tab3',
        'title'=>__('Внизу'),'open'=>false])
            <span class="form-horizontal">
              @component("my.hr-form-group",['lw'=>4,'fw'=>6,'id'=>'fullname','title'=>__('Полное имя компаний')])
                <input class="form-control" type="text"
                       v-model="content.companyFullName[selectedLanguage].value"/>
              @endcomponent
                @component("my.hr-form-group",['lw'=>4,'fw'=>6,'id'=>'startyear','title'=>__('Год рождения компаний')])
                  <input class="form-control" type="number"
                         v-model="content.companyStartYear"/>
                @endcomponent
                @component("my.hr-form-group",['lw'=>4,'fw'=>6,'id'=>'madebytext','title'=>__('Текст: "Спроектировано"')])
                  <input class="form-control" type="text"
                         v-model="content.madeByText[selectedLanguage].value"/>
                @endcomponent
                @component("my.hr-form-group",['lw'=>4,'fw'=>6,'id'=>'madebycomp','title'=>__('Компания-разработчик сайта')])
                  <input class="form-control" type="text"
                         v-model="content.madeByCompanyName"/>
                @endcomponent
                @component("my.hr-form-group",['lw'=>4,'fw'=>6,'id'=>'madebylink','title'=>__('Ссылка на сайт компаний-разработчика')])
                  <input class="form-control" type="text"
                         v-model="content.madeByCompanyLink"/>
                @endcomponent
                @component("my.hr-form-group",['lw'=>4,'fw'=>6,'id'=>'madebyyear','title'=>__('Год разработки сайта')])
                  <input class="form-control" type="number"
                         v-model="content.madeByYear"/>
                @endcomponent
            </span>
        @endcomponent
      @endcomponent
      <button class="btn btn-primary" @@click="save()">@lang('Сохранить')</button>
      {{--<button class="btn btn-default" @@click="reset()">@lang('Вернуть на значения по умолчанию')</button>--}}
    </div>
  @endcomponent
@endsection

@section('script')
  <script type="text/javascript">
  contentSensitivePage = true;
    new Vue({
      el: '#page',
      data:{
        selectedLanguage: 0,
        languages:[
            @foreach($languages as $language)
          {
            code: '{{$language->code}}',
            name: '{{$language->name}}',
          },
          @endforeach
        ],
        initialContent:{
          companyShortNames: [
              @foreach($languages as $language)
            {
              languageCode: '{{$language->code}}',
              value: "ЦАРКА",
            },
            @endforeach
          ],
          contactUsLinkNames: [
              @foreach($languages as $language)
            {
              languageCode: '{{$language->code}}',
              value: "оставить заявку",
            },
            @endforeach
          ],
          contactUsTitleNames: [
              @foreach($languages as $language)
            {
              languageCode: '{{$language->code}}',
              value: "подать заявку",
            },
            @endforeach
          ],
          contactUsModal: {
            fullNameTitles:[
                @foreach($languages as $language)
              {
                languageCode: '{{$language->code}}',
                value: "ФИО",
              },
              @endforeach
            ],
            contactPhoneTitles:[
                @foreach($languages as $language)
              {
                languageCode: '{{$language->code}}',
                value: "Контакный телефон",
              },
              @endforeach
            ],
            emailTitles:[
                @foreach($languages as $language)
              {
                languageCode: '{{$language->code}}',
                value: "Электронная почта",
              },
              @endforeach
            ],
            messageTitles:[
                @foreach($languages as $language)
              {
                languageCode: '{{$language->code}}',
                value: "Сообщение",
              },
              @endforeach
            ],
            sendButtonTitle:[
                @foreach($languages as $language)
              {
                languageCode: '{{$language->code}}',
                value: "ОТПРАВИТЬ",
              },
              @endforeach
            ],
            feedback:[
              @foreach($languages as $language)
              {
                languageCode: '{{$language->code}}',
                value: "Спасибо ваша заявка принята. Мы рассмотрим её в самом скором времени.",
              },
              @endforeach
            ],
          },
          companyMainPhone: '+7 705 658-89-76',
          companyFullName: [
              @foreach($languages as $language)
            {
              languageCode: '{{$language->code}}',
              value: "Центр анализа и расследования кибер атак",
            },
            @endforeach
          ],
          companyStartYear: 2015,
          madeByText: [
              @foreach($languages as $language)
            {
              languageCode: '{{$language->code}}',
              value: "Спроектировано",
            },
            @endforeach
          ],
          madeByCompanyName: "DDB DEPO",
          madeByCompanyLink: "",
          madeByYear: 2017,
          socialNetworks:[
            {name:"facebook",link:"",order:6},
            {name:"twitter",link:"",order:5},
            {name:"vk",link:"",order:4},
            {name:"youtube",link:"",order:1},
            {name:"instagram",link:"",order:2},
            {name:"telegram",link:"",order:3},
          ]
        },
        content:true,
      },
      created(){
        this.content = {!! $company->content !!};
        if(this.content==true){
          this.content = this.initialContent;
          console.log('initialised');
        }
        var i=0;
        for(language of this.languages){
          if(typeof this.content.companyShortNames[i] === 'undefined'){
            this.content.companyShortNames[i]={languageCode:language.code,value:""};
          }
          if(typeof this.content.contactUsLinkNames[i] === 'undefined'){
            this.content.contactUsLinkNames[i]={languageCode:language.code,value:""};
          }
          if(typeof this.content.contactUsModal.fullNameTitles[i] === 'undefined'){
            this.content.contactUsModal.fullNameTitles[i]={languageCode:language.code,value:""};
          }
          if(typeof this.content.contactUsModal.contactPhoneTitles[i] === 'undefined'){
            this.content.contactUsModal.contactPhoneTitles[i]={languageCode:language.code,value:""};
          }
          if(typeof this.content.contactUsModal.emailTitles[i] === 'undefined'){
            this.content.contactUsModal.emailTitles[i]={languageCode:language.code,value:""};
          }
          if(typeof this.content.contactUsModal.messageTitles[i] === 'undefined'){
            this.content.contactUsModal.messageTitles[i]={languageCode:language.code,value:""};
          }
          if(typeof this.content.contactUsModal.sendButtonTitle[i] === 'undefined'){
            this.content.contactUsModal.sendButtonTitle[i]={languageCode:language.code,value:""};
          }
          if(typeof this.content.companyFullName[i] === 'undefined'){
            this.content.companyFullName[i]={languageCode:language.code,value:""};
          }
          if(typeof this.content.madeByText[i] === 'undefined'){
            this.content.madeByText[i]={languageCode:language.code,value:""};
          }
          i++;
        }
      },
      computed:{
        sortedSocialNetworks(){
          return this.content.socialNetworks.sort(this.compare);
        }
      },
      methods:{
        rowDown(index){
          this.content.socialNetworks[index].order++;
          this.content.socialNetworks[index+1].order--;
        },
        rowUp(index){
          this.content.socialNetworks[index].order--;
          this.content.socialNetworks[index-1].order++;
        },
        compare(a,b) {
          if (a.order < b.order)
            return -1;
          if (a.order > b.order)
            return 1;
          return 0;
        },
        save(){
          var notValid = false;
          //validation
          if(notValid==false){
            axios.post('{{route("save-content")}}', this.content)
              .then(function (response) {
                if(response.data==='ok'){
                  alertify.success("@lang('Успешно сохранено')");
                }else if(response.data==='security exception'){
                  alertify.error('@lang("У вас нет прав на это")')
                }else if(response.data==='exception'){
                  alertify.error('@lang("Ошибка на сервере")')
                }
                console.log(response.data);
              })
              .catch(function (error) {
                alertify.error('@lang("Ошибка подключения на сервер")');
                console.log(error.data);
              });
          }else{
            alertify.error("@lang('Заполните все')");
          }
        },
        reset(){
          var r = confirm("@lang('Вы уверены?')");
          if (r == true) {
            this.content = this.initialContent;
          }
        }
      }
    });
  </script>
@endsection