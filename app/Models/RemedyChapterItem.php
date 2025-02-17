<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RemedyChapterItem extends Model
{
    protected $table = 'remedy_chapter_items';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];

    static $chapterFile = 'file';
    static $chapterSession = 'session';
    static $chapterTextLesson = 'text_lesson';
    static $chapterQuiz = 'quiz';
    static $chapterAssignment = 'assignment';

    static public function makeItem($userId, $chapterId, $itemId, $type)
    {
        $order = RemedyChapterItem::where('chapter_id', $chapterId)->count() + 1;

        RemedyChapterItem::updateOrCreate([
            'user_id' => $userId,
            'chapter_id' => $chapterId,
            'item_id' => $itemId,
            'type' => $type,
        ], [
            'order' => $order,
            'created_at' => time()
        ]);
    }

    static public function changeChapter($userId, $oldChapterId, $newChapterId, $itemId, $type)
    {
        $chapterItem = RemedyChapterItem::query()
            ->where('user_id', $userId)
            ->where('chapter_id', $oldChapterId)
            ->where('item_id', $itemId)
            ->where('type', $type)
            ->first();

        if (!empty($chapterItem)) {
            $order = RemedyChapterItem::where('chapter_id', $newChapterId)->count() + 1;

            $chapterItem->update([
                'chapter_id' => $newChapterId,
                'order' => $order,
            ]);
        } else {
            RemedyChapterItem::makeItem($userId, $newChapterId, $itemId, $type);
        }
    }

    public function session()
    {
        return $this->belongsTo('App\Models\Session', 'item_id', 'id');
    }

    public function file()
    {
        return $this->belongsTo('App\Models\Refile', 'item_id', 'id');
    }

    public function textLesson()
    {
        return $this->belongsTo('App\Models\TextLesson', 'item_id', 'id');
    }

    public function assignment()
    {
        return $this->belongsTo('App\Models\RemedyAssignment', 'item_id', 'id');
    }

    public function quiz()
    {
        return $this->belongsTo('App\Models\Quiz', 'item_id', 'id');
    }

    public function chapter()
    {
        return $this->belongsTo('App\Models\RemedyChapter', 'chapter_id', 'id');
    }
}
