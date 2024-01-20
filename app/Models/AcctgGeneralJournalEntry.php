<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcctgGeneralJournalEntry extends Model
{
    protected $guarded = ['id'];

    public $table = 'acctg_general_journals_entries';
    
    public $timestamps = false;

    public function journal()
    {
        return $this->belongsTo('App\Models\AcctgGeneralJournal', 'general_journal_id', 'id');
    }

    public function gl_account()
    {
        return $this->belongsTo('App\Models\AcctgAccountGeneralLedger', 'gl_account_id', 'id');
    }
}
