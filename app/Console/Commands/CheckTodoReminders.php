<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Todo;
use App\Models\EmailLog;
use App\Models\User;
use App\Jobs\SendTodoReminderEmail;


class CheckTodoReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    protected $signature = 'todos:check-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for todos that need reminder emails';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $todos = Todo::needsReminder()->get();
        dd($todos);

        foreach ($todos as $todo) {
            $user = User::find($todo->user_id);
            SendTodoReminderEmail::dispatch($todo, $user->email);
        }
    }
}
