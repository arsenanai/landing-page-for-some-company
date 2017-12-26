<div class="modal fade" id="change-image" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     xmlns:v-bind="http://www.w3.org/1999/xhtml">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">@lang('Изменить фото')</h4>
      </div>
      <div class="modal-body">
        <div>
          <!-- Nav tabs -->
          <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active">
              <a href="#upload" aria-controls="home" role="tab" data-toggle="tab" @@click="tab='upload'">
                @lang('Загрузить новый')
              </a>
            </li>
            <li role="presentation">
              <a href="#choose" aria-controls="profile" role="tab" data-toggle="tab" @@click="tab='choose'">
                @lang('Выбрать существующий')
              </a>
            </li>
          </ul>
          <!-- Tab panes -->
          <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade in active" id="upload">
              <br>
              <div class="form" v-show="!path" id="uploader">
                <div class="checkbox">
                  <label>
                    <input type="checkbox" v-model="doCrop"> @lang('Вырезать')
                  </label>
                </div>
                <div class="checkbox">
                  <label>
                    <input type="checkbox" v-model="watermark"> @lang('С водяным знаком')
                  </label>
                </div>
                <input type="file" id="filePhoto" @@change="preview" style="display:none;" accept="image/*">
                <button class="form-control" @@click="clickFilePhoto">@lang('Выбрать рисунок')</button>
              </div>
              <div class='table-responsive' v-show="path">
                <img :src="path" class="" id="previewer">
              </div>
              <canvas id="canvas" width=1140 v-show="false">
              </canvas>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="choose">
              <span v-for="(page,ind) in pages">
                <div class="for-choosing" v-for="(image,index) in page.data"
                     v-bind:class="{active:selectedUrl==image.path}">
                  <img :src="image.path" class="small-preview" @@click="selectedUrl=image.path"/>
                </div>
              </span>
              <div id="more-loader" v-if="moreLoading" class="text-center">
                <img src="{{asset('loader/black.svg')}}" style="height:50px;">
              </div>
              <div v-if="pages.length>0&&pages[0].total==0">
                <br>
                <div class="alert alert-info">
                  <p>@lang('Нет загруженных рисунков')</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('Закрыть')</button>
        <button type="button" class="btn btn-default" @@click="reset" v-if="path&&tab==='upload'">@lang('Стереть')</button>
        <button type="button" class="btn btn-primary" @@click="use"
                v-bind:class="{disabled:(!path&&tab==='upload')||(tab==='choose'&&!selectedUrl)}">
          @lang('Использовать')
          <img class="loader-btn" src="{{asset('loader/white.svg')}}" v-if="showLoader">
        </button>
      </div>
    </div>
  </div>
</div>