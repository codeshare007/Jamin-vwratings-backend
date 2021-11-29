<?php

namespace App\Services;

use App\Models\Avi;
use App\Models\AvisComments;
use App\Models\AvisRatings;
use App\Models\AvisSubscribers;
use App\Models\Messages;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StalkalotParserService
{

    public function opinions()
    {
        $database = DB::connection('opinions');

        $comments = $database->select('SELECT * FROM avis_comments');

        foreach ($comments as $comment) {
            if ($storedComment = AvisComments::find($comment->id)) {
                $storedComment->opinion = $comment->opinion;
                $storedComment->save();
            }
        }

    }

    public function execute()
    {
        $config = [
            'users' => 1,
            'avis' => 1,
            'interviews' => 1,
            'comments' => 1,
            'ratings' => 1,
            'subscribers' => 1,
            'messages' => 1,
            'avis_notes' => 1
        ];

        $database = DB::connection('old');

        if ($config['users']) {
            $users = $database->select('SELECT * FROM user');


            foreach ($users as $user) {
                $newUser = User::firstOrCreate([
                    'id' => $user->id,
                    'username' => $user->username,
                    'email' => $user->email == '' ? null : $user->email,
                    'password' => $user->password_hash,
                    'ip_address' => !empty($user->ip) ? $user->ip : null,
                    'status' => $user->status,
                    'confirmed' => $user->confirmed,
                    'referrer_name' => !empty($user->referred_by) ? $user->referred_by : null,
                    'points' => $user->points,
                    'last_visit' => Carbon::parse($user->last_visit),
                    'updated_at' => Carbon::createFromTimestamp($user->updated_at)->toDateTimeString(),
                    'created_at' => Carbon::createFromTimestamp($user->created_at)->toDateTimeString()
                ]);

                if ($user->notes !== 'x' && $user->notes !== '') {
                    $newUser->notes()->create([
                        'content' => $user->notes
                    ]);
                }
            }
        }

        if ($config['avis']) {
            $avis = $database->select('SELECT * from avis');
            foreach ($avis as $avi) {

                $newAvi = Avi::firstOrCreate([
                    'id' => $avi->id,
                    'user_id' => $avi->added_by !== 0 && $avi->added_by !== null ? $avi->added_by : null,
                    'name' => $avi->name,
                    'status' => $avi->claim_status,
                    'created_at' => Carbon::parse($avi->created_at)->toDateTimeString(),
                    'updated_at' => Carbon::parse($avi->updated_at)->toDateTimeString()
                ]);

                if ($avi->interview !== '123') {
                    $newAvi->interview()->create([
                        'content' => $avi->interview
                    ]);
                }
            }
        }

        if ($config['comments']) {
            $comments = $database->select('SELECT * from vid_comment');

            foreach ($comments as $comment) {
                $newComment = AvisComments::firstOrCreate([
                    'id' => $comment->id,
                    'user_id' => $comment->creator,
                    'avis_id' => $comment->item,
                    'content' => $comment->content,
                    'opinion' => null,
                    'created_at' => Carbon::rawParse($comment->created),
                    'updated_at' => Carbon::rawParse($comment->modified)
                ]);

                if ($comment->file_url !== '' && $comment->file_url !== null) {
                    $newComment->attachments()->create([
                        'filename' => basename($comment->file_url),
                        'path' => $comment->file_url,
                        'type' => $comment->file_mime_type
                    ]);
                }
            }
        }

        if ($config['ratings']) {
            $ratings = $database->select('SELECT * from ratings');

            foreach ($ratings as $rating) {
                AvisRatings::firstOrCreate([
                    'id' => $rating->id,
                    'user_id' => $rating->user_id,
                    'avis_id' => $rating->avi_id,
                    'rating' => $rating->avg_rating,
                    'updated_at' => Carbon::createFromTimestamp($rating->updated_at),
                    'created_at' => Carbon::createFromTimestamp($rating->created_at)
                ]);
            }
        }

        if ($config['subscribers']) {
            $subscribers = $database->select('SELECT * from follow');

            foreach ($subscribers as $subscriber) {
                AvisSubscribers::firstOrCreate([
                    'id' => $subscriber->id,
                    'user_id' => $subscriber->user_id,
                    'avis_id' => $subscriber->avi_id,
                    'status' => $subscriber->status
                ]);
            }
        }

        if ($config['messages']) {
            $messages = $database->select('SELECT * FROM inputs');

            foreach ($messages as $message) {
                Messages::firstOrCreate([
                    'id' => $message->id,
                    'name' => $message->name,
                    'email' => $message->email,
                    'content' => $message->message,
                ]);
            }
        }

        if ($config['avis_notes']) {

            $notes = $database->select('SELECT * FROM fun1');

            foreach ($notes as $note) {
                if ($noteAvi = Avi::where('name', '=', $note->avi)->first()) {
                    $noteAvi->notes()->create([
                        'content' => $note->message, 'comment' => $note->comment
                    ]);
                }
            }
        }
    }
}
