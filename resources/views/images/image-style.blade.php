<link rel="stylesheet" href="{{asset('css/croppie.css')}}" />
<style>
  .small-preview{
    max-width:270px;
    max-height:270px;
  }
  .preview{
    max-width:400px;
    max-height:400px;
  }
  .large-preview{
    max-width:800px;
    max-height:800px;
  }
  .max-preview{
    max-width:100%;
    max-height:100%;
  }
  .for-choosing{
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.2), 0 1px 2px 0 rgba(0, 0, 0, 0.19);
    border: 3px solid white;
    display:inline-table;
    border-radius:5px;
    overflow:hidden;
    margin:5px;
  }
  .for-choosing:focus, .for-choosing:hover{
    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 4px 8px 0 rgba(0, 0, 0, 0.19);
    border: 3px solid white;
    display:inline-table;
    border-radius:5px;
    overflow:hidden;
    margin:5px;
  }
  .for-choosing.active{
    box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.2), 0 2px 4px 0 rgba(0, 0, 0, 0.19);
    display:inline-table;
    border: 3px solid lightgreen;
    border-radius:5px;
    overflow:hidden;
    margin:5px;
  }
  #choose{
    height: 400px;
    overflow: scroll;
  }
  .watermark{
   position:fixed;
   bottom:5px;
   right:5px;
   opacity:0.5;
   z-index:1;
   font-size:20px;
   color:#f9f9f9;
   text-shadow: 2px 2px 4px #565656;
  }
</style>