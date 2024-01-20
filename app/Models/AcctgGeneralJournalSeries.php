<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcctgGeneralJournalSeries extends Model
{
    protected $guarded = ['id'];

    public $table = 'acctg_general_journals_series';
    
    public $timestamps = false;

    public function fund_code()
    {
        return $this->belongsTo('App\Models\AcctgFundCode', 'fund_code_id', 'id');
    }

    public function journal()
    {
        return $this->belongsTo('App\Models\AcctgGeneralJournal', 'general_journal_id', 'id');
    }
}
