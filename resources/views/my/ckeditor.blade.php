<script>
Vue.component('ckeditor',{
  template:`
    <div class="ckeditor">
        <textarea :id="id" :value="value" {{--:types="types" :config="config"--}}></textarea>
    </div>
  `,
  props: {
    value: {
      type: String
    },
    id: {
      type: String,
      default: () => `editor`
    },
    /*types: {
      type: String,
      default: () => `classic`
    },
    config: {
      type: Object,
      default: () => {}
    }*/
  },
  computed: {
    instance() {
      return CKEDITOR.instances[this.id];
    }
  },
  beforeUpdate () {
    var tf = false;
    if (this.value !== this.instance.getData()) {
      this.instance.setData(this.value)
      tf = true;
    }
    let html = this.instance.getData()
    this.$emit('change',html)
  },
  mounted () {
    if (typeof CKEDITOR === 'undefined') {
      console.log('CKEDITOR is missing (http://ckeditor.com/)')
    } else {
      //if (this.types === 'inline') {
        //CKEDITOR.inline(this.id)
      //} else {
        CKEDITOR.replace(this.id);
      //}
      //console.log("mounted");
      this.instance.on('change', () => {
        let html = this.instance.getData()
        if (html !== this.value) {
          this.$emit('input', html)
        }
      })
    }
  },
  beforeDestroy () {
    var tf = false;
    if (this.instance) {
      this.instance.focusManager.blur(true)
      this.instance.removeAllListeners()
      this.instance.destroy()
      this.instance = null
      tf = true;
    }
  }
});
</script>

<style>
.ckeditor::after {
  content: "";
  display: table;
  clear: both;
}
</style>
