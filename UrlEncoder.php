<?php
/**
 * UrlEncoder工程
 *
 * UrlEncoder.php文件
 *
 * User: Administrator
 * DateTime: 2015-01-26 13:57
 */
//namespace WHP0011\UrlEncoder;
include_once('constants.php');

class UrlEncoder
{
    private $strKey = KEY;
    private $intSplitNum = SPLIT_NUM;
    private $intStart_num = START_NUM;
    private $intStep_num = STEP_NUM;
    /**
     *加密
     * @param string $strData
     * @return string
     */
    public function encode($strData = '')
    {
        $key = $this->strKey;
        $intSplitNum = $this->intSplitNum;
        $code = self::codeGenerator($this->intStart_num,$this->intStep_num);

        if (!is_numeric($intSplitNum)) {
            return false; // SPLITE_NUM 必须为1-9的数字
        } else {
            $intSplitNum = intval($intSplitNum);
            if (0 == $intSplitNum) {
                return false; //SPLITE_NUM 不能为0
            }
        }

        //base64加密投注内容
        $strBase64 = base64_encode($strData);
        for ($i = 0; $i < 2; $i++) {
            $intLen = strlen($strBase64);
            if ('=' == substr($strBase64, $intLen - 1, $intLen)) {
                $strBase64 = substr($strBase64, 0, $intLen - 1);
            } else {
                break;
            }
        }
        $arrBase64 = str_split($strBase64, $intSplitNum);
        //加密key循环使用
        $intArrBase64 = count($arrBase64);
        while (true) {
            if (strlen($key) >= $intArrBase64) {
                break;
            } else {
                $key .= $key;
            }
        }
        $key = substr($key, 0, $intArrBase64);
        $arrKey = str_split($key);

        $strEncodeBase64 = '';
        foreach ($arrBase64 as $base64Val) {
            $charKey = array_shift($arrKey);
            $intPosition = bcmod($code[$charKey], $intSplitNum);

            if (strlen($base64Val) < $intSplitNum) {
                $intPosition = 0; //若最后的切分片段小于切分长度，加密key插入到最前面
            }

            $strPreBase64Val = substr($base64Val, 0, $intPosition);
            $strTailBase64Val = substr($base64Val, $intPosition);
            $strEncodeBase64 .= $strPreBase64Val . $charKey . $strTailBase64Val;
        }

        return $strEncodeBase64;
    }

    /**
     *解密
     * @param string $strData
     * @return string
     */
    public function decode($strData = '')
    {
        $key = $this->strKey;
        $intSplitNum = $this->intSplitNum;
        $code = self::codeGenerator($this->intStart_num,$this->intStep_num);

        if (!is_numeric($intSplitNum)) {
            return false; // SPLITE_NUM 必须为1-9的数字
        } else {
            $intSplitNum = intval($intSplitNum);
            if (0 == $intSplitNum) {
                return false; //SPLITE_NUM 不能为0
            }
        }

        $intSplitNum += 1;
        $arrBase64 = str_split($strData, $intSplitNum);
        //加密key循环使用
        $intArrBase64 = count($arrBase64);
        while (true) {
            if (strlen($key) >= $intArrBase64) {
                break;
            } else {
                $key .= $key;
            }
        }
        $key = substr($key, 0, $intArrBase64);
        $arrKey = str_split($key);

        $strDecodeBase64 = '';
        foreach ($arrBase64 as $base64Val) {
            $charKey = array_shift($arrKey);
            $intPosition = bcmod($code[$charKey], $intSplitNum - 1);
            if (strlen($base64Val) < $intSplitNum) {
                $intPosition = 0; //若最后的切分片段小于切分长度，加密key插入到最前面
            }
            $strPreBase64Val = substr($base64Val, 0, $intPosition);
            $strTailBase64Val = substr($base64Val, $intPosition + 1);
            $strDecodeBase64 .= $strPreBase64Val . $strTailBase64Val;
        }

        if (bcmod(strlen($strDecodeBase64), 4) == 3) {
            $strDecodeBase64 .= '=';
        } elseif (bcmod(strlen($strDecodeBase64), 4) == 2) {
            $strDecodeBase64 .= '==';
        }

        $originStr = base64_decode($strDecodeBase64);
        return $originStr;
    }

    /**
     *加密、解密编码表
     * @param int $intStartNum
     * @param int $intStepNum
     * @return array
     */
    public function codeGenerator($intStartNum = START_NUM, $intStepNum = STEP_NUM)
    {
        $code = [];
        for ($i = 65; $i < 91; $i++) {
            $code[chr($i)] = $intStartNum;
            $intStartNum += $intStepNum;
        }
        for ($i = 97; $i < 123; $i++) {
            $code[chr($i)] = $intStartNum;
            $intStartNum += $intStepNum;
        }
        for ($i = 0; $i < 10; $i++) {
            $code[$i] = $intStartNum;
            $intStartNum += $intStepNum;
        }
        return $code;
    }

    /**
     *设置加密key
     * @param string $strKey
     */
    public function setKey($strKey = KEY){
        $this->strKey = $strKey;
    }

    /**
     *设置加密切分间隔
     * @param int $intSplitNum
     */
    public function setSplitNum($intSplitNum = SPLIT_NUM){
        $this->intSplitNum = $intSplitNum;
    }

    /**
     *设置编码表起始值
     * @param int $intStartNum
     */
    public function setStartNum($intStartNum = START_NUM){
        $this->intStart_num = $intStartNum;
    }

    /**
     *设置编码表步进值
     * @param int $intStepNum
     */
    public function setStepNum($intStepNum = STEP_NUM){
        $this->intStep_num = $intStepNum;
    }
} 