<?php

namespace App\Http\Controllers;

use App\Bot\Message\Button\Primitive\PositiveButton;
use App\Conversation;
use App\Conversations\AboutConversation;
use App\Conversations\AdminMenuConversation;
use App\Conversations\CreateMemeMenuConversation;
use App\Conversations\GenerateMemeMenuConversation;
use App\Conversations\SelectLanguageConversation;
use App\Conversations\ShowBankListConversation;
use App\System\ApplicationPermissions;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;
use Throwable;

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
                PositiveButton::create(__("menu.options.generate-meme"))->value("generate_meme"),
                PositiveButton::create(__("menu.options.create-meme"))->value("create_meme"),
                PositiveButton::create(__("menu.options.manage-banks"))->value("manage_banks"),
                PositiveButton::create(__("menu.options.about-release"))->value("ver"),
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
        } catch (Exception $e){

        } catch (Throwable $e) {

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
    public function showAdminMenu(BotMan $bot){
        $bot->startConversation(new AdminMenuConversation(null));
    }

    /**
     * Show "about" menu
     * @param BotMan $bot
     */
    public function startAboutConversation(BotMan $bot){
        $bot->startConversation(new AboutConversation(null));
    }

    /**
     * Show select language options
     * @param BotMan $bot
     */
    public function startSelectLanguageConversation(BotMan $bot){
        $bot->startConversation(new SelectLanguageConversation());
    }

    /**
     * Start managing user's banks
     * @param BotMan $bot
     */
    public function startManageBanksConversation(BotMan $bot){

        $conversation = Conversation::ofID($bot->getMessage()->getConversationIdentifier())
            ->first();

        $id = $conversation->id;

        $bot->startConversation(new ShowBankListConversation(null, $id));
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
