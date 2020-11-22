<?php

namespace App\Http\Controllers;

use App\Conversation;
use App\Conversations\AdminMenuConversation;
use App\Conversations\CreateMemeMenuConversation;
use App\Conversations\GenerateMemeMenuConversation;
use App\Conversations\SelectLanguageConversation;
use App\System\ApplicationPermissions;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;

class BotManController extends Controller
{
    /**
     * Place your BotMan logic here.
     */
    public function handle(){
        $botman = app('botman');

        $botman->listen();
    }

    /**
     * @return Factory|View
     */
    public function tinker(){
        return view('tinker');
    }


    /**
     * Send main menu
     * @param BotMan $bot
     */
    public function sendMenu(BotMan $bot){

        // TODO: specify the keyboard color only for VK platform (dedicated additional parameter)

        $question = Question::create(__("menu.title"))
            ->addButtons([
                Button::create(__("menu.options.generate-meme"))->value("generate_meme")->additionalParameters([
                    "color" => "positive"
                ]),
                Button::create(__("menu.options.create-meme"))->value("create_meme")->additionalParameters([
                    "color" => "positive"
                ]),
//                Button::create(__("menu.options.settings"))->value("settings")->additionalParameters([
//                    "color" => "primary"
//                ])
            ]);

        try{
            if(Conversation::ofID($bot->getMessage()->getConversationIdentifier())
                ->first()->user()->hasPermissionTo(ApplicationPermissions::SHOW_ADMIN_MENU))
                $question->addButtons([
                    Button::create(__("menu.options.admin"))->value("admin")->additionalParameters([
                        "color" => "negative"
                    ])
                ]);
        } catch (\Exception $e){

        }

        $bot->reply($question);
    }


    /**
     * Starts a new conversation on generating memes
     * @param BotMan $bot
     */
    public function startGenerateMemeConversation(BotMan $bot){
        $bot->startConversation(new GenerateMemeMenuConversation(null));
    }

    /**
     * Starts a new conversation on creating user-made memes
     * @param BotMan $bot
     */
    public function startCreateMemeConversation(BotMan $bot){
        $bot->startConversation(new CreateMemeMenuConversation(null));
    }

    /**
     * Show admin menu
     * @param BotMan $bot
     */
    public function adminMenu(BotMan $bot){
        $bot->startConversation(new AdminMenuConversation(null));
    }

    /**
     * Show select language options
     * @param BotMan $bot
     */
    public function startSelectLanguageConversation(BotMan $bot){
        $bot->startConversation(new SelectLanguageConversation());
    }

    /**
     * Echo the validation token for VK
     * @param $payload
     * @param BotMan $bot
     */
    public function VKConfirmationToken($payload, Botman $bot){
        echo(env("VK_CONFIRMATION_TOKEN"));
    }

}
