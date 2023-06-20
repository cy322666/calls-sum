<?php

namespace App\Console\Commands;

use App\Models\Account;
use App\Services\amoCRM\Client;
use Illuminate\Console\Command;

class GetIncomingCalls extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calls:incoming';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     * @throws \Exception
     */
    public function handle()
    {
        $amoApi = (new Client(Account::first()))->init();

        //incoming_call
        //outgoing_call
        $events = $amoApi->service->ajax()->get('/api/v4/events', [
            'filter' => [
                'entity' => 'leads',
                'type'   => 'incoming_call',
                'page'   => 1,
                'limit'  => 10,
            ],
        ]);
        /*
         *
     *  +"id": "01h2wee6880v39d6mkrg1s5kj5"
        +"type": "incoming_call"
        +"entity_id": 27196405
        +"entity_type": "lead"
        +"created_by": 9310902
        +"created_at": 1686729333
        +"value_after": array:1 [
          0 => {#2507
            +"note": {#2510
              +"id": 198101581
            }
          }
        ]

         */

        foreach ($events->_embedded->events as $event) {

            $eventCall = $amoApi->service->notes()->find($event->value_after[0]->note->id);

            dd($eventCall);
        }
        /*
    "linkedLead" => null
    "linkedContact" => null
    "linkedCompany" => null
    "createdUser" => null
    "responsibleUser" => null
    "noteType" => null
    "element_id" => 27345667
    "element_type" => 2
    "is_editable" => false
    "note_type" => 10
    "text" => null
    "responsible_user_id" => 9475790
    "updated_at" => 1687256839
    "created_at" => 1687256724
    "created_by" => 9310902
    "attachment" => null
    "params" => {#2434
      +"PHONE": "+79162285869"
      +"UNIQ": "MToxMDEzODgzMToyMDI6ODQ1NDA0ODE4"
      +"DURATION": 58
      +"SRC": "MangoOfficeWidget"
      +"LINK": "https://amocrm.mango-office.ru/calls/recording/download/28929382/MToxMDEzODgzMToxNzc2ODIwNjE1MDow/NDAzMzc3Nzk2"
      +"call_status": 4
      +"call_result": "входящий"
    }
  ]

         */

        return Command::SUCCESS;
    }
}
