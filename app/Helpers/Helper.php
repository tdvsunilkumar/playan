<?php // Code within app\Helpers\Helper.php

namespace App\Helpers;

class Helper
{
    public static function money_format($money)
    {
        return '₱' . number_format(floor(($money*100))/100, 2);
    }

    public static function billing_quarters()
    {
        return [
        	'11' => '1st Qtr',
        	'22' => '2nd Qtr',
        	'33' => '3rd Qtr',
        	'44' => '4th Qtr'
        ];
    }

    public static function number_format($num)
    {
        return number_format(floor(($num*100))/100, 3);
    }

    public static function decimal_format($num)
    {
        return number_format(floor(($num*100))/100, 2);
    }

    public static function area_format($num)
    {
        return number_format(floor(($num*100))/100, 4);
    }

    public function numberToWord($num = '')
    {
       // dd($num);
        $arramount = explode(".", $num);
        $num    = ( string ) ( ( int ) $num );
        if( ( int ) ( $num ) && ctype_digit( $num ) ){
            $words  = array( );
            $num    = str_replace( array( ',' , ' ' ) , '' , trim( $num ) );
            $list1  = array('','one','two','three','four','five','six','seven',
                'eight','nine','ten','eleven','twelve','thirteen','fourteen',
                'fifteen','sixteen','seventeen','eighteen','nineteen');
             
            $list2  = array('','ten','twenty','thirty','forty','fifty','sixty',
                'seventy','eighty','ninety','hundred');
            $list3  = array('','thousand','million','billion','trillion',
                'quadrillion','quintillion','sextillion','septillion',
                'octillion','nonillion','decillion','undecillion',
                'duodecillion','tredecillion','quattuordecillion',
                'quindecillion','sexdecillion','septendecillion',
                'octodecillion','novemdecillion','vigintillion');
             
            $num_length = strlen( $num );
            $levels = ( int ) ( ( $num_length + 2 ) / 3 );
            $max_length = $levels * 3;
            $num    = substr( '00'.$num , -$max_length );
            $num_levels = str_split( $num , 3 );
            foreach( $num_levels as $num_part ){
                $levels--;
                $hundreds   = ( int ) ( $num_part / 100 );
                $hundreds   = ( $hundreds ? ' ' . $list1[$hundreds] . ' Hundred' . ( $hundreds == 1 ? '' : '' ) . ' ' : '' );
                $tens       = ( int ) ( $num_part % 100 );
                $singles    = '';
                if( $tens < 20 ) { $tens = ( $tens ? ' ' . $list1[$tens] . ' ' : '' ); } else { $tens = ( int ) ( $tens / 10 ); $tens = ' ' . $list2[$tens] . ' '; $singles = ( int ) ( $num_part % 10 ); $singles = ' ' . $list1[$singles] . ' '; } $words[] = $hundreds . $tens . $singles . ( ( $levels && ( int ) ( $num_part ) ) ? ' ' . $list3[$levels] . ' ' : '' ); } 
                $commas = count( $words ); if( $commas > 1 )
            {
                $commas = $commas - 1;
            }
             
            $words  = implode( ', ' , $words );
             
            $words  = trim( str_replace( ' ,' , ',' , ucwords( $words ) )  , ', ' );
            if( $commas )
            {
                $words  = str_replace( ',' , '' , $words );
            }
            $pointamount ="";
             if(count($arramount) > 1){
                if($arramount[1] > 0){
                  $pointamount = $arramount[1]."/100";
                }
             }
             if($pointamount == ''){
                return $words." Pesos";
             }else{
                return $words." and ".$pointamount.' Pesos';
             }
            
        }
        else if( ! ( ( int ) $num ) )
        {
            return 'Zero';
        }
        return '';
    }

    // used for LabForms.js
    public function json_msg($msg, $data = [])
    {
        return json_encode(
            [
                'ESTATUS'=>0,
                'msg'=>$msg,
                'data' => $data
            ]
        );
    }

}