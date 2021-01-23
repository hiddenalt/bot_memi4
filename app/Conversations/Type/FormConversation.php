<?php


namespace App\Conversations\Type;


use App\Conversations\BackFunctionConversation;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Log;

class FormConversation extends BackFunctionConversation {

    public function sayValidationDetails(Validator $v){

        Log::info(app()->getLocale());

        $errors = $v->errors();
        $list = [];
        $details = $errors->getMessages();
        foreach($details as $option => $detail){
            $list[] = "- " . implode(" ", $detail);
        }

        $this->say(__("form.sending-error", [
            "details" => implode("\n", $list)
        ]));
    }

}