<?php

function translate($array){
  foreach($array as $item){
  	if($item->languageCode===App::getLocale() and $item->value!==''){
  		return $item->value;
  	}
  }
  foreach($array as $item){
  	if($item->languageCode==='ru' and $item->value!==''){
  		return $item->value;
  	}
  }
  return '';
}
function short($phone){
	return preg_replace('/\D+/', '', $phone);
}
function fieldValue($page,$presentationIndex,$fieldIndex){
  if(array_key_exists($presentationIndex, $page->d->presentations)){
    if(array_key_exists($fieldIndex, $page->d->presentations[$presentationIndex]->fields)){
      if($page->d->presentations[$presentationIndex]->fields[$fieldIndex]->translatable==true){
        return translate($page->d->presentations[$presentationIndex]->fields[$fieldIndex]->values);
      }else{
        return $page->d->presentations[$presentationIndex]->fields[$fieldIndex]->value;
      }
    }else
      return "";
  }else
    return "";
}
function presValue($page,$presentationIndex){
  if(array_key_exists($presentationIndex, $page->d->presentations)){
    return translate($page->d->presentations[$presentationIndex]->titles);
  }else
    return "";
}
function newsFieldValue($post, $fieldIndex){
  if(array_key_exists($fieldIndex, $post->d->fields)){
    if($post->d->fields[$fieldIndex]->translatable==true){
      return translate($post->d->fields[$fieldIndex]->values);
    }else{
      return $post->d->fields[$fieldIndex]->value;
    }
  }else
    return "";
}
function firstLetter($post, $fieldIndex){
  $result = "";
  if(array_key_exists($fieldIndex, $post->d->fields)){
    if($post->d->fields[$fieldIndex]->translatable===true){
      $result = translate($post->d->fields[$fieldIndex]->values);
    }else{
      $result = $post->d->fields[$fieldIndex]->value;
    }
    return mb_substr($result, 0, 1, 'utf-8');
  }else
    return "";
}