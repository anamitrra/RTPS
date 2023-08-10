<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

if(!function_exists('calcSecurityAmtForContractors'))
{
    function calcSecurityAmtForContractors($deptt_code, $category, $caste, $category_of_regs)
    {
        $reg_fee = 0;
        if($deptt_code == 'PWDB' || $deptt_code == 'PWDNH')
        {
            if($category == "Class-1(A)")
            {
                $fd_amt = 15000;
            }
            else if($category == "Class-1(B)")
            {
                $fd_amt = 10000;
            }
            else if($category == "Class-1(C)")
            {
                if($deptt_code == 'PWDNH') {
                    $fd_amt = 5000;
                } else {
                    $fd_amt = 7500;
                }
            }
            else if($category == "Class-II")
            {
                if($deptt_code == 'PWDNH') {
                    $fd_amt = 2000;
                } else {
                    $fd_amt = 4000;
                }
            }
        }
        else if($deptt_code == 'PHED')
        {
            if($category == "Class-1(A)")
            {
                $fd_amt = 10000;
                $reg_fee = 500;
            }
            else if($category == "Class-1(B)")
            {
                $fd_amt = 10000;
                $reg_fee = 500;
            }
            else if($category == "Class-1(C)")
            {
                $fd_amt = 10000;
                $reg_fee = 500;
            }
            else if($category == "Class-II")
            {
                $fd_amt = 7000;
                $reg_fee = 300;
            }
        }
        else if($deptt_code == 'WRD')
        {
            if($category == "Class-1(A)")
            {
                $fd_amt = 15000;
            }
            else if($category == "Class-1(B)")
            {
                $fd_amt = 10000;
            }
            else if($category == "Class-1(C)")
            {
                $fd_amt = 5000;
            }
            else if($category == "Class-II")
            {
                $fd_amt = 3500;
            }
        }
        else if($deptt_code == 'GMC')
        {
            if($category == "Class-1(A)")
            {
                $fd_amt = 50000;
            }
            else if($category == "Class-1(B)")
            {
                $fd_amt = 30000;
            }
            else if($category == "Class-1(C)")
            {
                $fd_amt = 10000;
            }
            else if($category == "Class-II")
            {
                $fd_amt = 5000;
            }
        }
        
        if($caste != 'General' || $category_of_regs == 'Unemployed Graduate Engineer' || $category_of_regs == 'Unemployed Diploma Engineer')
        {
            $fd_amt = $fd_amt/2;
            $reg_fee = $reg_fee/2;

            if($deptt_code == 'PWDNH' && $category_of_regs == 'Unemployed Graduate Engineer')
            {
                $fd_amt = 2500;
                $reg_fee = 0;
            }
        }

        return $fd_amt.'|'.$reg_fee;
    }
}

if(!function_exists('calcMinExecutedWorkValue'))
{
    function calcMinExecutedWorkValue($deptt_code, $category, $total_work_value)
    {
        $flag = true;
        $amt = '';
        if($deptt_code == 'PWDB' || $deptt_code == 'GMC' || $deptt_code == 'PWDNH') {
            if($category == 'Class-1(A)' && $total_work_value < 8000000)
            {
                $flag = false;
                $amt = '8000000';
            }
            else if($category == 'Class-1(B)' && $total_work_value < 4000000)
            {
                $flag = false;
                $amt = '4000000';
            }
            else if($category == 'Class-1(C)' && $total_work_value < 2000000)
            {
                $flag = false;
                $amt = '2000000';
            }
            else if($category == 'Class-II' && $total_work_value < 1000000)
            {
                $flag = false;
                $amt = '1000000';
            }
        }
        else if($deptt_code == 'PHED') {
            if($category == 'Class-1(A)' && $total_work_value < 5000000)
            {
                $flag = false;
                $amt = '5000000';
            }
            else if($category == 'Class-1(B)' && $total_work_value < 5000000)
            {
                $flag = false;
                $amt = '5000000';
            }
            else if($category == 'Class-1(C)' && $total_work_value < 5000000)
            {
                $flag = false;
                $amt = '5000000';
            }
            else if($category == 'Class-II' && $total_work_value < 2000000)
            {
                $flag = false;
                $amt = '2000000';
            }
        }
        else if($deptt_code == 'WRD') {
            if($total_work_value != '' && $total_work_value > 0) {
            if($category == 'Class-1(A)' && $total_work_value < 10000000)
            {
                $flag = false;
                $amt = '10000000';
            }
            else if($category == 'Class-1(B)' && $total_work_value < 5000000)
            {
                $flag = false;
                $amt = '5000000';
            }
            else if($category == 'Class-1(C)' && $total_work_value < 2500000)
            {
                $flag = false;
                $amt = '2500000';
            }
            else if($category == 'Class-II' && $total_work_value < 1000000)
            {
                $flag = false;
                $amt = '1000000';
            }
         } else {
            $flag = true;
         }
        }

        return $amt.'|'.$flag;
    }
}

if(!function_exists('calcApplFeeAmtForContractors'))
{
    function calcApplFeeAmtForContractors($deptt_code, $category)
    {
        $appl_fee = 0;
        if($deptt_code == 'GMC')
        {
            if($category == "Class-1(A)")
            {
                $appl_fee = 5000;
            }
            else if($category == "Class-1(B)")
            {
                $appl_fee = 4000;
            }
            else if($category == "Class-1(C)")
            {
                $appl_fee = 3000;
            }
            else if($category == "Class-II")
            {
                $appl_fee = 2000;
            }
        }
        return $appl_fee;
    }
}