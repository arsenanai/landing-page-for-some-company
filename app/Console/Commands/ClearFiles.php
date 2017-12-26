<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\News;
use App\Page;
use App\File;
use Illuminate\Support\Facades\Storage;

class ClearFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'files:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try{
            $count=0;
            foreach(File::get() as $file){
                if($file->path!=='' &&
                    (   
                        News::where('content','like','%'.$file->path.'%')->count()==0 &&
                        Page::where('content','like','%'.$file->path.'%')->count()==0
                    )
                    ){
                    Storage::disk('images')->delete($file->filename);
                    $file->delete();
                    $count++;
                }
            }
            if($count>0)
                Log::info('unused images cleared: '.$count);
        }catch(\Exception $e){
            Log::info('failed on clearing unused images');
        }
    }
}
