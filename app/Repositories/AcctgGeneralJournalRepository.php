<?php

namespace App\Repositories;

use App\Interfaces\AcctgGeneralJournalInterface;
use App\Models\AcctgGeneralJournal;
use App\Models\AcctgGeneralJournalEntry;
use App\Models\AcctgGeneralJournalSeries;
use App\Models\AcctgAccountGeneralLedger;
use App\Models\User;
use App\Models\AcctgFundCode;
use App\Models\CboPayee;
use App\Models\AcctgDepartmentDivision;
use App\Models\GsoPropertyAccountability;
use App\Models\AcctgTrialBalance;
use App\Models\AcctgSLAccountReport;
use App\Models\AcctgGLAccountReport;

class AcctgGeneralJournalRepository implements AcctgGeneralJournalInterface 
{
    public function find($id) 
    {
        return AcctgGeneralJournal::findOrFail($id);
    }

    public function find_entry($id) 
    {
        return AcctgGeneralJournalEntry::findOrFail($id);
    }

    public function create(array $details) 
    {
        return AcctgGeneralJournal::create($details);
    }

    public function update($id, array $newDetails) 
    {
        AcctgGeneralJournal::whereId($id)->update($newDetails);
        $journal = AcctgGeneralJournal::find($id);
        if ($journal->status == 'completed') {
            $entries = AcctgGeneralJournalEntry::where(['general_journal_id' => $journal->id, 'is_active' => 1])->get();
            if ($entries->count() > 0) {
                foreach ($entries as $entry) {
                    $res = AcctgTrialBalance::where(['entity' => (new AcctgGeneralJournalEntry)->getTable(), 'entity_id' => $entry->id])->get();
                    if ($res->count() > 0) {
                        AcctgTrialBalance::whereId($res->first()->id)->update([
                            'voucher_id' => $journal->id,
                            'payee_id' => $journal->payee_id,
                            'fund_code_id' => $journal->fund_code_id,
                            'gl_account_id' => $entry->gl_account_id,
                            'debit' => $entry->debit_amount,
                            'credit' => $entry->credit_amount,
                            'posted_at' => $journal->updated_at,
                            'posted_by' => $journal->updated_by
                        ]);
                    } else {
                        AcctgTrialBalance::create([
                            'voucher_id' => $journal->id,
                            'payee_id' => $journal->payee_id,
                            'fund_code_id' => $journal->fund_code_id,
                            'gl_account_id' => $entry->gl_account_id,
                            'debit' => $entry->debit_amount,
                            'credit' => $entry->credit_amount,
                            'posted_at' => $journal->updated_at,
                            'posted_by' => $journal->updated_by,
                            'entity' => (new AcctgGeneralJournalEntry)->getTable(),
                            'entity_id' => $entry->id
                        ]);
                    }

                    $res1 = AcctgSLAccountReport::where(['entity' => (new AcctgGeneralJournalEntry)->getTable(), 'entity_id' => $entry->id])->get();
                    if ($res1->count() > 0) {
                        AcctgSLAccountReport::whereId($res1->first()->id)->update([
                            'voucher_id' => $journal->id,
                            'fund_id' => $journal->fund_code_id,
                            'payee_id' => $journal->payee_id,
                            'gl_account_id' => $entry->gl_account_id,
                            'debit_amount' => $entry->debit_amount,
                            'credit_amount' => $entry->credit_amount,
                            'posted_at' => $journal->updated_at,
                            'posted_by' => $journal->updated_by
                        ]);
                    } else {
                        AcctgSLAccountReport::create([
                            'voucher_id' => $journal->id,
                            'fund_id' => $journal->fund_code_id,
                            'payee_id' => $journal->payee_id,
                            'gl_account_id' => $entry->gl_account_id,
                            'debit_amount' => $entry->debit_amount,
                            'credit_amount' => $entry->credit_amount,
                            'posted_at' => $journal->updated_at,
                            'posted_by' => $journal->updated_by,
                            'entity' => (new AcctgGeneralJournalEntry)->getTable(),
                            'entity_id' => $entry->id
                        ]);
                    }

                    $res2 = AcctgGLAccountReport::where(['entity' => (new AcctgGeneralJournalEntry)->getTable(), 'entity_id' => $entry->id])->get();
                    if ($res2->count() > 0) {
                        AcctgGLAccountReport::whereId($res2->first()->id)->update([
                            'voucher_id' => $journal->id,
                            'fund_id' => $journal->fund_code_id,
                            'payee_id' => $journal->payee_id,
                            'gl_account_id' => $entry->gl_account_id,
                            'debit_amount' => $entry->debit_amount,
                            'credit_amount' => $entry->credit_amount,
                            'posted_at' => $journal->updated_at,
                            'posted_by' => $journal->updated_by
                        ]);
                    } else {
                        AcctgGLAccountReport::create([
                            'voucher_id' => $journal->id,
                            'fund_id' => $journal->fund_code_id,
                            'payee_id' => $journal->payee_id,
                            'gl_account_id' => $entry->gl_account_id,
                            'debit_amount' => $entry->debit_amount,
                            'credit_amount' => $entry->credit_amount,
                            'posted_at' => $journal->updated_at,
                            'posted_by' => $journal->updated_by,
                            'entity' => (new AcctgGeneralJournalEntry)->getTable(),
                            'entity_id' => $entry->id
                        ]);
                    }
                }
            }
        }
        return true;
    }

    public function create_entry(array $details) 
    {
        return AcctgGeneralJournalEntry::create($details);
    }

    public function update_entry($id, array $newDetails) 
    {
        return AcctgGeneralJournalEntry::whereId($id)->update($newDetails);
    }

    public function listItems($request)
    {   
        $columns = array( 
            0 => 'acctg_general_journals.id',
            1 => 'acctg_general_journals.general_journal_no',
            2 => 'acctg_general_journals.transaction_date',
            3 => 'gso_property_accountabilities.fixed_asset_no',
            4 => 'acctg_general_journals.particulars',
            5 => 'acctg_general_journals.total_debit',
            6 => 'acctg_general_journals.total_credit'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'acctg_general_journals.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];
        $status    = $request->get('status');

        $res = AcctgGeneralJournal::select([
            'acctg_general_journals.*'
        ])
        ->leftJoin('cbo_payee', function($join)
        {
            $join->on('cbo_payee.id', '=', 'acctg_general_journals.payee_id');
        })
        ->leftJoin('gso_property_accountabilities', function($join)
        {
            $join->on('gso_property_accountabilities.id', '=', 'acctg_general_journals.fixed_asset_id');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('acctg_general_journals.id', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_general_journals.general_journal_no', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_general_journals.transaction_date', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_general_journals.particulars', 'like', '%' . $keywords . '%')
                ->orWhere('gso_property_accountabilities.fixed_asset_no', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_general_journals.total_debit', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_general_journals.total_credit', 'like', '%' . $keywords . '%');
            }
        });
        if ($status != 'all') {
            $res->where('acctg_general_journals.status', $status);
        }
        $res->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }

    public function line_listItems($request, $id)
    {   
        $columns = array( 
            0 => 'acctg_account_general_ledgers.code',
            1 => 'acctg_general_journals_entries.debit_amount',
            2 => 'acctg_general_journals_entries.credit_amount',
            3 => 'acctg_general_journals_entries.id'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'acctg_general_journals_entries.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];
        $status    = $request->get('status');

        $res = AcctgGeneralJournalEntry::select([
            'acctg_general_journals_entries.*'
        ])
        ->leftJoin('acctg_account_general_ledgers', function($join)
        {
            $join->on('acctg_account_general_ledgers.id', '=', 'acctg_general_journals_entries.gl_account_id');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('acctg_account_general_ledgers.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_account_general_ledgers.description', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_general_journals_entries.debit_amount', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_general_journals_entries.credit_amount', 'like', '%' . $keywords . '%');
            }
        })
        ->where([
            'acctg_general_journals_entries.is_active' => 1,
            'acctg_general_journals_entries.general_journal_id' => $id
        ])
        ->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }

    public function allFundCodes()
    {
        return (new AcctgFundCode)->allFundCodes();
    }

    public function allPayees()
    {
        return (new CboPayee)->allPayees();
    }

    public function allDivisions()
    {
        return (new AcctgDepartmentDivision)->allDivisions();
    }

    public function reload_fixed_asset($journalID)
    {
        $res = GsoPropertyAccountability::select(['gso_property_accountabilities.*'])
        ->where(function ($query) use ($journalID) {
            $query->whereNotIn('gso_property_accountabilities.id',(function ($query) use ($journalID) {
                    $query->from('acctg_general_journals')
                        ->select('acctg_general_journals.fixed_asset_id')
                        ->whereNotNull('acctg_general_journals.fixed_asset_id')
                        ->where('acctg_general_journals.id','!=', $journalID);
                })
            );
        })
        ->where(['gso_property_accountabilities.is_active' => 1, 'gso_property_accountabilities.is_locked' => 1])
        ->get();

        return $res;
    }

    public function generateVoucherNo($fund, $journalID = 0, $user, $timestamp)
    {
        if ($journalID > 0) {
            $journals = AcctgGeneralJournal::find($journalID);
            if ($journals->fund_code_id == $fund && !empty($journals->general_journal_no)) {
                return $journals->general_journal_no;
            } else {
                $res0 = AcctgGeneralJournalSeries::where(['general_journal_id' => $journalID]);
                if ($res0->count() > 0) {
                    $res0 = AcctgGeneralJournalSeries::find($res0->first()->id);
                    $res0->general_journal_id = NULL;
                    $res0->updated_at = $timestamp;
                    $res0->updated_by = $user;
                    $res0->update();
                } else {
                    AcctgGeneralJournalSeries::create([
                        'general_journal_id' => NULL,
                        'fund_code_id' => $journals->fund_code_id,
                        'series' => $journals->general_journal_no,
                        'created_at' => $timestamp,
                        'created_by' => $user
                    ]);
                }

                $res1 = AcctgGeneralJournalSeries::where(['general_journal_id' => NULL, 'fund_code_id' => $fund])->get();
                if ($res1->count() > 0) {
                    $res1 = AcctgGeneralJournalSeries::find($res1->first()->id);
                    $res1->general_journal_id = $journalID;
                    $series = $res1->series;
                    $res1->update();
                    return $series;
                } else {
                    $fund_codes = AcctgFundCode::find($fund);
                    $year = date('Y'); $month = (strlen(date('m')) == 1) ? '0'.date('m') : date('m');

                    $count = AcctgGeneralJournal::whereYear('created_at', '=',  $year)
                    ->where(['fund_code_id' => $fund])
                    ->count();
                    $series = $fund_codes->code. '-' . $year . '-' . $month . '-';

                    if($count < 9) {
                        $series .= '000' . ($count + 1);
                    } else if($count < 99) {
                        $series .= '00' . ($count + 1);
                    } else if($count < 999) {
                        $series .= '0' . ($count + 1);
                    } else {
                        $series .= ($count + 1);
                    }
                    return $series;
                }
            }
        } else {
            $res1 = AcctgGeneralJournalSeries::where(['general_journal_id' => NULL, 'fund_code_id' => $fund])->get();
            if ($res1->count() > 0) {
                return $res1->first()->series;
            } else {
                $fund_codes = AcctgFundCode::find($fund);
                $year = date('Y'); $month = (strlen(date('m')) == 1) ? '0'.date('m') : date('m');

                $count = AcctgGeneralJournal::whereYear('created_at', '=',  $year)
                ->where(['fund_code_id' => $fund])
                ->count();
                $series = $fund_codes->code. '-' . $year . '-' . $month . '-';

                if($count < 9) {
                    $series .= '000' . ($count + 1);
                } else if($count < 99) {
                    $series .= '00' . ($count + 1);
                } else if($count < 999) {
                    $series .= '0' . ($count + 1);
                } else {
                    $series .= ($count + 1);
                }
                return $series;
            }
        }
    }

    public function updateSeries($details)
    {
        $res = AcctgGeneralJournalSeries::where(['general_journal_id' => $details['general_journal_id']]);
        if ($res->count() > 0) {
            $res = AcctgGeneralJournalSeries::find($res->first()->id);
            $res->fund_code_id = $details['fund_code_id'];
            $res->series = $details['series'];
            $res->updated_at = $details['created_at'];
            $res->updated_by = $details['created_by'];
            $res->update();
        } else {
            $res1 = AcctgGeneralJournalSeries::where([
                'general_journal_id' => NULL, 
                'fund_code_id' => $details['fund_code_id'], 
                'series' => $details['series']
            ])->get();
            if ($res1->count() > 0) {
                $res1 = AcctgGeneralJournalSeries::find($res1->first()->id);
                $res1->general_journal_id = $details['general_journal_id'];
                $res1->updated_at = $details['created_at'];
                $res1->updated_by = $details['created_by'];
                $res1->update();
            } else {
                AcctgGeneralJournalSeries::create($details);
            }
        }

        return true;
    }

    public function allGLAccounts()
    {
        return (new AcctgAccountGeneralLedger)->allGLAccounts();
    }
    
    public function validate_entry($gl_account, $journalID, $id = '')
    {   
        if ($id !== '') {
            return AcctgGeneralJournalEntry::where(['gl_account_id' => $gl_account, 'is_active' => 1])->where('general_journal_id', $journalID)->where('id', '!=', $id)->count();
        } 
        return AcctgGeneralJournalEntry::where(['gl_account_id' => $gl_account, 'is_active' => 1])->where('general_journal_id', $journalID)->count();
    }

    public function getTotalDebitAmount($id)
    {
        $debitAmt = AcctgGeneralJournalEntry::where(['general_journal_id' => $id, 'is_active' => 1])->sum('debit_amount');

        AcctgGeneralJournal::whereId($id)->update(['total_debit' => $debitAmt]);

        return $debitAmt;
    }

    public function getTotalCreditAmount($id)
    {
        $creditAmt = AcctgGeneralJournalEntry::where(['general_journal_id' => $id, 'is_active' => 1])->sum('credit_amount');

        AcctgGeneralJournal::whereId($id)->update(['total_credit' => $creditAmt]);

        return $creditAmt;
    }
}