<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Page;

class PagesTest extends TestCase
{
    public function testCountPages(){
        $count = Page::count();
        $this->assertEquals(5,$count);
    }
    public function testPageNames(){
        $pages = Page::get();
        $tf = true;
        $count = 0;
        foreach($pages as $page){
            try{
                $content = json_decode($this->content);
                if($content!==true){
                    foreach($content->presentations[0]->titles as $pageTitle){
                        if($pageTitle->languageCode==="ru"){
                            if($pageTitle->value!=='Главная' and $count==0){
                                $tf = false;
                                break;
                            }else if($pageTitle->value!=='О Центре' and $count==1){
                                $tf = false;
                                break;
                            }else if($pageTitle->value!=='Сервисы' and $count==2){
                                $tf = false;
                                break;
                            }else if($pageTitle->value!=='Пресс Центр' and $count==3){
                                $tf = false;
                                break;
                            }else if($pageTitle->value!=='Контакты' and $count==4){
                                $tf = false;
                                break;
                            }
                        }
                    }
                }else{
                    $tf = false;
                    break;
                }
            }catch(\Exception $e){
                $tf = false;
                break;
            }
            $count++;
        }
        $this->assertFalse($tf);
    }
}