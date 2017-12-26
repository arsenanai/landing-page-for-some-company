<?php

use Illuminate\Database\Seeder;
use App\News;
use App\Language;

class NewsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
  public function run(){
		DB::table('news')->delete();
    if(News::count()==0){
      $languages = Language::orderBy('order','asc')->get();
			for($i=0;$i<20;$i++){
				$p = new News();
        $nav = new Nav();
				$nav->fields = array();

				$field = new Field();
				$field->id="field#file";
				$field->args="";
				$field->type="image";
				$field->label="Фото";
				$field->order=1;
				$field->value="";
				$field->translatable=false;
				array_push($nav->fields,$field);

        $field = new Field();
        $field->id="field#category";
        $field->args="";
        $field->type="select";
        $field->label="Категория";
        $field->order=2;
        $field->value=($i%2==0)?"main":"smi";

        $nav->category=($i%2==0)?"main":"smi";

        $field->translatable=false;
        $field->options = array();
        $option = new Option();
        $option->key="main";
        $option->value="Основные";
        array_push($field->options,$option);
        $option = new Option();
        $option->key="smi";
        $option->value="СМИ о нас";
        array_push($field->options,$option);
        array_push($nav->fields,$field);

        $field = new Field();
        $field->id="field#title";
        $field->args="required";
        $field->type="textarea";
        $field->label="Заголовок";
        $field->order=3;
        $field->translatable=true;
        $field->values=array();
        foreach($languages as $language){
          $value = new Translation();
          $value->languageCode = $language->code;
          $value->value = "<h3>Длинный заголовок ".$i." на ".$language->name."</h3>";
          array_push($field->values,$value);
        }
        array_push($nav->fields,$field);

        $nav->title_ru = "<h3>Длинный заголовок ".$i." на рус</h3>";

        $field = new Field();
        $field->id="field#short";
        $field->args="required";
        $field->type="textarea";
        $field->label="Краткий заголовок";
        $field->order=4;
        $field->translatable=true;
        $field->values=array();
        foreach($languages as $language){
          $value = new Translation();
          $value->languageCode = $language->code;
          $value->value = "<p>Краткий заголовок ".$i." на ".$language->name."</p>";
          array_push($field->values,$value);
        }
        array_push($nav->fields,$field);

        $field = new Field();
        $field->id="field#lid";
        $field->args="required";
        $field->type="textarea";
        $field->label="Крышка";
        $field->order=5;
        $field->translatable=true;
        $field->values=array();
        foreach($languages as $language){
          $value = new Translation();
          $value->languageCode = $language->code;
          $value->value = "<p>Крышка ".$i." на ".$language->name."</p>";
          array_push($field->values,$value);
        }
        array_push($nav->fields,$field);

        $field = new Field();
        $field->id="field#body";
        $field->args="required";
        $field->type="textarea";
        $field->label="Описание";
        $field->order=6;
        $field->translatable=true;
        $field->values=array();
        foreach($languages as $language){
          $value = new Translation();
          $value->languageCode = $language->code;
          $value->value = "<p>Содержание ".$i." на ".$language->name."</p>";
          array_push($field->values,$value);
        }
        array_push($nav->fields,$field);

        $nav->body_ru = "<p>Содержание ".$i." на рус</p>";

        $p->content = json_encode($nav);
        $p->save();
			}
		}
	}
}
class Nav{
  public $category="";
  public $title_ru="";
  public $body_ru="";
  public $fields=array();
}
class Field{
	public $id = "";
  public $args  = "";
  public $type="";
  public $label="";
  public $order=0;
  public $values = array();
  public $value = "";
  public $options = array();
  public $translatable = true;
}
class Option{
	public $key="";
	public $value="";
}
class Translation{
	public $languageCode="";
	public $value="";
}