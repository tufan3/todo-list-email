<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Todo;
use App\Models\EmailLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\TodoReminderMail;
use Illuminate\Support\Collection;

class SendTodoReminderEmail implements ShouldQueue
    {
    use Queueable, Dispatchable, InteractsWithQueue, SerializesModels;
    protected $todo;
    protected $userEmail;

    /**
     * Create a new job instance.
     */
    public function __construct(Todo $todo, string $userEmail)
    {
        $this->todo = $todo;
        $this->userEmail = $userEmail;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $response = Http::get('https://jsonplaceholder.typicode.com/posts');
            $posts = collect($response->json())->take(10);

            $csvContent = $this->createCsvContent($posts);

            $csvPath = 'temp/posts_' . $this->todo->id . '.csv';
            Storage::put($csvPath, $csvContent);

            Mail::to($this->userEmail)->send(new TodoReminderMail($this->todo, $csvPath));

            $this->todo->update(['reminder_sent' => true]);

            $this->logEmail('sent');

            Storage::delete($csvPath);

        } catch (\Exception $e) {
            $this->logEmail('failed', $e->getMessage());
            throw $e;
        }
    }

    private function createCsvContent($posts): string
    {
        $csvData = [];

        $csvData[] = ['ID', 'User ID', 'Title', 'Body'];

        foreach ($posts as $post) {
            $csvData[] = [
                $post['id'],
                $post['userId'],
                $post['title'],
                substr($post['body'], 0, 200)
            ];
        }

        $output = fopen('php://memory', 'w');
        foreach ($csvData as $row) {
            fputcsv($output, $row);
        }
        rewind($output);
        $csvContent = stream_get_contents($output);
        fclose($output);

        return $csvContent;
    }

    private function logEmail(string $status, string $errorMessage = null): void
    {
        EmailLog::create([
            'to_email' => $this->userEmail,
            'subject' => 'Todo Reminder: ' . $this->todo->title,
            'body' => 'Reminder email for todo: ' . $this->todo->title,
            'attachments' => ['posts.csv'],
            'status' => $status,
            'error_message' => $errorMessage,
            'todo_id' => $this->todo->id
        ]);
    }
}
