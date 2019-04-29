<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FileController extends Controller
{
    //上传图片
    public function myfile(){
//        echo __METHOD__;
        return view('file');
    }

    /**
     * @param Request $request
     */
    public function myfileadd(Request $request){
        $file=$request->file('image'); //获取文件信息


    }



}
