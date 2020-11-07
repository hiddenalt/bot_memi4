<?php

namespace App\Conversations;

use App\Conversations\Type\BankConversation;
use App\Picture;
use BotMan\BotMan\Messages\Attachments\Image;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;

class BankPicturesConversation extends BankConversation
{

    public function showMenu() {
        $question = Question::create(__('bank.pictures.hint'))
            ->addButtons([
                Button::create(__('bank.pictures.add'))->value('add')->additionalParameters([
                    "color" => "primary"
                ]),
                Button::create(__('bank.pictures.edit-list'))->value('edit')->additionalParameters([
                    "color" => "primary"
                ]),


                Button::create(__('menu.back'))->value('back')->additionalParameters([
                    "color" => "secondary"
                ])
            ]);

        return $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                $selectedValue = $answer->getValue();

                switch($selectedValue){

                    case "back":
                        $this->moveBack();
                        break;

                    case "add":
                        $this->askForPictures();
                        break;

                    case "edit":
                        $this->bot->startConversation(new BankPicturesListConversation($this, $this->bankId));
                        break;

                    default:
                        $this->showMenu();

                        break;
                }
            } else {
                $this->showMenu();
            }
        });


    }


    public function askForPictures(){
        $this->askOrSayBankError(__("bank.pictures.add.hint"), function(Answer $answer){
            $this->performNewPictures($answer);
        });
    }

    public function performNewPictures(Answer $answer){
        $message = $answer->getMessage();
        $images = $message->getImages();
//        $documents = $message->getFiles();

        //TODO: documents upload

        if(count($images) <= 0){
            $this->say(__("bank.pictures.add.no-pictures-found"));
            return $this->askForPictures();
        }

        foreach($images as $image){
            /** @var Image $image */
            $picture = new Picture();
            $picture->copyAndCreateNew($image->getUrl(), $this->bankId);
        }

        $this->say(__("bank.pictures.add.successfully-added"));
        return $this->askForPictures();
    }


}
