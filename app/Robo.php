<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class Robo extends Model
{
    
    public function activateBot($id){
      DB::beginTransaction();

      $bot =  $this->find($id);
    
      $bot->status = 1;
      $response = $bot->save();
      if($response){
        DB::commit();
        return true;
      }
      DB::rollBack();
      return false;
    }

        
    public function disabledBot($id){
        DB::beginTransaction();
            $bot =  $this->find($id);
            $bot->status = 0;
            $response = $bot->save();
        if($response){
          DB::commit();
          return true;
        }
           DB::rollBack();
        return false;
      }
}
