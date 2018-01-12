<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function index(){

       // return 'index function';
       $title='hey user1';
       // return view('pages.index',compact('title'));
       return view('pages.index')->with('title',$title);
    }



    public function about(){

        // return 'index function';
         return view('pages.about');
     }

     public function services(){
          $data=array(
                'title'=>'service',
                'services'=>['web designing','seo','ceo']


          );
        // return 'index function';
         return view('pages.services')->with($data);
     }
}
