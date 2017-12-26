<script src="{{asset('js/croppie.min.js')}}"></script>
<script>
  var imageBus = new Vue({
    el:"#change-image",
    data:{
      path:"",
      showLoader:false,
      mimeType:"",
      h:0,
      w:0,
      image:null,
      tab:"upload",
      selectedUrl:"",
      pages:[],
      moreLoading:false,
      linka:"",
      vanilla:null,
      destination:'',
      doCrop:true,
      watermark:false,
    },
    methods:{
      //preview using click
      clickFilePhoto(){
        document.getElementById('filePhoto').click();
      },
      //preview using drag and drop
      drop(e){
        e.stopPropagation();
        e.preventDefault();
        var dt = e.dataTransfer;
        var files = dt.files;
        document.getElementById('filePhoto').files = files;
      },
      dragenter(event){
        if ( event.target.id == "uploader" ) {
          event.target.style.border = "3px dotted palevioletred";
        }
      },
      dragleave(event){
        if ( event.target.id == "uploader" ) {
          event.target.style.border = "";
        }
      },
      //preview image in filePhoto
      preview(event) {
        var input = event.target;
        if (input.files && input.files[0]) {
          this.mimeType = input.files[0].type;
          if(this.mimeType.includes('image')) {
            this.readImage( input.files[0] );
          }else{
            this.mimeType="";
          }
        }
      },
      readImage(file){
        window.URL    = window.URL || window.webkitURL;
        var useBlob   = false && window.URL;
        var reader = new FileReader();
        reader.onload = (e) => {
          var image  = new Image();
          image.onload = (ev) => {
            this.path = e.target.result;
            this.image = image;
            this.h = image.height;
            this.w = image.width;
            //crop
            var width = 600;//document.getElementById('upload').offsetWidth;
            var height;
            if(this.doCrop===true){
              if(this.destination.includes('destination')){
                height = width;
              }else{
                height = (470*width)/1140;
              }
            }else{
              height = (width*image.height)/image.width;
            }
            var ww = width*0.5, hh = height*0.5;
            var el = document.getElementById('previewer');
              this.vanilla = new Croppie(el, {
                  viewport: { width: ww, height: hh},
                  boundary: { width: width, height: height},
                  enforceBoundary: false,
              });
              this.vanilla.bind({
                  url: this.path,
              });
            //crop end
            if (useBlob) {
              window.URL.revokeObjectURL(image.src);
            }
          }
          image.src = useBlob ? window.URL.createObjectURL(file) : reader.result;
        }
        reader.readAsDataURL(file);
      },
      //upload image if need to server and get link
      use(){
        if(this.tab==='upload') {
          //NAME VUE INSTANCE IN FORM
          var params = this.vanilla.get();
          var src;
          this.vanilla.result('base64').then((base64)=>{
            formBus.$emit('image-uploaded', this.destination, base64);
          });
          this.crop(params.points);
          if(this.destination.includes("destination")){
            this.upload();
          }else
            $('#change-image').modal('hide');
        }else if(this.tab==='choose'){
          formBus.$emit('image-uploaded', this.destination, this.selectedUrl);
          $('#change-image').modal('hide');
        } 
      },
      upload(){
        this.showLoader=true;
        var canvas = document.getElementById("canvas");
        var dataURL = canvas.toDataURL('image/png', 0.9);
        var output = document.getElementById('uploader');
        var data = new FormData();
        data.append('mimetype', this.mimeType);
        data.append('upload', dataURL);
        var config = {
          onUploadProgress: function (progressEvent) {
            var percentCompleted = Math.round((progressEvent.loaded * 100) / progressEvent.total);
          }
        };
        axios.post("{{route('upload-image')}}", data, config)
        .then(function (res) {
          if (res.data.includes('exception') == false && res.data !== 'arguments not valid' &&
            res.data !== 'not a picture') {
            //NAME VUE INSTANCE IN FORM
            console.log("upload finished: "+res.data);
            imageBus.$emit('done',res);
          } else {
            alertify.error(res.data);
          }
        })
        .catch(function (err) {
          console.log(err);
          //imageBus.$emit('save-form',"","");
          alertify.error('@lang("Проверьте подключение к интернету")');
        });
      },
      crop(points){
        var canvas = document.getElementById("canvas");
        var ctx = canvas.getContext("2d");
        var startX = points[0];
        var startY = points[1];
        var endX = points[2];
        var endY = points[3];
        if(this.image.width>1140){
          canvas.width = 1140;
          if(this.doCrop===true){
            if(this.destination.includes('destination')){
              canvas.height = canvas.width;
            }else{
              canvas.height = 470;
            }
          }else{
            canvas.height = (this.image.height*canvas.width)/this.image.width;
          }
          //canvas.height = (this.image.height*canvas.width)/this.image.width;
        }else if(this.image.width<=1140){
          canvas.width = this.image.width;
          if(this.doCrop===true){
            if(this.destination.includes('destination')){
              canvas.height = canvas.width;
            }else{
              canvas.height = (470*canvas.width)/1140;
            }
          }else{
            canvas.height = this.image.height;
          }
          //canvas.height = this.image.height;
        }
        ctx.drawImage(this.image, startX, startY, endX-startX, endY-startY,
          0, 0, canvas.width, canvas.height);
        //adding watermark
        if(this.watermark===true){
          var cw,ch;
          cw=canvas.width;
          ch=canvas.height;
          ctx.font="50px verdana";
          var text = "cybersec.kz";
          var tw=ctx.measureText(text).width;
          var th=this.getTextHeight(ctx.font);
          ctx.globalAlpha=.50;
          ctx.fillStyle='#565656'
          ctx.fillText(text,cw/2-tw/2,ch/2-th.ascent/2);
          ctx.fillStyle='#f9f9f9'
          ctx.fillText(text,cw/2-tw/2+2,ch/2-th.ascent/2+2);
        }
        //watermark end
      },
      getTextHeight(font){

        var text = $('<span>Hg</span>').css({ fontFamily: font });
        var block = $('<div style="display: inline-block; width: 1px; height: 0px;"></div>');

        var div = $('<div></div>');
        div.append(text, block);

        var body = $('body');
        body.append(div);

        try {

          var result = {};

          block.css({ verticalAlign: 'baseline' });
          result.ascent = block.offset().top - text.offset().top;

          block.css({ verticalAlign: 'bottom' });
          result.height = block.offset().top - text.offset().top;

          result.descent = result.height - result.ascent;

        } finally {
          div.remove();
        }

        return result;
      },
      loadMore(){
        if(this.pages[this.pages.length-1].next_page_url!=null) {
          if(this.linka!==this.pages[this.pages.length-1].next_page_url){
            this.linka = this.pages[this.pages.length-1].next_page_url;
            this.loadPage(this.linka);
          } 
        }
      },
      loadPage(link){
        this.moreLoading = true;
        axios.get(link)
          .then(function (res) {
            imageBus.$emit('add-page', res.data);
          })
          .catch(function (err) {
            alertify.error('@lang("Ошибка подключения на сервер")');
            console.log(err.message);
          });
      },
      reset(){
        this.vanilla.destroy();
        this.path='';
      },
    },
  });
  imageBus.$on('update-vars',function(path,showLoader){
    if(path!==null){
      this.path = path;
    }
    this.showLoader = showLoader;
  });
  imageBus.$on('add-page', function(page){
    this.moreLoading = false;
    this.pages.push(page);
  });
  imageBus.$on('load-more',function(){
    this.loadMore();
  });
  imageBus.$on('save-image',function(){
    this.upload();
  });
  imageBus.$on('opened',function(destination){
    this.destination = destination;
    this.pages = [],
    this.linka = "/files/images-page/sz=10?page=1";
    this.loadPage(this.linka);
  });
  imageBus.$on('done',function(res){
    if(this.destination.includes('destination')==false)
      formBus.$emit('save-form', this.destination,res.data);
    else
      formBus.$emit('update-form', this.destination,res.data);
    this.showLoader=false;
    $('#change-image').modal('hide');
  });
  jQuery(
    function($)
    {
      $('#choose').bind('scroll', function()
      {
        if($(this).scrollTop() + $(this).innerHeight()>=$(this)[0].scrollHeight)
        {
          imageBus.$emit('load-more');
        }
      })
    }
  );
</script>