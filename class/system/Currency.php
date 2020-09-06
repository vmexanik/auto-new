<?
/**
 * @author Mikhail Starovoyt
 *
 */
class Currency extends Base
{
	public $aCurrencyAssoc=array();
	public static $aCurrency=array();

	//-----------------------------------------------------------------------------------------------
	function __construct()
	{
		$this->aCurrencyAssoc=$aCurrencyAssoc=Db::GetAssoc('Assoc/Currency',array(
		'multiple'=>1,
		));
		Base::$tpl->assign('aCurrencyAssoc',$this->aCurrencyAssoc);
	}
	//-----------------------------------------------------------------------------------------------
	function InitCurrency(){
		$aCurrency=Base::$db->GetAll("select * from currency");
		Currency::$aCurrency=Language::Array2Hash($aCurrency,'code');
	}
	//-----------------------------------------------------------------------------------------------
	public function BasePrice($dPrice=0,$iIdCurrency=1)
	{
		return round($dPrice / $this->aCurrencyAssoc[$iIdCurrency]['value'],2);
	}
	//-----------------------------------------------------------------------------------------------
	public function Price($dPrice=0,$iIdCurrency=1)
	{
		return round($dPrice * $this->aCurrencyAssoc[$iIdCurrency]['value'],2);
	}
	//-----------------------------------------------------------------------------------------------
	public function PrintPrice($dPrice=0,$iIdCurrency=0,$iRoundDigit=0,$sOutputType='')
	{
    	    // обрамил round в sprintf, так как иногда round(69.0710952,2) давал почему-то 69.069999999
	    if(isset(Base::$oCurrency)) $oCurrency = Base::$oCurrency;
		elseif(isset($this->aCurrencyAssoc)) $oCurrency = $this;
		else $oCurrency = new Currency();
		
		if ($dPrice == null)
			$dPrice = 0;
		
		if (!$iIdCurrency) $iIdCurrency=Auth::$aUser['id_currency'];
		if (!$iIdCurrency) $iIdCurrency='1';

		switch ($sOutputType) {
			case 'line':
				$sDelimiter='&nbsp;';
				break;

			default:
				$sDelimiter='<br>';
				break;
		}
		
		$sSymbol = " ".$oCurrency->aCurrencyAssoc[$iIdCurrency]['symbol'];
		if ($sOutputType == '<none>')
			$sSymbol = '';

		$iRoundValue = $oCurrency->aCurrencyAssoc[$iIdCurrency]['price_round'];
		if ($iRoundDigit)
			$iRoundValue = $iRoundDigit;
		
		$dCeilValue = $oCurrency->aCurrencyAssoc[$iIdCurrency]['price_ceil'];
		$dPriceX = $dPrice * $oCurrency->aCurrencyAssoc[$iIdCurrency]['value'];
		if ($dCeilValue != 0 && $dCeilValue >= 1){
			return sprintf('%.'.$iRoundValue.'f',round((ceil(ceil($dPriceX)/$dCeilValue)*$dCeilValue),$iRoundValue)) . $sSymbol;
		}
		elseif ($dCeilValue != 0 && $dCeilValue < 1){
			// if dPricex=7596 - incorrect % 5 work
			$dCeilValue*=100;
			$i=intval('0'.($dPriceX*100),10);
			if (($i % $dCeilValue) == 0) { 
				$iResult=$dPriceX;
			} else {
				$iResult=(ceil(ceil($i)/$dCeilValue)*$dCeilValue)/100; 
			}
			return sprintf('%.'.$iRoundValue.'f',round($iResult,$iRoundValue))." ".$sSymbol;
		}
		return sprintf('%.'.$iRoundValue.'f',round($dPrice * $oCurrency->aCurrencyAssoc[$iIdCurrency]['value'],$iRoundValue)) . $sSymbol;
	}
	//-----------------------------------------------------------------------------------------------
	public function BillRound($dPrice=0){
		$iIdCurrency='1';
		$oCurrency = new Currency();
		
		$iRoundValue = $oCurrency->aCurrencyAssoc[$iIdCurrency]['price_round'];
		$dCeilValue = $oCurrency->aCurrencyAssoc[$iIdCurrency]['price_ceil'];
		$dPriceX = $dPrice * $oCurrency->aCurrencyAssoc[$iIdCurrency]['value'];
		if ($dCeilValue != 0 && $dCeilValue >= 1){
			return (ceil(ceil($dPriceX)/$dCeilValue)*$dCeilValue);
		}
		elseif ($dCeilValue != 0 && $dCeilValue < 1){
			$dCeilValue*=100;
			if (($dPriceX*100) % $dCeilValue == 0) { 
				$iResult=$dPriceX;
			} else {
				$iResult=(ceil(ceil($dPriceX*100)/$dCeilValue)*$dCeilValue)/100; 
			}
			return $iResult;
		}
		return round($dPrice * $oCurrency->aCurrencyAssoc[$iIdCurrency]['value'],$iRoundValue);
	}
	//-----------------------------------------------------------------------------------------------
	public function GetPriceWithoutSymbol($dPrice=0){
		if (Auth::$aUser['id_currency']) $iIdCurrency=Auth::$aUser['id_currency'];
		else $iIdCurrency='1';
		$oCurrency = new Currency();
		
		$iRoundValue = $oCurrency->aCurrencyAssoc[$iIdCurrency]['price_round'];
		$dCeilValue = $oCurrency->aCurrencyAssoc[$iIdCurrency]['price_ceil'];
		$dPriceX = $dPrice * $oCurrency->aCurrencyAssoc[$iIdCurrency]['value'];
		if ($dCeilValue != 0 && $dCeilValue >= 1){
			return (ceil(ceil($dPriceX)/$dCeilValue)*$dCeilValue);
		}
		elseif ($dCeilValue != 0 && $dCeilValue < 1){
			$dCeilValue*=100;
			if (($dPriceX*100) % $dCeilValue == 0) { 
				$iResult=$dPriceX;
			} else {
				$iResult=(ceil(ceil($dPriceX*100)/$dCeilValue)*$dCeilValue)/100; 
			}
			return $iResult;
		}
		return round($dPrice * $oCurrency->aCurrencyAssoc[$iIdCurrency]['value'],$iRoundValue);
	}
	//-----------------------------------------------------------------------------------------------
	public function CurrecyConvert($L, $sType='RUB')
	{
		$s_print_lang = Language::GetConstant('global:print_language_prefix','ua');
		switch ($sType) {
			case 'USD':
				// print USD in ru/ua languages
				if ($s_print_lang == 'ru')
					include(SERVER_PATH.'/include/currency_convert_ru_en.php');
				elseif ($s_print_lang == 'ua')
					include(SERVER_PATH.'/include/currency_convert_ua_en.php');
				else
					include(SERVER_PATH.'/include/currency_convert_en.php');
				break;

			case 'RUB':
				include(SERVER_PATH.'/include/currency_convert_ru.php');
				break;

			default:
				// print UAH in ru language
				if ($s_print_lang == 'ru')
					include(SERVER_PATH.'/include/currency_convert_ua_ru.php');
				else
					include(SERVER_PATH.'/include/currency_convert_uk.php');
				break;
		}

		$s=" ";
		$s1=" ";
		$s2=" ";
		//$kop=intval(($L*100-intval($L)*100));
		$kop=round(($L*100-intval($L)*100));
		$L=intval($L);
		if($L>=1000000000){
			$many=0;
			Currency::SemanticUkr(intval($L/1000000000),$s1,$many,3, $sType);
			$s.=$s1." ".$namemrd[$many];
			$L%=1000000000;
		}

		if($L>= 1000000){
			$many=0;
			Currency::SemanticUkr(intval($L/1000000),$s1,$many,2, $sType);
			$s.=$s1." ".$namemil[$many];
			$L%=1000000;
			if($L==0){
				$s.=" ".$aMessage['own']." ";
			}
		}

		if($L >= 1000){
			$many=0;
			Currency::SemanticUkr(intval($L/1000),$s1,$many,1, $sType);
			$s.=$s1." ".$nametho[$many];
			$L%=1000;
			if($L==0) $s.=" ".$aMessage['own']." ";
		}

		if($L!=0){
			$many=0;
			Currency::SemanticUkr($L,$s1,$many,0, $sType);
			$s.=$s1." ".$namerub[$many];
		}

		if($kop>0){
			$many=0;
			Currency::SemanticUkr($kop,$s1,$many,1, $sType);
			$s.=" ".$kop." ".$kopeek[$many];
		} else {
			$s.=" ".$aMessage['sub'];
		}

		return $s;
	}
	//-----------------------------------------------------------------------------------------------
	private function SemanticUkr($i,&$words,&$fem,$f, $sType='RUB')
	{
		$s_print_lang = Language::GetConstant('global:print_language_prefix','ua');
		
		switch ($sType) {
			case 'USD':
				// print USD in ru/ua languages
				if ($s_print_lang == 'ru')
					include(SERVER_PATH.'/include/currency_convert_ru_en.php');
				elseif ($s_print_lang == 'ua')
					include(SERVER_PATH.'/include/currency_convert_ua_en.php');
				else
					include(SERVER_PATH.'/include/currency_convert_en.php');
				break;

			case 'RUB':
				include(SERVER_PATH.'/include/currency_convert_ru.php');
				break;

			default:
				// print UAH in ru language
				if ($s_print_lang == 'ru')
					include(SERVER_PATH.'/include/currency_convert_ua_ru.php');
				else
					include(SERVER_PATH.'/include/currency_convert_uk.php');
				break;
		}

		$words="";
		$fl=0;
		if($i>=100){
			$jkl=intval($i / 100);
			$words.=$hang[$jkl];
			$i%=100;
		}
		if($i>=20){
			$jkl=intval($i / 10);
			$words.=$des[$jkl];
			$i%=10;
			$fl=1;
		}
		switch($i){
			case 1: $fem=1; break;
			case 2:
			case 3:
			case 4: $fem=2; break;
			default: $fem=3; break;
		}
		if($i){
			if($i<3 && $f>0){
				if ($f>=2) $words.=" ".$_1_19[$i];
				else $words.=" ".$_1_2[$i];
			} else {
				$words.=" ".$_1_19[$i];
			}
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function GetExchangeRate($iIdCurrencyFrom,$iIdCurrencyTo)
	{
		return round($this->aCurrencyAssoc[$iIdCurrencyFrom]['value']/$this->aCurrencyAssoc[$iIdCurrencyTo]['value']
		,Base::GetConstant('currency:exchange_rate_round',3));
	}
	//-----------------------------------------------------------------------------------------------
    public static function PrintCurrencyPrice($dPrice=0,$sCurrency='USD')
	{
		if (!Currency::$aCurrency) Currency::InitCurrency();
		return Currency::PriceContent(Currency::$aCurrency[$sCurrency]['symbol'],$dPrice);
	}
	//-----------------------------------------------------------------------------------------------
	private static function PriceContent($sCurrency,$sPrice){
	    return "<nobr class='price'><span>".$sCurrency." ".round($sPrice,2)."</span></nobr>";
	}
	//-----------------------------------------------------------------------------------------------
	public function PrintSymbol($dPrice=0,$iIdCurrency=0)
	{
		if (!isset($dPrice))
		    $dPrice = 0;

		$oCurrency = new Currency();
		
		if (!$iIdCurrency) $iIdCurrency=Auth::$aUser['id_currency'];
		if (!$iIdCurrency) $iIdCurrency='1';
		if (!$oCurrency->aCurrencyAssoc[$iIdCurrency]['symbol']) $iIdCurrency='1';
		
		return $dPrice . " " . $oCurrency->aCurrencyAssoc[$iIdCurrency]['symbol'];
	}
    //-----------------------------------------------------------------------------------------------

    /**
     * Метод печатает код или символ валюты
     *
     * @param int $iIdCurrency
     * @param int $bPrintCode выбирает печатать символ или код валюты
     * @return mixed возвращает символ или код валюты
     */
    public function PrintCurrencySymbol($iIdCurrency=0,$bPrintCode=0)
    {
        $oCurrency = new Currency();

        if (!$iIdCurrency) $iIdCurrency=Auth::$aUser['id_currency'];
        if (!$iIdCurrency) $iIdCurrency='1';
        if (!$oCurrency->aCurrencyAssoc[$iIdCurrency]['symbol']) $iIdCurrency='1';

        if ($bPrintCode)
        {
            return $oCurrency->aCurrencyAssoc[$iIdCurrency]['code'];
        }else{
            return $oCurrency->aCurrencyAssoc[$iIdCurrency]['symbol'];
        }
    }
}
?>