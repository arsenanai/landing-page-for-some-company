<script>
  Vue.component('hr-form-group', {
    template:`
		<div class="form-group">
		    <label :for="id" :class="'col-md-'+lw+' control-label'">
		        @{{ title }}<span v-if="args.includes('required')==true" style="color:red;">*</span>
		    </label>
		    <div :class="'col-md-'+fw">
		        <slot></slot>
		    </div>
		</div>
		  `,
    props: {
      lw:Number,
      fw:Number,
      id:String,
      args:String,
      title:String,
    },
  });
</script>