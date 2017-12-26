<script type="text/javascript" src="{{asset('ckeditor/ckeditor.js')}}"></script>
@include('my.ckeditor')
@include('my.v-hr-form-group')
@include('images.image-script')
<script>
  contentSensitivePage = true;
  var formBus = new Vue({
    el: '#news-form',
    data:{
      showSaveButton:true,
      selectedLanguage:2,
      preview:false,
      previewUrl:null,
      showLoader:false,
      languages:[
          @foreach($languages as $language)
        {
          code: '{{$language->code}}',
          name: '{{$language->name}}',
        },
        @endforeach
      ],
      news: nw,
      link:'',
    },
    mounted(){
      if(this.news.fields[0].value){
        this.previewUrl=this.news.fields[0].value;
      }
    },
    methods:{
      opened(){
        imageBus.$emit('opened','');
      },
      update(field){
        if(field.id=='field#category'&&this.languages[this.selectedLanguage].code=='ru')
          this.news.category = field.value;
        else if(field.id=='field#title'&&this.languages[this.selectedLanguage].code=='ru')
          this.news.title_ru = field.values[this.selectedLanguage].value;
        else if(field.id=='field#body'&&this.languages[this.selectedLanguage].code=='ru')
          this.news.body_ru = field.values[this.selectedLanguage].value;
      },
      validateAndSendForm(link, toSend){
        var bool = false;
        for(field of toSend.fields){
          if(field.args.includes('required')){
            if(field.translatable==true){
              if(!field.values[this.selectedLanguage].value)
                bool = true;
            }else{
              if(!field.value)
                bool = true;
            }
          }
          if(bool==true)
            break;
        }
        if(bool==true){
          alertify.error('@lang("Заполните обязательные поля")');
        }else{
          var letters = /^[a-z_-]+$/;
          if(this.news.fields[6].value.match(letters)){
            this.validateSlug(link,toSend);
          }else{
            alertify.error('@lang("Проверьте формат ссылки")');
          }
        }
      },
      validateSlug(link, toSend){
        this.showLoader = true;
        axios.get('/content/news/check-slug/'+nid+'/'+this.news.fields[6].value)
          .then(function(response){
            if(response.data === 'valid'){
              console.log('ifka kirdi');
              formBus.$emit('validation-slug-finished',true,link,toSend);
            }else{
              console.log('elseka kirdi');
              formBus.$emit('validation-slug-finished',false,link,toSend);
            }
          })
          .catch(function(error){
            alertify.error('@lang("Ошибка подключения на сервер")');
            //formBus.$emit('validation-slug-finished',false);
            this.showLoader = false;
          });  
      },
      send(link, toSend){
        console.log('sendka kirdi');
        axios.post(link,toSend)
        .then(function (response) {
          if(response.data.indexOf('success') !== -1){
            alertify.success("@lang('Успешно сохранено')");
          }else if(response.data==='security exception'){
            alertify.error('@lang("У вас нет прав на это")')
          }else if(response.data==='exception'){
            alertify.error('@lang("Ошибка на сервере")')
          }
          formBus.$emit('sending-form-finished');
        })
        .catch(function (error) {
          alertify.error('@lang("Ошибка подключения на сервер")');
        });
      },
      previewImage(url){
        this.previewUrl = url;
      },
    }
  });
  formBus.$on('image-uploaded', function (destination,url) {
    if(url.includes('base64')==false){
      this.preview = false;
      this.previewUrl = url;
      this.news.fields[0].value=url;
    }else{
      this.preview = true;
      this.previewUrl = url;
    }  
  });
  formBus.$on('save-form',function(destination,path){
    this.news.fields[0].value=path;
    this.preview = false;
    this.previewUrl = path;
    this.send(this.link,this.news);
  });
  formBus.$on('sending-form-finished',function(){
    if(form==='add'){
      this.showSaveButton = false;
    }
    this.showLoader=false;
  });
  formBus.$on('validation-slug-finished',function(result,link,toSend){
    console.log("result: "+result);
    if(result == true){
      console.log("mynda da kirdi");
      if(this.preview==true){
        this.link = link;
        imageBus.$emit('save-image');
      }else{
        this.send(link, toSend);
      }
    }else{
      this.showLoader = false;
      alertify.error('@lang("Эта ссылка к новости уже используется")');
    }
  });
</script>
