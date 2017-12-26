@extends('layouts.admin.app')

@section('title',__('Контент страницы').': ')

@section('content')
  @component('my.panel')
    @component('my.page-header',['title'=>__('Контент страницы').': ']) @endcomponent
    <div id="page" xmlns:v-bind="http://www.w3.org/1999/xhtml">
      <div class="form-horizontal">
        @component("my.hr-form-group",['lw'=>3,'fw'=>9,'id'=>'lang','title'=>__('Язык')])
          <select id="lang" class="form-control" v-model="selectedLanguage" @@change="updateAreas">
          <option v-for="(language,index) in languages" :value="index">
            @{{language.name}}
          </option>
        </select>
        @endcomponent
      @component('my.accordion',['id'=>'myac'])
        @if((Entrust::hasRole("admin")))
          <my-accordion-tab v-if="typeof newPresentation!=='undefined'"
              id="new_presentation" aid="myac" title="@lang('Новая презентация')" expandable="false">
              <!-- new presentation -->
              <hr-form-group lw="3" fw="9" title="@lang('ID')" >
                <input type="text" class="form-control" v-model="newPresentation.id">
              </hr-form-group>
              <hr-form-group lw="3" fw="9" title="@lang('Порядок')" >
                <input type="number" class="form-control" v-model="newPresentation.order">
              </hr-form-group>
              @component("my.hr-form-slot",['lw'=>3,'fw'=>9])
                <div class="checkbox">
                  <label>
                    <input type="checkbox" v-model="newPresentation.useTitle"> @lang('Показывать название на странице')
                  </label>
                </div>
              @endcomponent
              @component("my.hr-form-slot",['lw'=>3,'fw'=>9])
                <button class="btn btn-success"
                @@click="addNewItem(content.presentations,newPresentation,[newPresentation.id],'presentation')">
                  @lang('Добавить')
                </button>
              @endcomponent
              <!-- new presentation end-->
          </my-accordion-tab>
        @endif
          <my-accordion-tab
            v-for="(presentation,index) in preparedPresentations"
            :id="'pres_'+index" aid="myac" :expandable="(index>0)?false:true" :title="presentation.titles[selectedLanguage].value">
            <div class="pull-right" slot="args">
              @if((Entrust::hasRole("admin")))
                <button class="btn btn-default btn-xs" @@click='rowDown(content.presentations,index)'
                  v-bind:class="{disabled: (index==(content.presentations.length-1))}">
                  <i class="fa fa-chevron-down"></i>
                </button>
                <button class="btn btn-default btn-xs" @@click='rowUp(content.presentations,index)'
                  v-bind:class="{disabled: (index==0)}">
                  <i class="fa fa-chevron-up"></i>
                </button>
                <button class="btn btn-danger btn-xs" v-if="typeof newPresentation!=='undefined'" 
                @@click="deleteItem(content.presentations,index)">
                  @lang("Удалить")
                </button>
              @endif
            </div>
            <!-- existing presentation -->
            <hr-form-group lw="3" fw="9" :title="getPresentationLabelName(index)">
              <input type="text" class="form-control"
                     v-model="presentation.titles[selectedLanguage].value"
              >
            </hr-form-group>
            @if((Entrust::hasRole("admin")))
              <hr-form-group lw="3" fw="9" title="@lang('ID')" >
                <input type="text" class="form-control" v-model="presentation.id">
              </hr-form-group>
              <hr-form-group lw="3" fw="9" title="@lang('Порядок')" >
                <input type="number" class="form-control" v-model="presentation.order">
              </hr-form-group>
              <hr-form-group lw="3" fw="9" title="@lang('Новости')" >
                <select class="form-control" v-model="presentation.newsCategory">
                  <option value="none">@lang('Не добавлять')</option>
                  <option value="main">@lang('Основные')</option>
                  <option value="smi">@lang('СМИ о нас')</option>
                </select>
              </hr-form-group>
              <hr-form-group lw="3" fw="9" title="@lang('Ссылка на презентацию')"
                             v-if="typeof presentation.inheritFrom!=='undefined'">
                <div>
                  <div>
                    <label for="exampleInput1">@lang('Страница')</label>
                    <select id="exampleInput1" class="form-control" v-model="presentation.inheritFrom.page">
                      <option value="-1">@lang('Не добавлять')</option>
                      <option v-for="(page,index) in pages" :value="index">@{{page.value}}</option>
                    </select>
                  </div>
                  <div v-if="typeof pages[presentation.inheritFrom.page]!=='undefined'">
                    <label for="exampleInput2">@lang('Презентация')</label>
                    <select id="exampleInput2" class="form-control" v-model="presentation.inheritFrom.presentation">
                      <option value="none">@lang('Не добавлять')</option>
                      <option value="all">@lang('Добавить всех')</option>
                      <option v-for="presentation in pages[presentation.inheritFrom.page].content.presentations"
                              :value="presentation.id"
                      >
                        @{{ getNativeTitle(presentation) }}
                      </option>
                    </select>
                  </div>
                  <div v-if="typeof pages[presentation.inheritFrom.page]!=='undefined'">
                    <label for="limit">@lang('Лимит')</label>
                    <input type="number" v-model="presentation.inheritFrom.limit" class="form-control">
                  </div>
                  <div v-if="typeof pages[presentation.inheritFrom.page]!=='undefined'">
                    <label for="origin">@lang("Ссылка")</label>
                    <input type="text" v-model="presentation.inheritFrom.origin" class="form-control">
                  </div>
                </div>
              </hr-form-group>
              @component("my.hr-form-slot",['lw'=>3,'fw'=>9])
                <div class="checkbox">
                  <label>
                    <input type="checkbox" v-model="presentation.useTitle"> @lang('Показывать название на странице')
                  </label>
                </div>
              @endcomponent
            @elseif(Entrust::hasRole("editor"))
              <p v-if="typeof presentation.inheritFrom!=='undefined' && presentation.inheritFrom.page>=0 && presentation.inheritFrom.presentation!=='none'" 
              class="alert-info">
                <em>
                  @lang("Эта презентация привязана к другой презентации с другой страницы"): 
                  @{{pages[presentation.inheritFrom.page].value}} / @{{presentationNativeTitle(pages[presentation.inheritFrom.page],presentation.inheritFrom.presentation)}} 
                </em>
              </p>
              <p v-if="typeof presentation.newsCategory!=='undefined' && presentation.newsCategory!=='none'" 
              class="alert-info">
                <em>
                  @lang("Эта презентация содержит новости категории"): 
                  @{{printNewsCategory(presentation.newsCategory)}} 
                </em>
              </p>
            @endif
            <hr>
            <!-- existing presentation end-->
            <!-- existing fields-->
            <div v-for="(field,i) in presentation.fields">
              <hr-form-group lw="3" fw="9" :title="field.label">
                <div v-if="(field.type=='image')">
                  <div class="input-group" style="width:100%;">
                    <input type="text" v-model="field.value" class="form-control my-image-receiver" :id="field.id"
                           placeholder="@lang('URL') @lang('ссылка')">
                    <div class="input-group-btn">
                      <button class="btn btn-default" data-toggle="modal" data-target="#change-image" @@click="opened('destination#'+index+'#'+i)">
                        @lang('Загрузить свой')
                      </button>
                    </div>
                  </div>
                  <br v-if="field.value">
                  <img :src="field.value" class="preview" v-if="field.value">
                </div>
                <input v-else-if="field.type!=='textarea'&&field.translatable==false" :type="field.type" class="form-control"
                       v-model="field.value"
                >
                <input v-else-if="field.type!=='textarea'&&field.translatable==true" :type="field.type" class="form-control"
                       v-model="field.values[selectedLanguage].value"
                >
                <textarea
                  class=form-control
                  rows="10"
                  v-else-if="field.type==='textarea'&&field.translatable==false"
                  v-model="field.value"
                  :id="'pres_'+index+'_field_'+i"
                ></textarea>
                <textarea
                  class=form-control
                  rows=10
                  v-else-if="field.type==='textarea'&&field.translatable==true"
                  v-model="field.values[selectedLanguage].value"
                  :id="'pres_'+index+'_field_'+i"
                ></textarea>
                {{--<ckeditor v-else-if="field.type==='textarea'&&field.translatable==false"
                          v-model="field.value"
                          :id="'pres_'+index+'_field_'+i">
                </ckeditor>
                <ckeditor v-else-if="field.type==='textarea'&&field.translatable==true"
                          v-for="(value,i3) in field.values"
                          v-show="i3==selectedLanguage"
                          v-model="value.value"
                          :id="'pres_'+index+'_field_'+i+'_value_'+i3">
                </ckeditor>--}}
              </hr-form-group>
              @if((Entrust::hasRole("admin")))
                <hr-form-group lw="3" fw="9" title="@lang('ID')">
                  <input type="text" class="form-control" v-model="field.id">
                </hr-form-group>
                <hr-form-group lw="3" fw="9" title="@lang('Название')">
                  <input type="text" class="form-control" v-model="field.label">
                </hr-form-group>
                <hr-form-group lw="3" fw="9" title="@lang('Порядок')">
                  <input type="number" class="form-control" v-model="field.order">
                </hr-form-group>
                <hr-form-group lw="3" fw="9" title="@lang('Тип')">
                  <select class="form-control" v-model="field.type">
                    <option v-for="type in newField.types" :value="type">@{{type}}</option>
                  </select>
                </hr-form-group>
                @component("my.hr-form-slot",['lw'=>3,'fw'=>9])
                  <div class="checkbox">
                    <label><input type="checkbox" v-bind:class="{disabled:(presentation.type=='text'||presentation.type=='textarea')}"
                    v-model="field.translatable">@lang('Переводимое')</label>
                  </div>
                @endcomponent
                @component("my.hr-form-slot",['lw'=>3,'fw'=>9])
                  <button class="btn btn-default btn-xs" @@click='rowDown(presentation.fields,i)'
                    v-bind:class="{disabled: (i===(presentation.fields.length-1))}"
                    v-if="typeof field.order !=='undefined'"
                  >
                    <i class="fa fa-chevron-down"></i>
                  </button>
                  <button class="btn btn-default btn-xs" @@click='rowUp(presentation.fields,i)'
                    v-bind:class="{disabled: (i===0)}"
                    v-if="typeof field.order !=='undefined'"
                  >
                    <i class="fa fa-chevron-up"></i>
                  </button>
                  <button class="btn btn-danger btn-xs" v-if="typeof presentation.newField!=='undefined'" 
                  @@click="deleteItem(presentation.fields,i)">
                    @lang("Удалить")
                  </button>
                @endcomponent
              @endif
            </div>
            @if((Entrust::hasRole("admin")))
            <!-- existing field end-->
            <!-- new field-->
            <hr>
              <hr-form-group lw="3" fw="9" title="@lang('ID')">
                <input type="text" class="form-control" v-model="newField.id">
              </hr-form-group>
              <hr-form-group lw="3" fw="9" title="@lang('Название')">
                <input type="text" class="form-control" v-model="newField.label">
              </hr-form-group>
              <hr-form-group lw="3" fw="9" title="@lang('Порядок')">
                <input type="number" class="form-control" v-model="newField.order">
              </hr-form-group>
              <hr-form-group lw="3" fw="9" title="@lang('Тип')">
                <select class="form-control" v-model="newField.type">
                  <option v-for="type in newField.types" :value="type">@{{type}}</option>
                </select>
              </hr-form-group>
              @component("my.hr-form-slot",['lw'=>3,'fw'=>9])
                <div class="checkbox">
                  <label><input type="checkbox"
                  v-model="newField.translatable">@lang('Переводимое')</label>
                </div>
              @endcomponent
              {{--<hr-form-group lw="3" fw="9" title="@lang('Значение')">
                <input type="text" name="">
              </hr-form-group>--}}
              @component("my.hr-form-slot",['lw'=>3,'fw'=>9])
                <button type="submit" class="btn btn-success" 
                @@click="addNewItem(presentation.fields,newField,[newField.label,newField.id,newField.type,newField.order],'field')">
                  @lang('Добавить поле')
                </button>
              @endcomponent
              <!-- new field end-->
            @endif
          </my-accordion-tab>
      @endcomponent
          <button class="btn btn-primary" v-bind:class="{disabled:this.content.presentations.length==0}" @@click="save()">@lang('Сохранить')</button>
          @if((Entrust::hasRole("admin")))
          <button class="btn btn-default" @@click="reset()">@lang('Стереть все')</button>
          @endif
        </div>
    </div>
  @endcomponent
  @include('images.image')
@endsection

@section('script')
  <script type="text/javascript" src="{{asset('ckeditor/ckeditor.js')}}"></script>
  @include('my.ckeditor')
  @include('pages.page-script')
@endsection

@section('style')
  @include('images.image-style')
@endsection