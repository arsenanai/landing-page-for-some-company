@include('images.image-script')
<script type="text/javascript">
contentSensitivePage = true;
Vue.component('my-accordion-tab', {
  template:`
<div class="panel panel-default">
  <div class="panel-heading" role="tab" :id="id">
    <h4 class="panel-title">
      <a role="button" data-toggle="collapse" :data-parent="'#'+aid" :href="'#collapse'+id" 
      :aria-controls="'collapse'+id" 
      :aria-expanded="expandable" v-bind:class="{ collapsed:expandable==false }"
      >
        @{{title}}
      </a>
      <slot name="args"></slot>
    </h4>
  </div>
  <div :id="'collapse'+id" class="panel-collapse collapse" v-bind:class="{ in: expandable==true}" role="tabpanel" 
  :aria-labelledby="'heading'+id">
    <div class="panel-body">
      <slot></slot>
    </div>
  </div>
</div>
  `,
  props: ['aid','id','expandable','title'],
});

Vue.component('hr-form-group', {
  template:`
<div class="form-group">
    <label for="name" :class="'col-md-'+lw+' control-label'">
        @{{ title }}<span v-if="args.includes('required')==true" style="color:red;">*</span>
    </label>
    <div :class="'col-md-'+fw">
        <slot></slot>
    </div>
</div>
  `,
  props: {
    lw:{
      type: Number,
      default: 4
    },
    fw:{
      type: Number,
      default: 6
    },
    args: {
      type: String,
      default: ''
    },
    title:String
  },
});

  //Vue.config.devtools = true;
    var formBus = new Vue({
      el: '#page',
      data:{
        pid: {{$page->id}},
        activeLanguage: '{{ App::getLocale() }}',
        selectedLanguage: 2,
        showLoader:false,
        languages:[
            @foreach($languages as $language)
          {
            code: '{{$language->code}}',
            name: '{{$language->name}}',
          },
          @endforeach
        ],
        pages:[
          @foreach(App\Page::where("id","!=",$page->id)->orderBy("order","asc")->get() as $p)
          {
            key: {{$p->id}},
            value: '{{$p->printName()}}',
            content:  {!! $p->content !!}
          },
          @endforeach
        ],
        newPresentation:{
          id: "pres#",
          useTitle: true,
          order:1,
          titles:[
            @foreach($languages as $language)
            {
              languageCode: '{{$language->code}}',
              value: 'Презентация {{$language->name}}',
            },
            @endforeach
          ],
          newsCategory:"none",
          hasFields:true,
          inheritFrom:{
            page:-1,
            presentation:"none",
            limit:10,
            origin:"",
          },
          inheritedContent:{},
          fields:[],
          sliders:[],
          tempField:{},
        },
        newField:{
          label:"Поле ",
          id:"field#",
          translatable:true,
          type:"textarea",
          types:['text','number','date','email','textarea','tel','image'],
          order:1,
          args:"",
          values:[
            @foreach($languages as $language)
            {
              languageCode: '{{$language->code}}',
              value: '',
            },
            @endforeach
          ],
          value:null,
        },
        content:true,
      },
      created(){
        this.content = {!! $page->content !!};
        if(this.content==true){
          this.content = {presentations:[]};
          console.log('initialised');
        }
      },
      mounted(){
        /*$.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });*/
        $("textarea").each(function(){
          CKEDITOR.replace( this.id);
        });
        /*CKEDITOR.on('dialogDefinition', function (ev) {
          var dialogName = ev.data.name;
          var dialogDefinition = ev.data.definition;
          if (dialogName == 'image') {
            dialogDefinition.removeContents('Загрузить');
            dialogDefinition.addContents({
              title: "Загрузить",
              id: "upload",
              label: "Загрузить",
              elements: [{
                type: "html",
                html: '<form><input id="imageupload" type="file" name="files[]" />{{ csrf_field() }}'
                +'<button class="btn btn-default">@lang("Загрузить на сервер")</button></form>'
              }]
            });
           }
        });*/
      },
      computed:{
        preparedPresentations(){
          var i;
          this.content.presentations.sort(this.compare);
          for(presentation of this.content.presentations){
            presentation.fields.sort(this.compare);
            i = 0;
            for(language of this.languages){
              if(typeof presentation.titles[i] === 'undefined')
                presentation.titles[i]={languageCode:language.code,value:""};
              if(typeof presentation.newField.values[i] === 'undefined')
                presentation.newField.values[i]={languageCode:language.code,value:""};
              i++;
            }
          }
          return this.content.presentations;
        }
      },
      methods:{
        itemOrFalse(array,x,y){
          if(typeof array[x][y] !== 'undefined'){
            return array[x][y];
          }else
            return false;
        },
        opened(destination){
          imageBus.$emit('opened',destination);
        },
        rowDown(array,index){
            array[index].order++;
            array[index+1].order--;
        },
        rowUp(array,index){
            array[index-1].order++;
            array[index].order--;
        },
        compare(a,b) {
          if (parseInt(a.order) < parseInt(b.order))
            return -1;
          if (parseInt(a.order) > parseInt(b.order))
            return 1;
          return 0;
        },
        save(){
          var notValid = false;
          //validation
          var result = '';
          if(this.pid===2){
            for(var i=19; i<19+8; i++){
              for(var j=19; j<19+8; j++){
                if(this.content.presentations[0].fields[i].value===
                  this.content.presentations[0].fields[j].value && i!=j){
                  result = 'non-unique';
                  notValid = true;
                  break;
                }else if(this.content.presentations[0].fields[i].value===''){
                  result = 'empty';
                  notValid = true;
                  break;
                }
              }
              if(result==='non-unique')
                break;  
            } 
          }
          //end validation
          if(notValid==false && result===''){
            axios.post('{{route("page-save",['id'=>$page->id])}}', this.content)
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
          }else if(notValid==true && result === 'empty'){
            alertify.error("@lang('Заполните все')");
          }else if(notValid==true && result === 'non-unique'){
            alertify.error("@lang('Ссылки на сервисы должны быть уникальными')");
          }
        },
        reset(){
          var r = confirm("@lang('Вы уверены?')");
          if (r == true) {
            this.content = {presentations:[]};
            console.log('initialised');
          }
        },
        addNewItem(array,item,requiredFields,what){
          var permission = true;
          for(requiredField of requiredFields){
            if(!requiredField){
              permission = false;
              break;
            }
          }
          if(permission==true){
            var temp;
            if(what=='presentation'){
              temp = {
                id: item.id,
                useTitle: item.useTitle,
                order:item.order,
                titles:[
                    @foreach($languages as $language)
                  {
                    languageCode: '{{$language->code}}',
                    value: 'Презентация {{$language->name}}',
                  },
                  @endforeach
                ],
                newsCategory:"none",
                hasFields:true,
                inheritFrom:{
                  page:-1,
                  presentation:"none",
                  limit:10,
                  origin:"",
                },
                inheritedContent:{},
                fields:[],
                sliders:[],
                tempField:{},
                newField:{
                  label:"Поле ",
                  id:"field#",
                  translatable:true,
                  type:"textarea",
                  types:['text','number','date','email','textarea','tel','image'],
                  order:1,
                  args:"",
                  values:[
                      @foreach($languages as $language)
                    {
                      languageCode: '{{$language->code}}',
                      value: '',
                    },
                    @endforeach
                  ],
                  value:"",
                },
              };
            }else if(what=='field'){
              temp = {
                label:item.label,
                id:item.id,
                translatable:item.translatable,
                type:item.type,
                types:['text','number','date','email','textarea','tel','image'],
                order:item.order,
                args:"",
                values:[
                  @foreach($languages as $language)
                  {
                    languageCode: '{{$language->code}}',
                    value: '',
                  },
                  @endforeach
                ],
                value:"",
              };
            }
            array.push(temp);
          }else{
            alertify.error('@lang("Заполните обязательные поля")');
          }
        },
        deleteItem(array,index){
          if(confirm("@lang('Вы уверены?')")==true){
            array.splice(index,1);
            alertify.success('@lang("Успешно удалено")');
          }
        },
        getLength(number){
          return number.toString().length;
        },
        getPresentationLabelName(index){
          return index==0?'@lang("Название страницы")':'@lang("Название презентаций")';
        },
        getNativeTitle(presentation){
          if(typeof presentation!=='undefined'){
            for(item of presentation.titles){
              if(item.languageCode==='ru'){
                return item.value;
              }
            }
          }
          return "";
        },
        presentationNativeTitle(page, presentationId){
          for(pres of page.content.presentations){
            if(pres.id===presentationId){
              return this.getNativeTitle(pres);
            }
          }
          if(presentationId==='all'){
            return '@lang("Все")';
          }else{
            return ''
          }
        },
        updateAreas(){
          var tf = false;
          if (this.instance) {
            this.instance.focusManager.blur(true)
            this.instance.removeAllListeners()
            this.instance.destroy()
            this.instance = null
            tf = true;
          }
        },
        printNewsCategory(category){
          if(category==='main'){
            return '@lang("Основные")';
          }else if(category==='smi'){
            return '@lang("СМИ о нас")'
          }
        },
      },
    });
    formBus.$on('image-uploaded', function (destination,url) {
      if(url.includes('base64')==false){
        this.content.presentations[destination.split("#")[1]].fields[destination.split("#")[2]].value=url;
      } 
    });
    formBus.$on('save-form',function(destination,path){
      if(destination.includes("destination")){
        this.content.presentations[destination.split("#")[1]].fields[destination.split("#")[2]].value=path;
      }
      this.send(this.link,this.news);
    });
    formBus.$on('update-form',function(destination,path){
      if(destination.includes("destination")){
        this.content.presentations[destination.split("#")[1]].fields[destination.split("#")[2]].value=path;
      }
    });
    formBus.$on('sending-form-finished',function(){
      this.showLoader=false;
    });
  </script>